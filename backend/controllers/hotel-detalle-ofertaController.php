<?php //Miramos si esta definida la variable de control de index.php
if (!defined('INDEXCONTROLVAL')) {echo 'No direct access allowed.';exit;}

//Contenido solo visible si logueado
include LIB . 'logueado.php';
hotelStaffLanding(); // Si no esta logueado lo manda a la landing

include_once LIB . 'obtenerDatosStaff.php';
$id_hotel = obtenerIdHotelStaff();
$id_cadena = obtenerIdCadenaStaff();

// For front purposes
$currentPage = 'offer-management';
$currentSubPage = 'offer-management';

include_once LIB . 'obtenerDatosUsuario.php';
include_once LIB . 'enviarEmail.php';
include_once LIB . 'cuponAcciones.php'; //Canjear 1 cupon
include_once LIB . 'sanitize.php';
include_once LIB . 'ordenacion.php';
include_once LIB . 'paginacion2.php';
include_once LIB . 'fecha.php';

// Campo ORDER BY
$pant = 'hot-det-of'; // Pantalla
if (!empty($_GET['ord'])) {
    $order = $_GET['ord'];
    $_SESSION['ord' . $pant] = $order;
    $sort = toggle();
} else if (!empty($_SESSION['ord' . $pant])) {
    $order = $_SESSION['ord' . $pant];
    $sort = $_SESSION['ascdesc'];
} else {
    // Orden por defecto
    //Si el usuario solicita nuevamente una oferta actualizamos el campo fecha_last_modified
    // y aparecera el primero sin que le de una nueva oferta
    $order = 'fecha_last_modified';
    $sort = 'DESC';
}

// Esta pantalla tiene la pagina en el dir3
!empty($url['dir3']) && is_numeric($url['dir3']) ? $pagina = $url['dir3'] : $pagina = 1;
$pagDir = $url['dir1'] . '/' . $url['dir2']; // Directorio antes de la pagina ($url['dir1']/$url['dir2'])

$id_oferta = $url['dir2'];
$info = parse_url($_SERVER["REQUEST_URI"]);
$urlNoParams = $info['path'];

$arrayDatosOferta = obtenerDatosOferta($id_oferta, $id_hotel, $id_cadena);
$goalsOferta = obtenerGoalsOferta($id_oferta);

$itemsPage = 10;
if (!empty($_GET['search'])) {
    $arrayCupones = obtenerCuponesOferta($id_oferta, $id_hotel, $order, $sort, $itemsPage, $pagina, $arrayDatosOferta['adq_ret'], $_GET['search']);
} else {
    $arrayCupones = obtenerCuponesOferta($id_oferta, $id_hotel, $order, $sort, $itemsPage, $pagina, $arrayDatosOferta['adq_ret']);
}

/*echo '<pre>';
print_r($arrayCupones);
echo '</pre>';*/
