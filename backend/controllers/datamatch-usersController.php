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
include_once RUTA_DIR . LIB . 'hotelinking_integrations.php';

include './lang/' . array_get($_SESSION, 'userLang', 'en') . '/datamatch-users.php';

// If datamatch is not activated - redirect
$datamatch_activated = checkDatamatchActivated(array_get($_SESSION, 'h_logueado'));
$datamatch_id = array_get($url, 'dir2');

// Cases to make redirect
if (!$datamatch_activated) {
    $datamatch_redirect = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . '/clients';
    Header('Location: ' . $datamatch_redirect . '/');
    exit();
} else if ($datamatch_id == DEFAULT_DIR2) {
    $datamatch_redirect = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . '/datamatch';
    Header('Location: ' . $datamatch_redirect . '/');
    exit();
}

// For front purposes
$currentPage = 'datamatch';
$currentSubPage = 'datamatch-users';

// Ids
$hotel_id = array_get($_SESSION, 'h_logueado');
$brand = getHotelBrand($hotel_id);
$brand_id = array_get($brand, 'id');

// Fetch datamatch_id
$datamatch = showDatamatchForBrand($brand_id, $datamatch_id);

// All fields in data fetched from integrations
$columnsArrayDatamatch = [ 'nombre', 'fecha_nacimiento', 'sexo', 'pais', 'email', 'confidence', 'pax_type', 'first_name', 'last_name', 'gender', 'birthday', 'nationality', 'document_id', 'telephone', 'birth_country', 'address', 'city', 'province', 'postal_code', 'hotel_name', 'check_in', 'check_out', 'res_room_number', 'res_room_type', 'residence_country', 'res_board', 'pms_id', 'res_id', 'res_amount', 'res_extras', 'res_currency', 'res_date', 'res_agency', 'res_intermediary', 'res_company', 'res_channel', 'res_nights', 'res_adults', 'res_juniors', 'res_children', 'res_babies', 'res_seniors', 'res_comments', 'subscribed'
];

// Get the index of the limit to put a divisor in datatable
$columnDivisorDatamatch = array_search('first_name', $columnsArrayDatamatch);

// Select the fields visibles in the datatable
$visiblesColumnsDatamatch = [
    // 'nombre', 'fecha_nacimiento', 'sexo', 'pais', 'first_name', 'last_name', 'gender', 'birthday', 'nationality', 'check_in', 'check_out',
    'first_name', 'last_name', 'email', 'gender', 'birthday', 'nationality', 'check_in', 'check_out', 'subscribed',
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
    $translatedColumns[$value] = array_get($DataMatchUsersLangs, $value);
}

// Set a schema for organize data in modal
$schemaDatamatchModal = [
    'user_data' => [
        'first_name', 'last_name', 'email', 'gender', 'birthday', 'nationality', 'pax_type', 'document_id', 'address', 'city', 'province', 'postal_code', 'telephone', 'birth_country', 'residence_country', 'subscribed',
    ],
    'reservation_data' => [
        'res_id', 'pms_id', 'res_room_number', 'res_room_type', 'res_board', 'res_agency', 'res_intermediary', 'res_company', 'res_channel', 'check_in', 'check_out', 'res_date', 'res_nights', 'res_adults', 'res_juniors', 'res_children', 'res_babies', 'res_seniors', 'res_amount', 'res_extras', 'res_currency', 'res_comments',
    ],
];

$visiblesColumnsSchemaUserIndexs = [];
foreach ($schemaDatamatchModal['user_data'] as $value) {
    $index = array_keys($columnsArrayDatamatch, $value);
    if (!empty($index)) {
        array_push($visiblesColumnsSchemaUserIndexs, $index[0]);
    }
}

$visiblesColumnsSchemaReservationIndexs = [];
foreach ($schemaDatamatchModal['reservation_data'] as $value) {
    $index = array_keys($columnsArrayDatamatch, $value);
    if (!empty($index)) {
        array_push($visiblesColumnsSchemaReservationIndexs, $index[0]);
    }
}

// // Set a schema for organize data in modal
// $schemaDatamatchModal = [
//     'hotelinking_data' => [
//         'user_data' => [
//             // 'nombre', 'fecha_nacimiento', 'sexo', 'pais', 'email',
//         ],
//         'hotel_data' => [
//             'hotel_name',
//         ],
//     ],
//     'datamatch_data' => [
//         'user_data' => [
//             'first_name', 'last_name', 'gender', 'birthday', 'nationality', 'pax_type', 'document_id', 'address', 'city', 'province', 'postal_code', 'telephone', 'birth_country', 'residence_country',
//         ],
//         'reservation_data' => [
//             'res_id', 'pms_id', 'res_room_number', 'res_room_type', 'res_board', 'res_agency', 'res_intermediary', 'res_company', 'res_channel', 'check_in', 'check_out', 'res_date', 'res_nights', 'res_adults', 'res_juniors', 'res_children', 'res_babies', 'res_seniors', 'res_amount', 'res_extras', 'res_currency', 'res_comments',
//         ],
//     ],
// ];

// Check if the datamatch is for only hotel or all hotels in a chain
$by_chain = array_get($_POST, 'datamatch_by_chain') == "1" ? true : false;

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
