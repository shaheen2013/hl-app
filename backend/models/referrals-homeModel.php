<?php
// Libreria para las estadísticas de referral, de hotel o de cadena

// Función que devuelve los kpis de referrals
// $id: id de quien realiza la llamada
// $tipo: h (hotel) o c (cadena)
// --------------------------------------------
// Los referrals_no_anonimos y los sign_ups son lo mismo para un hotel pero no para una cadena. Una persona puede ser referral de varios hoteles de una cadena pero para la cadena cuenta como un único referral con un único sign up
function kpisReferrals($id, $tipo, $fecha_desde = 0, $fecha_hasta = 0)
{
    global $log;
    $con = conectar(1);
    $id = mysqli_real_escape_string($con, $id);
    $tipo = mysqli_real_escape_string($con, $tipo);
    $fecha_desde = mysqli_real_escape_string($con, $fecha_desde);
    $fecha_hasta = mysqli_real_escape_string($con, $fecha_hasta);
    desconectar($con);

    //----Filtros-------------------------
    // Por hotel o por cadena
    $hotelCadena = '';
    if ($tipo == 'h') {
        // Tabla tracking_cookies
        $hotelCadena = "hotel_id='" . $id . "' ";
        // Tabla user_shares
        $hotelCadena2 = "id_hotel='" . $id . "' ";
    } else {
        // Tabla tracking_cookies
        $hotelCadena = "cadena_id='" . $id . "' ";
        // Tabla user_shares
        $hotelCadena2 = "id_cadena='" . $id . "' ";
    }
    // Entre fechas
    $fechas = $fechas_user_shares = $fechas2 = $fechas3 = $fechas4 = '';
    if ($fecha_desde != 0 && $fecha_hasta != 0) {
        if ($fecha_desde == $fecha_hasta) {
            $stringFechas = " = '" . $fecha_desde . "' ";
        } else {
            $stringFechas = " BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "' ";
        }
        //Tabla tracking_cookies
        $fechas = " AND DATE(created_at) " . $stringFechas;
        $fechas_user_shares = " AND DATE(user_shares.fecha) " . $stringFechas;
        $fechas2 = " DATE(user_hotels.fecha) " . $stringFechas;
        $fechas3 = " DATE(user_shares.fecha) " . $stringFechas;
        $fechas4 = " AND DATE(user_satisfaction.fecha_update) " . $stringFechas;
    } else if ($fecha_desde != 0 && $fecha_hasta == 0) {
        //Fecha desde
        $fechas = " AND DATE(created_at) >= '" . $fecha_desde . "' ";
        $fechas_user_shares = " AND DATE(user_shares.fecha) >= '" . $fecha_desde . "' ";
        $fechas2 = " DATE(user_hotels.fecha) >= '" . $fecha_desde . "' ";
        $fechas3 = " DATE(user_shares.fecha) >= '" . $fecha_desde . "' ";
        $fechas4 = " AND DATE(user_satisfaction.fecha_update) >= '" . $fecha_desde . "' ";
    } else if ($fecha_desde == 0 && $fecha_hasta != 0) {
        //Fecha hasta
        $fechas = " AND DATE(created_at) <= '" . $fecha_hasta . "' ";
        $fechas_user_shares = " AND DATE(user_shares.fecha) <= '" . $fecha_hasta . "' ";
        $fechas2 = " DATE(user_hotels.fecha) <= '" . $fecha_hasta . "' ";
        $fechas3 = " DATE(user_shares.fecha) <= '" . $fecha_hasta . "' ";
        $fechas4 = " AND DATE(user_satisfaction.fecha_update) <= '" . $fecha_hasta . "' ";
    }
    //----Filtros-------------------------

    $cacheName = 'kpisReferrals_1_' . $id . '_' . $tipo . '_' . $fecha_desde . '_' . $fecha_hasta;
    $log->debug($cacheName);
    $cache = getFromCache($cacheName);
    if (!$cache) {
        $log->debug('stats does not have cache with name ' . $cacheName);
        $sql = "SELECT 
		COUNT(DISTINCT referrer_id) AS total_referrers, ";
        if ($tipo == 'h') {
            $sql .= "COUNT(DISTINCT referral_id) AS referrals_no_anonimos, ";
        } else {
            // Es cadena. Los referrals_no_anonimos y sign_ups se miran de forma distinta que por hotel
            $sql .= "(SELECT COUNT(DISTINCT referral_id) FROM tracking_cookies 
			WHERE referral_id!='' AND " . $hotelCadena . $fechas . ") AS referrals_no_anonimos, 
			(SELECT COUNT(DISTINCT referral_id, hotel_id) FROM tracking_cookies WHERE referral_id IS NOT NULL 
			AND " . $hotelCadena . $fechas . ") AS sign_ups, ";
        }
        $sql .= "
		(SELECT IFNULL(SUM(IF(referral_id IS NULL,1,0)),0)
		FROM tracking_cookies WHERE " . $hotelCadena . $fechas . ") AS referrals_anonimos,
		
		COUNT(id) AS clicks_unicos,
		IFNULL(SUM(IF(transaction_num !='' OR amount !='0.00',1 ,0)),0) AS reservas_finalizadas, 
		IFNULL(SUM(amount),0) AS valor_reservas
		FROM tracking_cookies
		WHERE " . $hotelCadena . $fechas . "  
		AND tracking_cookies.referrer_id IN 
			(SELECT DISTINCT id_usuario FROM user_shares WHERE user_shares." . $hotelCadena2 . ")";
        $con = conectar();
        $rs = mysqli_query($con, $sql);
        $row = mysqli_fetch_assoc($rs);

        // Añadimos el campo total_referrers
        $row['total_referrals'] = $row['referrals_no_anonimos'] + $row['referrals_anonimos'];
        if ($tipo == 'h') {
            $row['sign_ups'] = $row['referrals_no_anonimos'];
        }
        //Social media friends
        $sql2 = "SELECT IFNULL(SUM(user_facebook.amigos),0) AS total_fb_friends
			FROM user_facebook
			INNER JOIN (SELECT DISTINCT id_usuario FROM user_shares
			WHERE " . $hotelCadena2 . $fechas_user_shares . ") AS t ON t.id_usuario = user_facebook.id_usuario";

        // error_log($sql2);

        //echo '<br>'.$sql2;
        $rs2 = mysqli_query($con, $sql2);
        $row2 = mysqli_fetch_assoc($rs2);


        $row2['total_sm_friends'] = $row2['total_fb_friends'];

        //--------------------------------------------------------------
        // Usuarios que han entrado por FB / email
        // En el caso de la cadena no puede haber repetición
        //Social media friends
        $sql3 = "SELECT ";
        //usuario email
        $sql3 .= "(SELECT COUNT(DISTINCT(user_hotels.id_usuario)) AS n FROM user_hotels 
		WHERE user_hotels." . $hotelCadena2 . " AND user_hotels.id_usuario NOT IN (SELECT id_usuario FROM user_facebook) ";
        if (!empty($fechas2))
            $sql3 .= " AND " . $fechas2;
        $sql3 .= " ) AS usuarios_email,";
        //usuario facebook
        $sql3 .= "(SELECT COUNT(DISTINCT(user_hotels.id_usuario)) AS n FROM user_hotels 
		WHERE user_hotels." . $hotelCadena2 . " AND user_hotels.id_usuario IN (SELECT id_usuario FROM user_facebook)";
        if (!empty($fechas2))
            $sql3 .= " AND " . $fechas2;
        $sql3 .= " ) AS usuarios_fb,";
        //total shares
        $sql3 .= "(SELECT IFNULL(COUNT(DISTINCT(id_share)), 0) FROM user_shares WHERE user_shares." . $hotelCadena2;
        if (!empty($fechas3))
            $sql3 .= " AND " . $fechas3;
        $sql3 .= " ) AS user_shares";
        $rs3 = mysqli_query($con, $sql3);
        $row3 = mysqli_fetch_assoc($rs3);

        //--------------------------------------------------------------
        //Datos satisfaction (solo si tiene el producto contratado)
        //cuantos del total han pasado a review email y cuantos no. De los que han pasado, cual es la satisfacción media total.
        if ($_SESSION['permisos']['satisfaction'] == 1) {
            $sql4 = "SELECT
			(SELECT COUNT(id) FROM user_satisfaction WHERE " . $hotelCadena2 . " AND review_send=1 AND done=1 " . $fechas4 . ") AS review_send,
			(SELECT IFNULL(ROUND(((SUM(done)/COUNT(id))*100),0),0) FROM user_satisfaction WHERE " . $hotelCadena2 . " " . $fechas4 . ") AS perc_satisfaction_done,
			(SELECT IFNULL(ROUND(SUM(puntuacion)/COUNT(id), 1),0) FROM user_satisfaction WHERE " . $hotelCadena2 . " AND done=1 " . $fechas4 . ") AS satisfaction_media";
            $row4 = lectura($sql4, $con, false);
            $row4['satisfaction'] = '1';
        } else {
            $row4 = array();
            $row4['review_send'] = '0';
            $row4['perc_satisfaction_done'] = '0';
            $row4['satisfaction_media'] = '0';
            $row4['satisfaction'] = '0';
        }

        desconectar($con);
        //Juntamos todos los arrays
        $result = array_merge($row, $row2, $row3, $row4);


        $log->debug('stats result', $result);

        if ($result) {
            $tags = array('hotel', 'hotel_satisfaction_' . array_get($_SESSION,'h_logueado', array_get($_SESSION,'staff_id_hotel')));
            setToCache($cacheName, $result, 10800, $tags); // expires 3 hours
        }

    } else {
        $log->debug('stats has cache with name ' . $cacheName);
        $result = $cache->get();
        $log->debug('stats result', $result);
    }
    return $result;
}

