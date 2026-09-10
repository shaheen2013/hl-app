<?php //Miramos si esta definida la variable de control de index.php
if (!defined('INDEXCONTROLVAL')) {echo 'No direct access allowed.';exit;}
//Contenido solo visible si logueado
include LIB . 'logueado.php';
include_once LIB . 'apiGateway.php';
include_once APP . 'Services/Connections/ApiGatewayConnection.php';

hotelStaffLanding(); // Si no esta logueado lo manda a la landing

// For front purposes
$currentPage = 'clients-management';
$currentSubPage = 'clients-management';

include LANG . $_SESSION['userLang'] . '/clients.php';

include_once LIB . 'obtenerdatosHotel.php';

$hotel_id = array_get($_SESSION, 'h_logueado', array_get($_SESSION, 'staff_id_hotel'));

$guidHotel = obtenerGUIDHotel($hotel_id);
$clientsConfiguration = isset($_SESSION['clientsConfiguration_' . $_SESSION['loggedBrandID']]) ? $_SESSION['clientsConfiguration_' . $_SESSION['loggedBrandID']] : null;
$_SESSION['clientsColumns'] = array(
    'client_name' => $clientsLang['name'],
    'client_email' => $clientsLang['email'],
    'brand_name' => $clientsLang['Hotel Name'],
    'first_login' => $clientsLang['First Login'],
    'check_in'  => $clientsLang['Check-in'],
    'check_out' => $clientsLang['Check-out'],
    'channel' => $clientsLang['Channel'],
    'agency' => $clientsLang['Agency'],
    'unsubscribed' => $clientsLang['subscribed'],
    'room_number' => $clientsLang['Room'],
);


$_SESSION['exportClientsColumns'] = array(
    'name' => $clientsLang['name'],
    'email' => $clientsLang['email'],
    'hotel_name' => $clientsLang['Hotel Name'],
    'birthday' => $clientsLang['Birthday'],
    'country' => $clientsLang['country'],
    'language' => $clientsLang['Language'],
    'gender' => $clientsLang['Gender'],
    'location' => $clientsLang['Location'],
    'fb_friends' => $clientsLang['Friends'],
    'last_login' => $clientsLang['Last Login'],
    'first_login' => $clientsLang['First Login'],
    'pms_id' => $clientsLang['pms_id'],
    'id_card' => $clientsLang['ID Card'],
    'source' => $clientsLang['Source'],
    'numVisitsToOurHotel' => $clientsLang['numVisitsToOurHotel'],
    'numVisitsToOurChain' => $clientsLang['numVisitsToOurChain'],
    'unsubscribed' => $clientsLang['unsubscribed'],
    'Room' => $clientsLang['Room'],
    'agency' => $clientsLang['Agency'],
);


if (!$clientsConfiguration) {
    $portalConfigId = 24;
    $portalconfigEndpoint= HOTELINKING_ENDPOINT . "brands/{$_SESSION['loggedBrandID']}/products/" . $portalConfigId . "/config"; 
    $gateway = new ApiGatewayConnection();
    $clientsConfiguration = $_SESSION['clientsConfiguration_' . $_SESSION['loggedBrandID']] = safeJsonParser($gateway->sendRequest([], $portalconfigEndpoint, 'GET'), true);
}

$phoneFormIsActive = $clientsConfiguration['config']['phone_active'] ?? false;
if($phoneFormIsActive){
    $_SESSION['clientsColumns']['phone_number'] = $clientsLang['Phone'];
    $_SESSION['exportClientsColumns']['phone_number'] = $clientsLang['Phone'];
}


if (hotelHasProduct('satisfaction')) {
    array_insert($_SESSION['exportClientsColumns'], sizeof($_SESSION['exportClientsColumns']), array('satisfaction' => $clientsLang['Satisfaction']));
    array_insert($_SESSION['exportClientsColumns'], sizeof($_SESSION['exportClientsColumns']), array('comentario' => $clientsLang['Comments']));
}
