<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

include_once LIB.'permisosUsuario.php';

function obtenerDatosHotelEncuesta($id_encuesta, $id_usuario){
	$sql = "SELECT user_encuestas.id AS id_encuesta, user_encuestas.id_hotel,
	hoteles.hotelName AS nombre_hotel, 
	hoteles.logo
	FROM user_encuestas
	INNER JOIN hoteles ON hoteles.id=user_encuestas.id_hotel
	WHERE user_encuestas.id='".$id_encuesta."' 
	AND user_encuestas.id_usuario='".$id_usuario."' AND done='0' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	return $row;
}

function obtenerDatosPuntos(){
	$sql = "SELECT action, points FROM action_points";
	$rs = mysqli_query (conectar(), $sql);

	while ($row = mysqli_fetch_assoc($rs)){
		$arrayPuntos[$row['action']]=$row['points'];
	}
	liberar($rs);
	return $arrayPuntos;
}
?>