function getTotalUsers($id, $tipo, $fecha_desde = 0, $fecha_hasta = 0)
{
    global $log;
    $con = conectar(1);
    $id = mysqli_real_escape_string($con, $id);
    $tipo = mysqli_real_escape_string($con, $tipo);
    $fecha_desde = mysqli_real_escape_string($con, $fecha_desde);
    $fecha_hasta = mysqli_real_escape_string($con, $fecha_hasta);
    $where="";
    $whereFechas="";

    if ($tipo == 'h') {
        $where = "AND user_hotels.id_hotel = $id";
    } else {
        $where = "AND user_hotels.id_hotel in (SELECT id_hotel from cadena_hotel where id_cadena = $id)";
    }
    if ($fecha_desde != 0 && $fecha_hasta != 0) {
        if ($fecha_desde == $fecha_hasta) {
            $whereFechas = "AND fecha = '" . $fecha_desde . "' ";
        } else {
            $whereFechas = " AND fecha BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "' ";
        }
    }

    $sql = "SELECT count(1) as totalUsers FROM (SELECT count(1) as totalUsers FROM user_hotels INNER JOIN connection_history on connection_history.id_hotel = user_hotels.id_hotel and user_hotels.id_usuario = connection_history.id_user  WHERE 1=1 $where $whereFechas GROUP BY id_user) as users_connected";
    $log->debug($sql);
    return lectura($sql);
}

//Get marketing multipliers
function getMultipliers()
{
    //Get from cache
    $con = conectar(1);
    $cacheName = 'marketing_multipliers';
    $cache = getFromCache($cacheName);
    if (!$cache) {
        $sql = "SELECT impressions_percents, single_click_price, thousand_impressions_price FROM marketing_multipliers";
        $row = lectura($sql, $con);
        if ($row) {
            $tags = array('marketing_multipliers');
            setToCache($cacheName, $row, 31536000, $tags);
        }
    } else {
        $row = $cache->get();
    }
    return $row;
}

?>
