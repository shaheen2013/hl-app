<?php //Miramos si esta definida la variable de control de index.php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}

// For front purposes
$currentPage = 'autocheckin';
$currentSubPage = 'autocheckin-configuration';

global $log;

include_once LIB . 'curateEmailsString.php';
include_once LIB . 'obtenerdatosHotel.php';
include_once LIB . 'hotelinking_emails.php';
include LIB . '/autocheckinDocumentVariables.php';
include_once APP . 'Services/Connections/ApiGatewayConnection.php';

$brandID = (int) ($_SESSION['hotel']['brand_id'] ?? null);

if (!$brandID) {
    $ok = array(false, '4016');
    return;
}

$reservationsEmailsList = $_POST['reservations_emails'] ?? null;
$documentsEmailsList = $_POST['documents_emails'] ?? null;

$gateway = new ApiGatewayConnection();
$endPoint = HOTELINKING_ENDPOINT . "brands/{$brandID}/products/" . getIdByProductName('autocheckin') . "/configuration";

$autocheckinProductConfig = safeJsonParser($gateway->sendRequest([], $endPoint, 'GET'), true);

$autocheckinCommentsProductActive = $autocheckinProductConfig['data']['comments'];
$autocheckinDocumentsProductActive = $autocheckinProductConfig['data']['send_signed_documents_to_reception'];
$autocheckinIdentityDocumentsProductActive = $autocheckinProductConfig['data']['send_identity_documents_to_reception'];

$autocheckinUrl = AUTOCHECKIN_ENDPOINT . 'brands/' . $brandID . '/emails';

$emails = safeJsonParser($gateway->sendRequest([], $autocheckinUrl, 'GET'), true)['data'];
$availableVariablesList = getVariables();
// Front purposes
$reservationCommentCreated = array_where($emails, function ($key, $value) {
    return data_get($value, 'action_name') === "reservation_comment_created";
});
$reservationCommentEmailList = implode(", ", array_pluck($reservationCommentCreated, 'email'));


$autocheckinDocumentsRequested = array_where($emails, function ($key, $value) {
    return data_get($value, 'action_name') === "autocheckin_documents_requested";
});
$documentsEmailList = implode(", ", array_pluck($autocheckinDocumentsRequested, 'email'));
$documentsSubject =  data_get(array_pluck($autocheckinDocumentsRequested, 'subject'), '0');

if (!empty($_POST)) {
    if (!empty($reservationsEmailsList) || (empty($reservationsEmailsList) && !array_key_exists('relogin_hotel_id', $_POST) && !array_key_exists('url', $_POST))) {
        $action = safeJsonParser($gateway->sendRequest([], AUTOCHECKIN_ENDPOINT . 'actions/reservation_comment_created', 'GET'), true);
        $actionID = $action['data']['id'];
        
        $curatedEmails = !empty($reservationsEmailsList) ? curateEmailsString($reservationsEmailsList) : [];

        $request = [
            'brand_id'  => $brandID,
            'action_id' => $actionID,
            'emails'    => $curatedEmails
        ];

        $responseApi = $gateway->sendRequest($request, $autocheckinUrl, 'POST');

        $responseApi ? header("Location: /autocheckin-configuration", true, 301) : $ok = [false, '4065'];
    }
    
    if (!empty($documentsEmailsList) || (empty($documentsEmailsList) && !array_key_exists('relogin_hotel_id', $_POST) && !array_key_exists('url', $_POST))) {
        $action = safeJsonParser($gateway->sendRequest([], AUTOCHECKIN_ENDPOINT . 'actions/autocheckin_documents_requested', 'GET'), true);
        $actionID = $action['data']['id'];
        $subject = !empty(array_get($_POST, 'documents_subject')) ? array_get($_POST, 'documents_subject') : null;

        $curatedEmails = !empty($documentsEmailsList) ? curateEmailsString($documentsEmailsList) : [];

        $request = [
            'brand_id'  => $brandID,
            'action_id' => $actionID,
            'subject'   => $subject,
            'emails'    => $curatedEmails
        ];

        $responseApi = $gateway->sendRequest($request, $autocheckinUrl, 'POST');

        $responseApi ? header("Location: /autocheckin-configuration", true, 301) : $ok = [false, '4065'];
    }
}
