<?php //Miramos si esta definida la variable de control de index.php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}

include LIB.'/autocheckinDocuments.php';

// For front purposes
$currentPage = 'autocheckin';
$currentSubPage = 'documents-management';

$documentList = getBrandDocuments($brandId);
