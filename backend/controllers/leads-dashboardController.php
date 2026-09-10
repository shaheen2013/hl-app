<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//contenido solo disponible si logueado
include LIB.'logueado.php';
hotelStaffLanding ();// Si no esta logueado lo manda a la landing

include_once LIB.'charts-common.php';

//Funnel of conversions
$arrayFunnel = obtenerFunnel($_SESSION['h_logueado']);

//Leads by month
$leadsByMonth = leadsByMonth($_SESSION['h_logueado']);

/*echo '<pre>';
print_r($arrayFunnel);
echo '</pre>';*/
?>