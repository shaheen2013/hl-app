<?php //Miramos si esta definida la variable de control de index.php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}

include_once LIB . 'sanitize.php';
include_once LIB . 'paramsUrl.php';

function exportarUsuarios($id_hotel)
{
    $id_hotel = mysqli_real_escape_string(conectar(1), $id_hotel);
    $sql = "SELECT DISTINCT user_hotels.user_hotel_id, users.nombre, users.email, users.user_card, user_facebook.amigos as fb_friends,  
            CASE WHEN user_facebook.id IS NULL THEN users.location ELSE user_facebook.locale END AS locale,
            CASE WHEN user_facebook.id IS NULL THEN users.fecha_nacimiento ELSE user_facebook.birthday END AS birthday,
            CASE WHEN user_facebook.id IS NULL THEN users.sexo
              ELSE user_facebook.gender END AS gender,
            (SELECT COUNT(user_shares.id) FROM user_shares WHERE id_usuario=users.id AND id_hotel=$id_hotel AND id_tipo_share='2' ) AS shares_prestay,
            (SELECT COUNT(user_shares.id) FROM user_shares WHERE id_usuario=users.id AND id_hotel=$id_hotel AND id_tipo_share='3' ) AS shares_stay,
            (SELECT nombre FROM users WHERE id=referrer_users.invitador) AS referrer_name,
            CASE WHEN connection_history.first_login IS NULL THEN users.created ELSE connection_history.first_login END AS first_checkin,
            CASE WHEN connection_history.last_login IS NULL THEN users.created ELSE connection_history.last_login END AS last_checkin,
            connection_history.id_room
            FROM user_hotels
            LEFT JOIN users ON users.id=user_hotels.id_usuario
            LEFT JOIN user_shares ON user_shares.id_usuario=users.id AND  user_shares.id_hotel=user_hotels.id_hotel
            LEFT JOIN user_facebook ON users.id = user_facebook.id_usuario
            LEFT JOIN referrer_users ON referrer_users.invitado=users.id AND referrer_users.id_hotel=$id_hotel
            LEFT JOIN connection_history ON connection_history.id_user = users.id AND connection_history.id_hotel=$id_hotel
            WHERE user_hotels.id_hotel=$id_hotel 
            GROUP BY
                users.nombre, 
                users.email,
                connection_history.id_room,
                date_format(connection_history.last_login, '%Y-%m')
            ORDER BY connection_history.last_login desc";


    $con = conectar(1);
    $rows = lecturaArray($sql, $con);
    return $rows;
}

function obtenerUsuarios($id_hotel, $busqueda, $order, $sort, $itemsPage, $pagina)
{
    $arrayUsuarios = array();
    $sql = "SELECT DISTINCT users.id, users.nombre, users.email, user_facebook.facebook_img as img , user_facebook.amigos as fb_friends, 	
	user_facebook.id_facebook, ";

    //Shares total
    $sql .= "(SELECT COUNT(user_shares.id) FROM user_shares WHERE id_usuario=users.id 
	AND id_hotel='" . $id_hotel . "') AS shares, ";

    //Shares survey
    /*$sql .= "(SELECT COUNT(user_shares.id) FROM user_shares WHERE id_usuario=users.id
    AND id_hotel='".$id_hotel."' AND id_tipo_share='1') AS shares_survey, ";*/
    //Shares prestay
    $sql .= "(SELECT COUNT(user_shares.id) FROM user_shares WHERE id_usuario=users.id 
	AND id_hotel='" . $id_hotel . "' AND id_tipo_share='2') AS shares_prestay, ";
    $sql .= "(SELECT COUNT(user_shares.id) as total_shares FROM user_shares WHERE id_hotel =$id_hotel ) AS total_shares, ";
    //Shares stay
    $sql .= "(SELECT COUNT(user_shares.id) FROM user_shares WHERE id_usuario=users.id 
	AND id_hotel='" . $id_hotel . "' AND id_tipo_share='3') AS shares_stay, ";
    //Shares poststay
    $sql .= "(SELECT COUNT(user_shares.id) FROM user_shares WHERE id_usuario=users.id 
	AND id_hotel='" . $id_hotel . "' AND id_tipo_share='4') AS shares_poststay, ";

    //Total referrals
    //$sql .= "totalReferrals2(users.id, '".$id_hotel."') AS total_referrals, ";
    $sql .= "(SELECT COUNT(tracking_cookies.id) FROM tracking_cookies 
    WHERE tracking_cookies.referrer_id=users.id AND tracking_cookies.hotel_id='" . $id_hotel . "' 
    AND referral_id !='') AS total_referrals ";


    $sql2 = " FROM user_shares
	LEFT JOIN users ON users.id=user_shares.id_usuario
	RIGHT JOIN user_facebook ON users.id = user_facebook.id_usuario ";

    $sql2 .= "WHERE user_shares.id_hotel='" . $id_hotel . "' ";
    if ($busqueda != '') {
        $sql2 .= "AND users.nombre LIKE '%" . $busqueda . "%'";
    }
    $sql3 = '';
    $sql3 .= " GROUP BY users.id  ";
    //$sql2 .= "  HAVING followers_visits!=0 ";
    //-----------------------------------------------

    if (campoOrdValido($order, $sql)) {//Miramos si $order es un campo válido para ordenar
        $sql3 = " ORDER BY " . $order . " " . $sort;
    }    
    $inicio = $itemsPage * $pagina - $itemsPage;
    $sql3 .= " LIMIT " . $inicio . "," . $itemsPage;
    global $log;
    $log->debug($sql.$sql2.$sql3);
    $row = lecturaArray($sql.$sql2.$sql3, '', true, 1);

    $arrayUsuarios = array();

    $i = 0;
    foreach($row as $usuario){
        foreach ($usuario as $key => $valor) {
            if ($key == 'total_spent') {
                $arrayUsuarios[$i][$key] = number_format($valor, 2, ',', '.');
            } else {
                $arrayUsuarios[$i][$key] = $valor;
            }
        }
        $i++;
    }

    $sql0 = "SELECT COUNT(DISTINCT(users.id)) as N ";
    paginacion2($sql0 . $sql2, $pagina, $itemsPage);
    return $arrayUsuarios;
}

function obtenerNombreHotel($id_hotel)
{
    $sql = "SELECT hotelName FROM hoteles WHERE id='" . $id_hotel . "' ";
    $rs = mysqli_query(conectar(), $sql);
    $row = mysqli_fetch_assoc($rs);
    liberar($rs);
    return $row['hotelName'];

}

?>
