<?php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

include_once RUTA_DIR . LIB . 'cache.php';
/**
 * Obtain all hotel satisfaction info
 * @param $id_hotel
 * @return array|mixed|null
 */
function obtenerDatosHotelSatisfaction($id_hotel)
{
	//Get from cache
    $cacheName = 'hotel_satisfaction_' . $id_hotel;
    $cache = getFromCache($cacheName);

    if(!$cache) 
    {
    	$con 		= conectar(1);
		$id_hotel 	= mysqli_real_escape_string($con, $id_hotel);

		$sql = "SELECT 
					hotel_satisfaction.puntMin,
					hotel_satisfaction.sendThanksMail,
					hotel_review.diasEnvio,
					fotoBg,
					logo,
					hotelName,
					hotel_review.ignoreRating
				FROM hoteles 
				LEFT JOIN hotel_review ON hotel_review.id_hotel=hoteles.id
				LEFT JOIN hotel_satisfaction ON hotel_satisfaction.id_hotel=hoteles.id
				WHERE hoteles.id = $id_hotel 
		";
		$row = lectura($sql, $con);

		if($row)
        {
            $tags = array ('hotel', 'hotel_profile', 'hotel_profile_'.$id_hotel , 'hotel_review_'.$id_hotel, 'hotel_satisfaction_'.$id_hotel);
            setToCache($cacheName, $row, 31536000, $tags);
        }

    }else{
        $row = $cache->get();
    }

    return $row;
}

// FX para verificar si ya se ha realizado esta encuesta de satisfacción anteriormente
//	$id_satisfaction : id satisfaction de la BD de HL_emails. Una vez mandado email de satisfaction es borrara de HL_emails.
function verificarEncuestaSatisfaction($id_satisfaction)
{
	$con 				= conectar();
    $id_satisfaction 	= mysqli_real_escape_string($con, $id_satisfaction);

	$sql = "SELECT COUNT(id) AS n FROM user_satisfaction WHERE id_satisfaction = $id_satisfaction LIMIT 1";
	$row = lectura($sql, $con);
	if($row['n']==0)
	{
		return true;
	}else{
		return false;
	}
}

/**
 * Store satisfaction
 * @param $id_hotel
 * @param $id_usuario
 * @param $puntuacion
 * @param $comentario
 * @param $id_satisfaction
 * @param $review_send
 * @param $time_stamp
 */
function guardarSatisfaction($id_hotel, $id_usuario, $puntuacion, $comentario, $id_satisfaction, $review_send, $time_stamp = 1)
{
	$con 			= conectar();
    $puntuacion 	= mysqli_real_escape_string($con, $puntuacion);
    $comentario 	= mysqli_real_escape_string($con, $comentario);
    $update_date ="";
    if($time_stamp){
        $update_date = "fecha_update='".date("Y-m-d H:i:s")."',";
    }
	global $log;
    $sql = "UPDATE user_satisfaction 
    SET puntuacion='".$puntuacion."', comentario='".$comentario."', done=1, $update_date review_send=$review_send  
	WHERE id=$id_satisfaction ";
	if($done = 0){
		$sql = $sql ." AND done=0 ";
	}
	$log->debug($sql);
    escritura($sql, $con);
    deleteCacheByKey('obtenerRatingHotel_' . $id_hotel);
}

/**
 * Check if token is valid
 * @param $token
 * @return array|null
 */
function comprobarTokenSatisfaction($token)
{
	$con 	= conectar();
    $token 	= mysqli_real_escape_string($con, $token);

	$sql = "SELECT user_satisfaction.done, hoteles.id AS id_hotel, hotel_guid.guid AS guid_hotel, users.id AS id_usario, user_guid.guid AS guid_usuario
	FROM user_satisfaction 
	LEFT JOIN hoteles ON hoteles.id=user_satisfaction.id_hotel
	LEFT JOIN hotel_guid ON hotel_guid.id_hotel=user_satisfaction.id_hotel
	LEFT JOIN users ON users.id=user_satisfaction.id_usuario
	LEFT JOIN user_guid ON user_guid.id_usuario=user_satisfaction.id_usuario
	WHERE user_satisfaction.id = '".$token."' ";
	return lectura($sql, $con);
}


function getUserNameAndLang($user_id)
{
    //Get from cache
    $cacheName = 'obtenerNombreUserSatisfactionThanks_' . $user_id ;
    $cache = getFromCache($cacheName);

    if (!$cache){
        $con = conectar(1);
        $user_id  = mysqli_real_escape_string($con, $user_id );
        $sql = "SELECT nombre, lang, sexo FROM users WHERE id = $user_id ";
        $row = lectura($sql, $con);
    
        $langs = ['en','es','de','fr','ca','it'];
        if ( !in_array($row['lang'],$langs) ) {
            $row['lang'] = 'en';
        }
        if ($row) {
            $tags = array('nombre','lang');
            setToCache($cacheName, $row, 31536000, $tags);
        }

    }else{
        $row = $cache->get();
    }
    
    return $row;
}

