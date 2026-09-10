<?php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}

global $log;

//Contenido solo visible si logueado
include_once LIB . 'logueado.php';
include_once LIB . 'obtenerdatosHotel.php';
include_once LIB . 'curateEmailsString.php';
include_once LIB . 'satisfactions.php';
include_once LIB . 'hotelinking_emails.php';
include_once APP . 'Services/Connections/ApiGatewayConnection.php';

hotelStaffLanding();// Si no esta logueado lo manda a la landing

$currentSubPage = 'satisfaction-filter';

$brandID = $_SESSION['loggedBrandID'];

$gateway = new ApiGatewayConnection();
$endPoint = HOTELINKING_ENDPOINT . "brands/{$brandID}/products/" . getIdByProductName('satisfaction') . "/configuration";

$key = 'satisfaction_hotel_' . $brandID;
$keyCustomized = 'customized_satisfaction_hotel_' . $brandID;
$customizedConfig = getCustomizedSatisfactionConfig($gateway, $keyCustomized, $brandID);

$customizedSatisfactionProductId = $customizedConfig['customizedSatisfactionProductId'];
$configuration = $customizedConfig['configuration'];
$questionsConfiguration = $customizedConfig['questionsConfiguration'];

$satisfactionHotel = safeJsonParser($gateway->sendRequest([], $endPoint, 'GET'), true);

$langs = array_column(safeJsonParser($gateway->sendRequest([], HOTELINKING_ENDPOINT . "langs", 'GET'), true), 'lang');
$brandToUpdate = array_get($configuration, 'customizedChainActivated') ?
    array_get($_SESSION, 'loggedParentBrandID') :
    $brandID;

// Check if satisfaction surveys product and customized satisfaction surveys product are active in order to show control in the page
include_once LIB . 'hotelinking_emails.php';
$brandProducts = obtenerProductosHotel($brandID);
$followUpProductActive = getBrandProductActive($brandProducts, 'followup_mail');
$portalProProductActive = getBrandProductActive($brandProducts, 'portal_pro');
$hotelSatisfactionSurveysProductActive = getBrandProductActive($brandProducts, 'satisfaction');
$customizedSatisfactionSurveysProductActive = getBrandProductActive($brandProducts, 'customized_satisfaction_surveys');
$activeCustomizedSatisfaction = (array_get($_POST, 'activeCustomizedSatisfaction') === 'on') ? 1 : 0;

if (!empty($_POST) && !empty($_SESSION['h_logueado']) && !array_has($_POST, 'relogin_hotel_id')) {
    $sendHour = !empty($_POST['sendHourSatisf']) ? $_POST['sendHourSatisf'] : 0;
    $sendAfterDays = !empty($_POST['diasEnvioSatisf']) ? $_POST['diasEnvioSatisf'] : 0;

    $diff = $customizedConfig['configuration']['customizedType'] == 'In a later email' ? getDiffInHours($sendAfterDays, $sendHour, $customizedConfig['configuration']['customizedSendDays'], $customizedConfig['configuration']['customizedSendHours']) : 0;

    if ($activeCustomizedSatisfaction && $diff < 0) {
        $ok = [false, '4091'];
    } else {
        $satisfactionHotelData = [
            'sendHour'           => $sendHour,
            'totalFollowupEmail' => (!empty($_POST['followupEmails']) ? $_POST['followupEmails'] : 2),
            'puntMin'            => (!empty($_POST['filter_warning']))
                ? 10
                : (!empty($_POST['puntMin']) ? $_POST['puntMin'] : null),
            'reviewAverageScore' => (!empty($_POST['reviewAverageScore']) ? $_POST['reviewAverageScore'] : null),
            'chainEmail'         => (!empty($_POST['chain-email']) ? $_POST['chain-email'] : null),
            'parentID'           => $_SESSION['loggedParentBrandID'] ?? null,
            'sendThanksMail'     => (!empty($_POST['sendThanksMail']) ? 1 : 0),
            'sendToNonCustomers' => (!empty($_POST['sendToNonCustomers']) ? 1 : 0),
            'sendAfterDays'      => $sendAfterDays,
            'filterWarning'      => (!empty($_POST['filter_warning']) ? 0 : 1),
            'forceComment'       => (!empty($_POST['force_comment']) ? 1 : 0),
            'defaultScore'       => (!empty($_POST['defaultScore']) ? $_POST['defaultScore'] : null)
        ];

        //Check if there are changes in warning emails field
        if ($_POST['warning_email'] != $satisfactionHotel['warningEmail']) {
            //check and cure warning emails split string by ',' and ';'
            $newWarningEmailsArray = curateEmailsString($_POST['warning_email']);
            //Log it
            $log->info("Warning emails updated", [
                "Previous warning emails" => $satisfactionHotel['warningEmail'],
                "New warning emails"      => $newWarningEmailsArray,
                "brand_id"                => array_get($_SESSION, 'c_logueado') ? $_SESSION['loggedParentBrandID'] : $_SESSION['loggedBrandID'],
                "hotel_id"                => $_SESSION['h_logueado']
            ]);
        }

        //Insert curated emails in PUT object or previous warning emails
        $satisfactionHotelData['warningEmail'] = (isset($newWarningEmailsArray) ? implode(",", $newWarningEmailsArray) : $satisfactionHotel['warningEmail']);

        $gateway->sendRequest($satisfactionHotelData, $endPoint, 'PUT');
        $satisfactionHotel = safeJsonParser($gateway->sendRequest([], $endPoint, 'GET'), true);

        setToCache($key, $satisfactionHotel, 54000);

        // Change email in all hotels in chain if needed
        // Feedback
        $ok = [true, '2007'];
    }
}

