<?php

include_once 'librerias.php';
include_once RUTA_DIR . LIB . 'apiGateway.php';

header('Content-Type: application/json');

$id = null;

global $log;

//POST values
$hotelId = $_POST['hotel_id'];
$chainId = $_POST['chain_id'];
$brandId = $_POST['brand_id'];
$status = $_POST['status'];

$payload = [
    'status' => $status,
];


$gateway = new ApiGatewayConnection();
$endPoint = HOTELINKING_ENDPOINT . "brands/$brandId/archive";

try {
    $response = $gateway->sendRequest($payload, $endPoint, 'POST');
    //we delete cache because hotel_wifi_integration is deleted on endpoint
    deleteCacheByKey('wifiStay_' . $hotelId);
    $changeHotel = "SELECT hoteles.id FROM hoteles LEFT JOIN cadena_hotel  ON cadena_hotel.id_hotel = hoteles.id WHERE cadena_hotel.id_cadena= $chainId AND hoteles.activated = 1 LIMIT 1";
    $id = lectura($changeHotel);

    $decodedResponse = json_decode($response, true);
    $decodedResponse['id'] = $id['id'] ?? null;

    echo json_encode($decodedResponse);
} catch (Exception $e) {
    $log->error("Error on archive-hotel", [$e->getMessage()]);
    echo json_encode(['error' => $e->getMessage()]);
}
