<?php
include 'librerias.php';// Librerias básicas
require_once __DIR__ . '/../../src/Services/Connections/ApiGatewayConnection.php';
$gateway = new ApiGatewayConnection();

// Restringir ips que pueden acceder
include_once RUTA_DIR.LIB.'check_access.php';
checkIpAccess('lo-ma', $_SERVER['REMOTE_ADDR']);

if($_POST){

    $brandId = array_get($_POST, 'brand_id');

    if(array_get($_POST, 'action') == 'save'){
        try {
            $brandPixel = $gateway->sendRequest([
                "active"        => array_get($_POST, 'active'),
                "email_type_id" => array_get($_POST, 'email_type_id'),
                "url"           => array_get($_POST, 'url')
            ], 
            EMAILS_ENDPOINT . "brands/{$brandId}/pixels", 'POST');

            echo $brandPixel;
        } catch (Exception $e) {
            global $log;
            $log->info("Error inserting brand pixel", [$e]);
            echo 'error';
        }
        
    }
    
    if(array_get($_POST, 'action') == 'delete') {
        $brandPixelId = array_get($_POST, 'brand_pixel_id');

       try {
            $gateway->sendRequest(null,
                EMAILS_ENDPOINT . 'brands/' . $brandId . '/pixels/' . $brandPixelId, 
                'DELETE');
    
            echo 'deleted';
        } catch (Exception $e) {
            global $log;
            $log->info("Error deleting brand pixel", [$e]);
            echo 'error';
        }
    }
}
else{
    echo 'error';
}
?>