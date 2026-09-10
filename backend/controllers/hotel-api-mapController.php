<?php
if (!defined('INDEXCONTROLVAL') || !(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')) {
    echo 'No direct access allowed.';
    exit;
}

include_once APP . 'Services/Connections/ApiGatewayConnection.php';
$apiUrl = HOTELINKING_ENDPOINT . ltrim(str_replace('hotel-api-map', '', ltrim($_SERVER['REQUEST_URI'], '/')), '/');

if (!empty($apiUrl)) {
    header("Content-Type: application/json; charset=UTF-8");
    $gateway = new ApiGatewayConnection();
    $response = $gateway->sendRequest(null, $apiUrl, $_SERVER['REQUEST_METHOD']);

    if (array_get($_SESSION, 'brandsAccess')) {
        $data = json_decode($response, true);

        // Filter the child_data array to return only the brands that the account have access
        if (isset($data['child_data']) && is_array($data['child_data'])) {
            $data['child_data'] = array_values(array_filter($data['child_data'], function($child) {
                return in_array($child['id'], $_SESSION['brandsAccess']);
            }));
        }
        
        $response = json_encode($data);
    }

    echo $response;
    exit;
}

header("Content-Type: text/html; charset=UTF-8");
header("Status: 403 Forbidden", true, 403);
exit;


