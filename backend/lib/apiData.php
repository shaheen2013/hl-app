<?php
//Get api credentials
/**
 * @param $id_hotel
 * @param $api_name
 * @return API credentials
 */
function getApiCredentials($id_hotel, $api_name)
{
    $cache = getFromCache('api_credentials_' . $id_hotel . '_' . $api_name);
    if (!$cache) {
        $con = conectar(1);
        $id_hotel = mysqli_real_escape_string($con, $id_hotel);
        $api_name = mysqli_real_escape_string($con, $api_name);
        //Query
        $sql = "SELECT `key`, secret, username, password FROM external_hotel_apis WHERE id_hotel = '$id_hotel' AND id_api = (SELECT id from external_apis WHERE api_name='$api_name')";
        $result = lectura($sql, $con);
        if ($result) {
            setToCache('api_credentials_' . $id_hotel . '_' . $api_name, $result, 31536000);
        }
    } else {
        $result = $cache->get();
    }
    return $result;

}

/**
 * @param $array of passed data to check
 * @param $array of action params
 * @return false|array
 */
function objectKeysDiff($objectParams, $actionParams)
{
    //check the difference of keys between the two arrays
    $diff = array_diff_key($objectParams, $actionParams);

    //if the difference is empty return false
    if (empty($diff)) {
        return false;
    } else {
        // else return string with the list of params missing
        return 'You are missing the following keys : ' . concatKeys($diff);
    }
}

/**
 * Build a string that specifies the list of missing params for an API action
 * @param $array of missing params
 * @return string
 */
function concatKeys($keys)
{
    global $log;

    //build the initial string
    $string = '';

    //concat the list of keys passed
    foreach (array_keys($keys) as $index => $key) {

        //for the first one don't add the comma
        if ($index === 0) {
            $string = $string . $key;
            //else concat with comma
        } else {
            $string = $string . ', ' . $key;
        }
    }
    return $string;
}

//Get external api URL
// this returns a constructed object e.g

/**
 * @param $apiName
 * @param $apiEndpoint
 * @return array|mixed|null (api url, endpoint, params)
 */
function getApiEndpoint($apiName, $apiAction, $credentials)
{
    global $log;
    $cache = getFromCache($apiName . '_' . $apiAction);
    if (!$cache) {
        $con = conectar(1);
        $apiName = mysqli_real_escape_string($con, $apiName);
        $apiAction = mysqli_real_escape_string($con, $apiAction);
        $sql = "SELECT external_apis.url, external_apis.api_header, external_apis_params.endpoint, external_apis_params.params, external_apis_params.method FROM external_apis
                INNER JOIN external_apis_params ON external_apis_params.id_api = external_apis.id
                WHERE external_apis.api_name = '$apiName'
                AND external_apis_params.action = '$apiAction'";
        $result = lectura($sql, $con);
        if ($result) {
            setToCache($apiName . '_' . $apiAction, $result, 31536000);
        }
    } else {
        $result = $cache->get();
    }

    if (!$result) {
        $log->error('Error getting API Endpoint', array('name' => $apiName, 'action' => $apiAction));
    }

    //TODO HACK : TO FIX!!! we should automatically merge params in url external_apis field with the credentials they should correspond and not be hard coded like below
    if (preg_match("/:/", $result['url']) == 1) {

        //this assumes the url column in external_apis has :account_id and needs to replace it with the key field in credentials taken from external_hotel_apis
        //better would be to merge the 2 objects from the selects of each tables and reconstruct the url after
        $url = str_replace(":account_id", $credentials['key'], $result['url']);

        $result['url'] = $url;

    }

    //if the endpoint has required/optional params return them well formatted
    if (!is_null($result['params'])) {

        //TODO: pass this before the set to cache

        //grab the required params and optional params with this regexp
        //required params -> :param
        //optional params -> [:param]
        preg_match_all("/\/\:(\w+)|\/\[\:(\w+)\]/", $result['params'], $matches, PREG_PATTERN_ORDER, 0);

        $params_required = array_flip(array_filter($matches[1]));
        $params_optional = array_flip(array_filter($matches[2]));

        //convert params string in db to array of params
        $result['params_required'] = $params_required;
        if ($params_optional) {
            $result['params_optional'] = $params_optional;
        }
    }

    //if endpoint url has params return them to be checked
    if (preg_match("/:/", $result['endpoint']) == 1) {
        preg_match_all("/\/\:(\w+)|\/\[\:(\w+)\]/", $result['endpoint'], $matches, PREG_PATTERN_ORDER, 0);

        $endpoint_params_required = array_flip(array_filter($matches[1]));
        $endpoint_params_optional = array_flip(array_filter($matches[2]));

        $result['endpoint_params_required'] = $endpoint_params_required;

        if ($endpoint_params_optional) {
            $result['endpoint_params_optional'] = $endpoint_params_optional;
        }

    }

    return $result;

}

//function to check if the keys in object1 are included in object2
//else throw error with list of missing keys
/**
 * @param $array of passed data to check
 * @param $array of params
 * @return false|array
 */
function includeKeysIn($object1, $object2)
{
    //check the difference of keys between the two arrays
    $diff = array_diff_key($object1, $object2);

    //if the difference is empty return false
    if (empty($diff)) {
        return false;
    } else {
        // else return string with the list of params missing
        throw new Exception('You are missing the following keys : ' . concatKeys($diff));
    }
}

