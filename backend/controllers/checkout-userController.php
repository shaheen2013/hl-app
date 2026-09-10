<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//Contenido solo visible si logueado
include LIB.'logueado.php';
hotelStaffLanding ();// Si no esta logueado lo manda a la landing

include_once LIB.'staff_id_hotel.php';
include_once LIB.'paginacion2.php';
include_once LIB.'ordenacion.php';
include_once LIB.'convertirDivisas.php';
include_once LIB.'obtenerdatosHotel.php';
include_once LIB.'obtenerDatosUsuario.php';

// Campo ORDER BY
$pant='chk-ou-us'; // Pantalla
if(!empty($_GET['ord'])){
	$order = mysqli_real_escape_string(conectar(), $_GET['ord']);
	$_SESSION['ord'.$pant] = $_GET['ord'];
	$sort = toggle();
}else if(!empty($_SESSION['ord'.$pant])){
	$order = $_SESSION['ord'.$pant];
	$sort = $_SESSION['ascdesc'];
}else{
	// Orden por defecto
	$order = 'puntos';
	$sort = '';
}

$id_hotel = obtenerIdHotel();
$moneda = obtenerMonedaHotel($id_hotel);
//echo 'moneda: '.$moneda;

if (!empty($_GET['id'])){
	$id_usuario = mysqli_real_escape_string(conectar(), $_GET['id']);
	$datosUsuario = obtenerDatosUsuario($id_usuario, $id_hotel);
	$itemsPage = 100;
	$cuponesUsuario = obtenerCuponesUsuario($id_usuario, $id_hotel, $order, $sort, $itemsPage, $pagina);
}

/*echo '<pre>';
print_r($cuponesUsuario);
echo '</pre>';*/
?>