<?php //Miramos si esta definida la variable de control de index.php
use App\Models\Hotel;

if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}

include LIB . '/enviarEmail.php';
include LIB . '/generarToken.php';

if (isset($_SESSION['privateError'])) {
    $ok = array(false, $_SESSION['privateError']);
    unset($_SESSION['privateError']);
}

// Search variable
$searchInput = array_get($_GET, 'search', '');

function newUrl($searchInput = null, $error = null)
{
    global $urlTree;
    $newUrl = '/private/' . $urlTree['private-invitar-hotel'];
    if ($searchInput) {
        $newUrl = $newUrl . "/?search=" . $_GET['search'];
    }

    if ($error) {
        $_SESSION['privateError'] = $error;
    }

    header('Location: ' . $newUrl);
}
//Update wifi days
if (!empty($_POST['wifiDaysForm'])) {
    include_once APP . 'Services/Connections/ApiGatewayConnection.php';

    $id_hotel = $_POST['id_hotel'];
    $bypass_status = array_get($_POST, 'bypass_active') ? 1 : 0;
    $phone_status = array_get($_POST, 'phone_active') ? true : false;
    $url_redirect = array_get($_POST, 'url_redirect') ? array_get($_POST, 'url_redirect') : '';
    $commercialProfile = array_get($_POST, 'commercial_profile') ? true : false;
    $showRadiusTicketToHosted = array_get($_POST, 'show_radius_ticket_to_hosted') ? true : false;
    $onlyHostedGuestsStatus = array_get($_POST, 'only_hosted_guests') ? true : false;
    $hasAccessToHotspot = array_get($_POST, 'has_access_to_hotspot') ? true : false;
    $addOtherOptionOnGender = array_get($_POST, 'add_other_option_on_gender') ? true : false;
    $removeGenderField = array_get($_POST, 'remove_gender_field') ? true : false;


    $hide_unsubscribed_clients = array_get($_POST, 'hide_unsubscribed_clients') == "on" ? true : false;


    $updated = setBypassActive($id_hotel, $bypass_status);

    $configurationProductId = array_get($_POST, 'product_id');
    $hasJsonConfig = [24];
    $productHasJsonConfig = in_array($configurationProductId, $hasJsonConfig);

    $endpointSufix = $productHasJsonConfig ? 'config' : 'configuration';

    $brandId = array_get($_POST, 'brand_id');
    $endpoint = HOTELINKING_ENDPOINT . "brands/$brandId/products/$configurationProductId/$endpointSufix";
    $gateway = new ApiGatewayConnection();

    if ($productHasJsonConfig) {
        $gateway->sendRequest([
            "phone_active" => $phone_status,
            "hide_unsubscribed_clients" => $hide_unsubscribed_clients,
            "url_redirect" => $url_redirect,
            "commercial_profile" => $commercialProfile,
            "show_radius_ticket_to_hosted" => $showRadiusTicketToHosted,
            "only_hosted_guests" => $onlyHostedGuestsStatus,
            "has_access_to_hotspot" => $hasAccessToHotspot,
            "add_other_option_on_gender" => $addOtherOptionOnGender,
            "remove_gender_field" => $removeGenderField,
        ], $endpoint, 'PUT');
    } else {
        $gateway->sendRequest([
            "hide_unsubscribed_clients" => $hide_unsubscribed_clients,
        ], $endpoint, 'PUT');
    }

    $ok = (!$updated ? array(false, '4065') : array(true, '2007'));
}
//Update pushtech data
if (!empty($_POST['pushtech_account_id']) && !empty($_POST['pushtech_account_secret'])) {

    //Store an array with credentials
    $credentials = array(
        'key' => $_POST['pushtech_account_id'],
        'secret' => $_POST['pushtech_account_secret'],
    );

    //Include pushtech cURL
    include_once LIB . 'pushtech_api.php';

    //Check if this config works with pushtech API
    $pushtech_valid = checkPushtechCredentials($credentials);

    $id_hotel = $_POST['id_hotel'];
    $brand_id = array_get($_POST, 'brand_id');

    //Create booking product in pushtech
    if ($pushtech_valid) {
        $product_to_create = array(
            'UUID' => 'booking',
            'name' => 'booking',
            'price' => 300,
            'currency' => 'EUR',
        );
        $product = createPushtechProduct($credentials, $product_to_create);

        //delete key used in stay-share
        deleteCacheByKey("getBrandProducts" . $brand_id);
    }

    //Give feedback to frontend
    $ok = (!$pushtech_valid ? array(false, '4066') : array(true, '2007'));

    //Update pushtech Data
    $data = array(
        'id_api' => 1,
        'id_hotel' => $_POST['id_hotel'],
        'key' => trim($_POST['pushtech_account_id']),
        'secret' => trim($_POST['pushtech_account_secret']),
    );

    $environment = $_POST['pushtech_account_environment'];

    //Update credentials at hotelinking_integrations
    if (isIntegrationEnabled()) {
        $brand_id = array_get($_POST, 'brand_id');
        setPushtechCredentials($brand_id, $data['key'], $data['secret'], $environment);
    }
}
//Invitacion de hotel nuevo
if (!empty($_POST['enviarInvitacion'])) {
    $hotelName = mysqli_real_escape_string(conectar(), $_POST['hotelName']);

    // From now on, the account will simply be generated from the private and from the user management
    // it will be given permissions from different e-mails to access it.
    include_once LIB . 'crearHotel.php';
    $id_hotel= insertarHotel($hotelName, '', '', '','', '', '', '','', '', '', '', 500);
}

