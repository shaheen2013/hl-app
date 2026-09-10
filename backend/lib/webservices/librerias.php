<?php
session_start();
//Incluir el config
if(isset($_REQUEST) && isset($_REQUEST['testing']) && $_REQUEST['testing'] == true){
    require_once '../../app/configTest.php';
}
else{
    require_once '../../app/config.php';
}
require_once '../../app/request.php';
//add autoload dependency 
include_once RUTA_DIR . LIB . 'composer/vendor/autoload.php';

//add laraver-helpers library
include_once RUTA_DIR . LIB . 'laravel-helpers.php';
//add log library
require_once '../../app/log.php';
//Incluir control de errores si no es produccion
if (ENV != 'production') {
    include_once '../../app/debug.php';
}

//Llamamos al archivo de errores
if (!empty($_SESSION['userLang'])) {
    include '../../lang/' . $_SESSION['userLang'] . '/errores.php';
    include '../../lang/' . $_SESSION['userLang'] . '.php';
} else {
    include '../../lang/en/errores.php';
    include '../../lang/en.php';
}
include '../../models/_indexModel.php';
include_once RUTA_DIR . LIB . 'utils.php';
?>