<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//Contenido solo visible si logueado
include LIB . 'logueado.php';
hotelStaffLanding ();// Si no esta logueado lo manda a la landing

include_once LIB.'charts-common.php';
include_once LIB.'fecha.php';

// Retention VS Adquisition
$retVSAdq = obtenerRetVSAdq($_SESSION['h_logueado']);

// Total campaigns created
$totalCampaignsCreated = totalCampaignsCreated($_SESSION['h_logueado']);

// Check in offers preferred hour
$preferredHour = checkInOffersPreferredHour($_SESSION['h_logueado']);

// Total campaigns by month (0 año actual, 1 año anterior)
$totalCampaignsByMonth = totalCampaignsByMonth($_SESSION['h_logueado']);
$anoActualAnterior = anoActualAnterior();

//Retention Vs Adquisition by month
$retentionVSAdquisitionByMonth = retentionVSAdquisitionByMonth($_SESSION['h_logueado']);

//Most campaigns redeemed by category
$campaignsByCategory=campaignsByCategory($_SESSION['h_logueado']);

//Most campaigns redeemed by generation
$mostRedeemedByGeneration=mostCampRedByGen($_SESSION['h_logueado']);

//The most Redeemed campaigns last month
$mostRedeemed=mostRedeemedCampaignsLastMonth($_SESSION['h_logueado']);

//The most wishlisted campaigns
$mostWishlisted = mostWishlisted($_SESSION['h_logueado']);

//echo $preferredHour;
/*echo '<pre>';
print_r($mostWishlisted);
echo '</pre>';*/
?>