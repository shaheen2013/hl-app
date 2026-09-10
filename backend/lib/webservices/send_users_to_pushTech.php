<?php
include_once 'librerias.php';
$usersToSend = 100;
$token = null;
$secret = null;
/**
 * Send users vía cURL to pushTech
 */
$userData = array(
    "name_first" => "Vanessa",
    "name_last" => "Tejada",
    "user_id" => 33,
    "device_id" => null,
    "city" => "Palma de Mallorca",
    "country" => "Spain",
    "tags" => [],
    "gender" => "female",
    "language" => "es_ES",
    "facebook_id" => "123123123",
    "facebook_friends" => 560,
    "born_date" => "1989-03-18",
    "email" => "hola@vanessatejada.com",
    "id_hotel_chain" => null,
    "id_hotel" => 3,
    "hotel_name" => "Hotel Xisco test",
    "conexion_hl_DATE" => "2017-01-12",
    "conexions_wifi_NUMBER" => 1,
    "unique_book_track" => null,
    "booking_DATE" => "2017-02-12",
    "check_in_DATE" => "2017-06-01",
    "booking_BOOLEAN" => true,
    "abandonned_booking_BOOLEAN" => false
);

$data = json_encode($userData);

$curl = new \Curl\Curl();
$curl->setHeader('Authorization', 'Token token='. $secret .'');
$curl->setHeader('Accept', 'application/json');
$curl->setHeader('Content-type', 'application/json');
$curl->post('https://www.pushtech.com/api/v2/account/'. $token .'/contact', $data);

if($curl->error){
    //manage error
}
