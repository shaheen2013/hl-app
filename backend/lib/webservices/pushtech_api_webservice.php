<?php

//Hotelinking mandatory libraries and methods
include_once 'librerias.php';

//include curl
include_once RUTA_DIR . LIB . 'pushtech_api.php';

//Include compose feecback from webservice library
include_once RUTA_DIR . LIB . 'webservices/msgFeedback.php';

//hack to reset $_POST object when calling the ws as curl
//passing it the json params to not loose it's types
if (!empty($_POST['ws-params'])) {
    $_POST = json_decode($_POST['ws-params'], true);
}

if (!empty($_POST['action'])) {

    $result = array();
    
    //Update campaign
    if ($_POST['action'] === 'updateCampaign') {

        //If not minimum data is available
        if (empty($_POST['campaign']) || empty($_POST['offer']) || empty($_POST['last_hotel_id'])) {
            //Send feedback error insufficient data
            $result['code'] = msgFeedbackWs('4069', $_SESSION['userLang']);
            echo json_encode($result);
            exit;
        }

        $updated = updateCampaign($_POST['campaign'], $_POST['offer'], $_POST['last_hotel_id']);
        if (!$updated) {
            //Send a feedback error duplicated
            $result['code'] = msgFeedbackWs('4068', $_SESSION['userLang']);
        } else {
            //Send Ok feedback
            $result['code'] = msgFeedbackWs('2007', $_SESSION['userLang']);
        }
        $result['response'] = $updated;
        echo json_encode($result);
    }

    //Delete mapping
    if ($_POST['action'] === 'deleteMapping') {
        $deleted = deleteMapping($_POST['id'], $_POST['last_hotel_id']);
        $result['code'] = msgFeedbackWs('2007', $_SESSION['userLang']);
        $result['response'] = $deleted;
        echo json_encode($result);
    }
}

///////////
/////////// Helper functions and DB connection
///////////

/**
 * Update campaign on database from Hotelinking Dashboard
 * @param $campaign
 * @param $offer
 * @param $mapping_id
 */
function updateCampaign($campaign, $offer, $id_hotel)
{
    $con = conectar();
    $campaign = mysqli_real_escape_string($con, $campaign);
    $offer = mysqli_real_escape_string($con, $offer);
    $id_hotel = mysqli_real_escape_string($con, $id_hotel);
    $sql = "INSERT INTO pushtech_campaigns_mappings (id_campaign, id_offer, id_hotel)
                VALUES ('$campaign', $offer, $id_hotel)
                ON DUPLICATE KEY UPDATE id_campaign = VALUES(id_campaign), id_offer = VALUES(id_offer), id_hotel = VALUES(id_hotel)";
    $result = escritura($sql, $con);
    if ($result) {
        deleteCacheByKey('pushtech_mapped_offers_' . $id_hotel);
    }

    return $result;
}

;

/**
 * Delete a mapping from database
 * @param $id
 * @param $id_hotel
 * @return int|string
 */
function deleteMapping($id, $id_hotel)
{
    $con = conectar();
    $id = mysqli_real_escape_string($con, $id);
    $sql = "DELETE FROM pushtech_campaigns_mappings WHERE id = '$id'";
    $result = escritura($sql, $con);
    if ($result) {
        deleteCacheByKey('pushtech_mapped_offers_' . $id_hotel);
    }

    return $result;

};

//get all the users for the associated account
function getPushtechUserList($credentials)
{
    $endpoint = getApiEndpoint('pushtech', 'get_user_list', $credentials);
    $curl = buildEndpointQuery($credentials, $endpoint);
    $res = sendPushtechEndpointQuery($curl, $endpoint);
    return $res['contacts'];
}

function getPushtechUser($userObj, $credentials)
{
    global $log;
    $endpoint = getApiEndpoint('pushtech', 'get_user_list', $credentials);
    $curl = buildEndpointQuery($credentials, $endpoint);
    $res = sendPushtechEndpointQuery($curl, $endpoint, $userObj);

    $arrayKeys = array_keys($res['contacts']);
    if (!$res && isset($res['contacts']) && $res['contacts'][$arrayKeys[0]]) {
        return $res['contacts'][$arrayKeys[0]];
    } else {
        $log->warning('Error getPushtechUser: user is not in pushtech', [$userObj]);
        return false;
    }
}
