<?php

require_once(RUTA_DIR . LIB . 'apiGateway.php');

$brandId = (int) $_SESSION['hotel']['brand_id'];

function safeJsonParser($object, $assoc = false)
{
    if ($object) {
        return \GuzzleHttp\json_decode($object, $assoc);
    }

    return [];
}

function getBrandDocuments($brandId)
{
    $gateway = createApiGatewayConnection();
    $apiUrl = AUTOCHECKIN_ENDPOINT . "brands/{$brandId}/documents";

    try {
        $brandDocumentList = safeJsonParser($gateway->sendRequest(null, $apiUrl, 'GET'), true);

        return $brandDocumentList['data'];
    } catch (Exception $exception) {
        $error = [
            "error" => true,
            "message" => json_decode($exception->getResponse()->getBody())->message
        ];

        return $error;
    }
}

function getDocument($documentId)
{
    global $brandId;
    $gateway = createApiGatewayConnection();
    $apiUrl = AUTOCHECKIN_ENDPOINT . "documents/{$documentId}";

    try {
        $document = safeJsonParser($gateway->sendRequest(null, $apiUrl, 'GET'), true);
       
        return $document['data'];
    } catch (Exception $exception) {
        $error = [
            "error" => true,
            "message" => json_decode($exception->getResponse()->getBody())->message
        ];

        return $error;
    }
}

function getCheckboxConfiguration($brandId, $documentId){
    $gateway = createApiGatewayConnection();
    $apiUrl = AUTOCHECKIN_ENDPOINT . "checkbox-configurations/{$brandId}";
    try {
        $checkboxConfigurations = safeJsonParser($gateway->sendRequest(null, $apiUrl, 'GET'), true);
        $filteredConfigurations = array_filter($checkboxConfigurations['data'], function ($config) use ($documentId) {
            return is_null($config['document_id']) || $config['document_id'] == $documentId;
        });
        
        return $filteredConfigurations;
    } catch (Exception $exception) {
        $error = [
            "error" => true,
            "message" => json_decode($exception->getResponse()->getBody())->message
        ];

        return $error;
    }
}

function getVariables()
{
    $gateway = createApiGatewayConnection();
    $apiUrl = AUTOCHECKIN_ENDPOINT . "document-variables";

    try {
        $variables = safeJsonParser($gateway->sendRequest(null, $apiUrl, 'GET'), true);

        return $variables['data'];
    } catch (Exception $exception) {
        $error = [
            "error" => true,
            "message" => json_decode($exception->getResponse()->getBody())->message
        ];

        return $error;
    }
}

function getLanguages()
{
    $gateway = createApiGatewayConnection();
    $apiUrl = HOTELINKING_ENDPOINT . "languages";

    try {
        $languages = safeJsonParser($gateway->sendRequest(null, $apiUrl, 'GET'), true);

        return $languages;
    } catch (Exception $exception) {
        $error = [
            "error" => true,
            "message" => json_decode($exception->getResponse()->getBody())->message
        ];

        return $error;
    }
}

function loadData()
{
    global $action;
    global $brandId;
    $url = $_SERVER['REQUEST_URI'];
    $documentId = intval(substr($url, strrpos($url, '/', -1) + 1));

    $documentAndCheckboxData = [];

    if ($documentId != 0) {
        $document = getDocument($documentId);
        $checkboxConfigurations = getCheckboxConfiguration($brandId, $documentId);

        $action = 'update';

        $documentAndCheckboxData = [
            'document' => $document,
            'checkbox_configurations' => $checkboxConfigurations,
        ];

    } else {
        $checkboxConfigurations = getCheckboxConfiguration($brandId, null);

        $action = 'create';
          $documentAndCheckboxData = [
            'document' => null, // No document data as we're creating a new one
            'checkbox_configurations' => $checkboxConfigurations,
        ];
    }

    return $documentAndCheckboxData;
}

function createPayload($postObj)
{
    $payload = [
        'name' => $postObj['document_name'],
        'active' => intval($postObj['active']),
        'brand_id' => (int) $_SESSION['hotel']['brand_id'],
        'document_variables' => extractVariablesIds($postObj['document_title_en'], $postObj['document_content_en']),
        'document_translations' => generateDocumentTranslationsList($postObj)
    ];

    return $payload;
}

function getLanguageIdByCode($languageCode)
{
    global $languages;
    foreach ($languages as $language) {
        if ($language['name'] === $languageCode) {
            return $language['id'];
        }
    }
    return null;
}

function createCheckboxPayload($postObj)
{

    $checkboxPayload = [];

    foreach($postObj as $key => $value){
        // Only process keys that start with 'checkbox_' and have non-empty values
        if(strpos($key, 'checkbox_') === 0 && !empty($value)){
            $strippedKey = substr($key, strlen('checkbox_'));
            $languageCode = substr($strippedKey, -2);
            // Remove the language code suffix and _ to get the checkbox type
            $checkboxType = substr($strippedKey, 0, -3);
            $languageId = getLanguageIdByCode($languageCode);

            if ($languageId) {
                $checkboxPayload[$checkboxType][] = [
                    'language_id' => $languageId,
                    'text' => $value
                ];
            }
        }
    }

    return $checkboxPayload;
}

