<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//Contenido solo visible si logueado
include LIB . 'logueado.php';
hotelStaffLanding ();// Si no esta logueado lo manda a la landing

include_once LIB . 'obtenerDatosStaff.php';
$id_hotel = obtenerIdHotelStaff();
$id_cadena = obtenerIdCadenaStaff();
// For front purposes
$currentPage = 'offer-management';
$currentSubPage = 'voucher-management';

include_once LIB . 'paginacion2.php';
include_once LIB . 'ordenacion.php';
include_once LIB . 'paramsUrl.php';
include_once LIB . 'cuponAcciones.php';
include_once LIB . 'obtenerDatosUsuario.php';

// Campo ORDER BY
$pant='ho-li-cu'; // Pantalla
if(!empty($_GET['ord'])){
	$_SESSION['ord'.$pant] = $order = $_GET['ord'];
	$sort = toggle();
}else if(!empty($_SESSION['ord'.$pant])){
	$order = $_SESSION['ord'.$pant];
	$sort = $_SESSION['ascdesc'];
}else{
	// Orden por defecto
	$order = 'canjeado ASC, fecha';
	$sort = 'DESC';
}

// Esta pantalla tiene la pagina en el dir2
!empty($url['dir2']) && is_numeric($url['dir2'])? $pagina = $url['dir2'] : $pagina = 1;
$pagDir = $url['dir1']; // Directorio antes de la pagina ($url['dir1'])

// Busca cupon por id de voucher
$itemsPage = 10;
if(!empty($_GET['search']))
{
	$search = $_GET['search'];
	$arrayCupones = obtenerListadoCupones($id_hotel,$order,$sort, $itemsPage, $pagina, $search);
}else{
	$arrayCupones = obtenerListadoCupones($id_hotel, $order, $sort, $itemsPage, $pagina);
}

/*echo '<pre>';
print_r($arrayCupones);
echo '</pre>';*/
?>