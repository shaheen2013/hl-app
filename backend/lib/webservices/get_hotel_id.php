<?php
//allow requests from anywhere
header('Access-Control-Allow-Origin: *');

define("INDEXCONTROLVAL", "1");
include_once 'librerias.php';

if (!empty($_GET['hotel_guid'])){

}

if (!empty($_GET['hotel_be_id'])){

}

/**
 * Search in database for hotel id given hotel_guid or hotel booking engine id
 * @param $hotel_guid
 * @param $hotel_be_id
 */
function getHotelId($hotel_guid, $hotel_be_id)
{
    global $log;
    $con = conectar(1);
    $hotel_guid = !empty($hotel_guid) ? mysqli_real_escape_string($con, $hotel_guid) : NULL;
    $hotel_be_id = !empty($hotel_be_id) ? mysqli_real_escape_string($con, $hotel_be_id) : NULL;

    $sql = "SELECT ";
}