<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//Contenido solo visible si logueado
include LIB.'logueado.php';
userLanding ();// Si no esta logueado lo manda a la landing

if(!empty($_GET['id'])){
	$id_encuesta = mysqli_real_escape_string(conectar(), $_GET['id']);
	$datosHotel = obtenerDatosHotelEncuesta($id_encuesta, $_SESSION['u_logueado']);
}

$arrayPuntos = obtenerDatosPuntos();
?>