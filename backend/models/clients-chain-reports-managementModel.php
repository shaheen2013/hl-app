

<?php

include_once  LIB . 'hotelinking_emails.php';
include_once  LIB . 'obtenerdatosHotel.php';

function getAutomaticReports($hotel_id, $chain_id)
{
    $brand_id = $chain_id ? $chain_id : $hotel_id;
    $brand_type = $chain_id ? 'chain_id' : 'hotel_id';
    $chain_search = $chain_id ? '' : 'AND chain_id is NULL';

    global $log;
    $sql = "SELECT id,emails,report_type,frequency FROM automatic_reports WHERE $brand_type = $brand_id $chain_search";
    $log->debug($sql);
    return lecturaArray($sql);
}

function findProductId($products, $productName)
{
    return array_get(array_first($products, function ($var, $product) use ($productName) {
        return ($product['producto'] == $productName);
    }), 'id');
}

function getPortalProAutomaticReports($brand_id, $product_id)
{
    global $log;
    $gateway = new ApiGatewayConnection();
    $endPoint = REPORTS_ENDPOINT . "brand/" . $brand_id . "/config/" . $product_id;
    try {
        $response = safeJsonParser($gateway->sendRequest([], $endPoint, 'GET'), true);
        // Emails are saved as array in Dynamo
        if ($response['data']) {
            $response['data']['config']['email_subscriptions'] = implode(',', $response['data']['config']['email_subscriptions']);
        }
        return $response['data'];
    }catch (Exception $e) {
        $log->error('client-chain-reports - Error retrieving portal pro automatic reports', ['message' => $e->getMessage(), 'error'=> $e]);
        return [];
    }
}

function safeJsonParser($object, $assoc = false)
{
    if ($object) {
        return \GuzzleHttp\json_decode($object, $assoc);
    }
    return [];
}
