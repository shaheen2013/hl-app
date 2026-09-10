<?php
include_once 'librerias.php';// Librerias básicas
include_once RUTA_DIR . LIB . 'obtenerdatosHotel.php';
require_once __DIR__ . '/../../src/Services/Connections/ApiGatewayConnection.php';

$method = $_SERVER['REQUEST_METHOD'] ?? null;
$brandId = $_REQUEST['brand_id'] ?? null;
$integrationId = $_REQUEST['integration_id'] ?? null;
$data = $_POST['data'] ?? [];
$backgroundColor = $_POST['background_color'] ?? null;
$response = [];
$error = null;

if (empty($_SESSION)) {
    $error['errors'] = 'Session expired';
    $method = null;
}

switch ($method) {
    case 'GET':
        if ($brandId) {
            $response = getIntegrations($brandId);
        } else {
            $response = getPlatforms();
        }
        break;
    case 'POST':
        $action = array_get($_POST, 'action');
        
        if ($action === "create") {
            $platformId = $_POST['platform_id'];
            $methodId = $_POST['method_id'];
            $config = [];
            foreach($_POST as $key => $value) {
                if (strpos($key, 'config_') === 0) {
                    $configValue = ($value == "true" || $value == "false") ? "true" == $value : $value;
                    $config[substr($key, strlen("config_"))] = $configValue;
                }
            }

            createIntegration($brandId, $platformId, $methodId, $config);
        } else {
            deleteIntegration($brandId, $integrationId);
        }

        
        break;
}

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
    header('Content-Type: application/json; charset=UTF-8');
    if (empty($error)) {
        echo json_encode($response);
    } else {
        $log->error($error['errors'], $_REQUEST);
        http_response_code(401);
        echo json_encode($error);
    }

} else {
    if (empty($_SESSION['private'])) {
        header('Location: /');
        exit;
    }

    header('Location: ' . $_SERVER['HTTP_REFERER']);
}

function getIntegrations($brandId)
{
    $gateway = new ApiGatewayConnection();
    $config = safeJsonParser(
        $gateway->sendRequest(null, PAYMENTS_ENDPOINT . "brands/{$brandId}/integrations", 'GET'),
        true
    );

    return $config;
}

function getPlatforms()
{
    $gateway = new ApiGatewayConnection();
    $platforms = safeJsonParser(
        $gateway->sendRequest(null, PAYMENTS_ENDPOINT . "platforms", 'GET')
        , true);

    return $platforms;
}

function deleteIntegration($brandId, $integrationId)
{
    $gateway = new ApiGatewayConnection();
    $gateway->sendRequest(null, PAYMENTS_ENDPOINT . "brands/{$brandId}/integrations/{$integrationId}", 'DELETE');
}

function createIntegration($brandId, $platformId, $methodId, $config)
{
    $gateway = new ApiGatewayConnection();
    $a = $gateway->sendRequest([
        "platform_id"   => $platformId, 
        "method_id"     => $methodId,
        "config"        => json_encode($config)
    ], PAYMENTS_ENDPOINT . "brands/{$brandId}/integrations", 'POST');

    return;
}
