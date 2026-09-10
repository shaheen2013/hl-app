<?php
header('Access-Control-Allow-Origin: *');
// Basic libraries
include_once 'librerias.php';
include_once __DIR__ . '/../../src/Services/Connections/ApiGatewayConnection.php';

// Disable indexcontrolval to be accesible as a webservice
define("INDEXCONTROLVAL", "1");

function safeJsonParser($object, $assoc=false){
    if($object){
        return \GuzzleHttp\json_decode($object, $assoc);
    }
    return [];
}

if (array_get($_GET, 'user_id') && array_get($_GET, 'brand_id') && array_get($_GET, 'widget_uuid')){
    
    require_once (__DIR__.'/../../src/Services/Connections/ApiGatewayConnection.php');

    $dateCheckIn = new DateTime(array_get($_GET, 'check_in_date'));
    $checkin = $dateCheckIn->format('Y-m-d');

    $dateCheckOut = new DateTime(array_get($_GET, 'check_out_date'));
    $checkout = $dateCheckOut->format('Y-m-d');

    $payload = [
        "schema" => "com.hotelinking/Bookings/booking_created/2.0.1",
        "originalEntity" => "widget",
        "origin" => "apiWidget/" . array_get($_GET, 'widget_uuid'),
        "eventSource" => array_get($_GET, 'url'),
        "context" => [
           "description" => 'Event that will send the information to Pushtech once a new widget booking has been sucesfully created'
        ],
        "payload" => [
            "booking" => [
                "checkIn" => $checkin,
                "checkOut" => $checkout,
                "amount" => (float) array_get($_GET, 'amount'),
                "currency" => array_get($_GET, 'currency'),
                "transactionCode" => array_get($_GET, 'transactionCode'),
                "bookingEngineCode" => array_get($_GET, 'bookingEngineCode', ''),
                "promo" => array_get($_GET, 'promo')
            ],
            "user" => [
                "id" => (int) array_get($_GET, 'user_id')
            ],
            "brand" => [
                "id" => (int) array_get($_GET, 'brand_id')
            ],
            "offer" => [
                "id" => (int) array_get($_GET, 'offer_id')
            ]
       ]
    ];

    $gateway = new ApiGatewayConnection();
    $booking = $gateway->sendRequest($payload, STREAM_SUB_DOMAIN, 'POST');

}