<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}
include_once LIB . 'obtenerdatosHotel.php';
/////DETECT FACEBOOK IN APP WEB BROWSER/////
use UAParser\Parser;
$ua = $_SERVER['HTTP_USER_AGENT'];

$parser = Parser::create();
$result = $parser->parse($ua);

$isFacebook =  $result->ua->family;     //Facebook
/////DETECT FACEBOOK IN APP WEB BROWSER/////

//Get id Hotel By GUID
$hotelId = obtenerIdHotelGUID($_GET['guid']);

//Save statistics
saveStatistics(array_get($_GET, 'fb', null), $hotelId);