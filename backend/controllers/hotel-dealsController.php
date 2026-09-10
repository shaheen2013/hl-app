<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

	//Include sanitize library
	include_once LIB . 'sanitize.php';
	//Asignar ID hotel
	$id_hotel = $_SESSION['id_hotel'] = mysqli_real_escape_string(conectar(), $url['dir3']);
	//Datos del hotel
	//Recoger toda la información de los Deals de este hotel
	$arrayDealsHotel = obtenerDealsHotel($id_hotel);
 ?>