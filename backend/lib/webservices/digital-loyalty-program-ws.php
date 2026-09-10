<?php
include 'librerias.php';// Librerias básicas

// Restringir ips que pueden acceder
include_once RUTA_DIR.LIB.'check_access.php';
checkIpAccess('fblogsh', $_SERVER['REMOTE_ADDR']);

include_once RUTA_DIR . LIB . 'webservices/msgFeedback.php';
include_once RUTA_DIR . LIB . 'obtenerdatosHotel.php';
include_once RUTA_DIR . LIB . 'seguridadHotel.php';

// Con los datos que nos devuelve facebook hacemos un string. 
// Después en el controlador comprobaremos este sring para verificar que los datos $_POST son los mismos
if (leerSeguridadWSHotel($_POST['hotelId'], $_POST['wsSec']) ){
	$id = $_POST['id'];
	$name = $_POST['name'];
	$email = $_POST['email'];
	$nFriends = $_POST['nFriends'];
	
	// Hacemos un hash con los datos de facebook + un string nuestro ($control)
	$control = 'HL#camaiot@';
	$fbSec = sha1($id.$control.$name.$control.$email);
	
	$result['code'] = 200;
	$result['fbSec'] = $fbSec;
	echo json_encode($result);
}else{
	$result['code'] = 400;
	$result['fbSec'] = '';
	echo json_encode($result);
}
?>