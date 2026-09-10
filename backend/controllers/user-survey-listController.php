<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//Contenido solo visible si logueado
include LIB.'logueado.php';
userLanding ();// Si no esta logueado lo manda a la landing

include_once LIB.'paginacion2.php';
include_once LIB.'ordenacion.php';
include_once LIB.'obtenerdatosHotel.php';
include_once LIB.'fecha.php';

// Campo ORDER BY
$pant='usl'; // Pantalla
if(!empty($_GET['ord'])){
	$order = mysqli_real_escape_string(conectar(), $_GET['ord']);
	$_SESSION['ord'.$pant] = $_GET['ord'];
	$sort = toggle();
}else if(!empty($_SESSION['ord'.$pant])){
	$order = $_SESSION['ord'.$pant];
	$sort = $_SESSION['ascdesc'];
}else{
	// Orden por defecto
	$order = 'id';
	$sort = 'DESC';
}

$itemsPage = 10;
$arrayEncuestas = obtenerEncuestas($_SESSION['u_logueado'], $order, $sort, $itemsPage, $pagina);

/*echo '<pre>';
print_r($arrayEncuestas);
echo '</pre>';*/
?>