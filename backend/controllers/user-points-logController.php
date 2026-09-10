<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//Contenido solo visible si logueado
include LIB.'logueado.php';
userLanding ();// Si no esta logueado lo manda a la landing

include_once LIB.'paginacion2.php';
include_once LIB.'ordenacion.php';
include_once LIB.'regalarPuntos.php';
include_once LIB.'obtenerDatosUsuario.php';
include_once LIB.'obtenerdatosHotel.php';
include_once LIB.'obtenerDatosCadena.php';
include_once LIB.'fecha.php';

// Campo ORDER BY
$pant='us-po-reg'; // Pantalla
if(!empty($_GET['ord'])){
	$order = mysqli_real_escape_string(conectar(), $_GET['ord']);
	$_SESSION['ord'.$pant] = $_GET['ord'];
	$sort = toggleArray();
}else if(!empty($_SESSION['ord'.$pant])){
	$order = $_SESSION['ord'.$pant];
	$sort = $_SESSION['ascdesc'];
}else{
	// Orden por defecto
	$order = 'id';
	$sort = 'DESC';
}

$itemsPage = 30;
$arrayPuntosUsuario = obtenerPuntosUsuarioReg($_SESSION['u_logueado'], $itemsPage, $pagina);
//$arrayPuntosUsuario = orderMultiDimensionalArray($arrayPuntosUsuario, $order, $sort);
//$arrayPuntosUsuario = paginacion($arrayPuntosUsuario,  $pagina, '20');
// array para la select de regalar puntos
$arrayPuntosHotel = obtenerPuntosHotel($_SESSION['u_logueado']);
$arrayPuntosCadena = obtenerPuntosCadena($_SESSION['u_logueado']);
$arraySelectPuntos = crearSelectPuntos($arrayPuntosHotel, $arrayPuntosCadena);

/*echo '<pre>';
print_r($arrayPuntosUsuario);
echo '</pre>';*/
?>