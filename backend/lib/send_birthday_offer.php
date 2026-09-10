<?php //Miramos si esta definida la variable de control de index.php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}

//Get Birthday by hotel ID offer @return offer ID
function getBirthdayOffer($id)
{
    $cacheName = 'birthdayOfferFromHotel_' . $id;
    $cache = getFromCache($cacheName);
    if (!$cache) {
        $con = conectar(1); // 1 significa que lee de la read réplica
        $id_hotel = mysqli_real_escape_string($con, $id);
        $sql = "SELECT oferta_id FROM hotel_oferta_birthday WHERE hotel_id = '$id_hotel'";
        $result = lectura($sql, $con);
        if ($result) {
            $tags = array("hotel", "hotel_goals", "hotel_goals_" . $id_hotel, "birthdayOffersFromHotel_" . $id_hotel);
            setToCache($cacheName, $result, 31536000, $tags);
        }
    } else {
        $result = $cache->get();
    }
    return array_get($result, 'oferta_id');
}

//Set new birthday offer @return boolean
function setBirthdayOffer($id_hotel, $id_oferta)
{
    $con = conectar();
    $id_hotel = mysqli_real_escape_string($con, $id_hotel);
    $id_oferta = mysqli_real_escape_string($con, $id_oferta);

    $sql = "INSERT INTO hotel_oferta_birthday (oferta_id, hotel_id) VALUES($id_oferta, $id_hotel) ON DUPLICATE KEY UPDATE   oferta_id='$id_oferta'";
    $query = mysqli_query(conectar(), $sql);

    if ($query) {
        deleteCacheByTag("birthdayOffersFromHotel_" . $id_hotel);
    }

    return $query;
}

function getBirthdayOfferDetails($id, $lang)
{
    $con = conectar();
    $sql = "SELECT hotel_oferta.id, hotel_oferta_lang.nombre, hotel_oferta_lang.descripcion, hotel_oferta_lang.condiciones, hotel_oferta.img
		FROM hotel_oferta
		INNER JOIN hotel_oferta_lang ON hotel_oferta_lang.id_oferta = hotel_oferta.id
		WHERE hotel_oferta.id = '$id'
		AND hotel_oferta_lang.lang = '$lang'";
    return lectura($sql, $con);
}

function sendBirthdayToEmailPlatform($user, $id_hotel, $offer, $url)
{
    global $log;
    $con = conectar(2);
    $id_user = mysqli_real_escape_string($con, $user['id']);
    $id_hotel = mysqli_real_escape_string($con, $id_hotel);
    $url = mysqli_real_escape_string($con, $url);
    $offer_name = mysqli_real_escape_string($con, $offer['nombre']);
    $offer_img = mysqli_real_escape_string($con, $offer['img']);
    
    $year = date('Y');
    $lastYear = $year -1;

    $sql = "INSERT INTO birthdays (user_id, hotel_id, offer_name, offer_image, token, send_date)
            VALUES ($id_user, $id_hotel, '" . $offer_name . "', '" . $offer_img . "' , '$url', '". $lastYear ."' )
            ON DUPLICATE KEY UPDATE offer_name =  '" . $offer_name . "',  offer_image = '" . $offer_img . "'";

    $log->debug('Birthday inserted: ' . $sql);
    $write = escritura($sql, $con);
    return $write;
}