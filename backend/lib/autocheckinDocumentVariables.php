<?php

require_once(RUTA_DIR . LIB . 'apiGateway.php');

if(!function_exists('safeJsonParser')){
    function safeJsonParser($object, $assoc = false)
    {
        if ($object) {
            return \GuzzleHttp\json_decode($object, $assoc);
        }
    
        return [];
    }
}


function getVariables()
{
    $gateway = createApiGatewayConnection();
    $apiUrl = AUTOCHECKIN_ENDPOINT . "document-variables";

    try {
        $variables = safeJsonParser($gateway->sendRequest(null, $apiUrl, 'GET'), true);

        return $variables['data'];
    } catch (Exception $exception) {
        $error = [
            "error" => true,
            "message" => json_decode($exception->getResponse()->getBody())->message
        ];

        return $error;
    }
}