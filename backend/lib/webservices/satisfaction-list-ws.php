<?php
// Basic libraries
include_once 'librerias.php';

// Disable indexcontrolval to be accesible as a webservice
define("INDEXCONTROLVAL", "1");
global $log;
if (array_get($_POST, "action") == "hasBeenPromoted") {
    if (empty($_POST['satisfaction']))
    {
        echo json_encode($_POST);
        $log->error('Access to satisfaction-list webservice without all data needed to promote survey, exiting', $_POST);
        exit;
    }

    $satisfactionFavorite = array_get($_POST, 'favorite') ? 1 : 0;
    $result = modifySatisfactionUserFavorite(array_get($_POST, 'satisfaction'), $satisfactionFavorite);

    header('Content-type: application/json');

    echo json_encode($result);
}

if (array_get($_POST, "action") == "deleteSurvey") {
    if (empty($_POST['satisfactionId'])) {
        $log->error('Access to satisfaction-list webservice without all data needed to delete survey, exiting', $_POST);
        http_response_code(400);
        exit;
    }

    $userSurveyID = $_POST['satisfactionId'];
    $brandID = $_POST['brandId'];

    require_once '../../src/Services/Connections/ApiGatewayConnection.php';
    $gateway = new ApiGatewayConnection();
    $endpoint = HOTELINKING_ENDPOINT . 'brands/' . $brandID . '/surveys/' . $userSurveyID . '/delete';
    $log->info($endpoint);

    try {
        $gateway->sendRequest(NULL, $endpoint, 'POST');
        http_response_code(200);
    } catch (\Exception $e) { 
        http_response_code(400);
    }
}

if (array_get($_POST, "action") == "hasBeenSeen") {
    if (empty($_POST['satisfaction']))
    {
        echo json_encode($_POST);
        $log->error('Access to satisfaction-list webservice without all data needed to mark survey as seen, exiting', $_POST);
        exit;
    }   

    $hasBeenSeen = array_get($_POST, 'hasBeenSeen') ? 1 : 0;
    $result = modifySatisfactionUserHasBeenSeen(array_get($_POST, 'satisfaction'), $hasBeenSeen);

    header('Content-type: application/json');

    echo json_encode($result);
}
else if(array_get($_POST, "action") == "exportCSV") {
    require_once '../../src/Services/Connections/ApiGatewayConnection.php';
    $satisfactionParams = [
        "search"    => array_get($_POST, 'search'),
        "from"      => array_get($_POST, 'from'),
        "to"        => array_get($_POST, 'to'),
        "order"     => array_get($_POST, 'order'),
        "sort"      => array_get($_POST, 'sort'),
        "emails"    => array_values(
            array_filter(
                array_map(
                    'trim', 
                    explode(",", array_get($_POST, 'emails'))
                ),
                'strlen'
            )
        )
    ];
    $brandID = array_get($_POST,'brandId');
    $gateway = new ApiGatewayConnection();

    try {
        $gateway->sendRequest($satisfactionParams, HOTELINKING_ENDPOINT . 'brands/' . $brandID . '/surveys/report', 'POST');
        http_response_code(200);
    } catch (\Exception $e) { 
        http_response_code(400);
    }

    exit;
}

function modifySatisfactionUserHasBeenSeen($satisfaction_id, $hasBeenSeen){
    $who_has_been_seen = "";
    $assisted_staff = "";
    $con = conectar();

    if ($hasBeenSeen == 0) {
        $who_has_been_seen = ", who_has_been_seen = -1 ";
    } else {
        if (!empty($_SESSION['staff_logueado'])) {
            $who_has_been_seen = ", who_has_been_seen = ".$_SESSION['staff_logueado'];
            $assisted_staff = ", assisted_staff_id = ".$_SESSION['staff_logueado'];
        }
    }
   
    $updateUserSatisfaction = "UPDATE user_satisfaction SET has_been_seen = $hasBeenSeen ".$who_has_been_seen." WHERE id = $satisfaction_id";
    $updateUserSurvey = "UPDATE user_survey SET assisted = $hasBeenSeen, assisted_at = NOW() ".$assisted_staff." WHERE user_satisfaction_id = $satisfaction_id";
    
    startTransaction($con);
    
    escritura($updateUserSatisfaction, $con, false);
    $userSatisfactionInserted = mysqli_affected_rows($con);
    
    escritura($updateUserSurvey, $con, false);
    $userSurveyInserted = mysqli_affected_rows($con);

    if ($userSatisfactionInserted && $userSurveyInserted) {
        commitTransaction($con);
    } else {
        rollbackTransaction($con);
        global $log;
        $log->error("Error updating survey assisted on transaction", ["User Survey" => $userSurveyInserted, "User Satisfaction" => $userSatisfactionInserted, "POST" => $_POST]);
    }

    return $hasBeenSeen;
}

function modifySatisfactionUserFavorite($satisfaction_id, $favorite){
    $con = conectar();
    $selectSurveyQuestionAnswer = "SELECT user_survey_question_answer.id FROM user_survey 
        INNER JOIN user_survey_question_answer ON user_survey.id = user_survey_question_answer.user_survey_id AND user_satisfaction_id = $satisfaction_id";
    $surveyQuestionAnswer = lectura($selectSurveyQuestionAnswer, $con, false);
    $updateUserSatisfaction = "UPDATE user_satisfaction SET favorite = $favorite WHERE id = $satisfaction_id";
    $updateUserSurvey = "UPDATE user_survey_question_answer SET favorite = $favorite WHERE id = ". $surveyQuestionAnswer['id'];

    startTransaction($con);
    escritura($updateUserSatisfaction, $con, false);
    $userSatisfactionInserted = mysqli_affected_rows($con);

    escritura($updateUserSurvey, $con, false);
    $userSurveyInserted = mysqli_affected_rows($con);
    if ($userSatisfactionInserted && $userSurveyInserted) {
        commitTransaction($con);
    } else {
        rollbackTransaction($con);
        global $log;
        $log->error("Error updating survey favorite on transaction", ["User Survey" => $userSurveyInserted, "User Satisfaction" => $userSatisfactionInserted, "POST" => $_POST]);
    }

    return $favorite;
}
