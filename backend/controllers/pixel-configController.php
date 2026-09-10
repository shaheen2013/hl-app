<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

include LIB . 'logueado.php';
include LIB . 'curateEmailsString.php';
require_once __DIR__ . '/../src/Services/Connections/ApiGatewayConnection.php';

hotelStaffLanding ();// Si no esta logueado lo manda a la landing

// For front purposes
$currentSubPage = 'pixel-config';

$gateway = new ApiGatewayConnection();
$brandId = $_SESSION['loggedBrandID'];

$pixelConfig = json_decode($gateway->sendRequest(["type" => "external"], EMAILS_ENDPOINT . "brands/$brandId/pixels", 'GET'));