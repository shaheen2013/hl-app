<?php //Miramos si esta definida la variable de control de index.php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}

// For front purposes
$currentPage = 'autocheckin';
$currentSubPage = 'autocheckin-confirmation';

global $log;

include_once APP . 'Services/Connections/ApiGatewayConnection.php';
include LIB . '/autocheckinDocumentVariables.php';
include_once LIB . 'obtenerdatosHotel.php';
include MODEL . 'dynamic-contentModel.php';

$brandID = array_get($_SESSION, 'loggedBrandID');

if (!$brandID) {
    $ok = array (false, '4016');
    return;
}

$types = [
    "confirmation" => 2
];

$typeName = array_get($_POST, "type");
$typeID = array_get($types, $typeName);
$endPoint = AUTOCHECKIN_ENDPOINT . "brands/{$brandID}/texts";

$gateway = new ApiGatewayConnection();
$autocheckinPersonalizedText = [];
$personalizedTextTranslations = [];
$personalizedTextActive = false;
$langs = getDynamicLanguages();

$availableVariablesList = getVariables();

if (array_get($_POST, 'save') && $typeID) {
    $editableText = "confirmation_customized_text";

    $active = boolval(array_get($_POST, $editableText));
    $request = [
        "active" => $active,
        "translations" => []
    ];
    
    foreach ($langs as $lang) {
        $langName = array_get($lang, "name");
        $text = array_get($_POST, $editableText. "_{$langName}");
        $request["translations"][] = [
            "lang" => $langName,
            "text" => $text ?? ""
        ];
    }
    $translationsWithText = array_filter($request["translations"], function($translation) {
        return $translation["text"];
    });

    if ($active && sizeof($translationsWithText) != sizeof($langs)) {
        $ok = array(false, '4082');
    } else {
        try {
            $responseApi = $gateway->sendRequest($request, $endPoint . "/{$typeID}", 'POST');
            $ok = array(true, '2007');
        } catch (\Exception $exception) {
            $log->error("Error inserting personalized legal text", [$exception]);
            $ok = array(false, '4065');
        }
    }
}

try {
    $personalizedText = safeJsonParser($gateway->sendRequest([], $endPoint . "?type_id=2", 'GET'), true);
} catch (\Exception $exception) {
    $log->error("Error getting personalized text", [$exception]);
    if ($exception->getCode() !=  404) {
        throw $exception;
    }
}

try {
    $autocheckinProductConfig = getProductConfig($brandID, 'autocheckin');
    $autocheckinConfirmationTextActive = $autocheckinProductConfig['data']['custom_confirmation_text'];
} catch (\Exception $exception) {
    $log->error("Error getting autocheckin product config", [$exception]);
    if ($exception->getCode() !=  404) {
        throw $exception;
    }
}