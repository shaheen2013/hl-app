<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//Content only avaliable if logged
include LIB . 'logueado.php';
hotelStaffLanding ();

// For front purposes
$currentPage = 'satisfaction-list';
$currentSubPage = 'satisfaction-users';

include_once LIB . 'obtenerDatosStaff.php';
include_once LIB.'paginacion2.php';
include_once LIB.'ordenacion.php';

$hotel_id = obtenerIdHotelStaff();

//Order By
$pant='stf-usr';
if(!empty($_GET['ord'])){
	$order = $_GET['ord'];
	$_SESSION['ord'.$pant] = $_GET['ord'];
	$sort = toggle();
}else if(!empty($_SESSION['ord'.$pant])){
	$order = $_SESSION['ord'.$pant];
	$sort = $_SESSION['ascdesc'];
}else{
	// Orden por defecto
	$order = 'nombre';
	$sort = 'DESC';
}


$hotel_id = (!empty($_SESSION['h_logueado']) ? $_SESSION['h_logueado'] : $hotel_id);

$urlActual = strtok($_SERVER['REQUEST_URI'],'?'); //Actual URL
$urlSearch = array_get($_GET, 'search'); //Url param

$urlOldGet = array_get(explode("ord", $_SERVER['QUERY_STRING']), "0");
$urlOldGet = $urlOldGet ? $urlOldGet.'&':'';

$itemsPage = 10;

$users_warnings_finished = getStaffWhoMarkSatisfactionFinished($hotel_id, $itemsPage, $pagina, $urlSearch, $order, $sort); 
$main_account_warnings_finished = getMainAccountWhoMarkSatisfactionFinished($hotel_id);
?>