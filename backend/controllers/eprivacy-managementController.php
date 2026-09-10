<?php //Miramos si esta definida la variable de control de index.php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}
global $log;
//Contenido solo visible si logueado
include LIB . 'logueado.php';
include MODEL . 'dynamic-contentModel.php';
hotelStaffLanding(); // Si no esta logueado lo manda a la landing

$error = false;
$hotel_id = array_get($_SESSION, 'h_logueado');
$currentSubPage = 'eprivacy-management';
$permisosHotel = getEprivacyPermisions($_SESSION['loggedBrandID']);

$chain_id = array_get($_SESSION, 'c_logueado');

$langs = getDynamicLanguages();
//pages list with his modules
$editablesTexts = [
    'first_eprivacy_page' => ['client' => 'eprivacy_text'],
    'second_eprivacy_page' => ['client' => 'second_eprivacy_text', 'not_client' => 'second_eprivacy_not_client_text'],
    'legal_text' => ['client' => 'legal_text']
];

if (array_get($_POST, 'save')) {

    include_once APP . 'Services/Connections/ApiGatewayConnection.php';

    $company_name = addslashes(array_get($_POST, 'company_name', ''));
    $company_address = addslashes(array_get($_POST, 'company_address', ''));
    $company_nif = addslashes(array_get($_POST, 'company_nif', ''));
    $company_email = addslashes(array_get($_POST, 'company_email', ''));

    $restrictivePortal = array_get($_POST, 'restricted_portal') == 'classic' ? 0 : 1;
    //set custom vars from post object
    $customVars = [
        'brandLegalName' => $company_name,
        'brandLegalAddress' =>  $company_address,
        'brandNIF' => $company_nif,
        'brandLegalEmail' => $company_email
    ];
    $companyData = [
        'companyName' => $company_name,
        'companyAddress' => $company_address,
        'companyNif' => $company_nif,
        'companyEmail' => $company_email,
        'restrictedPortal' =>  $restrictivePortal
    ];
    $privacyInfo = [
        'customVars' => $customVars,
        'companyData' => $companyData,
        'customContent' => [],
        'customTexts' => [],
    ];

    $not_hotel = array_get($_POST, 'restricted_portal') == 'restrictive_not_hotel' ? 1 : 0;
    $restrictive = $not_hotel ? 'not_hotel_' : ($restrictivePortal ? 'restrictive_' : '');

    if (array_has($_POST, 'company_name') || !empty(array_get($_POST, 'choose_ep_text', 'default'))) {

        $custom_texts = [];
        // populate custom_texts with different languages
        foreach ($langs as $language) {
            $langName = $language['name'];
            foreach ($editablesTexts as $editableTexts) {
                foreach ($editableTexts as $editableText) {
                    $key = $editableText . '_' . $langName;
                    if (trim(strip_tags(array_get($_POST, $key)))) {
                        $custom_texts["$editableText"]["$langName"] = addslashes(array_get($_POST, $key));
                    }
                }
            }
        }
        $configuration = array_get($_POST, 'choose_ep_text', 'default');

        foreach ($editablesTexts as $pageName => $editableTexts) {
            foreach ($editableTexts as $editableText) {
                $active = 0;
                $custom_text = array_get($custom_texts, $editableText);

                array_push($privacyInfo['customContent'], [
                    'configuration' => array_get($_POST, 'choose_ep_text', 'default'),
                    'restrictedPortal' => array_get($_POST, 'restricted_portal', 'classic'),
                    'pageName' => $pageName,
                    'moduleName' => $restrictive . $editableText
                ]);
                //if the active option is selected and the conditions are met (all translations exist for the page)
                if (array_get($_POST, $editableText) && $custom_text != null && sizeof($custom_text) == sizeof($langs)) {
                    $active = 1;
                }

                array_push($privacyInfo['customTexts'], [
                    'content' => $custom_text ?? [],
                    'active' => $active,
                    'configuration' => array_get($_POST, 'choose_ep_text', 'default'),
                    'pageName' => $pageName,
                    'moduleName' => $restrictive . $editableText
                ]);
            }
            deleteCacheByTag("hlDynamicModule_$pageName" . "_hotel_id=$hotel_id");
        }
        // Send data to api
        $response = setBrandEprivacyInfo($hotel_id, $chain_id, $privacyInfo);
        // Check if api returns a 204 or 400
        $error = $response['error'];

        $cacheName = 'brand_eprivacy_' . $hotel_id;
        deleteCacheByKey($cacheName);
    }
    if ($error) {
        $ok = array(false, '4082');
    } else {
        $ok = array(true, '2007');
    }
}

//get eprivacy fields
$brand_eprivacy_info = getBrandEprivacyInfo($hotel_id);

$restrictivePortal = array_get($brand_eprivacy_info, 'restricted_portal');
$not_hotel = array_get($brand_eprivacy_info, 'not_hotel');
$restrictive = $not_hotel ? 'not_hotel_' : ($restrictivePortal ? 'restrictive_' : '');

//get first_eprivacy_page info
$first_eprivacy_page_module_content = getParsedHotelCustomContentId($hotel_id, $chain_id, 'first_eprivacy_page', $restrictive . 'eprivacy_text', 0);
$second_eprivacy_page_module_content = getParsedHotelCustomContentId($hotel_id, $chain_id, 'second_eprivacy_page', $restrictive . 'second_eprivacy_text', 0);
$legal_text_page_module_content = getParsedHotelCustomContentId($hotel_id, $chain_id, 'legal_text', $restrictive . 'legal_text', 0);

$second_eprivacy_page_restrictive_module_content = getParsedHotelCustomContentId($hotel_id, $chain_id, 'second_eprivacy_page', 'second_eprivacy_not_client_text', 0);
$log->debug("second_eprivacy_page_restrictive_module_content", $second_eprivacy_page_restrictive_module_content);
