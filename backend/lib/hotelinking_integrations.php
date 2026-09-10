<?php
require_once(__DIR__ . '/apiGateway.php');

// ============================  COMMON  ============================

/**
 * Send a request to hotelinking integrations
 * @param $endpoint
 * @param $method
 * @param $data
 * @param $additionalHeaders
 * @return array
 */
function sendRequest($endpoint, $method, $data, $additionalHeaders)
{
    global $log;
    // Define headers
    $headers = [
        'Content-Type' => 'application/json',
        'Authorization' => INTEGRATIONS_TOKEN
    ];
    if ($additionalHeaders) {
        $headers = array_merge($headers, $additionalHeaders);
    }

    // Instance of gateway
    $gateway = createApiGatewayConnection();

    // Check method is allowed
    $method = strtoupper($method);
    if (in_array($method, ['GET', 'POST', 'PUT', 'DELETE'])) {
        try {
            $response = $gateway->sendRequest($data, INTEGRATIONS_ENDPOINT . $endpoint, $method, $headers);
        } catch (Exception $e) {
            $log->error('Integrations Error', ['message' => 'No data received from server', 'error' => $e->getMessage()]);
            return null;
        }
    } else {
        $log->error('Hotelinking Integrations wrong method', array('message' => 'method ' . $method . ' not allowed'));
        return ['errors' => true, 'message' => 'Request method not implemented'];
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
function parseResponseAndLog($response, $methodName)
{
    global $log;
    $error_result = array_get($response, 'errors');

    $log->debug("Integration response", array('response' => $response));

    if ($error_result == true) {
        errorLog($methodName, array_get($response, 'message'));
        return [];
    }

    $error_result = array_get($response, 'error');
    if (!empty($error_result)) {
        errorLog($methodName, $error_result);
        return $response;
    }

    $log->debug("Hotelinking Integrations $methodName resp", array('message' => array_get($response, 'data')));

    return array_get($response, 'data');
}

function errorLog($methodName, $error)
{
    global $log;

    // If is logical error dont report error
    if (array_get($error, "type", "") === "LOGIC_ERROR") {
        return;
    }

    $log->error("Hotelinking Integrations $methodName error", array('message' => $error));
}

// ============================  INTEGRATIONS  ============================

/**
 * Fetch the integration list
 * @return array
 */
function listIntegrations()
{
    $endpoint = "integration";
    $data = array();
    $response = sendRequest($endpoint, 'GET', $data, null);
    return parseResponseAndLog($response, "listIntegrations");
}

/**
 * Fetch the integration by id
 * @param $integration_id
 * @return array
 */
function getIntegration($integration_id)
{
    $endpoint = "integration/$integration_id";
    $data = array();
    $response = sendRequest($endpoint, 'GET', $data, null);
    return parseResponseAndLog($response, "getIntegration");
}

// ============================  INTEGRATIONS CONFIGS  ============================

/**
 * Fetch the integration list
 * @param $integration_id
 * @return array
 */
function listIntegrationConfigs($integration_id)
{
    $endpoint = "integration/$integration_id/config";
    $data = array();
    $response = sendRequest($endpoint, 'GET', $data, null);
    return parseResponseAndLog($response, "listIntegrationConfigs");
}

/**
 * Fetch the integration config by id
 * @param $integration_id
 * @param $integration_config_id
 * @return array
 */
function getIntegrationConfig($integration_id, $integration_config_id)
{
    $endpoint = "integration/$integration_id/config/$integration_config_id";
    $data = array();
    $response = sendRequest($endpoint, 'GET', $data, null);
    return parseResponseAndLog($response, "getIntegrationConfig");
}

/**
 * Create a the integration config
 * @param $integration_id
 * @param $payload
 * @return array
 */
function createIntegrationConfig($integration_id, $payload)
{
    $endpoint = "integration/$integration_id/config";
    $data = $payload;
    $response = sendRequest($endpoint, 'POST', $data, null);
    return parseResponseAndLog($response, "createIntegrationConfig");
}

// ============================  VALIDATE USER  ============================

/**
 * validate user by room at pms
 * @param $brand_id
 * @param $room_number
 * @param $user_data
 * @return array
 */
function validateUser($brand_id, $room_number, $user_data, $portalProConfig)
{
    // Filter only enabled portal pro options.
    $user_data = array_filter(
        $user_data,
        function ($item, $key) use ($portalProConfig) {
            return array_get($portalProConfig, $key, false);
        },
        ARRAY_FILTER_USE_BOTH
    );

    $endpoint = "brand/$brand_id/user/validate";
    $data = array(
        "first_name" => array_get($user_data, 'first_name'),
        "last_name" => array_get($user_data, 'last_name'),
        "birthday" => array_get($user_data, 'birthday'),
        "document_id" => array_get($user_data, 'document_id'),
        "room_number" => $room_number
    );
    $response = sendRequest($endpoint, 'POST', $data, null);
    return parseResponseAndLog($response, "validateUser");
}

function getRoomMapping($brandId)
{
    $endpoint = "brands/$brandId/mappings/rooms";
    return sendRequest($endpoint, 'GET', null, null);
}

/**
 * insert validate user in datamatch db
 * @param $brand_id
 * @param $pms_user
 * @return array
 */
function insertValidatedUser($brand_id, $pms_user)
{
    $endpoint = "brand/$brand_id/user/insert";
    $data = array(
        "pms_user" => $pms_user
    );
    $response = sendRequest($endpoint, 'POST', $data, null);
    return parseResponseAndLog($response, "insertValidatedUser");
}

// ============================  INTEGRATIONS BRAND  ============================

/**
 * Fetch the integration PMS config for a brand_id
 * @param $brand_id
 * @return array
 */
function listIntegrationOptions($brand_id)
{
    $endpoint = "brand/{$brand_id}/integration";
    $data = array();
    $response = sendRequest($endpoint, 'GET', $data, null);
    return parseResponseAndLog($response, "listIntegrationOptions");
}


/**
 * Fetch the integration PMS config for a brand_id
 * @param $brand_id
 * @return array
 */
function getIntegrationOptions($brand_id, $integration_brand_id, $type = 'pms')
{
    $endpoint = "brand/{$brand_id}/integration/{$type}/{$integration_brand_id}";
    $data = array();
    $response = sendRequest($endpoint, 'GET', $data, null);
    return parseResponseAndLog($response, "getIntegrationOptions");
}



/**
 * Update the integration PMS config for a brand_id
 * @param $brand_id
 * @return array
 */
function updateIntegrationOptions($brand_id, $integration_brand_id, $payload, $type = 'pms')
{
    $endpoint = "brand/{$brand_id}/integration/{$type}/{$integration_brand_id}";
    $data = $payload;
    $response = sendRequest($endpoint, 'PUT', $data, null);
    return parseResponseAndLog($response, "updateIntegrationOptions");
}


/**
 * Create the integration PMS config for a brand_id
 * @param $brand_id
 * @return array
 */
function createIntegrationOptions($brand_id, $payload, $type = 'pms')
{
    $endpoint = "brand/{$brand_id}/integration/{$type}";
    $data = $payload;
    $response = sendRequest($endpoint, 'POST', $data, null);
    return parseResponseAndLog($response, "createIntegrationOptions");
}

/**
 * Delete the integration config for a brand_id
 * @param $brand_id
 * @return array
 */
function deleteIntegrationOptions($brand_id, $integration_brand_id, $payload, $type)
{
    $endpoint = "brand/{$brand_id}/integration/{$type}/{$integration_brand_id}";
    $data = $payload;
    $response = sendRequest($endpoint, 'DELETE', $data, null);
    return parseResponseAndLog($response, "deleteIntegration");
}

// ============================  DATAMATCH  ============================

/**
 * Fetch the datamatch configuration
 * @return array
 */
function getDatamatchConfig()
{
    $endpoint = 'datamatch/config';
    $response = sendRequest($endpoint, 'GET', [], null);
    return parseResponseAndLog($response, "getDatamatchConfig");
}

/**
 * Fetch the datamatch config for a brand_id
 * @param $brand_id
 * @return array
 */
function getDatamatchOptions($brand_id)
{
    $endpoint = "brand/$brand_id/datamatch/config";
    $data = array();
    $response = sendRequest($endpoint, 'GET', $data, null);
    return parseResponseAndLog($response, "getDatamatchOptions");
}

/**
 * Update or create a datamatch config for a brand_id
 * @param $brand_id
 * @param $datamatch_integration_config_id
 * @param $datamatch_frequency_id
 * @return boolean
 */
function updateDatamatchConfig($brand_id, $datamatch_frequency_id)
{
    $endpoint = "brand/$brand_id/datamatch/config";
    $data = array(
        'frequencyId' => $datamatch_frequency_id,
    );
    $response = sendRequest($endpoint, 'POST', $data, null);
    return parseResponseAndLog($response, "updateDatamatchConfig");
}

/**
 * Fetch the datamatch pms users for a brand_id
 * @param $brand_id
 * @param $datamatch_id
 * @return array
 */
function getDatamatchPmsUsers($brand_id, $datamatch_id, $n_per_page, $page, $search_text, $order_list)
{
    if ($datamatch_id == 0) {
        $endpoint = 'datamatch/list-pms-users';
        $data = array(
            'brand_id' => $brand_id,
            'by_chain' => 1,
            'page' => $page,
            'n_per_page' => $n_per_page,
            'search' => $search_text,
            'order_list' => json_encode($order_list)
        );
    } else {
        $endpoint = "brand/$brand_id/datamatch/$datamatch_id/users";
        $data = array(
            'page' => $page,
            'n_per_page' => $n_per_page,
            'search' => $search_text,
            'order_list' => json_encode($order_list)
        );
    }

    $response = sendRequest($endpoint, 'GET', $data, null);
    return parseResponseAndLog($response, "getDatamatchPmsUsers");
}

/**
 * Fetch the datamatch list for a brand_id
 * @param $brand_id
 * @return array
 */
function listDatamatchForBrand($brand_id, $n_per_page, $page, $order_list)
{
    $endpoint = "brand/$brand_id/datamatch";
    $data = array(
        'page' => $page,
        'n_per_page' => $n_per_page,
        'order_list' => json_encode($order_list)
    );
    $response = sendRequest($endpoint, 'GET', $data, null);
    return parseResponseAndLog($response, "listDatamatchForBrand");
}

/**
 * Fetch the datamatch by id for a brand_id
 * @param $brand_id
 * @param $datamatch_id
 * @return array
 */
function showDatamatchForBrand($brand_id, $datamatch_id)
{
    $endpoint = "brand/$brand_id/datamatch/$datamatch_id";
    $data = array();
    $response = sendRequest($endpoint, 'GET', $data, null);
    return parseResponseAndLog($response, "showDatamatchForBrand");
}

/**
 * Import the datamatch by id for a brand_id
 * @param $brand_id
 * @param $datamatch_id
 * @return boolean
 */
function importDatamatchForBrand($brand_id, $datamatch_id)
{
    $endpoint = "brand/$brand_id/datamatch/$datamatch_id/import";
    $data = array();
    $response = sendRequest($endpoint, 'POST', $data, null);
    return parseResponseAndLog($response, "importDatamatchForBrand");
}

/**
 * Export the datamatch by id for a brand_id
 * @param $brand_id
 * @param $datamatch_id
 * @return boolean
 */
function exportDatamatchForBrand($brand_id, $datamatch_id)
{
    $endpoint = "brand/$brand_id/datamatch/$datamatch_id/users/export";
    $data = array();
    $response = sendRequest($endpoint, 'POST', $data, null);
    return parseResponseAndLog($response, "exportDatamatchForBrand");
}

function createDatamatchForBrand($brand_id, $date_from, $date_to, $brand_csv)
{
    $endpoint = "brand/$brand_id/datamatch";
    $data = [
        'brand_csv' => $brand_csv,
        'date_from' => $date_from,
        'date_to' => $date_to,
    ];
    $response = sendRequest($endpoint, 'POST', $data, null);
    return parseResponseAndLog($response, "createDatamatchForBrand");
}

// ============================  PUSHTECH  ============================

/**
 * Fetch the datamatch config for a brand_id
 * @param $brand_id
 * @return array
 */
function getPushtechCredentials($brand_id)
{
    $endpoint = 'pushtech/credentials';
    $data = array(
        'brand_id' => $brand_id,
    );
    $response = sendRequest($endpoint, 'GET', $data, null);
    return parseResponseAndLog($response, "getPushtechCredentials");
}

/**
 * Update or create a pushtech credentials for a brand_id
 * @param $brand_id
 * @param $key
 * @param $secret
 * @return boolean
 */
function setPushtechCredentials($brand_id, $key, $secret, $environment)
{
    $endpoint = 'pushtech/credentials';
    $data = array(
        'brand_id' => $brand_id,
        'key' => $key,
        'secret' => $secret,
        'environment' => $environment,
    );
    $response = sendRequest($endpoint, 'POST', $data, null);
    return parseResponseAndLog($response, "setPushtechCredentials");
}

/**
 * Activate or deactivate a pushtech credentials for a brand_id
 * @param $brand_id
 * @param $activated
 * @return boolean
 */
function changeActiveStatusPushtechCredentials($brand_id, $activated)
{
    $endpoint = 'pushtech/change-status';
    $data = array(
        'brand_id' => $brand_id,
        'activated' => $activated,
    );
    $response = sendRequest($endpoint, 'POST', $data, null);
    return parseResponseAndLog($response, "changeActiveStatusPushtechCredentials");
}

// -----------  New brands-integrations routes
/**
 * Fetch the integrations list for a brand_id using a type filter
 * 
 * @param int $brandId
 * @param string|null $type
 * 
 * @return array
 */
function getBrandIntegrationList($brandId, $type = null)
{
    $endpoint = "brands/{$brandId}/integrations";
    $data = array();
    if ($type) {
        $data['type'] = $type;
    }
    $response = sendRequest($endpoint, 'GET', $data, null);
    return parseResponseAndLog($response, "getBrandIntegrationList");
}

/**
 * Get the redirect Url from a redirect integration
 * 
 * @param int $brandId
 * @param int $integrationBrandId
 * @param array $params
 * 
 * @return array
 */
function getRedirectUrl($brandId, $params)
{
    $endpoint = "brands/{$brandId}/redirect-url";
    $data = [
        "userId" => array_get($params, 'userId'),
        "checkIn" => array_get($params, 'checkIn'),
        "checkOut" => array_get($params, 'checkOut'),
        "locator" => array_get($params, 'locator'),
        "roomNumber" => array_get($params, 'roomNumber'),
        "resId" => array_get($params, 'resId'),
    ];
    $response = sendRequest($endpoint, 'GET', $data, null);
    return parseResponseAndLog($response, "getRedirectUrl");
}

/**
 * Fetch the brands integrated with a integration filtering by type, name or key
 *
 * @param string|null $types
 * @param string|null $names
 * @param string|null $key
 *
 * @return array
 */
function getIntegrationBrandList($types = null, $names = null, $key = null)
{
    $endpoint = "integrations/brands";
    $data = array();
    !is_null($types) ? array_set($data, 'type', $types) : null;
    !is_null($names) ? array_set($data, 'name', $names) : null;
    !is_null($key) ? array_set($data, 'key', $key) : null;

    $response = sendRequest($endpoint, 'GET', $data, null);
    return parseResponseAndLog($response, "getIntegrationBrandList");
}
