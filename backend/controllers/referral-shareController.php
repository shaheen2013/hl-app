<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//Pantalla pública. Pueden acceder tanto usuarios como hoteles.

include_once LIB.'obtenerdatosHotel.php';
include_once LIB.'paramsUrl.php';

//Parametros:
//Si esta pantalla tiene el parametro /?o=hotelDesk realiza el post en la misma pantalla
//Si tiene el paramatro /?o=user, realiza el post a share-your-experience-step-2

//Debe tener todos los parametros en la URL
if(empty($url['dir2']) || empty($url['dir3']) || empty($url['args'][0]) )
{
	header('Location: /');
}

//url share-your-exprerience-at/nombre-hotel-san/id_hotel.
// Obtenemos el id del hotel de la URL
$guidHotel = $url['dir3'];
$id_hotel = obtenerIdHotelGUID($guidHotel);
if($id_hotel != NULL)
{
	//datos hotel
	$datosHotel = obtenerDatosHotelShare($id_hotel);
	//Listado de los Goals del hotel
	$goalsHotel = obtenerGoalsHotel($id_hotel);
}else{
	//GUID incorrecto
	header('Location: /');		
}

/*echo '<pre>';
print_r($datosHotel);
echo '</pre>';*/
?>