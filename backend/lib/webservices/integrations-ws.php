<?php
include_once 'librerias.php';// Librerias básicas
require_once __DIR__ . '/../../src/Services/Connections/ApiGatewayConnection.php';
include_once RUTA_DIR . LIB . 'obtenerdatosHotel.php';
global $log;


$method = $_SERVER['REQUEST_METHOD'] ?? null;
$brandId = $_REQUEST['brand_id'] ?? null;
$integrationBrandId = $_REQUEST['integration_brand_id'] ?? null;
$actionMethod = $_REQUEST['action_method'] ?? null;
$data = $_POST['data'] ?? [];

$log->debug(
    "INTEGRATION-WS", 
    [
        "message" => "Request params",
        "brand_id" => $brandId,
        "integration_brand_id" => $integrationBrandId,
        "method" => $method,
        "actionMethod" => $actionMethod
    ]
);

$response = [];
$error = null;

// Inputs validations
if (!$brandId || !$integrationBrandId || !$actionMethod) {
    $error['errors'] = 'Empty brand_id, integration_brand_id or action_method';
    $error['http_error'] = 422;
}

if (empty($_SESSION)) {
    $error['errors'] = 'Session expired';
    $error['http_error'] = 401;
}


$actionMethodMapping = [
    "GET" => [
        "requiredValues" => "getRequiredValues"
    ]
];


$actionFnName = $actionMethodMapping[$method][$actionMethod] ?? null;


if(!$actionFnName){
    $error['errors'] = 'Action method not found';
    $error['http_error'] = 400;
}


if(!$error) {
    $response = $actionFnName($_REQUEST);  
}

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
    header('Content-Type: application/json; charset=UTF-8');
    if (empty($error)) {
        echo json_encode($response);
    } else {
        $log->error($error['errors'], $_REQUEST);
        http_response_code($error['http_error']);
        echo json_encode($error);
    }

} else {
    if (empty($_SESSION['private'])) {
        header('Location: /');
        exit;
    }

    header('Location: ' . $_SERVER['HTTP_REFERER']);
}


// Actions methods
function getRequiredValues($data)
{
    global $log;

    $brandId = array_get($data, "brand_id");
    $integrationBrandId = array_get($data, "integration_brand_id");

    
    $gateway = new ApiGatewayConnection();
    $response = safeJsonParser(
        $gateway->sendRequest(
            [], 
            INTEGRATIONS_ENDPOINT . "brands/{$brandId}/integrations/{$integrationBrandId}/minimum-fields", 'GET'
        ), 
        true
    );

    $log->debug(
        "INTEGRATION-WS", 
        [
            "message" => "getRequiredValues",
            "data" => $data,
            "url" => INTEGRATIONS_ENDPOINT . "brands/{$brandId}/integrations/{$integrationBrandId}/minimum-fields",
            "response" => $response
        ]
    );

    return $response;
}