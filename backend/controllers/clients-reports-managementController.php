<?php //Miramos si esta definida la variable de control de index.php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}

//Contenido solo visible si logueado
include LIB . 'logueado.php';
hotelStaffLanding(); // Si no esta logueado lo manda a la landing

$currentSubPage = 'clients-reports-management';

$hotelId = array_get($_SESSION, 'h_logueado');
$chainId = array_get($_SESSION, 'loggedParentBrandID', "null");
$brandId = array_get($_SESSION, 'loggedBrandID');

$brandProducts = obtenerProductosHotel($brandId);
$hotelAutomaticReports = getAutomaticReports($hotelId, NULL);

$portalProActivated = getBrandProductActive($brandProducts, "portal_pro");

$portalProProductId = findProductId($brandProducts, 'portal_pro');


if($portalProActivated){
    $hotelPortalProReports = getPortalProAutomaticReports($brandId, $portalProProductId);
    // In order to check if we need to update or create a new entry
    $newPortalProReport = isset($hotelPortalProReports['config']) ? "false" :  "true";
}


