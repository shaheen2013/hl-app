<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

function obtenerRedesSociales($id_usuario){
	$sql = "SELECT
	(SELECT COUNT(id) FROM user_facebook WHERE id_usuario='".$id_usuario."') AS facebook,
	(SELECT COUNT(id) FROM user_twitter WHERE id_usuario='".$id_usuario."') AS twitter ";
	//echo $sql;
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar ($rs);
	return ($row);
}

function obtenerNRedesSociales($id_usuario){
	$sql = "SELECT
	(SELECT COUNT(id) FROM user_facebook WHERE id_usuario='".$id_usuario."') AS facebook,
	(SELECT COUNT(id) FROM user_twitter WHERE id_usuario='".$id_usuario."') AS twitter ";
	//echo $sql;
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar ($rs);
	$nRedesSociales[0] = $row['facebook']+$row['twitter']; // Redes sociales vinculadas
	$nRedesSociales[1] = 2; // Total redes sociales
	return $nRedesSociales;
}
function guardarTokenVincularSM($id_usuario, $token, $red_social, $ruta){
	$sql = "SELECT COUNT(id) AS n FROM vincular_redes_sociales
	WHERE id_usuario='".$id_usuario."' AND red_social='".$red_social."' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar ($rs);
	if($row['n']==0){
		$sql2 = "INSERT INTO vincular_redes_sociales (id_usuario, token, red_social, ruta) VALUES
		( '".$id_usuario."', '".$token."', '".$red_social."', '".$ruta."')";
	}else{
		$sql2 = "UPDATE vincular_redes_sociales SET token='".$token."', ruta='".$ruta."'
		WHERE id_usuario='".$id_usuario."' AND red_social='".$red_social."' ";
	}
	mysqli_query (conectar(), $sql2);
}

function vincularTwitterUsuario($id_usuario, $id_twitter, $username, $followers, $img, $lang, $location
,$oauth_token, $oauth_token_secret){
	//mirar que este usuario no exista
	$sql1 = "SELECT COUNT(id) AS n FROM user_twitter WHERE id_twitter = '".$id_twitter."' ";
	$rs1 = mysqli_query (conectar(), $sql1);
	$row1 = mysqli_fetch_assoc($rs1);
	liberar ($rs1);
	if($row1['n']==0){
		//alta bd
		//Guardamos los datos de twitter
		$sql3 = "INSERT INTO user_twitter
		(twitter_user, id_twitter, twitter_followers, twitter_img, id_usuario, lang, location,
		oauth_token, oauth_token_secret) VALUES
		('".$username."', '".$id_twitter."', '".$followers."', '".$img."', '".$id_usuario."',
		'".$lang."', '".$location."', '".$oauth_token."', '".$oauth_token_secret."')";
		mysqli_query (conectar(), $sql3);

		$sql4 = "UPDATE users SET img='".$img."', tw_followers='".$followers."'
		WHERE id='".$id_usuario."' ";
		mysqli_query (conectar(), $sql4);
	}else{
		//Ya existe este usuario de twitter. No vinculamos
	}
}

function vincularFacebookUsuario($id_usuario, $id_facebook, $nombre, $facebook_img, $gender, $link, $amigos, $token_fb){
	//mirar que este usuario no exista
	$sql1 = "SELECT COUNT(id) AS n FROM user_facebook WHERE id_facebook = '".$id_facebook."' ";
	$rs1 = mysqli_query (conectar(), $sql1);
	$row1 = mysqli_fetch_assoc($rs1);
	liberar ($rs1);
	if($row1['n']==0){
		$sql = "INSERT INTO user_facebook
		(id_usuario, id_facebook, nombre, facebook_img, gender, link, amigos, token_fb)
		VALUES
		('".$id_usuario."', '".$id_facebook."', '".$nombre."', '".$facebook_img."', '".$gender."', '".$link."'
		, '".$amigos."', '".$token_fb."')";
		//echo '<br>'.$sql;
		mysqli_query (conectar(), $sql);

		$sql4 = "UPDATE users SET img='".$facebook_img."' , fb_friends='".$amigos."'
		WHERE id='".$id_usuario."' ";
		mysqli_query (conectar(), $sql4);
	}else{
		//Ya existe este usuario de twitter. No vinculamos
	}
}

function borrarCookieLSM(){
	if (isset($_COOKIE['token_lsm'])) {
    	unset($_COOKIE['token_lsm']);
	}
}

function borrarLSMdeBD($id){
	$sql = "DELETE FROM vincular_redes_sociales WHERE id='".$id."' ";
	mysqli_query (conectar(), $sql);
}

if(!empty($_POST['btn-facebook']) || !empty($_POST['btn-twitter']) ){
	if(!empty($_POST['btn-facebook'])){
		$social_media='fb';
	}
	if(!empty($_POST['btn-twitter'])){
		$social_media='tw';
	}

	//volvemos a mirar las redes sociales que tiene este usuario
	$redesSociales = obtenerRedesSociales($_SESSION['u_logueado']);

	//generar token
	do{// Si el token ya existe lo volvemos a generar
		$token = generarTokenAN(100);
	} while (tokenRepetido($token, 'vincular_redes_sociales'));

	//Guardar token en BD
	guardarTokenVincularSM($_SESSION['u_logueado'], $token, $social_media, $_SERVER['REQUEST_URI']);

	//crear cookie con token
	setcookie ('token_lsm', $token, 0 ,'/', '', true, true);

	//Redireccionar
	if ($social_media=='fb'){
		//redireccionamos a facebook
		header('Location: /'.$urlTree['facebook-login']);
	}else if ($social_media=='tw'){
		include_once(LIB.'twitter-async/EpiCurl.php');
		include_once(LIB.'twitter-async/EpiOAuth.php');
		include_once(LIB.'twitter-async/EpiTwitter.php');
		include_once(LIB.'twitter-async/keyTwitter.php');//keys winhotel / hotelinking
		$twitterObj = new EpiTwitter($consumer_key, $consumer_secret);
		$authenticateUrl = $twitterObj->getAuthenticateUrl();
		//redireccionamos a twitter
		header('Location: '.$authenticateUrl.'');
	}
}

function obtenerIdHotelOferta($id_oferta){
	$sql = "SELECT id_hotel FROM hotel_oferta WHERE id='".$id_oferta."' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	return $row['id_hotel'];
}
?>