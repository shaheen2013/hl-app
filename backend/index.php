<?php

// Configure session settings before session_start
ini_set('session.gc_maxlifetime', 3600); // 1 hour
ini_set('session.cookie_lifetime', 3600); // 1 hour
ini_set('session.use_strict_mode', 1);
ini_set('session.use_cookies', 1);
ini_set('session.use_only_cookies', 1);

// Redis session locking configuration to prevent race conditions
ini_set('redis.session.locking_enabled', 1);
ini_set('redis.session.lock_expire', 30);
ini_set('redis.session.lock_retries', 100);
ini_set('redis.session.lock_wait_time', 10000); // 10ms in microseconds

if(isset($_REQUEST['PHPSESSID'])){
    // Set Session ID
    session_id($_REQUEST['PHPSESSID']);
}

// Determine if we're behind a load balancer with SSL termination
$isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') 
    || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
    || (!empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] === 'on');

session_start([
    'cookie_httponly' => true,
    'cookie_secure' => $isSecure, // Dynamic based on actual protocol
    'cookie_samesite' => 'Lax',
    'cookie_lifetime' => 3600,
    'gc_maxlifetime' => 3600
]);


//recogemos el idioma del navegador y lo guardamos en una variable de sesión
//$userLang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);

//mandamos un header para no usar quirks
header('X-UA-Compatible: IE=edge,chrome=1');

// Security Headers
header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');

if (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') {
    header('Content-Security-Policy: upgrade-insecure-requests');
}


//Incluir el config
if($_REQUEST && isset($_REQUEST['testing']) && $_REQUEST['testing']){
    require 'app/configTest.php';}
else{
    require 'app/config.php';
}
require 'app/request.php';

//add autoload dependency
include_once RUTA_DIR . LIB . 'composer/vendor/autoload.php';
include_once RUTA_DIR . LIB . 'laravel-helpers.php';
include_once RUTA_DIR . LIB . 'utils.php';
include_once 'app/log.php';


//Variable de control para saber si ha pasado por index.php
define("INDEXCONTROLVAL", "1");

//variables de idioma
if (empty($_SESSION['userLang'])) {
    include_once LIB . 'idiomas.php';
    // Miramos el lang del navegador,
    // Guardamos userNavLang para contenido de idiomas de hoteles (nombre oferta, ....)
    $lang = $_SESSION['userNavLang'] = isset($_SERVER["HTTP_ACCEPT_LANGUAGE"]) ? strtok(locale_get_primary_language($_SERVER["HTTP_ACCEPT_LANGUAGE"]),  ",") : '';
    //Miramos si el lang del navegador esta en la plataforma, si no devolvemos el idioma default
    // Guardamos userLang para idioma de la plataforma (System)
    $_SESSION['userLang'] = mirarIdiomaPlataforma($lang);
}
include_once LANG . $_SESSION['userLang'] . '.php';
include_once LANG . $_SESSION['userLang'] . '/errores.php';
include_once LANG . $_SESSION['userLang'] . '/feedback.php';


if (MAINTENANCE == 'off') {
    //Include a list of all front files already changed
    //Incluir los archivos _index principales, primero el model, luego el controlador y por último la vista
    require MODEL . '_indexModel.php';
    require CONTROLLERS . '_indexController.php';
    require VIEWS . '_indexView.php';
} else {
    include 'maintenance/index.html';
}
?>