<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

function usuarioExiste($token){
	$sql = "SELECT COUNT(users.id) AS n
	FROM users
	INNER JOIN invitaciones_users ON invitaciones_users.email=users.email
	WHERE invitaciones_users.token='".$token."' AND users.verificado!='0'";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	if($row['n']=='0'){
		//Usuario no ha activado su cuenta
		return false;
	}else{
		//Usuario ha activado su cuenta
		return true;
	}
}

function obtenerDatosUsuario($token){
	$sql = "SELECT users.id, users.email, invitador, tipo_invitador, id_encuesta, user_encuestas.id_hotel
	FROM users
	INNER JOIN invitaciones_users ON invitaciones_users.email=users.email
	LEFT JOIN user_encuestas ON user_encuestas.id=invitaciones_users.id_encuesta
	WHERE invitaciones_users.token='".$token."' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	return $row;
}
?>