<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

include LIB . 'logueado.php';
include LIB . 'curateEmailsString.php';
require_once __DIR__ . '/../src/Services/Connections/ApiGatewayConnection.php';

hotelStaffLanding ();// Si no esta logueado lo manda a la landing

// For front purposes
$currentSubPage = 'loyalty-config';

$hotel_id = $_SESSION['hotel']['id'];
$gateway = new ApiGatewayConnection();
$brandID = array_get($_SESSION, 'c_logueado') ? $_SESSION['loggedParentBrandID'] : $_SESSION['loggedBrandID'];

if ($_POST && !array_has($_POST, 'relogin_hotel_id')) {
    $loyalty_email_alert= array_get($_POST, 'emails', null);

    if(!is_null($loyalty_email_alert)){
        $loyalty_email_alert = curateEmailsString($loyalty_email_alert);
        $loyalty_email_alert = (!empty($loyalty_email_alert) ? implode( ",", $loyalty_email_alert ) : null);
    }



    $loyalty_alert= array_get($_POST, 'alerts', null) =='on'? 1:0;

    if ($loyalty_alert!==null && $loyalty_email_alert!==null){
        saveHotelLoyaltyConfig($hotel_id, $loyalty_email_alert, $loyalty_alert);
    }
    
    $payload = [
        "summaryActive" => array_get($_POST, 'summaryActive') == 'on' ? 1 : 0,
        "summarySendDays" => array_get($_POST, 'summarySendDays'),
        "summarySendHours" => array_get($_POST, 'summarySendHours')
    ];
    $gateway->sendRequest($payload, HOTELINKING_ENDPOINT . "brands/$brandID/products/11/configuration", 'PUT');

    //Feedback
    $ok = array(true, '2007');
}

$hotelLoyalty = getHotelLoyaltyConfig($hotel_id) ;
$loyaltyConfig = json_decode($gateway->sendRequest(null, HOTELINKING_ENDPOINT . "brands/$brandID/products/11/configuration", 'GET'));