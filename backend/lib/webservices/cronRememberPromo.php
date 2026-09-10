<?php

// Cron remember promo de Referral Tool
//include_once 'libreriasCron.php';// Librerias básicas
/*include_once RUTA_DIR.LIB.'enviarEmail.php';
include_once RUTA_DIR.LIB.'make_unsuscribe_hash.php';
include_once RUTA_DIR.LIB.'idiomas.php';*/

function obtenerPromoCodesPendientes($id_usuario, $lang, $id_hotel)
{
	$promos = array();
	$sql = "SELECT DISTINCT token, 
	case when oferta_lang.nombre is null 
    then   oferta_en.nombre 
    else oferta_lang.nombre end AS name
	FROM oferta_referral_token
	LEFT JOIN hotel_oferta_lang as oferta_en on oferta_referral_token.id_oferta = oferta_en.id_oferta  and oferta_en.lang='en' 
    LEFT JOIN hotel_oferta_lang as oferta_lang on oferta_referral_token.id_oferta = oferta_lang.id_oferta and oferta_lang.lang='". $lang . "' 
	WHERE  oferta_referral_token.id_usuario='".$id_usuario."' 
	AND oferta_referral_token.id_hotel='".$id_hotel."'
	AND oferta_referral_token.id_tipo_share!=2";
	$promos = lecturaArray($sql);
	return $promos;
}

function cronRememberPromo()
{
	$result['exec'] = 'cronRememberShare';
	$emails_enviados = 0;
	
	//Mandamos un email por cada hotel/usuario 
	//un mismo usuario puede recibir varios emails de distintos hoteles 
	$sql = "SELECT DISTINCT users.id, users.email, users.lang, 
	CASE WHEN users.nombre='' THEN 'Guest' ELSE users.nombre END AS nombre, user_guid.guid AS user_guid, 
	oferta_referral_token.id_hotel, hotel_guid.guid,
    hoteles.hotelName AS nombre_hotel, hoteles.email AS email_hotel, hoteles.logo AS logo
  	FROM oferta_referral_token
	INNER JOIN users ON users.id=oferta_referral_token.id_usuario
	INNER JOIN user_guid ON user_guid.id_usuario=users.id
	INNER JOIN hotel_guid ON hotel_guid.id_hotel=oferta_referral_token.id_hotel
  	INNER JOIN hoteles ON hoteles.id=oferta_referral_token.id_hotel
	WHERE users.notif_hotelinking=1 AND (users.email!='' AND users.email!='undefined')
	AND oferta_referral_token.id_tipo_share!=2";
	if( ENV != 'production' )
		 $sql .= " LIMIT 5";
	$rows = lecturaArray($sql);

	foreach($rows AS $row){
		//Puede tener varios promo codes de un mismo hotel/cadena, los unificamos en un solo email
		$promos = obtenerPromoCodesPendientes($row['id'], $row['lang'], $row['id_hotel']);
		
		//Si no tiene nombre lo mandamos como 'guest'
		$row['nombre'] == '' ?	$nombre_destino = 'guest' : $nombre_destino = $row['nombre'];

		//Logo hotel/cadena
		global $urlTree;
		if($row['id_hotel']!=0)
		{
			$hotelLogo = $row['logo'];
		}else{
			$hotelLogo = $row['logo'];
		}

		$urlBaja = createUrlUnsuscribe($row['email'], $row['user_guid']);
		
		$lang = mirarIdiomaPlataforma($row['lang']);
		include RUTA_DIR.LANG.$lang.'/email/rememberPromo.php';
		include RUTA_DIR.LIB.'plantillasMails/rememberPromo.php';
		
		ENV == 'production'? $email_envio = $row['email'] : $email_envio = 'jaumehotelinking@gmail.com';

		mandarEmailMandrillPlantillaSoloContenido($email_envio, $nombre_destino, $asunto, $template_content, 'hotel-standard-template', $row['email_hotel'], $row['nombre_hotel']);

		$emails_enviados ++;
	}
	
	$result['emails_enviados'] = $emails_enviados;
	
	return $result;
}
//mandarEmailMandrill('j.cabrer@hotelinking.com', 'jaume', 'cron RSINV', 'cron RSINV ejecutado');
?>