<?php
// Basic libraries
include_once 'librerias.php';

// Disable indexcontrolval to be accesible as a webservice
define("INDEXCONTROLVAL", "1");

// Receive a post with some actions to perform & user_id and $hotel_id or die
if (empty($_POST['action']) || empty($_POST['id_user']) && empty($_POST['id_hotel']) || empty($_POST['email'])) {
    $log->error('Access to emails webservice without all data needed, exiting', [$_POST]);
    exit;
}

// Set variables
$user = array(
    'id' => $_POST['id_user'],
    'email' => $_POST['email'],
    'name' => !empty($_POST['name']) ? $_POST['name'] : 'customer',
    'lang' => !empty($_POST['lang']) ? $_POST['lang'] : 'en',
    'birthday' => !empty($_POST['birthday']) ? $_POST['birthday'] : null,
);
$action = $_POST['action'];
$id_hotel = $_POST['id_hotel'];
$brandID = $_POST['brand_id'];
$brandParentID = array_get($_POST, 'brand_parent_id');
$guid_hotel = !empty($_POST['guid_hotel']) ? $_POST['guid_hotel'] : null;
$brandProducts = !empty($_POST['brandProducts']) ? $_POST['brandProducts'] : null;
$nonCustomerChecks = array_get($_POST, 'nonCustomerOptions');
$room_number = array_get($_POST, 'id_room', null);
$stayTime = array_get($_POST, 'stayTime', 7);
$customerType = array_get($_POST, 'customerType');
$checkoutDate = array_get($_POST, 'checkout_date');
$log->debug('$nonCustomerChecks', [$nonCustomerChecks]);

//Add libraries of users and hotels data
include_once RUTA_DIR . LIB . 'get_hotel_wifi_permissions_and_offers.php';
include_once RUTA_DIR . LIB . 'obtenerdatosHotel.php';
include_once RUTA_DIR . LIB . 'obtenerDatosUsuario.php';

//Get hotel GUID or die
if (empty($guid_hotel)) {
    $guid_hotel = obtenerGUIDHotel($id_hotel);
    if (empty($guid_hotel)) {
        $log->error('Hotel GUID do not exists, exiting');
        exit;
    }
}

//Get user GUID or die
$guid_user = obtenerGUIDUsuarioId($user['id']);
if (empty($guid_user)) {
    $log->warning('User GUID do not exists, exiting', array('userId' => $user['id'], 'userEmail' => $user['email'], 'guid_hotel' => $guid_hotel));
    exit;
} else {
    $user['guid'] = $guid_user;
}

$log->debug('emails-webservice');
// Load emails library
include_once RUTA_DIR . LIB . 'hotelinking_emails.php';
include_once RUTA_DIR . LIB . 'emails-webservice-helpers.php';

//Check if this hotel is from any chain
$id_chain = hotelIdCadena($id_hotel);

// By getting the activated features an hotel have
// we will know if we need to send any email to the current user
$brandProducts = (!empty($brandProducts) ? $brandProducts : obtenerProductosHotel($brandID));

// When user logged in send surveys by satisfaction type
if ($action === 'login' || $action === 'facebook_login' || $action === 'facebook_share') {

    include_once RUTA_DIR . LIB . 'create_review_survey.php';
    
    $satisfactionHotelData = obtenerDatosHotelSatisfactionAndReview($id_hotel);
    $ignoreRating = array_get($satisfactionHotelData, 'ignoreRating') ? 
        array_get($satisfactionHotelData, 'ignoreRating', 0) : 
        0;
    $brandSurvey = array_get($satisfactionHotelData, 'customized_chain_activated') ?
        $brandParentID : 
        $brandID;
        
    // Create satisfaction
    if (getBrandProductActive($brandProducts, 'satisfaction') && !getBrandProductActive($brandProducts, 'review') && array_get($nonCustomerChecks, 'sendSatisfactionToNonCustomers', 1)) {
        include_once RUTA_DIR . LIB . 'create_satisfaction_survey.php';
        $log->debug('Starting satisfaction email process');
        
        $customizedActive = getBrandProductActive($brandProducts, 'customized_satisfaction_surveys');
        $satisfaction = createSatisfactionSurvey($user, $id_hotel, $guid_hotel, $id_chain, $stayTime, $brandID, $brandSurvey, $customizedActive, $checkoutDate, $ignoreRating, $room_number);        
    }

    // Create birthday
    if (empty($_SESSION['birthday_email_sent']) && array_get($nonCustomerChecks, 'sendBirthdayOfferToNonCustomers', 1)) {
        if (getBrandProductActive($brandProducts, 'birthday_emails')) {
            $log->debug('Starting Birthday email process');
            include_once RUTA_DIR . LIB . 'send_birthday_offer.php';
            include_once RUTA_DIR . LIB . 'encrypt.php';
            $birthdayOfferId = getBirthdayOffer($id_hotel);
            $log->debug('$birthdayOfferId', [$birthdayOfferId]);
            $offer = getBirthdayOfferDetails($birthdayOfferId, $user['lang']);
            $token = dec_enc('encrypt', $user['id'] . '-' . $id_hotel);
            $log->debug('offers', [$offer, $user['lang']]);
            if ($offer) {
                $url = SECURE_BASE_PATH . $urlTree['create-offer-from-token'] . '/?type=birthday&tk=' . $token;
                $birthdaySent = sendBirthdayToEmailPlatform($user, $id_hotel, $offer, $url);
                $_SESSION['birthday_email_sent'] = true;
            }
        }
    }

    // Send wifi offer
    if (empty($_SESSION['wifi_offer_sent'])) {
        if ($action === 'login' || $action === 'facebook_login' || ($action === 'facebook_share')) {
            if (getBrandProductActive($brandProducts, 'wifi_offers')) {
                $log->debug('Starting wifi offer process');
                include_once RUTA_DIR . LIB . 'send_wifi_offer.php';
                sendWifiOffer($user, $id_hotel, $action, $customerType);
                $_SESSION['wifi_offer_sent'] = true;
            }
        }
    }
}

/*Send satisfaction thanks mail*/
if ($action == 'satisfaction_thanks') {
    if (getBrandProductActive($brandProducts, 'satisfaction') && isset($_POST['satisfaction_thanks']) && $_POST['satisfaction_thanks'] == 1) {
        $log->debug('Starting satisfaction thanks email process');
        include_once RUTA_DIR . LIB . 'create_satisfaction_thank.php';
        $thanksSent = createSatisfactionThankOnEmailPlatform($user, $id_hotel, $_POST['satisfied_customer'], $_POST['hotel_url']);
    }
}
