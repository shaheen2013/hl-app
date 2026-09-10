<?php //Miramos si esta definida la variable de control de index.php

if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}

include_once LIB . 'obtenerdatosHotel.php';
include_once LIB . 'obtenerDatosUsuario.php';
include_once LIB . 'enviarEmail.php';
include_once LIB . 'emailValidate.php';
include_once LIB . 'crearNuevoUsuario.php';
include_once LIB . 'get_hotel_wifi_permissions_and_offers.php';
include_once LIB . 'hotelinking_integrations.php';
include_once LIB . 'portal_pro.php';
include_once LIB . 'protocolManagement.php';
include_once LIB . 'utils.php';
include_once MODEL . 'dynamic-contentModel.php';
include_once MODEL . 'eprivacy-managementModel.php';
include_once APP . 'Services/Connections/ApiGatewayConnection.php';
$treatment = $_SESSION['hotel']['treatment'] ?? 'formal';
include LANG . $_SESSION['userLang'] . '/stay-wifi-redirect.php';
include_once LIB . 'hotelinking_emails.php';

$showGDPR = true;
$regularUser = false;
$gdpr_events = false;
$stayTimeReconnection = false;
$_SESSION['acceptedGDPR'] = (array_get($_SESSION, 'gdpr_events', false)) ? true : false;
$_SESSION['showPortalPro'] = $_SESSION['showPortalPro'] ?? false;
$gateway = new ApiGatewayConnection();

$firstNameLength = $lastNameLength = getNameMinLength();

if ($_SESSION['userNavLang'] === "zh") {
    $firstNamePattern = $lastNamePattern = "^[^0-9]{1,}$";
} else {
    $firstNamePattern = $lastNamePattern = "^(?!.*(.)\\1\\1)(?!.*[bcdfghjklmnpqrstvwxyzñçBCDFGHJKLMNPQRSTVWXYZÑÇ]{8})[^\\d]{2,}$";
}
$googleClient = new Google_Client();
$googleClient->setClientId(GOOGLE_PORTAL_CLIENT_ID);
$googleClient->setClientSecret(GOOGLE_PORTAL_CLIENT_SECRET);
$googleClient->setRedirectUri(SECURE_BASE_PATH . 'stay-share/');

$googleClient->addScope('email');
$googleClient->addScope('profile');
$googleClient->addScope('https://www.googleapis.com/auth/user.gender.read');
$googleClient->addScope('https://www.googleapis.com/auth/user.birthday.read');

//check if user exists in session already
function userIsInSession()
{
    return array_has($_SESSION, 'user.id');
}

function hotelIsInSession($hotel_id)
{
    global $log;
    if (array_get($_SESSION, 'hotel.id') == $hotel_id || !array_has($_SESSION, 'hotel.id')) {
        return true;
    } else {
        $log->info("session in other hotel", [
            'session_hotel' => array_get($_SESSION, 'hotel.id'),
            'user_id' => array_get($_SESSION, 'user.id'),
            'new_hotel' => $hotel_id
        ]);

        unset($_SESSION['user']);
        unset($_SESSION['regularUser']);
        unset($_SESSION['customer']);
        unset($_SESSION['gdpr_events']);

        unset($_SESSION['name']);
        unset($_SESSION['roomNumber']);
        unset($_SESSION['birthday']);
        unset($_SESSION['gender']);
        unset($_SESSION['pms_user_email']);
        unset($_SESSION['email']);
        unset($_SESSION['connectionHistorySent']);

        return false;
    }
}

//get the user from its user_id and adds it to regularUser
function setRegularUserInSession($user_id)
{
    $_SESSION['regularUser'] = array_get($_SESSION, 'regularUser', getUser($user_id));

    // $_SESSION['regularUser']['stayTimeReconnection'] =  false;
    return $_SESSION['regularUser'];
}

function checkUserIsReturning() {
    global $stayWifiRedirect;
    global $log;

    // Check if user is returning.
    if (array_get($_SESSION, 'regularUser.stayTimeReconnection') === 0 && array_get($_SESSION,'customer') == 1) {
        $_SESSION['userIsReturning'] = true;
        // $log->info('checkUserIsReturning', ['regularUser' => $_SESSION['regularUser'] ?? '', 'user'=> $_SESSION['user'] ?? '', 'session'=>$_SESSION]);
        $GLOBALS["welcomeBackMessage"] = ", " . $_SESSION['user']['name'];
    } else {
        $_SESSION['userIsReturning'] = false;
    }
}


// Some error happened. Probably SESSION expired show error in front ($ok)
if (!empty($_GET['er'])) {
    $log->error('Stay share GET error', $_GET);
    $ok = [false, $_GET['er']];
}

if (!empty($_SESSION['error'])) {
    $logOp->error('Mikrotik error', ['error' => $_SESSION['error']]);
}

if (!empty($_GET['demo'])) {
    $log->info('Demo session', $_GET);
    $_SESSION['demo'] = true;
}

