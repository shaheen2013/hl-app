<?php
//FORCE HTTPS
include_once RUTA_DIR . LIB . 'whitelistHttp.php';
include_once RUTA_DIR . LIB . 'device_blacklist.php';

if (ENV != 'test' && !in_array($url['dir1'], $whitelistHttp)) {
    $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') 
        || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') 
        || (!empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on');
    if (!$isHttps) {
        // if request is not secure, redirect to secure url
        $url = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        header('Location: ' . $url);
        exit;
    }
}

//recogemos el idioma del navegador
//$userLang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
date_default_timezone_set('UTC');
$contenido = '';
include_once LIB.'sanitize.php';


//Antes de cargar nada verificamos si el hotel tiene permisos
if (!empty($_SESSION['h_logueado'])) {
    $controller = array_search($url['dir1'], $urlTree);
    include_once RUTA_DIR . LIB . 'loggedHotelData.php';
    $access = permisosHotel($_SESSION['h_logueado'], $controller);
}

if (ENV === 'production'){
(new DeviceBlacklist($url, $logOp))->stopIfBlacklisted();
}

if (empty($url['dir1'])) {
    $contenido = DEFAULT_DIR1;
} elseif (!empty($url['dir1'])) {
    if ($url['dir1'] == 'app') {

        //NEW APP
        include APP .'/app.php';
        exit();
    } elseif (!in_array($url['dir1'], $urlTree)) {
        $contenido = '404';
    } else {
        $key = array_search($url['dir1'], $urlTree);
        $contenido = $key;
    }
}


if ($url['dir1']=='private') {
    $contenido = $url['dir2'];
}

if (file_exists(MODEL . $contenido .'Model.php')) {
    include MODEL . $contenido .'Model.php';
}

//we set these variables "globally"
$currentPage = null;
$currentsubPage = null;


if (file_exists(CONTROLLERS . $contenido .'Controller.php')) {
    include CONTROLLERS . $contenido .'Controller.php';
}

//if relogin hotel from sidebar dropdown
if ($_POST && array_has($_POST, 'relogin_hotel_id')) {
    include_once LIB.'loguearHotel.php';
    include_once LIB . 'cookieLogin.php';

    $staff_id = array_get($_SESSION, 'staff_logueado');
    $relogin_hotel_id = array_get($_POST, 'relogin_hotel_id');
    $origin = array_get($_POST, 'originUrl');
    $role = array_get($_POST, 'role');

    $chain_id = hotelIdCadena(array_get($_SESSION, 'h_logueado'));
    $new_chain_id  = hotelIdCadena($relogin_hotel_id);

    // If we come from the private we have to be able to log in with a different chain than the logged one
    if (!empty($_SESSION['private']) && str_contains($origin, 'private')) {
        session_destroy();
        if (isset($_COOKIE['PHPSESSID'])) {
            unset($_COOKIE['PHPSESSID']);
            setcookie('PHPSESSID', '', time() - 3600, '/', '', true, true);
        }
        session_start();
        $_SESSION['private'] = true;

        $_SESSION['superadmin'] = $role === "super_admin";

        if ($new_chain_id) {
            $loginResult = loguearCadena($new_chain_id, $relogin_hotel_id);
        } else {
            $loginResult = loguearHotel($relogin_hotel_id, 1);
        }

        return header('Location: ' . SECURE_BASE_PATH . array_get($loginResult, 'defaultPage'));

    } elseif ($chain_id && $new_chain_id == $chain_id) {
        loguearHotel($relogin_hotel_id, 1);
    } elseif ($staff_id) {
        loguearStaff($staff_id, $relogin_hotel_id);
    }

    borrarCookieLogin();

    $redirect_url = preg_replace('/datamatch-users(.+)/', "datamatch", array_get($_POST, 'url'));
    $redirect_url = preg_replace('/document-detail(.+)/', "documents-management", array_get($_POST, 'url'));

    header('Location: '.$redirect_url);
}


// Si no existe la página el contenido es 404
if ((!file_exists(VIEW_PAGES . $contenido . '.php') && (!file_exists(PAGES_PRIVATE . $contenido . '.php')))) {
    $contenido = '404';
}
