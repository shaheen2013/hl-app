<?php //Miramos si esta definida la variable de control de index.php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}

//Contenido solo visible si logueado
include LIB . 'logueado.php';
hotelStaffLanding();// Si no esta logueado lo manda a la landing
require_once __DIR__ . '/../src/Services/Connections/ApiGatewayConnection.php';

$currentPage = 'hotel-management';
$currentSubPage = 'login-configuration';
$brandId = $_SESSION['loggedBrandID'];
$hotelId = $_SESSION['h_logueado'];
$gateway = new ApiGatewayConnection();

if (isset($_POST['accessTypeId']) && isset($_POST['active'])) {
    try {
        $response = $gateway->sendRequest(
            ['active' => $_POST['active'] == "true"], 
            HOTELINKING_ENDPOINT . 'brands/' . $brandId . '/access_types/' . $_POST['accessTypeId'], 
            'PUT'
        );

        deleteCacheByKey('loginConfiguration_' . $brandId);
        $ok = array(true, '2007');
    } catch (Exception $e) {
        global $log;
        $log->error("Error updating access type", [$e]);
        $ok = array(false, '4065');
    }
}

try {
    $accessTypes = json_decode($gateway->sendRequest(null, HOTELINKING_ENDPOINT . 'brands/' . $brandId . '/access_types', 'GET'));
} catch (Exception $e) {
    global $log; 
    $log->error("Error retrieving access types on login configuration page", [$e]);
    $accessTypes = null;
    $ok = array(false, '4067');

}
