<?php //Miramos si esta definida la variable de control de index.php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}

include_once RUTA_DIR . LIB . 'cache.php';
//Obtain Hotel satisfaction data
include_once LIB . 'obtenerDatosSatisfaction.php';

function obtenerDatosHotelSatisfactionThanks($hotel_id)
{
    //Get from cache
    $cacheName = 'obtenerDatosHotelSatisfactionThanks_' . $hotel_id;
    $cache = getFromCache($cacheName);

    if (!$cache) {
        $con = conectar(1);
        $hotel_id = mysqli_real_escape_string($con, $hotel_id);

        $sql = "SELECT fotoBg, logo, hotelName
		FROM hoteles 
		WHERE hoteles.id = $hotel_id ";
        $row = lectura($sql, $con);

        if ($row) {
            $tags = array('hotel', 'hotel_profile', 'hotel_profile_' . $hotel_id);
            setToCache($cacheName, $row, 31536000, $tags);
        }

    } else {
        $row = $cache->get();
    }

    return $row;
}
