<?php //Miramos si esta definida la variable de control de index.php
if (!defined('INDEXCONTROLVAL')) {echo 'No direct access allowed.';exit;}

//Contenido solo visible si logueado
include RUTA_DIR . LIB . 'logueado.php';
hotelStaffLanding(); // Si no esta logueado lo manda a la landing

include_once RUTA_DIR . LIB . 'paginacion2.php';
include_once RUTA_DIR . LIB . 'ordenacion.php';
include_once RUTA_DIR . LIB . 'obtenerdatosHotel.php';
include_once RUTA_DIR . LIB . 'obtenerDatosUsuario.php';
include_once RUTA_DIR . LIB . 'sanitize.php';

include './lang/' . array_get($_SESSION, 'userLang', 'en') . '/datamatch.php';

// For front purposes
$currentPage = 'datamatch';
$currentSubPage = 'datamatch-list';

$datamatch_activated = checkDatamatchActivated(array_get($_SESSION, 'h_logueado'));

if (!$datamatch_activated) {
    // $datamatch_redirect = str_replace('data-match','clients',$_SERVER['HTTP_REFERER'] );
    $datamatch_redirect = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . '/clients';

    Header('Location: ' . $datamatch_redirect . '/');
    exit();
}

// Datamatch CSV columns
$csvColumns = json_decode(DATAMATCH_COLUMNS, true);

// All fields in data fetched from integrations
$columnsArrayDatamatch = [
    'id', 'date_from', 'date_to', 'match_count', 'status', 'imported',
];

// Column status
$columnStatusDatamatch = array_search('status', $columnsArrayDatamatch);
// Column imported
$columnImportedDatamatch = array_search('imported', $columnsArrayDatamatch);

// Select the fields visibles in the datatable
$visiblesColumnsDatamatch = [
    'date_from', 'date_to', 'match_count', 'status', 'imported',
];

$visiblesColumnsIndexs = [];
foreach ($visiblesColumnsDatamatch as $value) {
    $index = array_keys($columnsArrayDatamatch, $value);
    if (!empty($index)) {
        array_push($visiblesColumnsIndexs, $index[0]);
    }
}

// Get the translate for fields to be used in modal
$translatedColumns = [];
foreach ($columnsArrayDatamatch as $value) {
    $translatedColumns[$value] = array_get($DataMatchLangs, $value);
}

$hotel_id = array_get($_SESSION, 'h_logueado');
$brand = getHotelBrand($hotel_id);
$brand_id = array_get($brand, 'id', '');
$brand_uid = array_get($brand, 'uuid', '');

// Campo ORDER BY
$pant = 'rfr'; // Pantalla
if (!empty($_GET['ord'])) {
    $order = $_GET['ord'];
    $_SESSION['ord' . $pant] = $_GET['ord'];
    $sort = toggle();
} else if (!empty($_SESSION['ord' . $pant])) {
    $order = $_SESSION['ord' . $pant];
    $sort = $_SESSION['ascdesc'];
} else {
    // Orden por defecto
    $order = 'total_spent';
    $sort = 'DESC';
}

//Urls para share de hotelDesk & user
$guidHotel = obtenerGUIDHotel($_SESSION['h_logueado']);

// El gestor de la cadena acaba de cambiar de hotel. Viene de 'chain-management' y solo tien RF
if (!empty($_GET['change'])) {
    if ($change = 'ok') {
        $ok = array(true, '2018');
    }
}
