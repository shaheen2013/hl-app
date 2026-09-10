<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}


//Los token de share son unicos para usuario/hotel, si ya tiene lo devolvemos para ser nuevamente usado
function obtenerTokenShareUsuario($id_usuario, $id_hotel)
{
	$con = conectar();
    $id_usuario = mysqli_real_escape_string($con, $id_usuario);
    $id_hotel = mysqli_real_escape_string($con, $id_hotel);

	$sql = "SELECT token 
	FROM referrer_tokens 
	WHERE referrer_tokens.id_usuario='".$id_usuario."' AND referrer_tokens.id_hotel='".$id_hotel."' 
	ORDER BY referrer_tokens.id DESC LIMIT 1";
	$row = lectura($sql, $con, false);
	desconectar($con);
	
	return $row['token'];
}
?>