function checkRequiredParams($obj, $endpointObj, $keysArray = ['endpoint_params_required', 'params_required'])
{
    global $log;

    $log->debug("endpointObj", ["endpointObj", $endpointObj]);

    foreach ($keysArray as $key) {
        $log->debug("key", ["key", $key]);
        if (isset($endpointObj[$key])) {
            $log->debug("endpointObj[key]", ["endpointObj[key]", $endpointObj[$key]]);
            $log->debug("obj", ["obj", $obj]);
            includeKeysIn($endpointObj[$key], $obj);
        }
    }
}

function buildEndpointQuery($credentials, $endpointObj)
{
    global $log;

    //create the curl
    $curl = new Curl\Curl();

    //add simple http headers
    $headers = [
        'Accept:application/json',
        'Content-Type:application/json',
    ];

    //add the authorization token header if secred is defined in credentials
    if (isset($credentials['secret'])) {
        array_push($headers, 'Authorization:Token token=' . $credentials['secret'] . '');
    }

    if (isset($endpointObj['api_header']) && $endpointObj['api_header'] != "") {
        array_push($headers, $endpointObj['api_header']);
    }

    //set options for curl
    $curl->setOpt(CURLOPT_RETURNTRANSFER, true);
    $curl->setOpt(CURLOPT_HTTPHEADER, $headers);
    $curl->setOpt(CURLOPT_VERBOSE, true);
    $curl->setOpt(CURLOPT_HEADER, false);
    $curl->setOpt(CURLOPT_FAILONERROR, false);
    $curl->setOpt(CURLOPT_HTTP200ALIASES, (array) 400);

    // $curl->setOpt(CURLOPT_FAILONERROR, true);
    // $curl->setOpt(CURLOPT_HTTP200ALIASES, array(400));

    //return instance of curl
    return $curl;
}

function sendEndpointQuery($curl, $endpointObj, $queryObj = array(), $asoc = false)
{
    global $log;

    if (!isset($endpointObj['method'])) {
        error_log('We cannot build the external API endpoint since it does not define a http request method : ' . json_encode($endpointObj) . json_encode($queryObj));
        // throw new Exception('We cannot build the external API endpoint since it does not define a http request method : ' . $endpointObj['url']);
    }

    if (!is_array($queryObj)) {
        error_log("The passed queryObj $queryObj is not an array, so we cannot send it to the called external API endpoint }");
        // throw new Exception("The passed queryObj $queryObj is not an array, so we cannot send it to the called external API endpoint }");
    }

    $endpoint_params_required = (isset($endpointObj['endpoint_params_required']) ? $endpointObj['endpoint_params_required'] : []);

    if (!empty($endpoint_params_required)) {

        //TODO: extract this to help function
        //fill the endpoint object with correct values
        $filledEndpointObj = array_intersect_key($queryObj, $endpoint_params_required);

        //prefix : to each key of the endpoint object
        $prefix = ':';
        $prefixed_array = array();
        //create a new array to use in strtr replace below e.g. [:account_id : 234324323, :user_id : 2323]
        foreach ($filledEndpointObj as $key => $value) {
            $prefixed_array[$prefix . $key] = $value;
        }

        //replace the endpoint url with correct values e.g. /endpoint/:account_id/:user_id => /endpoint/23422323/2342
        $endpointObj['endpoint'] = strtr($endpointObj['endpoint'], $prefixed_array);
    }

    //simply append the beginning url endpoint with the rest of the filled in endpoint url
    $finalEndpoint = $endpointObj['url'] . $endpointObj['endpoint'];

    $params_required = (isset($endpointObj['params_required']) ? $endpointObj['params_required'] : []);
    $params_optional = (isset($endpointObj['params_optional']) ? $endpointObj['params_optional'] : []);

    //if query has optional params merge them with $queryObj
    $optionalObj = array_intersect_key($queryObj, $params_optional);

    $requiredObj = array_merge($params_required, $queryObj);

    //merge the passed queryObject with the params
    $mergedObj = array_merge($requiredObj, $optionalObj);

    //TODO: remove fields ['endpoint_params_optional'] from $mergedObj
    // If endPoint params required is not empty check if duplicated params are present and unset
    if (!empty($endpointObj['endpoint_params_required'])) {
        foreach ($endpointObj['endpoint_params_required'] as $key => $value) {
            unset($mergedObj[$key]);
        }
    }

    //check what method for endpoint
    switch ($endpointObj['method']) {
        case 'GET':
            $curl->get($finalEndpoint, $mergedObj);
            break;
        case 'POST':
            $curl->post($finalEndpoint, json_encode($mergedObj));
            break;
        case 'PUT':
            $curl->put($finalEndpoint, json_encode($mergedObj), true);
            break;
        case 'PATCH':
            $curl->patch($finalEndpoint, $mergedObj);
            break;
        case 'DELETE':
            $curl->delete($finalEndpoint, $mergedObj);
            break;
    }
    $log->debug("curl responese", [$curl->response]);
    if ($asoc == true) {
        return json_decode($curl->response, true);
    }
    //return response if all is good
    return (array) json_decode($curl->response);
}
