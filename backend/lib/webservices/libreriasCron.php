<?php
// Incuye la ruta de las librerias de conexion para crons

//Variable de control para saber si ha pasado por index.php
define("INDEXCONTROLVAL", "1");

$_SERVER['DOCUMENT_ROOT'] = dirname(dirname(dirname(__FILE__))).'/';

//Incluir control de errores
require $_SERVER['DOCUMENT_ROOT'].'app/debug.php';

//Incluir el config
require $_SERVER['DOCUMENT_ROOT'].'app/config.php';

require RUTA_DIR.LANG.'en.php';

require RUTA_DIR.MODEL.'_indexModel.php';

?>