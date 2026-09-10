<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

include_once RUTA_DIR . LIB . 'Facebook/keys.php';

function actualizarDatosFacebook ($id_usuario, $nombre, $totalAmigos, $img_usuario, $email, $token_fb, $lang='en', $gender = NULL, $fbLocale = NULL, $birthday=NULL, $location_name = NULL, $location_id = NULL)
{
	$con 			= conectar();
	$id_usuario 	= mysqli_real_escape_string($con, $id_usuario);
	$nombre 		= mysqli_real_escape_string($con, $nombre);
	$totalAmigos 	= mysqli_real_escape_string($con, $totalAmigos);
	$img_usuario 	= mysqli_real_escape_string($con, $img_usuario);
	$email 			= mysqli_real_escape_string($con, $email);
	$token_fb 		= mysqli_real_escape_string($con, $token_fb);
	$gender 		= mysqli_real_escape_string($con, $gender);
	$locale 		= mysqli_real_escape_string($con, $fbLocale);
	$birthday 		= mysqli_real_escape_string($con, $birthday);
	$location_name 	= mysqli_real_escape_string($con, $location_name);
	$location_id 	= mysqli_real_escape_string($con, $location_id);
	
	$sql = "UPDATE user_facebook, users
	SET user_facebook.nombre='$nombre', user_facebook.facebook_img='$img_usuario', user_facebook.amigos='$totalAmigos', 
	user_facebook.email='$email', user_facebook.token_fb='$token_fb', user_facebook.gender='$gender', user_facebook.locale='$locale', 
	user_facebook.birthday='$birthday', user_facebook.locationName='$location_name', user_facebook.locationID='$location_id',
	users.img='$img_usuario', users.fb_friends='$totalAmigos',  users.email ='$email'
	WHERE user_facebook.id_usuario='$id_usuario' AND users.id='$id_usuario'  ";
	escritura($sql, $con, false);
	desconectar($con);
}

function obtenerIdUsuarioIdFacebook($id_facebook)
{
	$con = conectar();
	$id_facebook = mysqli_real_escape_string($con, $id_facebook);
	$sql = "SELECT id_usuario FROM user_facebook WHERE id_facebook='$id_facebook' ";
	$row = lectura($sql, $con, false);
	desconectar($con);
	return $row['id_usuario'];
}
?>