<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//Contenido solo visible si logueado
include LIB.'logueado.php';
hotelStaffLanding ();// Si no esta logueado lo manda a la landing

include_once LIB.'paginacion2.php';
include_once LIB.'ordenacion.php';
include_once LIB.'obtenerdatosHotel.php';
include_once LIB.'obtenerDatosUsuario.php';

// Datamatch activated verification
$datamatch_activated = checkDatamatchActivated($_SESSION['h_logueado']);

// For front purposes
$currentPage = 'clients-management';
$currentSubPage = 'referrals-management';

// Campo ORDER BY
$pant='rfer'; // Pantalla
if(!empty($_GET['ord'])){
	$order = $_GET['ord'];
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

$urlActual = strtok($_SERVER['REQUEST_URI'],'?'); //Url actual sin parametros

$itemsPage = 10;
if (!empty($_GET['search'])){
	$busqueda = $_GET['search'];
	$arrayUsuarios = obtenerUsuarios($_SESSION['h_logueado'], $busqueda,$order, $sort, $itemsPage, $pagina); 
}else if ( !empty($url['dir2']) && !empty($url['dir3']) ){
	// Los parametros $_GET['filter'] y $_GET['us'] vienen de la pantalla 'referrals'
	// Puede tener los valores: trfr (total referrals), rfr(new referrals), gst (guest)
	$filter = $url['dir3'];
	$id_usuario = $url['dir2'];
	$arrayUsuarios = obtenerUsuarios($_SESSION['h_logueado'], '',$order, $sort, $itemsPage, $pagina, $filter, $id_usuario);
}else{
	$arrayUsuarios = obtenerUsuarios($_SESSION['h_logueado'], '',$order, $sort, $itemsPage, $pagina);
}

$totalUsuarios = array_get($arrayUsuarios, 'total');
unset($arrayUsuarios['total']);

$monedaHotel = obtenerMonedaHotel($_SESSION['h_logueado']);

//Urls para share de hotelDesk & user
$guidHotel = obtenerGUIDHotel($_SESSION['h_logueado']);

/*echo '<!--<pre>';
print_r($arrayUsuarios);
echo '</pre>-->';*/
?>