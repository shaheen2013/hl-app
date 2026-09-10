<?php

include_once APP . 'Services/Connections/ApiGatewayConnection.php';
include_once RUTA_DIR . LIB . 'hotelinking_emails.php';
include_once RUTA_DIR . LIB . 'obtenerdatosHotel.php';

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


function saveAutomaticReports($automatic_report_id, $hotel_id, $chain_id, $emails, $report_types, $frequency)
{

    if ($chain_id) {
        $brand_type = 'chain';
    } else {
        $chain_id = 'NULL';
        $brand_type = 'hotel';
    }

    if (!$frequency) {
        $frequency = 30;
    }

    $con = conectar();
    $automatic_report_id = (int)$automatic_report_id;
    $hotel_id = is_numeric($hotel_id) ? $hotel_id : 'NULL';
    $chain_id = is_numeric($chain_id) ? $chain_id : 'NULL';
    $frequency = (int)$frequency;
    $emails = mysqli_real_escape_string($con, $emails);
    $report_types = mysqli_real_escape_string($con, $report_types);

    $sql = "
        INSERT INTO
            automatic_reports
            (hotel_id, chain_id, emails, report_type, frequency, last_send, brand_type)
        VALUES 
            ({$hotel_id}, {$chain_id}, '{$emails}', '{$report_types}', {$frequency}, '0000-00-00', '{$brand_type}')";

    if ($automatic_report_id) {
        $sql = "
            UPDATE
               automatic_reports
            SET
                emails = '{$emails}',
                report_type = '{$report_types}',
                frequency = {$frequency}
            WHERE
                id = {$automatic_report_id}";
    }

    return escritura($sql);
}

function deleteAutomaticReports($automatic_report_id)
{
    $sql = "DELETE FROM automatic_reports
            WHERE id=$automatic_report_id";

    return escritura($sql);
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
        $log->error('client-reports - Error retrieving portal pro automatic reports', ['message' => $e->getMessage(), 'error'=> $e]);
        return [];
    }
    
}

function savePortalProAutomaticReports($brand_id, $product_id, $payload, $method)
{
    $gateway = new ApiGatewayConnection();
    $endPoint = REPORTS_ENDPOINT . "brand/" . $brand_id . "/config/" . $product_id;
    $gateway->sendRequest($payload, $endPoint, $method);
    return 'success';
}

function deletePortalProAutomaticReports($brand_id, $product_id)
{
    $gateway = new ApiGatewayConnection();
    $endPoint = REPORTS_ENDPOINT . "brand/" . $brand_id . "/config/" . $product_id;
    $gateway->sendRequest([], $endPoint, 'DELETE');

    return 'success';
}
function findProductId($products, $productName)
{
    return array_get(array_first($products, function ($var, $product) use ($productName) {
        return ($product['producto'] == $productName);
    }), 'id');
}
function safeJsonParser($object, $assoc = false)
{
    if ($object) {
        return \GuzzleHttp\json_decode($object, $assoc);
    }
    return [];
}