// If we see that you access the stay share page many times in the same session, 
// we understand that you have been in a loop trying to connect and we block access.
if(array_has($_SESSION, 'stayShareArrived')) {
    $_SESSION['stayShareArrived']++;
} else {
    $_SESSION['stayShareArrived'] = 1;
}

if ($_SESSION['stayShareArrived'] > MAX_STAY_SHARE_TRIES) {
    $log->info('Triggered stay share too many times in session', [$_SESSION]);
    
    // If the user comes from bypass we show a much more personalised page.
    if (array_get($_SESSION, 'user.stayTimeReconnection')) {
        $_SESSION['bypassStuck'] = true;
    } else {
        header('HTTP/1.0 403 Forbidden');
        echo $stayWifiRedirect['device_blacklisted'];
        exit();
    }
}

//Recoge el GUID del hotel y busca la información del hotel
if (!empty($url['dir2']) && substr($url['dir2'], 0, 1) !== "?") {
    //es un GUID?
    $guidHotel = $_SESSION['guidHotel'] = $url['dir2'];
    //Buscar hotel por GUID
    $hotel = obtenerIdHotelGUID($guidHotel);
    $cadena = hotelIdCadena($hotel);

    //Es la primera vez que el usuario accede, necesitamos pedir el access token
    $_SESSION['ask_for_access_token'] = true;

    //Identifica el controlador que va a hacer peticiones a Facebook
    $_SESSION['access_fb_from'] = 'stay-share';

    //Si no hay hotel 404
    if (empty($hotel)) {
        $log->error('REDIRECT', ['location' => 'stay-share', 'message' => 'No hotel is defined', 'destination' => 'stay-share']);
        header('Location: /' . $urlTree['404']);
        exit();
    } else {

        //Obtener el proveedor de wifi del hotel
        $wifi_provider = getWifiProvider($hotel);

        //keep wifi_provider in session to acces in connection-history
        $_SESSION['wifi_provider'] = $wifi_provider;

        //Si que existe el hotel, recogemos la info
        $hotelInfo = getHotelData($hotel);
        $brand_id = $hotelInfo['brand_id'] ?? null;
        $parent_id = $hotelInfo['parent_id'] ?? null;
        $_SESSION['brandID'] = $brand_id;
        
        try {
            $cacheName = 'loginConfiguration_' . $brand_id;
            $cache = getFromCache($cacheName);
            if (!$cache) {
                $accessTypes = json_decode($gateway->sendRequest(null, HOTELINKING_ENDPOINT . 'brands/' . $brand_id . '/access_types', 'GET'), true);
                $loginConfiguration = array_get($accessTypes, "data");
                setToCache($cacheName, $loginConfiguration, 86400);
            } else {
                $loginConfiguration = $cache->get();
            }

            $formIsActivated = getLoginConfigurationActive($loginConfiguration, "Form");
            $facebookIsActivated = FACEBOOK_ENABLE ? getLoginConfigurationActive($loginConfiguration, "Facebook") : false;
            $googleIsActivated = getLoginConfigurationActive($loginConfiguration, "Google");
        } catch (Exception $e) {
            $log->error('Error retrieving login access types on stay share', [$e]);
            $formIsActivated = 1;
            $facebookIsActivated = FACEBOOK_ENABLE;
            $googleIsActivated = 0;
        }

        $brandProtocols = getBrandProtocols($brand_id);
        $brandTreatment = $brandProtocols['portal']['treatment'];
        $eprivacyTreatment = $brandProtocols['portal']['dbsufix'];
        // Obtener los permisos del hotel
        $brandProducts = obtenerProductosHotel($brand_id); //obtener productos hotel (review, satisfacción...)
        $wifiOfferPermissions = getHotelWifiPermissions($hotel);
        $_SESSION['stayTime'] = array_get($wifiOfferPermissions, 'stay_time', 7);

        $log->info('stay share', ['post' => $_POST, 'get' => $_GET]);

        //get eprivacy fields
        $brand_eprivacy_info = getBrandEprivacyInfo($hotel);

        $_SESSION['brand_is_not_hotel'] = $brand_is_not_hotel = array_get($brand_eprivacy_info, 'not_hotel') ? true : false;
        $_SESSION['gdpr_restrictive'] = $gdpr_restrictive = (array_get($brand_eprivacy_info, 'restricted_portal') || array_get($brand_eprivacy_info, 'not_hotel'))  ? true : false;

        // Verify if the hotel has pms validation
        $isPortalPro = empty($_SESSION['showNormalPortal']) 
            ? getBrandProductActive($brandProducts, 'portal_pro')
            : false;

        if ($isPortalPro) {
            // Get portalPro config
            $portalProConfig = getPortalProConfiguration($brand_id);
            $log->debug('portal pro config', $portalProConfig);
            $_SESSION['showPortalPro'] = true;
            $_SESSION['portalPro']['isPmsValidation'] = (bool) (($portalProConfig['first_name'] ?? null) || ($portalProConfig['last_name'] ?? null) || ($portalProConfig['document_id'] ?? null) || ($portalProConfig['room_number'] ?? false));
            $_SESSION['portalPro']['isAccessCodeValidation'] = (bool) (($portalProConfig['access_code'] ?? false));
            $_SESSION['portalPro']['isRadiusTicketValidation'] = (bool) ($portalProConfig['radius_ticket'] ?? false);
        } else {
            // Fix to avoid the black modal when this variable is already in session from another portal
            $_SESSION['showPortalPro'] = false;
        }

        $gdpr_intro = array_get(getBrandCustomContent($hotel, $cadena, 'first_eprivacy_page', $brand_is_not_hotel ? 'not_hotel_eprivacy_text' : ($gdpr_restrictive ? 'restrictive_eprivacy_text' . $eprivacyTreatment : 'eprivacy_text' . $eprivacyTreatment)), $_SESSION['userLang']);
        $gdpr_outro = array_get(getBrandCustomContent($hotel, $cadena, 'second_eprivacy_page', $brand_is_not_hotel ? 'not_hotel_second_eprivacy_text' : 'second_eprivacy_text' . $eprivacyTreatment), $_SESSION['userLang']);
        $gdpr_restrictive_outro = array_get(getBrandCustomContent($hotel, $cadena, 'second_eprivacy_page', $brand_is_not_hotel ? 'not_hotel_second_eprivacy_text' : 'second_eprivacy_text' . $eprivacyTreatment), $_SESSION['userLang']);
        // Extraemos el brandLegalName
        $brandCustomContent = getBrandCustomContent($hotel, $cadena, 'second_eprivacy_page', 'second_eprivacy_text' . $eprivacyTreatment);
        $module_id = $brandCustomContent['module_id'];
        $isOwnVarsOrCustomContent = $brandCustomContent['configuration'] == 'own_vars' || $brandCustomContent['configuration'] == 'custom_content';
        $brandCustomVarsValues = getBrandCustomVarsValues($module_id, $hotel, $cadena);

        // We replace "{" "}" from custom Vars for a cleaner index search
        $columnsParsed = str_replace(array("{", "}"), "", array_column($brandCustomVarsValues, 'name'));
        $brandCustomVarsValuesParsed = array_combine($columnsParsed, $brandCustomVarsValues);

        $brandLegalName = $isOwnVarsOrCustomContent
                                ? $brandCustomVarsValuesParsed['brandLegalName']['value'] ?? $brandCustomVarsValuesParsed['brandLegalName']['default_value'] 
                                : getCustomVarsDefaultValue(1)[0]['value'];
                                
        //GDPR events exist when user accepts the gdpr modal
        if (array_has($_POST, 'gdprEvents') && !array_has($_SESSION, 'gdpr_events') && hotelIsInSession($hotel)) {
            $gdpr_events = json_decode(array_get($_POST, 'gdprEvents'), true);

            $log->info('acceptedGDPR', [$gdpr_events]);

            //process events for session
            processGdprEvents($gdpr_events);

            //dont show the gdpr modal again
            if ($isPortalPro) {
                $_SESSION['first_name'] = array_get($_POST, 'first-name');
                $_SESSION['last_name'] = array_get($_POST, 'last-name');
                $_SESSION['name'] = array_get($_POST, 'first-name') . (strlen(array_get($_POST, 'last-name')) > 0 ? ' ' : '') . array_get($_POST, 'last-name');
                $_SESSION['roomNumber'] = $_SESSION['roomNumber'] ?? array_get($_POST, 'room-number');
                $_SESSION['birthday'] = $_SESSION['birthday'] ?? array_get($_POST, 'birthday');
                $_SESSION['gender'] = $_SESSION['gender'] ?? array_get($_POST, 'gender');
                $_SESSION['pms_user_email'] = array_get($_POST, 'pms_user_email');
                $_SESSION['phoneNumber'] = $_SESSION['phoneNumber'] ?? array_get($_POST, 'phone-number');

                // Log portalPro session variables setting
                $log->info('StayShareController', ['message' => "PortalPro setting session variables", '$_POST' => $_POST]);

                // Variable to trigger the form if all data is already collected from pms
                if ($_SESSION['first_name'] && $_SESSION['last_name'] && $_SESSION['roomNumber'] && $_SESSION['birthday'] && $_SESSION['gender'] && $_SESSION['pms_user_email']) {
                    $isLoginFormComplete = true;
                } else {
                    $isLoginFormComplete = false;
                }
            }
        }

        //check if stay time reconnection
        $stayTimeReconnection = array_get($_SESSION, 'regularUser.stayTimeReconnection', false);
        //if user is in session and hotel is the correct one OR its a user redirecting post do bypass
        if ((array_get($_SESSION, 'user.id') && hotelIsInSession($hotel)) || array_get($_POST, 'UserRedirecting')) {
            if (array_get($_SESSION, 'user.id') || array_get($_SESSION, 'regularUser.id')) {
                $user_id = array_get($_SESSION, 'user.id', array_get($_SESSION, 'regularUser.id'));
                $regularUser = setRegularUserInSession($user_id);
                // $regularUser = array_get($_SESSION, 'regularUser');
                $stayTimeReconnection = array_get($_SESSION, 'regularUser.stayTimeReconnection', false);
                // Check if user is returning.
                checkUserIsReturning();
            }

        } else {
            try {
                $macVariablesNames = ['mac', 'client_mac', 'usermac', 'umac'];
                $result = array_intersect($macVariablesNames, array_keys($_REQUEST));


                //check if we manage to recover a mac from the integration
                if (array_get($wifiOfferPermissions, 'bypass_active', false) && ((sizeof($result) > 0) || array_get($_SESSION, 'mac'))) {
                    if (!array_get($_SESSION, 'mac')) {
                        //get the mac from the request , reset get the value of first element in array $result i.e $result = [2 => 'usermac'] , reset($result) = 'usermac'
                        $userMac = array_get($_REQUEST, reset($result), false);
                    } else {
                        $userMac = array_get($_SESSION, 'mac');
                    }

                    //check if we manage to recover a mac from the connection
                    if ($userMac && $hotel) {
                        //if we manage to recover a mac from the connection we recover the user associated to that device
                        $userMac = macFormatter($userMac);
                        $chain_id = "";
                        $stayTime = array_get($wifiOfferPermissions, 'stay_time', 7);
                        if (useChainBypass($hotel) && $cadena) {
                            $chain_id = $cadena;
                            $stayTime = getChainStayTime($chain_id);
                        }
                        $connectionHistoryForMac = checkMacInConnectionHistory($userMac, $hotel, $chain_id, $stayTime);

                        // if connection_history times_login is > that max_hotspot_tries then dont do the bypass
                        // for the same hotel (if chain bypass dont check times_login)
                        if ($connectionHistoryForMac && array_get($connectionHistoryForMac,'id_hotel') === $hotel && $connectionHistoryForMac['times_login'] > MAX_HOTSPOT_TRIES) {
                            $connectionHistoryForMac = false;
                        }

                        //hotfix to check issue with mikrotik belive roomNumber connection history problem
                        if (array_get($wifi_provider, 'name') === 'mikrotik_belive') {
                            if (empty($connectionHistoryForMac['roomNumber'])) {
                                $log->warning("StayShareController", ['message' => "Belive user roomNumber empty", 'connectionHistoryForMac' => $connectionHistoryForMac, 'session' => $_SESSION]);
                                $connectionHistoryForMac = false;
                            }
                        }
                    } else {
                        $connectionHistoryForMac = false;
                    }

                    if ($connectionHistoryForMac && array_get($connectionHistoryForMac, 'id_user') && array_get($connectionHistoryForMac, 'stayTimeReconnection')) {

                        //we get user object from id
                        $regularUser = getUser(array_get($connectionHistoryForMac, 'id_user'));

                        if (array_get($wifi_provider, 'name') === 'mikrotik_belive') {
                            if (empty($regularUser['card_id'])) {
                                $log->warning("StayShareController", ['message' => "Belive user cardId empty", 'regularUser' => $regularUser, 'session' => $_SESSION]);
                                unset($_SESSION['pms_user']);
                                unset($_SESSION['pms_user_email']);
                                unset($_SESSION['is_pms_user_email_valid']);
                                unset($_SESSION['portal_pro_user']);
                                $regularUser = false;
                            }
                        }

                        if (array_get($regularUser, 'id')) {

                            $_SESSION['card_id'] = $regularUser['card_id'];
                            $_SESSION['roomNumber'] = $connectionHistoryForMac['roomNumber'];
                            $_SESSION['mac'] = $userMac;
                            $_SESSION['customer'] = array_get($connectionHistoryForMac, 'customer');
                            $log->debug('regularUser', [$regularUser]);

                            //check if this user already accepted gdpr for this hotel
                            $_SESSION['acceptedGDPR'] = (array_get($_SESSION, 'gdpr_events', checkInGDPRHistory(array_get($regularUser, 'id'), $hotel, $chain_id))) ? true :  false;
                            if ($_SESSION['acceptedGDPR']) {
                                $_SESSION['regularUser'] = $_SESSION['user'] = $regularUser;
                                $stayTimeReconnection = array_get($connectionHistoryForMac, 'stayTimeReconnection');
                                $_SESSION['regularUser']['stayTimeReconnection'] = $stayTimeReconnection;
                            } else {
                                $regularUser = false;
                            }
                        } else {
                            $regularUser = false;
                        }
                        // Check if user is returning.
                        checkUserIsReturning();
                    }
                }
            } catch (Error $e) {
                $log->error("error in wifi redirect", ["error" => $e->getMessage()]);
                $regularUser = false;
                $stayTimeReconnection = false;
            }
        }

        // If portal pro reconnection do bypass.
        if ($isPortalPro && $stayTimeReconnection == 1) {
            $_SESSION['showPortalPro'] = false;
        }

        // Not showing GDPR When
        $showGDPR = !(
                        // User is redirecting (Bypass)
                        (array_get($_POST, 'UserRedirecting'))
                        // User has accepted gdpr and hotel data is in session.
                        || (array_has($_SESSION, 'gdpr_events') && hotelIsInSession($hotel))
                        // User has been validated from Portal Pro.
                        || (array_get($_SESSION, 'user.id') && hotelIsInSession($hotel) && $isPortalPro && ($stayTimeReconnection == 1))
                        // User had gdpr accepted in our system.
                        || (!$isPortalPro && $_SESSION['acceptedGDPR'])
                    );



        //Obtener la información de la oferta de wifi del hotel Si tiene permisos
        if (getBrandProductActive($brandProducts, 'wifi_offers')) {
            $ofertaWifiHotel = getActiveWifiOffer($brand_id, array_get($_SESSION, 'customer'));
            $ofertaWifiHotel = data_get($ofertaWifiHotel, 'condition') !== "always" ? "" : $ofertaWifiHotel;
        } else {
            $ofertaWifiHotel = '';
        }

        //Store for late use on facebook-login-callback
        $_SESSION['wifi_offer'] = $ofertaWifiHotel;

        //Store for late use on facebook-login-callback
        $_SESSION['hotelInfo'] = $hotelInfo;

        //Obtener la oferta de Referral
        $ofertaReferralHotel = obtenerOfertaReferral($hotel);



        //Obtener configuración wifi Hotel
        $datosWifiHotel = obtenerWifiStayHotel($hotel);

        //keep settings of hotel's wifi in session to acces in connection-history
        $_SESSION['wifi_hotel'] = $datosWifiHotel;

        //Guarda la información del hotel en sesión
        $_SESSION['hotel'] = [];
        $_SESSION['hotel']['id'] = $hotel;
        $_SESSION['hotel']['brand_id'] = $brand_id;
        $_SESSION['hotel']['parent_id'] = $parent_id;
        $_SESSION['hotel']['treatment'] = $brandTreatment;

        setChainInfo($hotelInfo);

        //----HL_emails actions------------------
        $_SESSION['brandProducts'] = $brandProducts;
        
        $portalProductConfig = null;
        $portalProduct = array_filter($_SESSION['brandProducts'], function($product) {
            return $product['product_id'] == '24';
        });

        if(!empty($portalProduct)) {
            $portalProductConfig = json_decode(reset($portalProduct)['config']);
            $_SESSION['portalConfig'] = $portalProductConfig;
        }

        if ($googleIsActivated) {
            $googleAuthUrl = $googleClient->createAuthUrl();
        }

        if (array_get($_GET, 'error') == 'google')
        {
            $ok = [false, '4064', 10000];
        }
        
        ////////////////////////
        /////FORM EMAIL/////////
        ////////////////////////
        //Evaluate if the main form information is set, or the user has connect before using Hotelinking (in order to bypass)
        if ((!empty($_POST['refShareStep2firstName']) && !empty($_POST['refShareStep2lastName']) && !empty($_POST['refShareStep2email'])) || array_get($_POST, 'UserRedirecting') || array_get($_SESSION, 'tryGoogleLogin')) {
            // if is mikrotik and not session mac => error
            
            if (preg_match('/^mikrotik/', array_get($wifi_provider, 'name')) && !array_has($_SESSION, 'mac') && !array_get($_SESSION, 'demo')) {
                $log->warning('No mac in session for mikrotik', ['session' => $_SESSION, 'cookies'=> $_COOKIE, 'post' => $_POST, 'redirect_ur' => WIFI_REDIRECT_URL]);
                header("Location: " . WIFI_REDIRECT_URL);
                exit();
            }

            $form_user = [];
            // Datos usuario
            $sendexDefault = 0.0;
            $emailResultDefault = 'risky';

            if (isset($_POST["refShareStep2firstNameIdUpdate"]) && isset($_POST['refShareStep2firstName'])) {
                unset($_SESSION['first_name']);
                unset($_SESSION['name']);
                unset($_SESSION['portal_pro_user']['first_name']);
                unset($_SESSION['portal_pro_user']['name']);
            }
            
            if (isset($_POST["refShareStep2lastNameIdUpdate"]) && isset($_POST['refShareStep2lastName'])) {
                unset($_SESSION['last_name']);
                unset($_SESSION['name']);
                unset($_SESSION['portal_pro_user']['last_name']);
                unset($_SESSION['portal_pro_user']['name']);

            }
            
            if (isset($_POST["refShareStep2genderIdUpdate"]) && isset($_POST['refShareStep2gender'])) {
                unset($_SESSION['gender']);
                unset($_SESSION['portal_pro_user']['gender']);

            }

            // New user and sent form
            if ($regularUser == false || array_get($_SESSION, 'bypassInvalid')) {
                // User comes from form
                $form_user['first_name'] = $firstName = $_SESSION['first_name'] = !empty($_SESSION['first_name']) ? $_SESSION['first_name'] : normalize_text(ucwords(strtolower(array_get($_POST, 'refShareStep2firstName'))));
                $form_user['last_name'] = $lastName = $_SESSION['last_name'] = !empty($_SESSION['last_name']) ? $_SESSION['last_name'] : normalize_text(ucwords(strtolower(array_get($_POST, 'refShareStep2lastName'))));
                $form_user['name'] = $name = $_SESSION['name'] = !empty($_SESSION['name']) ? $_SESSION['name'] : strtolower(setNameFromFirstAndLastNames($firstName, $lastName));
                $form_user['phone_number'] = $phone = $_SESSION['phone'] = !empty($_SESSION['phone']) 
                ? $_SESSION['phone'] 
                : array_get($_POST, 'phone_country_code') . array_get($_POST, 'phone_number');
                $form_user['email'] = $email = $_SESSION['email'] = !empty($_SESSION['email']) ? $_SESSION['email'] : array_get($_POST, 'refShareStep2email');
                $form_user['gender'] = $gender = $_SESSION['gender'] = !empty($_SESSION['gender']) ? $_SESSION['gender'] : array_get($_POST, 'refShareStep2gender');
                $form_user['birthday'] = $birth = $_SESSION['birthday'] = !empty($_SESSION['birthday']) 
                    ? $_SESSION['birthday'] 
                    : (array_get($_POST, 'refShareStep2year') && array_get($_POST, 'refShareStep2month') && array_get($_POST, 'refShareStep2day')
                        ? date('Y-m-d', strtotime($_POST['refShareStep2year'] . '-' . $_POST['refShareStep2month'] . '-' . $_POST['refShareStep2day']))
                        : null);
            } else {
                // User is bypassed
                if ($isPortalPro) {
                    $form_user['first_name'] = $firstName = $_SESSION['first_name'] = !empty(array_get($_SESSION, 'portal_pro_user.first_name')) ? array_get($_SESSION, 'portal_pro_user.first_name') : ucwords(strtolower(array_get($regularUser, 'first_name')));
                    $form_user['last_name'] = $lastName = $_SESSION['last_name'] = !empty(array_get($_SESSION, 'portal_pro_user.last_name')) ? array_get($_SESSION, 'portal_pro_user.last_name') : ucwords(strtolower(array_get($regularUser, 'last_name')));
                    $form_user['name'] = $name = $_SESSION['name'] = !empty(array_get($_SESSION, 'portal_pro_user.name')) ? array_get($_SESSION, 'portal_pro_user.name') : ucwords(strtolower(array_get($regularUser, 'name')));
                    $form_user['phone_number'] = $phone = $_SESSION['phone'] = !empty(array_get($_SESSION, 'portal_pro_user.phone')) ? array_get($_SESSION, 'portal_pro_user.phone') : array_get($regularUser, 'phone');
                    $form_user['email'] = $email = $_SESSION['email'] = array_get($regularUser, 'email');
                    $form_user['gender'] = $gender = $_SESSION['gender'] = !empty(array_get($_SESSION, 'portal_pro_user.gender')) ? array_get($_SESSION, 'portal_pro_user.gender') : array_get($regularUser, 'gender');
                    $form_user['birthday'] = $birth = $_SESSION['birthday'] = !empty(array_get($_SESSION, 'portal_pro_user.birthday')) ? array_get($_SESSION, 'portal_pro_user.birthday') : array_get($regularUser, 'birthday');
                } else {
                    $form_user['first_name'] = $firstName = $_SESSION['first_name'] = ucwords(strtolower(array_get($regularUser, 'first_name')));
                    $form_user['last_name'] = $lastName = $_SESSION['last_name'] = ucwords(strtolower(array_get($regularUser, 'last_name')));
                    $form_user['name'] = $name = $_SESSION['name'] = ucwords(strtolower(array_get($regularUser, 'name')));
                    $form_user['phone_number'] = $phone = $_SESSION['phone'] = array_get($regularUser, 'phone');
                    $form_user['email'] = $email = $_SESSION['email'] = array_get($regularUser, 'email');
                    $form_user['gender'] = $gender = $_SESSION['gender'] = array_get($regularUser, 'gender');
                    $form_user['birthday'] = $birth = $_SESSION['birthday'] = array_get($regularUser, 'birthday');
                }

                $sendexDefault = array_get($regularUser, 'sendex');
                $emailResultDefault = array_get($regularUser, 'email_result');
            }
            
            // Do the same validation as on the front end for those browsers or devices where it does not work.
            if (!validateName($form_user['first_name'])) {
                $log->info('first name not valid in stay-share form', ['post' => $_POST]);
                unset($_SESSION['first_name']);
            }

            if (!validateName($form_user['last_name'])) {
                $log->info('last name not valid in stay-share form', ['post' => $_POST]);
                unset($_SESSION['last_name']);
            }
            
            if (!validateEmailString($form_user['email'])) {
                $log->info('email not valid in stay-share form', ['post' => $_POST]);
                unset($_SESSION['email']);
            }
            
            if (!validateGender($form_user['gender'])) {
                $log->info('gender not valid in stay-share form', ['post' => $_POST]);
                unset($_SESSION['gender']);
            }
            
            if (!validateDate($form_user['birthday'])) {
                $log->info('birthday not valid in stay-share form', ['post' => $_POST]);
                unset($_SESSION['birthday']);
            }

            if (!isset($_SESSION['first_name']) || !isset($_SESSION['last_name']) || !isset($_SESSION['email']) || (!isset($_SESSION['gender']) && (!$portalProductConfig || !isset($portalProductConfig->remove_gender_field) || !$portalProductConfig->remove_gender_field)) || !isset($_SESSION['birthday']) ) {
                $_SESSION['bypassInvalid'] = true;
                $_SESSION['formIncomplete'] = true;
                $ok = [false, '4099', 20000];
            } else {
                //get locale from parsing http_accept_language
                if (array_get($_SERVER, 'HTTP_ACCEPT_LANGUAGE')) {
                    $accept_languages_arr = explode(",", $_SERVER['HTTP_ACCEPT_LANGUAGE']);
                    preg_match("/^(([a-zA-Z]+)(-([a-zA-Z]+)){0,1})/", $accept_languages_arr[0], $matches);
                    $locale = $matches[1];

                    //reformate the locale if comes like en-gb, pt-pt to en_GB or pt_PT
                    $locale_array = explode('-', $locale);
                    if ($locale_array && count($locale_array) == 2) {
                        $locale = $locale_array[0] . '_' . strtoupper($locale_array[1]);
                    }
                    $form_user['locale'] = $locale;
                } else {
                    $form_user['locale'] = $locale = $_SESSION['userNavLang'];
                }

                $form_user['lang'] = $lang = $_SESSION['userNavLang'];
                $form_user['hotel_id'] = $hotel;

                //Miramos si debemos caducar las variables de SESSION de este procedimiento
                $resultEmail = validateEmail($email, $regularUser);

                $log->debug("validacion mail", [
                    "msg" => $resultEmail, 
                    "Default" => $sendexDefault, 
                    "erDefault" => $emailResultDefault
                ]);

                $sendex = $resultEmail['sendex'] ?? $sendexDefault;
                $emailResult = $resultEmail['result'] ?? $emailResultDefault;
                // Si el email es válido (o no estamos en producción), procedemos
                if (array_get($resultEmail, 'valid')) {
                    //Create new user or return user ID if already exists
                    $source = !empty($_SESSION['tryGoogleLogin']) ? "google" : "form";
                    $user = createNewUser($form_user, $sendex, $emailResult, $source);
                    $_SESSION['user'] = [
                        'id' => array_get($user, 'id') ? array_get($user, 'id') : array_get($_SESSION, 'user.id'),
                        'email' => $email,
                        'name' => $name,
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                        'phone_number' => $phone,
                        'lang' => $lang,
                        'gender' => $gender,
                        'birthday' => array_get($user, 'fecha_nacimiento') ? array_get($user, 'fecha_nacimiento') : $birth,
                        'locale' => $locale,
                        'isNew' => array_get($user, 'isNew'),
                        'regularUser' => $regularUser ? true : false,
                        'stayTimeReconnection' => $stayTimeReconnection ? true : false,
                        'source' => $source,
                        'sendex' => $sendex,
                        'result' => $emailResult,
                    ];

                    // El hotel tiene contratado algún producto necesario en stay?
                    if (getBrandProductActive($brandProducts, 'satisfaction') || getBrandProductActive($brandProducts, 'review') || getBrandProductActive($brandProducts, 'wifi_offers')) {
                        if (!empty($_SESSION['fbLoginError']) && $_SESSION['fbLoginError']) {
                            //Viene de facebook, pero se ha producido un error. Debemos dar oferta
                            $errorFacebook = true;
                            unset($_SESSION['fbLoginError']);
                        } else {
                            $errorFacebook = false;
                        }
                    }
                    // HL_emails actions END
                    $userId = $_SESSION['user']['id'];

                    $log->info('Redirecting to stay-wifi-redirect from form', ['user' => $_SESSION['user'], 'cookies' => $_COOKIE]);

                    // Redirigir a URL wifi
                    header('Location: /' . $urlTree['stay-wifi-redirect'] . '/?i=' . $hotel . '&u=' . $userId . '&PHPSESSID=' . session_id());
                    exit();
                } else {
                    $log->info('email not valid in stay-share form', ['post' => $_POST]);
                    unset($_SESSION['email']);
                    // In case that the user email from pms is not validate
                    if (array_get($_SESSION, 'pms_user_email')) {
                        $_SESSION['is_pms_user_email_valid'] = false;
                        // PmsEmail inválido -> feedback
                        $ok = [false, '4090', 20000];
                    } else {
                        // Email inválido -> feedback
                        $ok = [false, '4043', 20000];
                    }
                }
            }
        }
    }
} else {
    if (!empty($_SESSION['guidHotel']) && !empty($_GET['code'])) {
        // Redirect from Google oAuth
        global $log;

        $token = $googleClient->fetchAccessTokenWithAuthCode($_GET["code"]);
        $googleError = false;

        if(!isset($token['error']))
        {
            $_SESSION['access_token'] =  $token['access_token'];
            $curl = new Curl\Curl();
            $curl->setHeader('Authorization', 'Bearer ' . $token['access_token']);
            $curl->get('https://people.googleapis.com/v1/people/me?personFields=names,emailAddresses,birthdays,genders');

            if ($curl->error) {
                $googleError = true;
                $log->error("Google People Api call error", [$curl->error_code]);
            } else {
                $_SESSION['tryGoogleLogin'] = true;

                $response = json_decode($curl->response, true);
                $year = array_get($response, 'birthdays.0.date.year');
                $month = array_get($response, 'birthdays.0.date.month');
                $day = array_get($response, 'birthdays.0.date.day');


                $_SESSION['first_name'] = !empty($_SESSION['first_name']) ? $_SESSION['first_name'] : array_get($response, 'names.0.givenName');
                $_SESSION['last_name'] = !empty($_SESSION['last_name']) ? $_SESSION['last_name'] : array_get($response, 'names.0.familyName');
                $_SESSION['email'] = !empty($_SESSION['email']) ? $_SESSION['email'] : array_get($response, 'emailAddresses.0.value');
                $_SESSION['gender'] = !empty($_SESSION['gender']) ? $_SESSION['gender'] : array_get($response, 'genders.0.value');
                $_SESSION['birthday'] = !empty($_SESSION['birthday']) 
                    ? $_SESSION['birthday']
                    : ($year && $month && $day
                        ? date('Y-m-d', strtotime($year . '-' . $month . '-' . $day))
                        : null);
            }
        } else {
            $googleError = true;
            $log->error("Google Get Token error", [$token['error']]);
        }
        
        $error = $googleError ? '/?error=google' : '';
        header('Location: /' . $urlTree['stay-share'] . '/' . $_SESSION['guidHotel'] . $error);
    } else {
        //Si no hay dir 2 404
        $log->error('REDIRECT', ['location' => '404', 'message' => 'No GUID found (dir2)', 'destination' => '404']);
        header('Location: /' . $urlTree['404']);
        exit();
    }
}

