<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//Contenido solo visible si logueado
include LIB . 'logueado.php';
hotelStaffLanding ();// Si no esta logueado lo manda a la landing

include_once LIB.'sanitize.php';
include_once LIB.'follow.php';
include_once LIB.'obtenerDatosCadena.php';
include_once LIB.'obtenerdatosHotel.php';

//Obtenemos el id de la cadena de la URL
$guid = $url['dir3'];

//obtener id_hotel a partir del GUID 
$id_cadena = obtenerIdCadenaGUID($guid);

$datosCadena = obtenerDatosCadena($id_cadena);
$hotelesCadena = obtenerHotelesCadena($id_cadena);

/*echo '<pre>';
print_r($hotelesCadena);
echo '</pre>';*/
?>