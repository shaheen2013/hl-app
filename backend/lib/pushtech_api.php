<?php
// if (!defined('INDEXCONTROLVAL')) {
//     echo 'No direct access allowed.';
//     exit;
// }

include_once 'cache.php';

include_once RUTA_DIR . LIB . 'apiData.php';

/**
 * Get mapped offers pushtech <-> Hotelinking
 * @param $id_hotel
 * @return array of offers
 */
function getMappedOffers($id_hotel)
{
    $cache = getFromCacheByHotelOrChain('pushtech_mapped_offers', $id_hotel);
    if (!$cache) {
        $con = conectar(1);
        $id_hotel = mysqli_real_escape_string($con, $id_hotel);
        $sql = "SELECT id, id_campaign, id_offer, days_valid FROM pushtech_campaigns_mappings WHERE id_hotel = '$id_hotel'";
        $resultado = lecturaArray($sql, $con);
        if ($resultado) {
            setToCacheByHotelOrChain('pushtech_mapped_offers', $id_hotel, $resultado, 31536000);
        }
    } else {
        $resultado = $cache->get();
    }
    return $resultado;
}

//call the pushtech webservice
//this should be done asyncrhonously since we dont want to wait for it in the stay-share (used to create/update pushtech user)
function callPushtechWebservice($action, $paramsArray)
{
    global $log;

    $paramsArray['action'] = $action;
    //call the pushtech api webservice to insert a user to pushtech
    $url = BASE_PATH . LIB . 'webservices/pushtech_api_webservice.php/';
    $curl = new Curl\Curl();
    $curl->post($url, array('ws-params' => json_encode($paramsArray)));
    if ($curl->error) {
        $log->error('Pushtech WS Error', array('message' => $curl->error_message));
    }
}

//Check if theses credentials work with pushtech API
function checkPushtechCredentials($credentials)
{
    //Create endPoint
    $endpoint = getApiEndpoint('pushtech', 'current_balance', $credentials);
    $curl = buildEndpointQuery($credentials, $endpoint);
    return sendPushtechEndpointQuery($curl, $endpoint);
}

//get the list of pushtech campaigns for the currently logged hotel
function getPushtechCampaigns($credentials, $filters = array("status" => ['draft', 'inprogress']))
{
    //Create endPoint
    $endpoint = getApiEndpoint('pushtech', 'campaign_list', $credentials);
    //check if params of user match with the params needed in external APIs
    checkRequiredParams($filters, $endpoint);
    $curl = buildEndpointQuery($credentials, $endpoint);
    //Send Request to pushtech api
    return sendPushtechEndpointQuery($curl, $endpoint, $filters, true);
}

/**
 * Create a pushtech product
 * @param $credentials array
 * @param $product_to_create array
 * @return array product response from pushtech
 */
function createPushtechProduct($credentials, $product_to_create)
{
    $create_product_endpoint = getApiEndpoint('pushtech', 'create_product', $credentials);
    $curl_object = buildEndpointQuery($credentials, $create_product_endpoint);
    return sendPushtechEndpointQuery($curl_object, $create_product_endpoint, $product_to_create);

}

/**
 * Create a pushtech product
 * @param $curl \Curl\Curl
 * @param $endpointObj array
 * @param $queryObj array
 * @param $asoc boolean
 * @return array response from pushtech
 */
function sendPushtechEndpointQuery($curl, $endpointObj, $queryObj = array(), $asoc = false)
{
    global $log;
    $pushtechAnswer = sendEndpointQuery($curl, $endpointObj, $queryObj, $asoc);
    if (array_get($pushtechAnswer, 'error') == null) {
        $log->debug('Success in communicate  with pushtech', ["answer" => $pushtechAnswer, "endpoint" => $endpointObj]);
        return $pushtechAnswer;
    } else if (array_get($pushtechAnswer, 'error.0') == 'User id has already been taken') {
        $log->warning('Could not create user in Pushtech, it might exist in the already ', ["endpoint" => $endpointObj]);
    } else if (array_get($pushtechAnswer, 'error') == 'Contact not found') {
        $log->warning('Contact not found in Pushtech', ["endpoint" => $endpointObj]);
    } else {
        $log->error('Error in Pushtech API:', ["error" => array_get($pushtechAnswer, 'error'), "queryObj" => $queryObj]);
    }
    return false;
}

