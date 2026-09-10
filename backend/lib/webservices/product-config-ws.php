<?php
include_once 'librerias.php';// Librerias básicas
include_once RUTA_DIR . LIB . 'obtenerdatosHotel.php';
include_once RUTA_DIR . LIB . 'portal_pro.php';
require_once __DIR__ . '/../../src/Services/Connections/ApiGatewayConnection.php';

$method = $_SERVER['REQUEST_METHOD'] ?? null;
$brandId = $_REQUEST['brand_id'] ?? null;
$productId = $_REQUEST['product_id'] ?? null;
$hotelId = $_REQUEST['hotel_id'] ?? null;
$productName = $_REQUEST['product_name'] ?? null;
$data = $_POST['data'] ?? [];
$backgroundColor = $_POST['background_color'] ?? null;
$selectedBrandValues = $_POST['selectedBrandsValues'] ?? null;
$response = [];
$error = null;

global $log;

$integrationEnabled = isIntegrationEnabled();

if (!$brandId || !$productId) {
    $error['errors'] = 'Empty brand or product ID';
    $method = null;
}

if (empty($_SESSION)) {
    $error['errors'] = 'Session expired';
    $method = null;
}

switch ($method) {
    case 'GET':
        $response = getConfig($brandId, $productId);
        $brandInfo = getBrandInfo($brandId);
        $response['background_color'] = $brandInfo['data']['background_color'] ?? "";
        break;

    case 'POST':
        if (empty($data)) {
            $error['errors'] = 'Empty data';
        } else {
            $response['productName'] = $productName;
          
            if ($selectedBrandValues === null){
                // If no selected brands, use $brandId and $hotelId directly
                $selectedBrandValues = [
                    [
                        'brandId' => $brandId, 
                        'hotelId' => $hotelId
                    ]
                ];

            } else {
                // Add $brandId and $hotelId if not present amongst the selected brands
                $found = false;
                foreach ($selectedBrandValues as $selectedBrand) {
                    if ($selectedBrand['brandId'] == $brandId) {
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    $selectedBrandValues[] = ['brandId' => $brandId, 'hotelId' => $hotelId]; 
                }
            }
                $success = [];
                $failure = [];
                           
                foreach ($selectedBrandValues as $selectedBrand) {
                    $selectedBrandId = $selectedBrand['brandId'];
                    $selectedHotelId = $selectedBrand['hotelId'];
                    try {
                        if($productId === "13" && $integrationEnabled){ // productId 13 -> portalPro
                            setPortalProConfig($selectedBrandId, $productId, $data,  $selectedHotelId);
                        } else if($productId === "21"){  // productId 21 -> autocheckin
                            setConfig($selectedBrandId, $productId, $data);
                            setBackgroundColor($selectedBrandId, $backgroundColor);
                        }
                        $success[] = $selectedBrandId;
                    } catch (Exception $e) {
                        global $log;
                        $log->error("Error on set config", [$e]);
                        $failure[] = $selectedBrandId;
                    }
                }

                $response['success'] = $success;
                $response['failure'] = $failure;
                $response['messageCode'] = !empty($failure) ? "4102" : "2041";
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

function getConfig($brandId, $productId)
{   
    $hasJsonConfig = [24]; // Product IDs that have a JSON config(brand_products table) / schema_config (products table)

    $endpointSuffix = in_array($productId, $hasJsonConfig) ? "/config" : "/configuration";
    $endpoint = HOTELINKING_ENDPOINT . "brands/{$brandId}/products/{$productId}{$endpointSuffix}";

    $gateway = new ApiGatewayConnection();
    $config = safeJsonParser(
        $gateway->sendRequest(null, $endpoint, 'GET')
        , true);
    return $config;
}

function setConfig($brandId, $productId, $data)
{
    $hasJsonConfig = [24]; // Product IDs that have a JSON config(brand_products table) / schema_config (products table)
    $endpointSuffix = in_array($productId, $hasJsonConfig) ? "/config" : "/configuration";
    $endpoint = HOTELINKING_ENDPOINT . "brands/{$brandId}/products/{$productId}{$endpointSuffix}";

    $gateway = new ApiGatewayConnection();
    $gateway->sendRequest($data, $endpoint, 'PUT');
}   

function getBrandInfo($brandId)
{
    $url = HOTELINKING_ENDPOINT . "brands/{$brandId}/info";
    $cacheName = str_replace("/", "-", $url);

    $cache = getFromCache($cacheName);

    if (!$cache) {
        $gateway = new ApiGatewayConnection();
        $info = safeJsonParser(
            $gateway->sendRequest(null, $url, 'GET')
            , true);

        setToCache($cacheName, $info, 31536000);
    } else {
        $info = $cache->get();
    }

    return $info;
}

function setBackgroundColor($brandId, $color)
{
    $cacheKey = HOTELINKING_ENDPOINT . "brands/{$brandId}/info";
    $data = [
        'brand' => [
            'id' => $brandId,
            'background_color' => $color
        ]
    ];

    $gateway = new ApiGatewayConnection();
    $gateway->sendRequest($data, HOTELINKING_ENDPOINT . "brands/{$brandId}/info", 'PUT');

    deleteCacheByKey(str_replace("/", "-", $cacheKey));
}

function setPortalProConfig($selectedBrandId, $productId, $data, $selectedHotelId)
{
       global $log;
       // Update portalPro config
       $payload = [
        'first_name' => array_get($data, 'portalPro_name_surname', '0'),
        'last_name' => array_get($data, 'portalPro_name_surname', '0'),
        'document_id' => array_get($data, 'portalPro_document_id', '0'),
        'room_number' => array_get($data, 'portalPro_room_number', '0'),
        'access_code' => array_get($data, 'portalPro_access_code', '0'),
        'premium_code' => array_get($data, 'portalPro_premium_access_code', '0'),
        'radius_ticket' => array_get($data, 'portalPro_radius_ticket', '0'),
        'premium_ticket' => array_get($data, 'portalPro_premium_ticket', '0'),
        'max_validations' => array_get($data, 'portalPro_max_validations'),
        'restrictive' => array_get($data, 'portalPro_restrictive', '0'),
        ];
      
        updatePortalProConfig($selectedBrandId, $payload);
        // Active portal_pro
        $gateway = new ApiGatewayConnection();

        $gatewayResponse = $gateway->sendRequest([], HOTELINKING_ENDPOINT . "brands/$selectedBrandId/products/$productId/activate/1", 'PUT');
        $log->debug(HOTELINKING_ENDPOINT . "brands/$selectedBrandId/products/$productId/1", [
             'response' => $gatewayResponse->getContents()
        ]);

        // Delete cache for products
        deleteCacheByKey("getBrandProducts" . $selectedBrandId);
        deleteCacheByKey('hotel_wifi_offers_' .  $selectedHotelId);
}