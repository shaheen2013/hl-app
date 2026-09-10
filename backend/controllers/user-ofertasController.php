<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//Contenido solo visible si logueado
include LIB.'logueado.php';
userLanding ();// Si no esta logueado lo manda a la landing

include_once LIB.'sanitize.php';
include_once LIB.'fecha.php';

$arrayUserOfertas = obtenerUserOfertas($_SESSION['u_logueado']);

/*echo '<pre>';
print_r($arrayUserOfertas);
echo '</pre>';*/
?>