function extractVariablesIds($defaultDocumentTitle, $defaultDocumentContent)
{
    global $availableVariablesList;

    $documentVariablesIds = [];

    $placeholderPattern = "/\{\{[A-Za-z0-9]*\}\}/";
    $text = $defaultDocumentTitle . ' ' . $defaultDocumentContent;

    preg_match_all($placeholderPattern, $text, $matches);
    $foundVariables = array_values(array_unique($matches[0]));

    foreach ($foundVariables as $variable) {
        $variableId = array_search($variable, array_column($availableVariablesList, 'placeholder', 'id'));

        if (in_array($variableId, $documentVariablesIds)) {
            continue;
        }

        array_push($documentVariablesIds, $variableId);
    }

    return $documentVariablesIds;
}

function generateDocumentTranslationsList($postObj)
{
    global $languages;

    $documentTranslations = [];

    foreach ($languages as $language) {
        $titleIndex = 'document_title_' . $language['name'];
        $contentIndex = 'document_content_' . $language['name'];

        if (empty($postObj[$titleIndex]) || empty($postObj[$contentIndex])) {
            continue;
        }

        $documentTranslationData = [
            'language_id' => $language['id'],
            'title' => $postObj[$titleIndex],
            'content' => $postObj[$contentIndex],
        ];

        array_push($documentTranslations, $documentTranslationData);
    }

    return $documentTranslations;
}

function createDocumentAndCheckboxConfiguration()
{
    global $log, $documentData, $languages, $documentTranslationsTitles, $documentTranslationsContents, $brandId;

    $payload = createPayload($_POST);

    $gateway = createApiGatewayConnection();
    $apiUrl = AUTOCHECKIN_ENDPOINT . "documents";

    try {
        $response = $gateway->sendRequest($payload, $apiUrl, 'POST');
        $createdDocument = safeJsonParser($response, true);
        $documentId = $createdDocument['data']['id'];

        $checkboxPayload = createCheckboxPayload($_POST);
    
        if($documentId && !empty($checkboxPayload)){
            $apiUrl = AUTOCHECKIN_ENDPOINT . "checkbox-configurations/{$brandId}/{$documentId}";
            $gateway->sendRequest($checkboxPayload, $apiUrl, 'POST');
        }
        
    } catch (Exception $exception) {
        $error = [
            "error" => true,
            "message" => json_decode($exception->getResponse()->getBody())->message
        ];

        $log->error('Create document error', $error);

        $documentData['name'] = $_POST['document_name'];
        $documentData['active'] = $_POST['active'];

        foreach ($languages as $language) {
            $documentTranslationsTitles[$language['id']] = $_POST['document_title_' . $language['name']];
            $documentTranslationsContents[$language['id']] = $_POST['document_content_' . $language['name']];
        }

        return false;
    }

    return true;
}

function updateDocument($payload)
{
    global $log;

    $gateway = createApiGatewayConnection();
    $apiUrl = AUTOCHECKIN_ENDPOINT . "documents";

    $url = $_SERVER['REQUEST_URI'];
    $documentId = intval(substr($url, strrpos($url, '/', -1) + 1));

    try {
        $gateway->sendRequest($payload, $apiUrl . "/$documentId", 'PUT');
    } catch (Exception $exception) {
        $error = [
            "error" => true,
            "message" => json_decode($exception->getResponse()->getBody())->message
        ];

        $log->error('Update document error', $error);

        return false;
    }

    return true;
}

function updateCheckboxConfigurations($payload)
{
    global $brandId; 
    global $log;
    $gateway = createApiGatewayConnection();
    $apiUrl = AUTOCHECKIN_ENDPOINT . "checkbox-configurations";

    $url = $_SERVER['REQUEST_URI'];
    $documentId = intval(substr($url, strrpos($url, '/', -1) + 1));

    try {
        $gateway->sendRequest($payload, $apiUrl . "/$brandId/$documentId", 'PUT');
    } catch (Exception $exception) {
        $error = [
            "error" => true,
            "message" => json_decode($exception->getResponse()->getBody())->message
        ];

        $log->error('Update checkbox configuration error', $error);

        return false;
    }

    return true;
}

function updateDocumentAndCheckboxes()
{
    $documentPayload = createPayload($_POST);
    $updateDocumentSuccess = updateDocument($documentPayload);

    if($updateDocumentSuccess) {
        $checkboxPayload = createCheckboxPayload($_POST);
        $updateCheckboxSuccess = updateCheckboxConfigurations($checkboxPayload);

        if ($updateCheckboxSuccess) {
            return true;
        } else {
            return false;
        }
    } else {
        // Document update failed
      return false;
    }
}

function deleteDocumentAndCheckboxConfiguration()
{
    global $log;

    $gateway = createApiGatewayConnection();
    $apiUrl = AUTOCHECKIN_ENDPOINT . "documents";

    $url = $_SERVER['REQUEST_URI'];
    $documentId = intval(substr($url, strrpos($url, '/', -1) + 1));

    try {
        $gateway->sendRequest(null, $apiUrl . "/$documentId/destroy", 'DELETE');
    } catch (Exception $exception) {
        $error = [
            "error" => true,
            "message" => json_decode($exception->getResponse()->getBody())->message
        ];

        $log->error('Delete document error', $error);

        return false;
    }

    return true;
}