//Get all WebsiteReserva langs
function getAllHotelWebsiteReservaLang()
{
    $brand_id = $_SESSION['hotel']['brand_id'];
    // Petición a API
    $endPoint = HOTELINKING_ENDPOINT . "brands/url/brand/{$brand_id}";
    $gateway = new ApiGatewayConnection();

    return safeJsonParser($gateway->sendRequest([], $endPoint, 'GET'));

}

//Retrieve data to send a warning email
function getDataForWarning($satisfactionSurvey)
{
    $con = conectar(1);
    $sql = "SELECT 
                hotel_satisfaction.warning_email AS hotel_email,
                hoteles.id AS hotel_id,
                hoteles.hotelName AS hotel_name,
                hotel_satisfaction.puntMin AS min_score,
                hotel_satisfaction.diasEnvio AS send_days,
                users.id AS user_id,
                users.nombre AS user_name,
                users.email AS user_email,
                users.lang AS user_lang,
                users.fecha_nacimiento AS user_birthday,
                users.sexo AS user_gender,
                user_hotels.fecha AS user_linked,
                user_satisfaction.fecha_update AS user_fill,
                user_satisfaction.fecha_creado AS satisfaction_created,
                user_satisfaction.comentario,
                CASE WHEN user_satisfaction.id_room IS NOT NULL THEN user_satisfaction.id_room ELSE connection_history.id_room END AS id_room,
                connection_history.last_login ";
    $sql .= "FROM user_satisfaction
             RIGHT JOIN hoteles ON hoteles.id = user_satisfaction.id_hotel
             RIGHT JOIN users ON users.id = user_satisfaction.id_usuario
             RIGHT JOIN hotel_satisfaction ON hotel_satisfaction.id_hotel = user_satisfaction.id_hotel
             RIGHT JOIN user_hotels ON user_hotels.id_usuario = user_satisfaction.id_usuario
             LEFT JOIN connection_history ON connection_history.id_user = user_satisfaction.id_usuario AND connection_history.id_hotel = user_satisfaction.id_hotel
             WHERE user_satisfaction.id = " . $satisfactionSurvey;
    $sql .= " ORDER BY connection_history.last_login DESC LIMIT 1";
    return lectura($sql, $con);
}

//Send satisfaction warning to email platform
function sendSatisfactionWarning($warningData, $emails)
{
    global $log;
    $con = conectar(2);
    $comment = mysqli_real_escape_string($con, $warningData['comentario']);
    $comment = str_replace("/", "-", $comment);
    $log->debug('comment: ' . $comment);
    $user_name = mysqli_real_escape_string($con, $warningData['user_name']);
    $hotel_name= mysqli_real_escape_string($con, $warningData['hotel_name']);
    $sql = "INSERT INTO 
                satisfaction_warnings (
                    hotel_email,
                    hotel_id,
                    hotel_name,
                    user_email,
                    user_name,
                    user_lang,
                    user_birthday,
                    user_gender,
                    user_linked,
                    survey_created,
                    survey_filled,
                    user_comment,
                    room_number,
                    survey_sent,
                    min_score,
                    user_score,
                    last_login
                ) VALUES ";
    $i = 0;
    $numItems = count($emails);
    foreach ($emails as $email) {
        $sql .= "
                    ('" . trim($email) . "',
                    '" . $warningData['hotel_id'] . "',
                    '" . $hotel_name . "',
                    '" . $warningData['user_email'] . "',
                    '" . $user_name . "',
                    '" . $warningData['user_lang'] . "',
                    '" . $warningData['user_birthday'] . "',
                    '" . $warningData['user_gender'] . "',
                    '" . $warningData['user_linked'] . "',
                    '" . $warningData['satisfaction_created'] . "',
                    '" . $warningData['user_fill'] . "',
                    '" . $comment . "',
                    '" . $warningData['id_room'] . "',
                    '" . $warningData['satisfaction_send'] . "',
                    '" . $warningData['min_score'] . "',
                    '" . $warningData['score'] . "',
                    '" . $warningData['last_login'] . "')";
        if(++$i !== $numItems) {
            $sql .= ", ";
        }
    }
    $log->debug('for each email insert on DDBB ' . $sql);
    escritura($sql, $con);
}
