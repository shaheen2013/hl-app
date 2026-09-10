<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//Contenido solo visible si logueado
include LIB.'logueado.php';
require_once __DIR__ . '/../src/Services/Connections/ApiGatewayConnection.php';

hotelStaffLanding ();// Si no esta logueado lo manda a la landing


$gateway = new ApiGatewayConnection();
$chainID = array_get($_SESSION, 'c_logueado');
$brandID = $chainID ? $_SESSION['loggedParentBrandID'] : $_SESSION['loggedBrandID'];


$currentPage = 'loyalty-management';
$arrayDatosCadena=null;
if($chainID){
    $arrayDatosCadena=obtenerDatosChainDetails($chainID);

    try {
        $brands = json_decode($gateway->sendRequest(null, HOTELINKING_ENDPOINT . 'brands/' . $brandID, 'GET'), true);
    } catch (Exception $e) {
        global $log;
        $log->error("Error getting brand childs", [$e]);
    }
}

try {
    $loyaltyOffers = json_decode($gateway->sendRequest(["lang" => $_SESSION['userLang']], HOTELINKING_ENDPOINT . 'brand/' . $brandID . '/loyalty-offers', 'GET'), true);
    $offers = json_decode($gateway->sendRequest(null, HOTELINKING_ENDPOINT . 'offers/brand/' . $_SESSION['loggedBrandID'] . '/' . $_SESSION['userLang'], 'GET'), true);
} catch (Exception $e) {
    global $log;
    $log->error("Error getting  Offers", [$e]);
}

$groupedLoyalty = array();
foreach ($loyaltyOffers as $offer) {
    $groupedLoyalty[$offer['visits']][] = $offer;
}

// Add row to configure default offer if not set
$groupedLoyalty[0] = $groupedLoyalty[0] ?? [[]];

// Order array by number of visits
ksort($groupedLoyalty);

?>
