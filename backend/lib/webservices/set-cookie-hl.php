<?php
// Basic libraries
$log->debug("funciona plis");
include_once 'librerias.php';
// include_once RUTA_DIR . LIB . 'check_access.php';
// checkIpAccess('ch-pos', $_SERVER['REMOTE_ADDR']);

// Disable indexcontrolval to be accesible as a webservice
define("INDEXCONTROLVAL", "1");

$cookie_name = array_get($_GET,'name','test');
$cookie_value = array_get($_GET,'value','funciona');
setcookie($cookie_name, $cookie_value, time() + (86400 * 365), "/", '', true, true); // 86400 = 1 day