if (!empty($_POST['tokenMail'])) {
    $email = mysqli_real_escape_string(conectar(), $_POST['tokenMail']);
    $token = recuperarTokenUrl($_POST['tokenMail']);
}

//cambiar plantilla del iframe
if (!empty($_GET['template'])) {
    $changeTemplate = changeHotelTemplate($_GET['id'], $_GET['template']);
    if ($changeTemplate) {
        newUrl($searchInput);
    }
}
//Change affilired info
if (!empty($_POST['afName'])) {
    updateAffilired($_POST['afName'], $_POST['afId'], $_POST['id_hotel'], $_POST['guid']);
    newUrl($searchInput);
}

//Get all data
$hotels = collect(getHotels($searchInput))->groupBy('brand_id')->toArray();
$integrationEnabled = isIntegrationEnabled();
// Variable to control the inconsistency error with integrations
$show_error = false;
if ($integrationEnabled) {

    if (array_get($_GET, 'product') == 'datamatch') {
        // Clear the datamatch cache
        $brand_id =  array_get($_GET, 'brand_id');
        deleteCacheByKey("Datamatch_activated_$brand_id");
    }

    //Update datamatch data and product
    if (array_get($_POST, 'datamatch_frequency')) {
        $updateDatamatchWasDone = updateDatamatchConfig($_POST['datamatch_brand_id'], $_POST['datamatch_frequency']);

        // Set to on the product Datamatch
        actualizarProductoHotel($_POST['datamatch_hotel_id'], $_POST['datamatch_brand_id'], 'datamatch', 1);

        newUrl($searchInput);
    }

    //Create/Update integration config
    if (
        array_get($_POST, 'integration_config_id') &&
        array_get($_POST, 'integration_connection_hotel_code') &&
        array_get($_POST, 'integration_brand_id') &&
        array_has($_POST, 'integration_activated') &&
        array_get($_POST, 'action') != 'delete'
    ) {

        if (array_get($_POST, 'integration_brand_integration_id')) {
            //Update integration config
            $update_integration_brand = [
                'integration_config_id' => array_get($_POST, 'integration_config_id'),
                'key' => array_get($_POST, 'integration_key'),
                'secret' => array_get($_POST, 'integration_secret'),
                'parser_hotel_code' => array_get($_POST, 'integration_parser_hotel_code'),
                'connection_hotel_code' => array_get($_POST, 'integration_connection_hotel_code'),
                'activated' => array_get($_POST, 'integration_activated'),
                'export' => array_get($_POST, 'integration_export'),
                'import' => array_get($_POST, 'integration_import'),
            ];

            $updateDatamatchWasDone = updateIntegrationOptions(array_get($_POST, 'integration_brand_id'), array_get($_POST, 'integration_brand_integration_id'), $update_integration_brand);
        } else {

            //Create integration config
            $create_integration_brand = [
                'integration_config_id' => array_get($_POST, 'integration_config_id'),
                'key' => array_get($_POST, 'integration_key'),
                'secret' => array_get($_POST, 'integration_secret'),
                'parser_hotel_code' => array_get($_POST, 'integration_parser_hotel_code'),
                'connection_hotel_code' => array_get($_POST, 'integration_connection_hotel_code'),
                'activated' => array_get($_POST, 'integration_activated'),
                'export' => array_get($_POST, 'integration_export'),
                'import' => array_get($_POST, 'integration_import'),
            ];

            $createDatamatchWasDone = createIntegrationOptions(array_get($_POST, 'integration_brand_id'), $create_integration_brand);
        }

        if (array_get($_POST, 'integration_type') == 'redirect') {
            $brand_id = array_get($_POST, 'portalRedirect_brand_id');
            $product_id = array_get($_POST, 'portalRedirect_product_id');
            $activated = array_get($_POST, 'integration_activated');
            // Mount url
            $portalRedirectProductUrl = HOTELINKING_ENDPOINT . "brands/{$brand_id}/products/{$product_id}/activate/{$activated}";
            // Active/Deactivate portal_redirect
            require_once(__DIR__ . '/../lib/apiGateway.php');
            $gateway = createApiGatewayConnection();
            $gatewayResponse = $gateway->sendRequest([], $portalRedirectProductUrl, 'PUT');
            $log->debug("Changing product portalRedirect", [
                'url' => $portalRedirectProductUrl,
                'response' => $gatewayResponse->getContents()
            ]);

            // Delete cache for products
            deleteCacheByKey("getBrandProducts" . $brand_id);
            deleteCacheByKey('hotel_wifi_offers_' . $hotel_id);
        }

        newUrl($searchInput);
    } else if (array_get($_POST, 'action') == 'delete') {

        $deleteIntegrationBrand = [
            'id' => array_get($_POST, 'integration_id'),
            'integration_type' => array_get($_POST, 'integration_type'),
            'brand_id' => array_get($_POST, 'integration_brand_id'),
        ];

        $deleteIntegrationBrandWasDone = deleteIntegrationOptions(
            array_get($_POST, 'integration_brand_id'),
            array_get($_POST, 'integration_brand_integration_id'),
            $deleteIntegrationBrand,
            array_get($_POST, 'integration_type')
        );
    }

    // ================= DATAMATCH ===================
    // Get the datamatch config
    $datamatchConfig = getDatamatchConfig();
    if (empty($datamatchConfig)) {
        $show_error = true;
    }

    // List of frequency in days
    $datamatch_frequencies = array_get($datamatchConfig, 'frequencyInDays', []);

    // Array with integrations and configs
    $datamatch_integrations_configs = array_get($datamatchConfig, 'integrations', []);

    // List of integrations
    $pms_integrations = array_get($datamatch_integrations_configs, 'pms.list', []);
    $api_integrations = array_get($datamatch_integrations_configs, 'api.list', []);
    $redirect_integrations = array_get($datamatch_integrations_configs, 'redirect.list', []);
    $survey_integrations = array_get($datamatch_integrations_configs, 'survey.list', []);
    $engine_integrations = array_get($datamatch_integrations_configs, 'engine.list', []);

    // Configs avaliable for each integration
    $integrationConfigs = array_merge(
        array_get($datamatch_integrations_configs, 'pms.configs', []),
        array_get($datamatch_integrations_configs, 'api.configs', []),
        array_get($datamatch_integrations_configs, 'redirect.configs', []),
        array_get($datamatch_integrations_configs, 'survey.configs', []),
        array_get($datamatch_integrations_configs, 'engine.configs', [])
    );

    // List integrations by integration_config_id
    $integrations = [];
    foreach ($integrationConfigs as $dm_integration => $dm_int_config) {
        foreach ($dm_int_config as $dm_key => $dm_config) {
            $integrations[$dm_key] = ucwords($dm_integration);
        }
    }

    // Configs avaliable for each integration
    $integrations_brands = array_get($datamatch_integrations_configs, 'brands', []);

    $integrations_brands_pms = array_get($integrations_brands, 'pms', []);
    $integrations_brands_api = array_get($integrations_brands, 'api', []);
    $integrations_brands_redirect = array_get($integrations_brands, 'redirect', []);
    $integrations_brands_survey = array_get($integrations_brands, 'survey', []);
    $integrations_brands_engine = array_get($integrations_brands, 'engine', []);

    // Get all portalPros
    $portalProList = listPortalProConfigs();

    //Add integration active to hotels that have a integration pms activated and add secret and key for pushtech hotels
    $hotels = array_map(function ($hotel) use (&$integrations_brands, &$portalProList) {
        // Get the integrations of the iterated brand
        $brand_id = $hotel[0]['brand_id'];
        $integration_brand = array_only($integrations_brands, $brand_id);
        $integrationBrand = $integration_brand[$brand_id] ?? [];
        // Set value depending on whether any integration is actvited or not
        $anyIntegrationActivated = 0;

        foreach ($integrationBrand as $integration) {
            if ($integration['activated']) {
                $anyIntegrationActivated = 1;
            }
        }

        $pmsIntegrations = array_filter($integrationBrand, function ($integrations) {
            return $integrations['integration_type_name'] === 'pms';
        });

        // Get the first activated integration
        $pms = array_first($pmsIntegrations, function ($key, $integration) {
            return $integration['activated'];
        }, $pmsIntegrations[0] ?? null);


        $hotel['integration']['pms']['activated'] = $anyIntegrationActivated;
        $hotel['integration']['pms']['integration_brand_id'] = $pms['id'] ?? null;

        // Pushtech integration
        $pushtechData = array_first($integrationBrand, function ($key, $integration) {
            return $integration['integration_name'] === 'pushtech';
        });
        $hotel['integration']['pushtech']['secret'] = $pushtechData['secret'] ?? null;
        $hotel['integration']['pushtech']['key'] = $pushtechData['key'] ?? null;
        $hotel['integration']['pushtech']['integration_brand_id'] = $pushtechData['id'] ?? null;

        $redirectActivated = 0;
        foreach ($integrationBrand as $integration) {
            if ($integration['integration_type_name'] === 'redirect' && $integration['activated'] == 1) {
                $redirectActivated = 1;
            }
        }

        $redirect = array_first($integrationBrand, function ($key, $integration) {
            return $integration['integration_type_name'] === 'redirect';
        });
        $hotel['integration']['redirect']['activated'] = $redirectActivated;
        $hotel['integration']['redirect']['integration_brand_id'] = $redirect['id'] ?? null;

        // Append portalPro to hotelData
        $brandPortalPro = array_first($portalProList, function ($key, $portalPro) use (&$hotel) {
            return array_get($hotel, '0.brand_id') == array_get($portalPro, 'brand_id');
        });

        $hotel['portalPro'] = $brandPortalPro ?? getDefaultPortalProConfig();
        return $hotel;
    }, $hotels);
}

// ==============================================

####NEW PRODUCTS

$productsNames = [
    'customized_satisfaction_surveys' => 'Customized satisfaction surveys',
    'wifi_offers' => 'Wifi offers',
    'birthday_emails' => 'Birthday emails',
    'require_room_num' => 'Require room num',
    'user_enrichment' => 'User enrichment',
    'not_hotel' => 'Not Hotel'
];

//get all products
$products = getProducts();
$products = array_combine(array_column($products, 'producto'), $products);

$portalProProductId = findProductId($products, 'portal_pro');
$datamatchProductId = findProductId($products, 'datamatch');
$pushtechProductId = findProductId($products, 'pushtech');

//function to check if the product_id exists in for this hotel_id
// i.e. it is in the hotel['products'] array
function hasAssignedProduct($hotel, $product_id)
{
    $product = array_first($hotel, function ($key, $value) use ($product_id) {
        return array_get($value, 'product_id') == $product_id;
    });

    return data_get($product, 'active', 0);
}

function findProductId($products, $productName)
{
    return array_get(array_first($products, function ($var, $product) use ($productName) {
        return ($product['producto'] == $productName);
    }), 'id');
}

