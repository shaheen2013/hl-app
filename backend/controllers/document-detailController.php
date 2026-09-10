<?php //Miramos si esta definida la variable de control de index.php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}

include LIB.'/autocheckinDocuments.php';

// For front purposes
$currentPage = 'autocheckin';
$currentSubPage = 'document-detail';

if (!array_has($_POST, 'relogin_hotel_id')) {
    $action = '';
    $languages = getLanguages();
    $availableVariablesList = getVariables();
    
    $documentAndCheckboxData = loadData();
    $documentData = $documentAndCheckboxData['document'];
    $checkboxConfigurations = $documentAndCheckboxData['checkbox_configurations'];
    $documentTranslationsTitles = array_column(array_get($documentData, 'document_translations', []), 'title', 'language_id');
    $documentTranslationsContents = array_column(array_get($documentData, 'document_translations', []), 'content', 'language_id');

    
    if (isset($_POST['delete_document'])) {
        $feedback = deleteDocumentAndCheckboxConfiguration();
        $feedback ? header("Location: /documents-management", true, 301) : $ok = [false, '4065'];
    } elseif ($_POST && $action == 'create') {
        $feedback = createDocumentAndCheckboxConfiguration();
        $feedback ? header("Location: /documents-management", true, 301) : $ok = [false, '4065'];
    } elseif ($_POST && $action == 'update') {
        $feedback = updateDocumentAndCheckboxes();
        $feedback ? $ok = [true, '2007'] : $ok = [false, '4065'];
    
        $documentAndCheckboxData = loadData();
        $documentData = $documentAndCheckboxData['document'];
        $checkboxConfigurations = $documentAndCheckboxData['checkbox_configurations'];
        $documentTranslationsTitles = array_column($documentData['document_translations'], 'title', 'language_id');
        $documentTranslationsContents = array_column($documentData['document_translations'], 'content', 'language_id');
    }
}
