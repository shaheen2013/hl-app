<?php //Miramos si esta definida la variable de control de index.php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}

include_once RUTA_DIR . LIB . 'cache.php';

// FX para obtener el texto defaul, si el hotel no tiene texto customizado para el share de stay
function getHotelDefaultShareData($id_hotel)
{
    //Get from cache
    $cacheName = 'hotelDefaultShareData_' . $id_hotel;
    $cache = getFromCache($cacheName);

    if (!$cache) {
        $con = conectar();
        $id_hotel = mysqli_real_escape_string($con, $id_hotel);
        $sql = "SELECT city FROM hoteles WHERE id=$id_hotel";
        $row = lectura($sql, $con);

        if ($row) {
            setToCache($cacheName, $row, 31536000);
        }
    } else {
        $row = $cache->get();
    }

    return $row;
}

function getAllLangsAvailables($hotel_id, $chain_id=null)
{
    $hotel_id = mysqli_real_escape_string(conectar(), $hotel_id);
    $chain_id = mysqli_real_escape_string(conectar(), $chain_id);

    $sql = "SELECT url, locale FROM hotel_country_lang_url left join country_langs on hotel_country_lang_url.country_lang_id=country_langs.id WHERE active=1 and (hotel_id = $hotel_id ";
    if ($chain_id) {
        $sql .=  "or chain_id= $chain_id) order by chain_id desc";
    } else {
        $sql .= ")";
    }

    $row = lecturaArray($sql);

    return $row;
}

function getHotelNonCustomerChecks($hotel_id)
{
    $sql = "
    SELECT hoteles.id,
    hotel_satisfaction.sendToNonCustomers as sendSatisfactionToNonCustomers,
    hotel_oferta_birthday.sendWarningFromNonUsers as sendBirthdayOfferToNonCustomers
    FROM hoteles 
    LEFT JOIN hotel_satisfaction ON  hoteles.id=hotel_satisfaction.id_hotel
    LEFT JOIN hotel_oferta_birthday ON  hoteles.id=hotel_oferta_birthday.hotel_id 
    WHERE hoteles.id = $hotel_id ";
    return lectura($sql);
}