//get the list of visited hotels by a user in a chain
//returns e.g. 1,32,5,334
function getVisitedHotelsOfChainID($userID, $chainID)
{
    $con = conectar(1);

    $userID = mysqli_real_escape_string($con, $userID);
    $chainID = mysqli_real_escape_string($con, $chainID);
    $sql = "SELECT GROUP_CONCAT(DISTINCT id_hotel) as hotels FROM user_hotels WHERE id_usuario='$userID' and id_cadena='$chainID'";
    $row = lectura($sql, $con);
    return $row['hotels'];
}

//Get number of visits by hotel parsed in json
function get_hotel_visits($user_id, $chain_id, $hotel_id)
{

    global $log;

    $hotel_visits_where = select_hotel_or_chain($hotel_id, $chain_id, 'hotel_visits');

    $select_hotel_visits = "SELECT CONCAT('{\"hotel_id\": ', '\"', hotel_id, '\", \"visits\": \"',num_visits,'\"}') AS hotel_visits
                            FROM users_visits" . $hotel_visits_where . " AND user_id = $user_id";

    $hotel_visits = lecturaArray($select_hotel_visits);

    $parsed_hotel_visits = array_map(function ($element) {
        return $element['hotel_visits'];
    }, $hotel_visits);

    return $parsed_hotel_visits;
}

//Get number of chain visits
function get_chain_visits($user_id, $chain_id)
{

    $select_chain_visits = "SELECT num_visits AS chain_visits FROM users_visits WHERE user_id = $user_id AND chain_id=$chain_id";
    $chain_visits = lectura($select_chain_visits);

    return (int) array_get($chain_visits, 'chain_visits', 0);
}

//Get all rooms stayed by user, date and hotel id parsed in json
function get_room_id($user_id, $chain_id, $hotel_id)
{
    if ($user_id && $hotel_id) {
        $select_room_id = "SELECT 
                            id_room 
                        FROM connection_history 
                        WHERE 
                            id_user=$user_id AND 
                            id_room <> '' AND 
                            id_hotel=$hotel_id 
                        ORDER BY last_login DESC LIMIT 1";

        $room_id = lecturaArray($select_room_id);

        return array_get($room_id, 'id_room');
    } else {
        return null;
    }
}

//Get AVG Satisfaction Score
function get_avg_satisfaction_score($user_id, $chain_id, $hotel_id)
{

    $avg_satisfaction_where = select_hotel_or_chain($hotel_id, $chain_id, 'satisfaction');
    $select_satisfaction_score = "SELECT AVG(puntuacion) AS satisfaction_avg
                                    FROM user_satisfaction" . $avg_satisfaction_where . " AND id_usuario=$user_id AND done=1";

    $score = lectura($select_satisfaction_score);

    $satis = array_get($score, 'satisfaction_avg', 0);

    return round($satis, 1);
}

//Chack if the user is staying on the hotel
function get_is_hotel_customer($user_id, $hotel_id)
{
    $select_customer = "SELECT customer FROM user_hotels WHERE id_hotel=$hotel_id AND id_usuario=$user_id ORDER BY fecha ASC";
    $customer = lectura($select_customer);

    if ($customer['customer'] == 1) {
        return true;
    } else {
        return false;
    }
}

//Helper function to previous functions to do properly selects (depending if user is sign up on chain or hotel and the function who called)
function select_hotel_or_chain($hotel_id, $chain_id, $origin)
{

    if ($chain_id) {
        if ($origin == 'rooms_id') {
            $select = " INNER JOIN cadena_hotel ON cadena_hotel.id_hotel = connection_history.id_hotel WHERE id_cadena = $chain_id";
        } else if ($origin == 'hotel_visits') {
            $select = " INNER JOIN cadena_hotel ON cadena_hotel.id_hotel = users_visits.hotel_id WHERE cadena_hotel.id_cadena = $chain_id AND hotel_id IS NOT NULL";
        } else {
            $select = " WHERE id_cadena=$chain_id";
        }
    } else {
        if ($origin == 'hotel_visits') {
            $select = " WHERE hotel_id = $hotel_id";
        } else {
            $select = " WHERE id_hotel = $hotel_id";
        }

    }

    return $select;
}
