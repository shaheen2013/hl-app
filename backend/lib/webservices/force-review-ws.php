<?php
// Basic libraries
include_once 'librerias.php';

// Disable indexcontrolval to be accesible as a webservice
define("INDEXCONTROLVAL", "1");
global $log;

if (empty($_POST['hotel_id']) ||
    empty($_POST['user_id']) ||
    array_get($_POST, 'score') == null ||
    empty($_POST['satisfaction_id']) ||
    empty($_POST['comment']) ||
    empty($_POST['sending_days']) ||
    empty($_POST['lang']))
{
    echo json_encode($_POST);
    $log->error('Access to force review webservice without all data needed, exiting', $_POST);
    exit;
}

include_once RUTA_DIR . LIB . 'hotelinking_emails.php';
include_once RUTA_DIR . LIB . 'create_review_survey.php';
include_once RUTA_DIR . LIB . 'emails-webservice-helpers.php';
include_once RUTA_DIR . 'models/satisfaction-surveyModel.php';

$hotel_id = $_POST['hotel_id'];
$sending_days = $_POST['sending_days'];

$user = array(
    'id' => $_POST['user_id'],
    'score' => $_POST['score'],
    'comment' => $_POST['comment'],
    'satisfaction_id' => $_POST['satisfaction_id'],
    'lang' => $_POST['lang'],
);

$log->info('Starting Review email process');

$sending_days = $sending_days == "now" ?  date('Y-m-d') : getSendDate($hotel_id, 'review');

$statusCreateReviewSurvey = createReviewSurvey($user, $hotel_id, $sending_days);

$reviewUser = getReviewUser($user['id'], $hotel_id);
$diffDate = 0;

if($reviewUser){

    $date1 = date_create(date('Y-m-d'));
    $date2 = date_create($reviewUser['send_date']);
    $diff = date_diff($date1, $date2);
    $diffDate = intval($diff->format("%a"));

}

include_once RUTA_DIR . LANG . $user['lang'] . '/satisfaction-list.php';

if($statusCreateReviewSurvey){
    
    $result['send_review'] = TRUE;

    if($diffDate < 1){

        $msgStatus = $satisfactionLang['success_send_review'].' '.$satisfactionLang['today'].'.';
        
    } else {

        $msgStatus = $satisfactionLang['success_send_review'].' '.$diffDate.' '.$satisfactionLang['days'].'.';

    }
    
    guardarSatisfaction($hotel_id, $user['id'], $user['score'], $user['comment'], $user['satisfaction_id'], 1 , 0);

} else {

    $result['send_review'] = FALSE;

    $waitingDays = ($diffDate + 1) > 1 ? $satisfactionLang['days'] : $satisfactionLang['day'];
    $msgStatus = $satisfactionLang['error_send_review'].' '.($diffDate + 1).' '.$waitingDays;

}

$msgType = $result['send_review'] ? $msgType = "fa-check" : $msgType = "fa-ban";
$result['msgStatus'] = '<i class="fa ' . $msgType . ' fa-2x blanco pull-left"></i>
	<ul><li>' . $msgStatus . '</li></ul>
	<a href="#" class="close-feedback blanco"><img src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTguMS4xLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgdmlld0JveD0iMCAwIDE3NC4yMzkgMTc0LjIzOSIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgMTc0LjIzOSAxNzQuMjM5OyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSIgd2lkdGg9IjI0cHgiIGhlaWdodD0iMjRweCI+CjxnPgoJPHBhdGggZD0iTTg3LjEyLDBDMzkuMDgyLDAsMCwzOS4wODIsMCw4Ny4xMnMzOS4wODIsODcuMTIsODcuMTIsODcuMTJzODcuMTItMzkuMDgyLDg3LjEyLTg3LjEyUzEzNS4xNTcsMCw4Ny4xMiwweiBNODcuMTIsMTU5LjMwNSAgIGMtMzkuODAyLDAtNzIuMTg1LTMyLjM4My03Mi4xODUtNzIuMTg1UzQ3LjMxOCwxNC45MzUsODcuMTIsMTQuOTM1czcyLjE4NSwzMi4zODMsNzIuMTg1LDcyLjE4NVMxMjYuOTIxLDE1OS4zMDUsODcuMTIsMTU5LjMwNXoiIGZpbGw9IiNGRkZGRkYiLz4KCTxwYXRoIGQ9Ik0xMjAuODMsNTMuNDE0Yy0yLjkxNy0yLjkxNy03LjY0Ny0yLjkxNy0xMC41NTksMEw4Ny4xMiw3Ni41NjhMNjMuOTY5LDUzLjQxNGMtMi45MTctMi45MTctNy42NDItMi45MTctMTAuNTU5LDAgICBzLTIuOTE3LDcuNjQyLDAsMTAuNTU5bDIzLjE1MSwyMy4xNTNMNTMuNDA5LDExMC4yOGMtMi45MTcsMi45MTctMi45MTcsNy42NDIsMCwxMC41NTljMS40NTgsMS40NTgsMy4zNjksMi4xODgsNS4yOCwyLjE4OCAgIGMxLjkxMSwwLDMuODI0LTAuNzI5LDUuMjgtMi4xODhMODcuMTIsOTcuNjg2bDIzLjE1MSwyMy4xNTNjMS40NTgsMS40NTgsMy4zNjksMi4xODgsNS4yOCwyLjE4OGMxLjkxMSwwLDMuODIxLTAuNzI5LDUuMjgtMi4xODggICBjMi45MTctMi45MTcsMi45MTctNy42NDIsMC0xMC41NTlMOTcuNjc5LDg3LjEyN2wyMy4xNTEtMjMuMTUzQzEyMy43NDcsNjEuMDU3LDEyMy43NDcsNTYuMzMxLDEyMC44Myw1My40MTR6IiBmaWxsPSIjRkZGRkZGIi8+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPC9zdmc+Cg==" /></i></a>';

header('Content-type: application/json');

echo json_encode($result);