function checkIfUnsubscribed($events)
{
    global $log;
    if (in_array('notifications', $events)) {
        return false;
    } else {
        $log->info('GDPR user is not subscribed');

        return true;
    }
}

function unsubscribeUserClassicPortal($type, $events)
{
    if ($type == 'not_client') {
        return checkIfUnsubscribed($events);
    }

    return false;
}

function unsubscribeUserRestrictivePortal($events)
{
    return checkIfUnsubscribed($events);
}

function isUserClient($type)
{
    return $type == 'client' || array_get($_SESSION, 'brand_is_not_hotel');
}

//process gdprevents
function processGdprEvents($gdpr_events)
{
    global $log;
    $_SESSION['gdpr_events'] = $gdpr_events;
    $not_hotel = array_get($_SESSION, 'brand_is_not_hotel');
    //check if the latest gdpr_event was client or not_client
    $events = array_pluck(array_reverse($_SESSION['gdpr_events']), 'event');
    $type = array_first($events, function ($key, $value) {
        return $value == 'client' || $value == 'not_client';
    });

    if ($events && ($type || $not_hotel || array_has($_SESSION, 'customer'))) {
        //check subscribed
        if ($_SESSION['gdpr_restrictive']) {
            $_SESSION['unsubscribed'] = unsubscribeUserRestrictivePortal($events);
        } else {
            $_SESSION['unsubscribed'] = unsubscribeUserClassicPortal($type, $events);
        }
        $_SESSION['commercial_profile'] = in_array('commercial_profile', $events);
  
        //check if is customer
        $_SESSION['customer'] = array_get($_SESSION, 'customer', isUserClient($type));
        $log->info('GDPR user is ' . ($_SESSION['customer'] ? '' : 'not ') . 'hotel client');
    } else {
        $log->error('GDPR errors', ['events' => $gdpr_events, 'not_hotel' => $not_hotel]);
    }
}

function setChainInfo($hotelInfo)
{
    if (isset($_SESSION['chain'])) {
        return;
    }

    $chainId = $hotelInfo['id_cadena'] ?? null;
    if ($chainId) {
        $_SESSION['chain'] = [];
        $_SESSION['chain']['id'] = $chainId;
        $_SESSION['chain']['brand_id'] = $hotelInfo['parent_id'];
    }
}

function setNameFromFirstAndLastNames($firstName, $lastName) {
    return $firstName . ' ' . $lastName;
}
