<?php
require_once (__DIR__.'/apiGateway.php');

// ============================  COMMON  ============================

/**
 * Send a request to hotelinking noc
 * @param $endpoint
 * @param $method
 * @param $data
 * @param $additionalHeaders
 * @return array
 */
function sendNocRequest($endpoint, $method, $data, $additionalHeaders)
{
    global $log;
    // Define headers
    $headers = [
        'Content-Type' => 'application/json',
    ];
    if ($additionalHeaders) {
        $headers = array_merge($headers, $additionalHeaders);
    }

    // Instance of gateway
    $gateway = createApiGatewayConnection();

    // Check method is allowed
    $method = strtoupper($method);
    if (in_array($method, ['GET', 'POST', 'PUT'])) {
        try {
            $response = $gateway->sendRequest($data, NOC_ENDPOINT . $endpoint, $method, $headers);
        } catch (Exception $e) {
            $log->error('Noc request errors', [
                'message' => 'Exception catched', 
                'error' => $e->getMessage()
            ]);
            return null;
        }
    } else {
        $log->error('Noc wrong method', [
            'message' => "method {$method} not allowed"
        ]);
        return [
            'errors' => true, 
            'message' => 'Request method not implemented'
        ];
    }

    $result = json_decode($response, true);
    return $result;
}

/**
 * Parse response and log errors
 * 
 * @param $response 
 * @param $methodName
 */
function parseNocResponseAndLog($reponse, $methodName)
{
    global $log;
    $errorMessage = array_get($reponse, 'error');
    if($errorMessage){
        $log->error("Noc $methodName error", [
            'message' => $errorMessage
        ]);
        return [];
    }
    $log->debug("Noc $methodName resp", [
        'message' => array_get($reponse, 'data')
    ]);

    return array_get($reponse, 'data');
}

// ============================  NOC Radius tickets  ============================

/**
 * Validate radius ticket
 * @return array
 */
function validateRadiusTicket($brandId, $radiusTicket)
{
    $endpoint = "brands/{$brandId}/radius/validate";
    $data = [
        'ticket' => $radiusTicket
    ];
    $response = sendNocRequest($endpoint, 'GET', $data, null);
    return parseNocResponseAndLog($response, "validateRadiusTicket");
}
