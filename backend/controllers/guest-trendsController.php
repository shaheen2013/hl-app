<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//Contenido solo visible si logueado
include LIB.'logueado.php';
hotelStaffLanding ();// Si no esta logueado lo manda a la landing

//Estrellas %
$arrayEstrellas = obtenerEstrellasTrends($_SESSION['h_logueado']);

//Precio %
$arrayPrecio = obtenerPrecioTrends($_SESSION['h_logueado']);

//Tipo hotel %
$arrayTipoHotel = obtenerTipoHotel($_SESSION['h_logueado']);

//Decoración hotel %
$arrayDecoracionHotel = obtenerDecoracionHotel($_SESSION['h_logueado']);

//Tipo de habitaciones hotel %
$arrayTipoHab = obtenerTipoHab($_SESSION['h_logueado']);

//Room features
$arrayRoomFeatures = obtenerRoomFeatures($_SESSION['h_logueado']);

//Hotel services
$arrayHotelServices = obtenerHotelServices($_SESSION['h_logueado']);

//Paises donde viajar

//Tipo de ofertas que le gustaría recibir al usuario
$arrayRecibirOfertas = obtenerRecibirOfertas($_SESSION['h_logueado']);

/*echo '<pre>';
print_r($arrayEstrellas);
echo '</pre>';*/
?>