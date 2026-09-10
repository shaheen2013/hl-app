<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//FX para generar un token hasheando parametros fijos de la cuenta del usuario (id, id_tarjeta, email)
//Devolvemos: id_usuario, token (hash)
/*function generaraTokenBajaMailing($email)
{
	$email = mysqli_real_escape_string(conectar(), $email);
	
	$sql = "SELECT id, id_tarjeta, email
	FROM users WHERE email='".$email."' ";
	$rs = mysqli_query (conectar(), $sql) or die (mysqli_error());
	$row = mysqli_fetch_assoc($rs);
	liberar ($rs);
	
	!empty($row) ? $token = sha1($row['id'].$row['id_tarjeta'].$row['email']) : $token = '';
	
	$data['id_usuario']=$row['id'];
	$data['token']=$token;
	
	return $data;
}

function quitarNotificaciones($id_usuario)
{
	$id_usuario = mysqli_real_escape_string(conectar(), $id_usuario);
	
	$sql = "UPDATE users 
	SET notif_hotelinking=0, notif_especiales=0, compart_hoteles=0 
	WHERE id='".$id_usuario."' ";
	mysqli_query (conectar(), $sql) or die (mysqli_error());
}*/
?>