<?php //Miramos si esta definida la variable de control de index.php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}


// For front purposes
$currentPage = 'autocheckin';
$currentSubPage = 'autocheckin-customized-texts';

global $log;

include_once APP . 'Services/Connections/ApiGatewayConnection.php';
include_once LIB . 'obtenerdatosHotel.php';
include MODEL . 'dynamic-contentModel.php';

$brandID = (int) ($_SESSION['hotel']['brand_id'] ?? null);

if (!$brandID){
    $ok = array (false, '4016');
    return;
}

$types = [
    "scan" => 1,
    "confirmation" => 2,
    "gdpr" => 3,
    "phone" => 4,
    "comments" => 5,
];

$typeName = array_get($_POST, "type");
$typeID = array_get($types, $typeName);
$endPoint = AUTOCHECKIN_ENDPOINT . "brands/{$brandID}/texts";

$gateway = new ApiGatewayConnection();
$autocheckinPersonalizedText = [];
$personalizedTextTranslations = [];
$personalizedTextActive =  false;
$langs = getDynamicLanguages();

if (array_get($_POST, 'save') && $typeID) {
    // Retrieve the corresponding text configuration according to the type of text to be saved.
    $editableText = "{$typeName}_customized_text";

    $active = boolval(array_get($_POST, $editableText));
    $request = [
        "active" => $active,
        "translations" => []
    ];
    
    foreach ($langs as $lang) {
        $langName = array_get($lang, "name");
        $text = array_get($_POST,$editableText. "_{$langName}");
        $request["translations"][] = [
            "lang" => $langName,
            "text" => $text ?? ""
        ];
    }
    $translationsWithText = array_filter($request["translations"], function($translation) {
        return $translation["text"];
    });

    if($active && sizeof($translationsWithText) != sizeof($langs)) {
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
    $autocheckinPersonalizedTexts = safeJsonParser($gateway->sendRequest([], $endPoint, 'GET'), true);
} catch (\Exception $exception) {
    $log->error("Error geting personalized text", [$exception]);
    if($exception->getCode() !=  404) {
        throw $exception;
    }
}