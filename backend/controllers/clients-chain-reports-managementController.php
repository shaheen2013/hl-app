<?php //Miramos si esta definida la variable de control de index.php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}

//Contenido solo visible si logueado
include LIB . 'logueado.php';
hotelStaffLanding(); // Si no esta logueado lo manda a la landing

$currentSubPage = '';
$currentPage = 'clients-chain-reports-management';

$hotelId = array_get($_SESSION, 'h_logueado');
$chainId = array_get($_SESSION, 'c_logueado');
$brandId = array_get($_SESSION, 'loggedBrandID');
$chainBrandId = array_get($_SESSION, 'loggedParentBrandID');

$brandProducts = obtenerProductosHotel($brandId);

$portalProActivated = getBrandProductActive($brandProducts, "portal_pro");
$portalProProductId = findProductId($brandProducts, 'portal_pro');

$hotelAutomaticReports = getAutomaticReports(null, $chainId);

if($portalProActivated) {
    $hotelPortalProReports = getPortalProAutomaticReports($chainBrandId, $portalProProductId);
    // In order to check if we need to update or create a new entry
    $newPortalProReport = isset($hotelPortalProReports['config']) ? "false" :  "true";
}

