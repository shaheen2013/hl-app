<?php //Miramos si esta definida la variable de control de index.php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}
global $log;
//Contenido solo visible si logueado
include LIB . 'logueado.php';
include MODEL . 'dynamic-contentModel.php';
hotelStaffLanding();// Si no esta logueado lo manda a la landing
$currentSubPage = 'chain-privacy';
$hotel_id = $_SESSION['hotel']['id'];
$chain_id = array_get($_SESSION, 'c_logueado');

$chainAllowed = getEprivacyPermisions(array_get($_SESSION, 'loggedParentBrandID'));
global $log;
$log->debug("Chain Allowed", [$chainAllowed]);

$langs = getDynamicLanguages();

//pages list with his modules
$editablesTexts = [
    'first_eprivacy_page' => ['client' => 'eprivacy_text'],
    'second_eprivacy_page' => ['client' => 'second_eprivacy_text', 'not_client' => 'second_eprivacy_not_client_text'],
    'legal_text' => ['client' => 'legal_text']];

if (array_get($_POST, 'save')) {
    setBrandCustomVars(null, $chain_id, 'brandLegalName', array_get($_POST, 'company_name'));
    setBrandCustomVars(null, $chain_id, 'brandLegalAddress', array_get($_POST, 'company_address'));
    setBrandCustomVars(null, $chain_id, 'brandNIF', array_get($_POST, 'company_nif'));
    setBrandCustomVars(null, $chain_id, 'brandLegalEmail', array_get($_POST, 'company_email'));
    setBrandEprivacyInfo($chain_id, array_get($_POST, 'company_name'), array_get($_POST, 'company_address'), array_get($_POST, 'company_nif'), array_get($_POST, 'company_email'), 0);
    $custom_texts = [];

    if (array_has($_POST, 'company_name')) {
        foreach ($langs as $language) {
            $langName = $language['name'];
            foreach ($editablesTexts as $editableTexts) {
                foreach ($editableTexts as $editableText) {
                    $key = $editableText . '_' . $langName;
                    if (trim(strip_tags(array_get($_POST, $key)))) {
                        $custom_texts["$editableText"]["$langName"] = array_get($_POST, $key);
                    }
                }
            }
        }
        $configuration = array_get($_POST, 'choose_ep_text', 'default');


        foreach ($editablesTexts as $pageName => $editableTexts) {
            foreach ($editableTexts as $editableText) {
                $active = 0;
                $custom_text = array_get($custom_texts, $editableText);
                if (array_get($_POST, $editableText) && $custom_text != null && sizeof($custom_text) == sizeof($langs)) {
                    $active = 1;
                }
                setBrandCustomContent(null, $chain_id, $pageName, 'classic', 1, $configuration);
                if (!$editableText == 'second_eprivacy_not_client_text') {
                    setBrandCustomModuleContent(null, $chain_id, $custom_text, $active, $pageName, 'restrictive_' . $editableText);
                }
                setBrandCustomModuleContent(null, $chain_id, $custom_text, $active, $pageName, $editableText);
            }
        }
    }
}
//get eprivacy fields
$brand_eprivacy_info = getBrandEprivacyInfo($chain_id);

$restrictive = array_get($brand_eprivacy_info, 'restricted_portal') ? 'restrictive_' : '';


//get first_eprivacy_page info
$first_eprivacy_page_module_content = getParsedHotelCustomContentId($hotel_id, $chain_id, 'first_eprivacy_page', $restrictive . 'eprivacy_text', 0);
$second_eprivacy_page_module_content = getParsedHotelCustomContentId($hotel_id, $chain_id, 'second_eprivacy_page', $restrictive . 'second_eprivacy_text', 0);
$legal_text_page_module_content = getParsedHotelCustomContentId($hotel_id, $chain_id, 'legal_text', $restrictive . 'legal_text', 0);

$second_eprivacy_page_restrictive_module_content = getParsedHotelCustomContentId($hotel_id, $chain_id, 'second_eprivacy_page', 'second_eprivacy_not_client_text', 0);
