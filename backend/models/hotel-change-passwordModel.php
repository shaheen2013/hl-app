<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

function cambiarContrasenaHotel($pass, $id_hotel){
	$sql = "UPDATE hoteles SET password='".sha1($pass)."' WHERE id='".$id_hotel."'";
	mysqli_query(conectar(), $sql);
}

function getPasswordHotel($hotel_id){
	$con = conectar(1);
	$sql = "SELECT password FROM hoteles where id = $hotel_id";
	$row = lectura($sql,$con);
	return $row;
}

?>