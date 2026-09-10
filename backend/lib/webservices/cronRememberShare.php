<?php

// Cron remember share de Referral Tool
//include_once 'libreriasCron.php';// Librerias básicas
/*include_once RUTA_DIR.LIB.'enviarEmail.php';
include_once RUTA_DIR.LIB.'make_unsuscribe_hash.php';
include_once RUTA_DIR.LIB.'idiomas.php';*/

include_once RUTA_DIR.LIB.'referral-share-actions.php';
//include_once RUTA_DIR.LIB.'generarToken.php';
include_once RUTA_DIR.LIB.'obtenerdatosHotel.php';
include_once RUTA_DIR.LIB.'sanitize.php';

// Si no ha hecho ningun share de una URL le mandamos un mail recordatorio
function cronRememberShare()
{
	$result['exec'] = 'cronRememberShare';
	$emails_enviados = 0;
	
	$sql = "SELECT DISTINCT 
	referrer_tokens.id_usuario, referrer_tokens.token,
	users.nombre, users.email, users.lang,
	user_guid.guid AS user_guid, 
	referrer_tokens.id_hotel,
  	hoteles.hotelName AS nombre_hotel, hoteles.email AS email_hotel, 
	hoteles.logo, hotel_guid.guid
	FROM referrer_tokens 
	INNER JOIN users ON users.id=referrer_tokens.id_usuario
	INNER JOIN user_guid ON user_guid.id_usuario=users.id
	LEFT JOIN user_shares ON user_shares.id_hotel=referrer_tokens.id_hotel 
    AND user_shares.id_usuario=referrer_tokens.id_usuario
	INNER JOIN hoteles ON hoteles.id=referrer_tokens.id_hotel
	INNER JOIN hotel_guid ON hoteles.id=hotel_guid.id_hotel
	WHERE share=1 AND user_shares.id IS NULL AND users.notif_hotelinking=1 ";
	if( ENV != 'production' )
		$sql .= " LIMIT 5";

	$rows = lecturaArray($sql);

	foreach($rows AS $row)
	{		
		$lang = mirarIdiomaPlataforma($row['lang']);//Lang plataforma
		//Obtenemos la url de share
		global $urlTree;
		$urlShare = SECURE_BASE_PATH . $urlTree['referral-share-step-2'].'/'.$row['guid'].'/';
		//Logo hotel
		$hotelLogo = SECURE_BASE_PATH . DIR_IMG_FICHA_HOTEL . $row['id_hotel'].'/logo/big_'.$row['logo'];
		
		//generamos la URL para darse de baja
		$urlBaja = createUrlUnsuscribe($row['email'], $row['user_guid']);
		
		include RUTA_DIR.LANG.$lang.'/email/rememberShare.php';
		include RUTA_DIR.LIB.'plantillasMails/rememberShare.php';
		
		//Para testing
		(ENV == 'production')? $email_envio = $row['email'] : $email_envio = 'jaumehotelinking@gmail.com';
		
		mandarEmailMandrillPlantillaSoloContenido($email_envio, $row['nombre'], $asunto, $template_content, 'hotel-standard-template', $row['email_hotel'], $row['nombre_hotel']);
		
		$emails_enviados ++;
	}
	
	$result['emails_enviados'] = $emails_enviados;
	
	return $result;
}
?>