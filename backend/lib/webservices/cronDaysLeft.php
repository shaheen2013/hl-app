<?php
//Cuando quedan 5 dias para caducar un cupón, se le manda un email de aviso al usuario

// Este cron es de LY. No se usa. 
// Revisar si se vuelve a activar LY. 
// (No mandar correo si el usuario no tiene las notificaciones activadas)

//include_once 'librerias.php';// Librerias básicas
//include_once RUTA_DIR.LIB.'enviarEmail.php';

function cronDaysLeft()
{
	$sql = "SELECT hotel_oferta.id AS id_oferta, 
	case when oferta_lang.nombre is null 
    then   oferta_en.nombre 
    else oferta_lang.nombre end AS nombre_oferta, fin,
	DATE(fecha) AS fecha_adquis, hotelName AS nombre_hotel, users.email, users.lang, 
	CASE WHEN users.nombre!='' THEN users.nombre 
	WHEN user_twitter.twitter_user!='' THEN user_twitter.twitter_user
	ELSE user_facebook.nombre 
	END AS nombre,
	CASE WHEN fin!=0000-00-00 THEN DATEDIFF(fin, NOW()) END AS days_left
	FROM user_cupones
	INNER JOIN hotel_oferta ON hotel_oferta.id=user_cupones.id_oferta
	INNER JOIN hoteles ON hoteles.id=hotel_oferta.id_hotel
	INNER JOIN users ON users.id=user_cupones.id_usuario
	LEFT JOIN hotel_oferta_lang as oferta_en on hotel_oferta.id = oferta_en.id_oferta  and oferta_en.lang='en' 
    LEFT JOIN hotel_oferta_lang as oferta_lang on hotel_oferta.id = oferta_lang.id_oferta and oferta_lang.lang= users.lang 
	LEFT JOIN user_twitter ON user_twitter.id_usuario=users.id
	LEFT JOIN user_facebook ON user_facebook.id_usuario=users.id
	WHERE canjeado=0 AND fin!=0000-00-00 AND users.notif_hotelinking=1
	GROUP BY users.id  ";
	$sql .= " HAVING days_left=5";// dias para caducar
	
	$rs = mysqli_query (conectar(), $sql);
	while($row = mysqli_fetch_assoc($rs)){
		//Ahora mandamos con idioma en porque solo tenemos ese idioma, 
		//pero debemos de hacerlo en el idioma del usuario $row['nombre']
		//include RUTA_DIR.LANG.'email/cronDaysLeft-'.$row['nombre'].'.php';
		include RUTA_DIR.LANG.'en/email/cronDaysLeft.php';
		include RUTA_DIR.LIB.'plantillasMails/cronDaysLeft.php';
		mandarEmailMandrillPlantilla($row['email'], $row['nombre'], $asunto, $cuerpo,'standard-template');
	}
	liberar($rs);
}




?>
