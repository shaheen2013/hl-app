<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

include_once 'generarToken.php';
include_once 'hotelinking_emails.php';

function insertarHotel($name, $city, $web, $street, $place_name, $place_country, $place_adm_area,
	$lat, $lng, $place_id, $pass='', $email='', $quota='', $chain_id = null, $country_name = '')
{
	$sql = "INSERT INTO hoteles 
	(hotelName, city, website, password, email, street, verificado, quota, place_name, place_country, 
	place_adm_area, lat, lng, place_id, country) 
	VALUES 
	('$name', '$city', '$web', '$pass', '$email', '$street', '1', '$quota',
	'$place_name', '$place_country', '$place_adm_area', '$lat', '$lng', '$place_id', '$country_name')";
	$id_hotel = escritura($sql);

	//$id_hotel = ultimoIdInsertado('hoteles');
	//$_SESSION['h_logueado']= $id_hotel; //guardamos el ID del usuario recien insertado

	//Insertamos hotel con el total de usuarios para ver usuarios nuevos en la home
	//$sql2 = "INSERT INTO hotel_total_users (id_hotel) VALUES ('$id_hotel')";
	//escritura($sql2);
	// Insertamos el hotel en charts-positions
	$sql3 = "INSERT INTO hotel_charts_position (id_hotel) VALUES ('$id_hotel')";
	escritura($sql3);
	//Insertamos hotel en tabla hotel_charts_data
	$sql2 = "INSERT INTO hotel_charts_data (id_hotel) VALUES ('$id_hotel')";
	escritura($sql2);
	//Insertamos el hotel en la tabla de Onboarding
	$sql4 = "INSERT INTO onboarding (id_hotel) VALUES ('$id_hotel')";
	escritura($sql4);
	//Insertamos un idioma al hotel por defecto (EN) para crear ofertas y poner el tagline de la landing
	$sql5 = "INSERT INTO lang_hotel (id_hotel, id_lang) VALUES ('$id_hotel', (SELECT id FROM lang where lang = 'en'))";
	escritura($sql5);

    $guid = guidv4();
	$sql6 = "INSERT INTO hotel_guid (id_hotel, guid) VALUES ('$id_hotel', '$guid')";
	escritura($sql6);

	//Guardamos los datos del hotel en la BD de emails de HL
//	HLEcrearHotel($id_hotel, $name, $email, $logo);

	//crear carpeta hotel
	$rutaHotel = DIR_IMG_FICHA_HOTEL.$id_hotel;
	if (!file_exists($rutaHotel)) {//Si no existe el directorio lo creamos
		mkdir($rutaHotel, 0777, true);
	}
	if($chain_id !== null){
        deleteCacheByKey('hotelDeCadena_' . $chain_id);
    }
	return ($id_hotel);
}
?>