if (!empty($_POST) && !array_has($_POST, 'relogin_hotel_id')) {

    if (array_has($_POST, 'hotelConfirmButton')) {
        $success = true;
        $simultaneousSatisfaction = array_get($_POST, 'sendSimultaneousSatisfaction', 2);
        $customizedSendDays = array_get($_POST, 'diasEnvioCustomizedSatisf', 0);
        $customizedSendHours = array_get($_POST, 'sendHourCustomizedSatisf', 0);
        
        if ($activeCustomizedSatisfaction && $simultaneousSatisfaction ==  2) {
            $diff = getDiffInHours($satisfactionHotel['sendAfterDays'], $satisfactionHotel['sendHour'], $customizedSendDays, $customizedSendHours);
            if ($diff < 0) {
                $success = false;
                $ok = [false, '4091'];
            }
        }

        if ($success) {

            $payload = [
                'customizedActive'         => $activeCustomizedSatisfaction,
                'customizedType'           => $simultaneousSatisfaction, // 1 = send simultaneous to satisfaction, 2 = send after X (configured by customizedSendHours and customizedSendDays), 3 = After PMS checkout date
                'customizedSendDays'       => $customizedSendDays,
                'customizedSendHours'      => array_get($_POST, 'sendHourCustomizedSatisf', 0),
                'customizedChainActivated' => (array_get($_POST, 'useBrandQuestions') === 'on') ? 1 : 0,
                'customizedComment'        => (array_get($_POST, 'customizedComment') === 'on') ? 1 : 0,
                'customizedWarning'        => (array_get($_POST, 'customizedWarning') === 'on') ? 1 : 0,
                'categoryOrder'            => $_POST['categoryOrder'],
                'questionOrder'            => $_POST['questionOrder']
            ];

            try {
                $answer = $gateway->sendRequest($payload, HOTELINKING_ENDPOINT . "brands/$brandID/products/$customizedSatisfactionProductId/configuration", 'PUT');
                $ok = [true, '2007'];
            } catch (Exception $e) {
                $log->error("Error sending request to " . HOTELINKING_ENDPOINT . "brands/$brandID/products/$customizedSatisfactionProductId/configuration", [$e]);
            }
            //Feedback
            $ok = [true, '2007'];
        }
    }

    if (array_has($_POST, 'shareWithChain')) {
        try {
            $answer = $gateway->sendRequest($payload, HOTELINKING_ENDPOINT . "brands/$brandID/survey-questions/spread", 'POST');
            $ok = array(true, '2007');
        } catch (Exception $e) {
            $log->error("Error sending request to " . HOTELINKING_ENDPOINT . "brands/$brandID/survey-questions/spread", [$e]);
        }
    }

    if (isset($_POST['create_category'])) {
        $category_texts = [];

        foreach ($langs as $lang) {
            $category_texts  [] = ["lang_value" => "$lang", "text" => array_get($_POST, 'category_' . $lang)];
        }
        $payload = ['survey_category_text' => json_encode($category_texts)];

        try {
            $answer = $gateway->sendRequest($payload, HOTELINKING_ENDPOINT . "brands/$brandToUpdate/surveys/satisfactions/categories", 'POST');
            $ok = array(true, '2007');
        } catch (Exception $e) {
            $log->error("Error sending request to " . HOTELINKING_ENDPOINT . "brands/$brandToUpdate/surveys/satisfactions/categories" . $category_id, [$e]);
        }
    }
    
    if (isset($_POST['update_category'])) {
        $category_texts = [];
        $categoryId = array_get($_POST, 'category_id');


        foreach ($langs as $lang) {
            $category_texts  [] = ["lang_value" => "$lang", "text" => array_get($_POST, 'category_' . $lang)];
        }

        $payload = ['survey_category_text' => json_encode($category_texts)];

        try {
            $answer = $gateway->sendRequest($payload, HOTELINKING_ENDPOINT . "brands/$brandToUpdate/surveys/satisfactions/categories/" . $categoryId . "", 'PUT');
            $ok = array(true, '2007');
        } catch (Exception $e) {
            $log->error("Error sending request to " . HOTELINKING_ENDPOINT .  "brands/$brandToUpdate/surveys/satisfactions/categories/" . $categoryId . "", [$e]);
        }
    }

    if (isset($_POST['create_question'])) {
        $questions_texts = [];

        foreach ($langs as $lang) {
            $questions_texts [] = ["lang_value" => "$lang", "text" => array_get($_POST, 'question_' . $lang)];
        }

        $answers = [];
        foreach (range(1, count(preg_grep('/^answer[\d]*/', array_keys($_POST)))/count($langs)) as $idx) {
            $answer = [];

            foreach ($langs as $lang) {
                $answer [] = ["lang_value" => "$lang", "text" => array_get($_POST, 'answer_' . $idx. '_' . $lang)];
            }
            $answers [] = $answer;
        }

        $payload = [
            'survey_questions_text' => json_encode($questions_texts), 
            "survey_answers" => json_encode($answers),
            'required' => 1, 
            'type' => array_get($_POST, 'questionType'), 
            'allow_comment' => array_get($_POST, 'otherResponse'), 
            'multiple_selection' => array_get($_POST, 'multipleSelection'), 
            'survey_category_id' => array_get($_POST, 'category_id')
        ];
      
        try {
            $answer = $gateway->sendRequest($payload, HOTELINKING_ENDPOINT . "brands/$brandToUpdate/survey-questions", 'POST');
            $ok = array(true, '2007');
        } catch (Exception $e) {
            $log->error("Error sending request to " . HOTELINKING_ENDPOINT . "brands/$brandToUpdate/survey-questions", [$e]);
            $ok = array(false, '4065');
        }

    }

    if (isset($_POST['delete_category'])) {

        $category_id = array_get($_POST, 'delete_category_id');
        try {
            $answer = $gateway->sendRequest(null, HOTELINKING_ENDPOINT . "brands/$brandToUpdate/surveys/satisfactions/categories/" . $category_id . "", 'DELETE');
            $ok = array(true, '2007');
        } catch (Exception $e) {
            $log->error("Error sending request to " . HOTELINKING_ENDPOINT . "brands/$brandToUpdate/surveys/satisfactions/categories/" . $category_id, [$e]);
        }
    }

    if (isset($_POST['delete_query'])) {

        $query_id = array_get($_POST, 'delete_question_id');
        try {
            $answer = $gateway->sendRequest(null, HOTELINKING_ENDPOINT . "brands/$brandToUpdate/survey-questions/$query_id", 'DELETE');
            $ok = array(true, '2007');
        } catch (Exception $e) {
            $log->error("Error sending request to " . HOTELINKING_ENDPOINT . "brands/$brandToUpdate/survey-questions/$query_id", [$e]);
        }
    }

    if (isset($_POST['questionID'])) {
        try {
            $questionID = $_POST['questionID'];
            $payload = [
                "required" => array_get($_POST, 'mandatoryQuestion') ? 0 : 1,
                "active" => array_get($_POST, 'questionActive') ? 1 : 0
            ];

            $gateway->sendRequest($payload, HOTELINKING_ENDPOINT . "brands/$brandToUpdate/survey-questions/$questionID", 'PUT');
            $ok = array(true, '2007');
        } catch (Exception $e) {
            $log->error("Error sending request to " . HOTELINKING_ENDPOINT . "brands/$brandToUpdate/survey-questions/$questionID", [$e]);
            $ok = array(false, '4065');
        }
    }
    
    if (isset($_POST['update_question_text'])) {
        try {
            $questionID = $_POST['question_id'];
            $questions_texts = [];

            foreach ($langs as $lang) {
                $questions_texts [] = ["lang_value" => "$lang", "text" => array_get($_POST, 'updateQuestion_' . $lang)];
            }
            
            $answers = [];
            if (count(preg_grep('/^answer[\d]*/', array_keys($_POST)))) {
                foreach (range(1, count(preg_grep('/^answer[\d]*/', array_keys($_POST)))/count($langs)) as $idx) {
                    $answer = [];

                    foreach ($langs as $lang) {
                        $answer [] = [
                            "question_response_id"  => array_get($_POST, 'answer_' . $idx. '_id'),
                            "lang_value"            => "$lang", 
                            "text"                  => array_get($_POST, 'answer_' . $idx. '_' . $lang)
                        ];
                    }
                    $answers [] = $answer;
                }
            }

            $payload = [
                'survey_questions_text' => json_encode($questions_texts), 
                "survey_answers" => !empty($answers) ? json_encode($answers) : null,
            ];

            $gateway->sendRequest($payload, HOTELINKING_ENDPOINT . "brands/$brandToUpdate/survey-questions/$questionID", 'PUT');
            $ok = array(true, '2007');
        } catch (Exception $e) {
            $log->error("Error sending request to " . HOTELINKING_ENDPOINT . "brands/$brandToUpdate/survey-questions/$questionID", [$e]);
            $ok = array(false, '4065');
        }
    }

    deleteCacheByKey($keyCustomized);
    deleteCacheByKey($key);
    deleteCacheByKey('hotel_satisfaction_' . $_SESSION['h_logueado']);
    $cacheObject = getCustomizedSatisfactionConfig($gateway, $keyCustomized, $brandID);
    $configuration = $cacheObject['configuration'];
    $questionsConfiguration = $cacheObject['questionsConfiguration'];
}

function safeJsonParser($object, $assoc = false)
{
    if ($object) {
        return \GuzzleHttp\json_decode($object, $assoc);
    }
    return [];
}
