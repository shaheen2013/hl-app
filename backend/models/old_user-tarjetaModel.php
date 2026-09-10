<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

include_once LIB.'permisosUsuario.php';

function obtenerDatosTarjeta($id_usuario){
	$sql = "SELECT id_tarjeta, nombre FROM users WHERE id='".$id_usuario."' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	return ($row);
}
?>