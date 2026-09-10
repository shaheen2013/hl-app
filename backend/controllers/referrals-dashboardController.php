<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//Contenido solo visible si logueado
include LIB.'logueado.php';
hotelStaffLanding ();// Si no esta logueado lo manda a la landing

include_once LIB.'charts-common.php';
include_once LIB.'obtenerDatosUsuario.php';

//Total check-ins from referrals
$checkinsReferrals = checkinsReferrals($_SESSION['h_logueado']);

//Landing conversion by month
$landingConversionMonth = landingConversionMonth($_SESSION['h_logueado']);

//Referrals by social media
$referralsBySocialMedia = referralsBySocialMedia($_SESSION['h_logueado']);
if (empty($referralsBySocialMedia['tw'])){$referralsBySocialMedia['tw']=0;}
if (empty($referralsBySocialMedia['fb'])){$referralsBySocialMedia['fb']=0;}
if (empty($referralsBySocialMedia['in'])){$referralsBySocialMedia['in']=0;}
if (empty($referralsBySocialMedia['li'])){$referralsBySocialMedia['li']=0;}

//Landing conversion (total)
$landingConversion = landingConversion($_SESSION['h_logueado']);

//Top ten referrers
$top10Referrers = top10Referrers($_SESSION['h_logueado']);

//Top ten referrers by Earnings
$top10ReferrersByEarnings = top10referrersByEarnings($_SESSION['h_logueado']);

/*echo '<pre>';
print_r($top10ReferrersByEarnings);
echo '</pre>';*/
?>