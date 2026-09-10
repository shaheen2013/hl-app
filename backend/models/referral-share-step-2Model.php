<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

function obtenerDatosToken($token){
	$token = mysqli_real_escape_string(conectar(), $token);
	$sql = "SELECT referrer_tokens.id_usuario, user_encuestas.id_hotel, 
	referrer_tokens.id_encuesta AS id_share, users.email, users.nombre
	FROM referrer_tokens 
  	INNER JOIN user_encuestas ON user_encuestas.id=referrer_tokens.id_encuesta
	INNER JOIN users ON users.id=referrer_tokens.id_usuario
	WHERE referrer_tokens.token='".$token."' AND share=1
  	ORDER BY referrer_tokens.fecha DESC LIMIT 1";
	$rs = mysqli_query (conectar(), $sql) or die(mysqli_error());
	$n_resultados = mysqli_num_rows($rs);
	if($n_resultados=='0'){
		$row['code']='404';//token incorrecto
	}else{
		$row = mysqli_fetch_assoc($rs);
		$row['code']='200';
	}
	liberar ($rs);
	return $row;
}

//Miramos si la cuenta de usuario esta activada
function usuarioActivado($id_usuario){
	$sql = "SELECT created FROM users WHERE id='".$id_usuario."' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	if($row['created']=='0000-00-00 00:00:00'){
		return false;
	}else{
		return true;
	}
}
?>