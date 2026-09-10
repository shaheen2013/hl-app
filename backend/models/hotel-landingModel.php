<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

include_once LIB.'fecha.php';
include_once LIB.'enviarEmail.php';

function guardarDatosUserLanding($Website, $email){
	$fecha = dateTimeHoy();
	// guardamos los datos en invitaciones pendientes usuario
	$sql = "INSERT INTO invitaciones_hotel_pendientes (website, email,  fecha) VALUES ('".$Website."', '".$email."', '".$fecha."')";
	mysqli_query (conectar(), $sql);
}
?>