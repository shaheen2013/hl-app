<?php
// Basic libraries
include_once 'librerias.php';

// Disable indexcontrolval to be accesible as a webservice
define("INDEXCONTROLVAL", "1");

//if post has hotel_id and product_id
if (array_has($_POST, 'hotel_id') && array_has($_POST, 'product_id')) {
    $hotelId = array_get($_POST, 'hotel_id');
    $productId = array_get($_POST, 'product_id');
    $productName = array_get($_POST, 'product_name');
    $brandId = array_get($_POST, 'brand_id');
    $active = array_get($_POST, 'active') == '1' ? '0' : '1';
    $error = null;

    require_once(__DIR__ . '/../apiGateway.php');
    
    $gateway = createApiGatewayConnection();

    try {
        $gateway->sendRequest([], HOTELINKING_ENDPOINT . "brands/$brandId/products/$productId/activate/$active", 'PUT');
        $messageCode = "2040";
        echo json_encode(['messageCode' => $messageCode]);
        http_response_code(200);
    } catch (Exception $e) {
        $log->error("Error activating product", [$e]);
        $error = "4101";
        echo json_encode($error);
        http_response_code(400);       
    }

    if ($productName == 'widget' && $active) {
        try {
            $gateway->sendRequest(['brand_id' => $brandId, 'domain' => 'devhotelia.hotelinking.com', 'active' => 0], WIDGET_ENDPOINT . 'widgets', 'POST');
        } catch (Exception $e) {
            $log->error("Error activating widget", [$e]);        
        }
    }

    if($productName == 'not_hotel') {
        deleteCacheByKey('brand_eprivacy_' . $hotelId);
    }
    //Delete cache
    deleteCacheByKey("getBrandProducts" . $brandId);
    deleteCacheByKey('hotel_wifi_offers_' . $hotelId);
}
    
