<?php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}
// Includes
include_once LIB . 'obtenerdatosHotel.php';
include_once LIB . 'hotelinking_emails.php';
include_once LIB . 'socialMediaShareText.php';
include_once LIB . 'get_hotel_wifi_permissions_and_offers.php';
include_once LIB . 'apiGateway.php';
include_once RUTA_DIR . LIB . 'webservices/msgFeedback.php';
include_once RUTA_DIR . LIB . 'tokens.php';
include_once LIB . 'hotelinking_integrations.php';

include_once RUTA_DIR . LIB . 'composer/vendor/autoload.php';

//included to insert the connection history
// include_once LIB . 'connectionHistory.php';

//GETTER
$showPage = false;
$identifiedUser = false;
$userCanceled = false;
$unifiOk = false;
$hotel_room_list = null;
$hotel_access_room_list = null;
$BrandHasBookingUrl = true;
$trigger_integration = false;
$brand_id = (int)array_get($_SESSION, 'brandID');
$ok = false;

// bypassRoomMissing by default is set to false
// this is necessary in the case that a bypass happens with a room_number that is empty (but required)
$_SESSION['bypassRoomMissing'] = $_SESSION['bypassRoomMissing'] ?? false;

$user = array_get($_SESSION, 'user');
$treatment = $_SESSION['hotel']['treatment'] ?? 'formal';

if (!array_get($_SESSION, 'user.id') || !array_get($_SESSION, 'hotel.id')) {
    $log->warning('No user or hotel in session in wifi redirect : redirecting to wifisafe', $_SESSION);
    header("Location: " . WIFI_REDIRECT_URL);
    exit();
}

function unifiRedirect($stayData, $stayTimeReconnection)
{
    //If wifi provider is unifi
    global $allOk, $unifiOk;
    if ($stayData['wifi_name'] == 'unifi') {
        //Front end purposes
        if (getBrandProductActive($_SESSION['brandProducts'], 'require_room_num') && !$stayTimeReconnection) {
            $unifiOk = false;
        } else {
            $unifiOk = true;
        }
        $allOk = true;
    } else {
        $allOk = true;
    }
}

/*Values for by pass a reconection*/
$regularUser = array_get($_SESSION, 'user.regularUser');
$stayTimeReconnection = false;
$user_bypassed = array_get($_SESSION, 'user.stayTimeReconnection');
if ($user_bypassed) {
    $log->info("User bypassed", ['user' => array_get($_SESSION, 'user')]);
    /*Values for give a personalized welcome to hotelinking users*/
    $stayTimeReconnection = true;
}

function getUrlForCountryLang($arrayLangs, $fbUserLang)
{
    foreach ($arrayLangs as $brandLangs) {
        $langsArray = explode(",", $brandLangs['locale']);
        if (array_search(str_replace("_", "-", $fbUserLang), $langsArray, true) !== false) {
            return $brandLangs['url'];
        }
    }
    return '';
}

/**
 * Get portal redirect URL with fallback logic
 * Priority:
 * 1. Response from redirectIntegrationUrlResponse
 * 2. url_redirect from portal config
 * 3. null
 *
 * @param array $redirectIntegrationUrlResponse Response from getRedirectUrl API call
 * @return string|null The portal redirect URL or null
 */
function getPortalRedirectUrlWithFallback($redirectIntegrationUrlResponse)
{
    // First priority: URL from redirect integration response
    $redirectUrl = $redirectIntegrationUrlResponse['url'][0] ?? null;
    if (!empty($redirectUrl)) {
        return $redirectUrl;
    }

    // Second priority: URL from portal config
    $brandProducts = $_SESSION['brandProducts'];
    $portalConfigId = 24; // Corresponds to the id of the portal product
    foreach ($brandProducts as $product) {
        if ($product['id'] == $portalConfigId && !empty($product['config'])) {
            $config = json_decode($product['config'], true);
            if (isset($config['url_redirect'])) {
                return $config['url_redirect'];
            }
        }
    }

    // Third priority: null
    return null;
}

if ($_GET) {
    $log->debug('get params ', $_GET);
    if (empty($_GET['i'])) {
        $log->error('REDIRECT', array('destination' => '404', 'location' => 'wifi-redirect', 'message' => 'Hotel_id not in GET parameter ', 'session' => $_SESSION));
        header("location: " . WIFI_REDIRECT_URL);
        exit();
    }

    $log->info('stay wifi redirect', ['user' => array_get($_SESSION, 'user'), '$_POST' => $_POST]);

    //hotel Id exists on query
    if ($_GET['i']) {
        //all is not ok already
        $allOk = false;
        $hotel_id = $_GET['i'];
        //Get hotel wifi data (Integration information)
        $stayData = obtenerWifiStayHotel($hotel_id);
        if ($stayData) {
            // Check if hotel has portalRedirect activated to get url from redirect integration
            // and guest is not bypassed
            if (getBrandProductActive($_SESSION['brandProducts'], 'portal_redirect') && !array_get($_SESSION, 'user.stayTimeReconnection') && empty($_SESSION['showNormalPortal'])) {
                // Get url calling redirect integration in case that is not already fetched
                if (empty(array_get($_SESSION, 'portalRedirectUrl'))) {
                    $log->info(
                        "--- DEBUG ---",
                        [
                            "user" => $_SESSION['user'] ?? null,
                            "portal_pro_user" => $_SESSION['portal_pro_user'] ?? null
                        ]
                    );
                    // Check if has a user before perform request
                    if (
                        !empty($_SESSION['user'] ?? null)
                        && !empty($_SESSION['portal_pro_user'] ?? null)
                    ) {
                        // Perform call
                        $redirectIntegrationUrlResponse = getRedirectUrl(
                            $brand_id,
                            [
                                "userId" => $_SESSION['user']['id'] ?? null,
                                "roomNumber" => $_SESSION['portal_pro_user']['room_number'] ?? $_SESSION['roomNumber'] ?? $_POST['roomNumber'] ?? null,
                                "checkIn" => $_SESSION['portal_pro_user']['check_in'] ?? null,
                                "checkOut" => $_SESSION['portal_pro_user']['check_out'] ?? null,
                                "locator" => $_SESSION['portal_pro_user']['pms_id'] ?? null,
                                "resId" => $_SESSION['portal_pro_user']['res_id'] ?? null
                            ]
                        );
                        // Get result from response with fallback to portal config
                        $_SESSION['portalRedirectUrl'] = getPortalRedirectUrlWithFallback($redirectIntegrationUrlResponse);

                        $log->info('PortalRedirect - StayWifiRedirectController', [
                            'message' => "GetRedirectUrl result",
                            'user' => $_SESSION['user'] ?? null,
                            'portal_pro_user' => $_SESSION['portal_pro_user'] ?? null,
                            'redirectIntegrationUrlResponse' => $redirectIntegrationUrlResponse,
                        ]);
                    } else {
                        $log->error('PortalRedirect - StayWifiRedirectController', [
                            'message' => "No user or portal_pro_user to perform getRedirectUrl call",
                            'user' => $_SESSION['user'] ?? null,
                            'portal_pro_user' => $_SESSION['portal_pro_user'] ?? null,
                        ]);
                    }
                }
            }

            // Case user was verificated by pms verification
            if (array_get($_SESSION, 'pms_user') && array_get($_SESSION, 'user.id')) {
                // Fetch array pms user from session
                $pms_user = array_get($_SESSION, 'pms_user');

                // Add user_id to array
                $pms_user['user_id'] = array_get($_SESSION, 'user.id');

                // rewrite the gender and birthday for pms_user
                // if ever the pms_user does not come with gender or birthday we ask for it in link-modal-template
                $pms_user['gender'] = $_SESSION['gender'];
                $pms_user['birthday'] = $_SESSION['birthday'];

                // Send pms_user to integrations
                insertValidatedUser($brand_id, $pms_user);

                // Remove pms_user from session
                unset($_SESSION['pms_user']);
                unset($_SESSION['pms_user_email']);
                unset($_SESSION['is_pms_user_email_valid']);
            }

            //Check if hotel has permission to show wifi offers on stay screen
            //If yes, check wich offer we need to show on stay screen
            if (getBrandProductActive($_SESSION['brandProducts'], 'wifi_offers')) {
                $ofertaWifiHotel = getActiveWifiOffer($brand_id, array_get($_SESSION, 'customer'));
            }

            $user_id = (isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : null);

            //Get hotel data (name, img...)
            $datosHotel = getHotelData($hotel_id);
            // $chain_id = !empty($datosHotel['id_cadena']) ? $datosHotel['id_cadena'] : NULL;
            $chain_id = array_get($datosHotel, 'id_cadena', null);

            //Determine wich website use for share
            include_once RUTA_DIR . LIB . 'idiomas.php';
            $userLang = mirarIdiomaPlataforma($_SESSION['userNavLang']);
            $fbUserLang = array_get($_SESSION, 'user.locale');

            //Obtener configuraciÃ³n wifi Hotel REDUNDANT
            $datosWifiHotel = $stayData;

            if (getBrandProductActive($_SESSION['brandProducts'], 'require_room_num')) {
                $hotel_room_list = getBrandAccessCodes($brand_id);
                $hotel_access_room_list = getBrandAccessCodes($brand_id, 'guest');
            }

            /*testeando*/
            $arrayLangs = getAllLangsAvailables($hotel_id, $chain_id);
            $shared_website = '';
            /* $langsArray are locales related to a url. On the first position come all the chain urls, then hotel's ones.
            So chain urls has preference over hotel's urls*/
            $shared_website = getUrlForCountryLang($arrayLangs, $fbUserLang);

            /* testeando */
            if (!$shared_website) {
                if ($userLang == 'en' && !empty($_SESSION['hotel']['brand_id'])) {
                    $shared_website = getHotelWebsiteUrl($_SESSION['hotel']['brand_id'], $userLang);
                } else {
                    if (!empty($userLang)) {
                        $shared_website = getHotelWebsiteUrl($_SESSION['hotel']['brand_id'], $userLang);
                    } else {
                        $shared_website = (!empty($userLang) ? getHotelWebsiteUrl($_SESSION['hotel']['brand_id'], $userLang) : getHotelWebsiteUrl($_SESSION['hotel']['brand_id'], 'en'));
                    }
                }
            }
            /*Check if exist any url to redirect share actions, if it doesn't exist we set $BrandHasBookingUrl to false
            for jump stay-wifi-redirect to room validation/get-wifi*/
            if (!$shared_website) {
                $BrandHasBookingUrl = false;
            }

            //Create the correct url
            if (!empty($user_id)) {
                $query = parse_url($shared_website, PHP_URL_QUERY);
                //Generate map token used in booking engine for iframe
                $referrer_token = getReferrerTokenByUserId($user_id, $hotel_id);

                $token = $referrer_token ? $referrer_token : generateToken($user_id, $hotel_id);

                // Returns a string if the URL has parameters or NULL if not
                if ($query) {
                    $shared_website .= '&utm_source=hotelinking&utm_medium=facebook&utm_campaign=hotelinking_guest_friends&hltr=facebook&hlre=' . $user_id . '&hlho=' . $hotel_id . '&hlch=' . $chain_id . '&hltoken=' . $token;
                } else {
                    $shared_website .= '?utm_source=hotelinking&utm_medium=facebook&utm_campaign=hotelinking_guest_friends&hltr=facebook&hlre=' . $user_id . '&hlho=' . $hotel_id . '&hlch=' . $chain_id . '&hltoken=' . $token;
                }

                //Creamos el enlace para el redirect de Facebook
                $data = array(
                    'app_id' => FACEBOOK_APP_ID,
                    'display' => 'iframe',
                    'link' => $shared_website,
                    //'caption' => $datosHotel['hotelName'] . ' - ' . $socialMediaShareText,
                    "redirect_uri" => SECURE_BASE_PATH . 'stay-wifi-redirect/?i=' . $hotel_id . (!empty($_GET['u']) ? '&u=' . $user_id : '') . '&shared=true',
                );
                //Make query
                $facebookQuery = http_build_query($data, '', '&amp;');
                //Store in session for another uses
                $_SESSION['facebookQuery'] = $facebookQuery;

                //Obtener configuraciÃ³n wifi Hotel REDUNDANT
                $datosWifiHotel = $stayData;

                if (getBrandProductActive($_SESSION['brandProducts'], 'require_room_num') && $hotel_room_list != null) {
                    $hotel_room_list = getBrandAccessCodes($brand_id);
                }

                //Get hotel facebook page (for likes)
                $facebookHotelPage = getFacebookPage($hotel_id);

                //Get wich wifi provider hotel uses to know wich data from $staydata we need to look
                $wifi_provider = $_SESSION['wifi_provider'] = getWifiProvider($hotel_id);

                //Show page (check front end)
                $showPage = true;
            }
        }
    } else {
        $log->warning('REDIRECT', array('message' => 'id_hotel not set in $_GET', 'location' => 'wifi-redirect', 'destination' => '404'));
        header("location: " . WIFI_REDIRECT_URL);
        exit();
    }
    //No post ID on query
    //    removido  && !isset($_GET['shared']) cuidado bypass
    if (!isset($_GET['shared']) && !array_get($_SESSION, 'user.stayTimeReconnection')) {
        $log->debug('user is not in bypass');
        //Check if error code on query
        if (isset($_GET['error_code'])) {
            $log->debug('Error code returned from facebook');
            //If error code is 4201, user cancelled
            if ($_GET['error_code'] == '4201') {
                //Front end purposes
                $userCanceled = true;
                $log->warning('FACEBOOK: User canceled share');
                //if all is OK but post ID maybe an error from facebook, let user pass
            } elseif (isset($_GET['u'])) {
                //Redirect to wifi with all OK
                $redirectUrl = SECURE_BASE_PATH . $urlTree['stay-wifi-redirect'] . '/?i=' . $hotel_id . '&PHPSESSID=' . session_id() . '&action=skip';
                header("location: $redirectUrl");
                exit();
            }
            //All not OK, show try again message
            $allOk = false;
        } else {
            //Hotelinking user identified
            if (isset($_GET['u']) && isset($_SESSION['user']['facebook_id'])) {
                $urlInvite = SECURE_BASE_PATH . $urlTree['login'];

                //Tenemos usuario identificado de Facebook
                $identifiedUser = true;
                if (!$BrandHasBookingUrl) {
                    unifiRedirect($stayData, $stayTimeReconnection);
                }
            } else {
                unifiRedirect($stayData, $stayTimeReconnection);
            }
        }
    } else {
        // Post on FB done
        if (!empty($_GET['u'])) {
            //Save share
            include_once LIB . 'referral-share-actions.php';

            //Evitamos registrar 2 veces el share en caso de recargar la pÃ¡gina
            if (array_get($_GET, 'shared') && !array_get($_SESSION, 'shared')) {
                if (!isset($_GET['error_code'])) {
                    // Store user share in session
                    $_SESSION['shared'] = array_get($_GET, 'shared');
                    //Store share
                    guardarShareUsuario($_GET['u'], $_GET['i'], 'fb', null, 0, 3);

                    // $facebook_friends = !empty($_SESSION['user']['facebook_friends']) ? $_SESSION['user']['facebook_friends'] : NULL;
                    $facebook_friends = array_get($_SESSION, 'user.facebook_friends', 0);

                    //Send offer by email
                    if (getBrandProductActive($_SESSION['brandProducts'], 'wifi_offers')) {
                        // Create a session variable to tell connection history
                        // to send an email to this user with the offer for make share
                        $_SESSION['trigger_offer']['facebook_share'] = true;
                    }
                }
            }
        }
        unifiRedirect($stayData, $stayTimeReconnection);
    }
}

use UAParser\Parser;

function parseHeaders()
{
    $headers = array();
    $ua = array_get($_SERVER, 'HTTP_USER_AGENT');
    $parser = Parser::create();
    $result = $parser->parse($ua);
    //Curate header
    $headers['browser'] = $result->ua->family;
    $headers['browser_version'] = $result->ua->toVersion();
    $headers['os'] = $result->os->family;
    $headers['os_version'] = $result->os->toVersion();
    $headers['device_family'] = $result->device->family;
    $headers['device_brand'] = $result->device->brand;
    $headers['device_model'] = $result->device->model;
    $headers['browser_lang'] = array_get($_SERVER, 'HTTP_ACCEPT_LANGUAGE');
    return $headers;
}

if (
    array_get($wifi_provider, 'name') == 'mikrotik_belive' &&
    // (empty($_SESSION['mac']) || empty($_SESSION['card_id']) || empty($_SESSION['user']['email']))
    (empty($_SESSION['mac']) || empty($_SESSION['user']['email']))
) {
    $log->info('Redirecting user to wifi url in belive mikrotik since not have all info', [
        "mac" => $_SESSION['mac'],
        "card_id" => $_SESSION['card_id'],
        "mail" => $_SESSION['user']['email']
    ]);

    header("Location: " . WIFI_REDIRECT_URL);
    exit();
}

//connection history
if ($_POST || $unifiOk || ($stayTimeReconnection && !$_SESSION['bypassRoomMissing'])) {

    /*Unifi needs to send login information by CURL, so only if the wifi_provider
    is distinct to unifi we will set trigge_integretion to true in order to bypass the user*/
    if (array_get($_SESSION, 'wifi_provider.name') != 'unifi' && array_get($_SESSION, 'wifi_provider.name') != 'ibrowse') {
        $trigger_integration = true;
    }
    $roomNumber = $_SESSION['roomNumber'] = !empty($_POST['roomNumber']) ? $_POST['roomNumber'] : array_get($_SESSION, 'portal_pro_user.room_number', array_get($_SESSION, 'roomNumber'));

    $hotelAccessCodes = strlen(array_get($_SESSION, 'hotel_access_codes', '')) ? array_get($_SESSION, 'hotel_access_codes') : array_get($_POST, 'hotelAccessCodes', null);
    if (getBrandProductActive(array_get($_SESSION, 'brandProducts'), 'require_room_num') && !getBrandProductActive(array_get($_SESSION, 'brandProducts'), 'portal_pro') && ((!$_SESSION['brand_is_not_hotel'] && $_SESSION['customer'] && $hotel_room_list != null) || ( (!$_SESSION['customer'] || $_SESSION['brand_is_not_hotel']) && $hotel_access_room_list != null))) {
        if (!$roomNumber && !$hotelAccessCodes) {
            $redirectUrl = SECURE_BASE_PATH . $urlTree['stay-wifi-redirect'] . '/?i=' . $hotel_id . '&PHPSESSID=' . session_id() . '&action=skip';
            $log->warning('No valid room number or access code present', ['session' => $_SESSION]);

            //if its a $stayTimeReconnection (bypass) but the session roomNumber is empty then set bypassRoomMissing , so next time it will ask the user for the room_number (or access code)
            $_SESSION['bypassRoomMissing'] = true;
            header("location: $redirectUrl");
            exit();
        }
    }
    // Get hotel_id from session
    $hotel_id = array_get($_SESSION, 'hotel.id', null);

    // If hotel_id exists
    if (!is_null($hotel_id) && !$user_bypassed) {
        $brand_id = $brand_id ?? getHotelBrand($hotel_id)['id'] ?? 0;

        // Get list of paid products the hotel has subscribed to
        // We need to know if user is an hotel customer or a guest
        // If he is a guest there are some emails might not have to send
        $urlWebservice = SECURE_BASE_PATH . LIB . 'webservices/emails-webservice.php';

        // Set up cUrl
        $curl = new Curl\Curl();
        $curl->setOpt(CURLOPT_FRESH_CONNECT, true);
        $curl->setOpt(CURLOPT_TIMEOUT_MS, 5000);

        $nonCustomerChecks = getHotelNonCustomerChecks($hotel_id);

        // Create an array with common data for all emails
        $email_data = array(
            'brandProducts'     => array_get($_SESSION, 'brandProducts'),
            'id_hotel'          => $hotel_id,
            'guidHotel'         => null,
            'id_user'           => array_get($_SESSION, 'user.id', null),
            'brand_id'          => $brand_id,
            'brand_parent_id'   => array_get($_SESSION, 'hotel.parent_id'),
            'name'              => array_get($_SESSION, 'user.name', null),
            'email'             => array_get($_SESSION, 'user.email', null),
            'lang'              => array_get($_SESSION, 'user.lang', null),
            'birthday'          => array_get($_SESSION, 'user.birthday', null),
            'id_room'           => $roomNumber ? $roomNumber : $hotelAccessCodes,
            'stayTime'          => array_get($_SESSION, 'stayTime', 7),
            'customerType'      => array_get($_SESSION, 'customer'),
            'checkout_date'     => array_get($_SESSION, 'portal_pro_user.check_out')
        );

        // Check if the code inserted by user is an access code
        if ($hotelAccessCodes || !array_get($_SESSION, 'customer')) {
            $email_data['nonCustomerOptions'] = $nonCustomerChecks;
        }
        $wifi_offer = getActiveWifiOffer($brand_id, array_get($_SESSION, 'customer'), array_get($email_data, 'user.lang'));

        // get the condition that triggers the offer
        $wifi_offer_condition = array_get($wifi_offer, 'condition');

        // Check if there is a offer for some action and that it has been done, if it was, declare action
        if (array_get($_SESSION, "trigger_offer.$wifi_offer_condition")) {
            $email_data['action'] = $wifi_offer_condition;
            // Unset the variable to prevent resend the email
            unset($_SESSION['trigger_offer']);
        } else {
            $email_data['action'] = 'login';
        }

        $log->info('sending info to emails webservice', $email_data);

        $curl->post($urlWebservice, $email_data);
        if ($curl->curl_error_message) {
            $log->error("curl error in emails web service", [$curl->curl_error_message, $curl->http_status_code]);
        }
    }

    //if wifi provider is unify then do redirect for curl
    $wifi_provider = $_SESSION['wifi_provider'];

    //if wifi provider is unifi send curl
    if ($wifi_provider['name'] == 'unifi') {
        $wifi_hotel = $_SESSION['wifi_hotel'];

        //load unifi integration file
        include_once RUTA_DIR . LIB . 'wifi_integrations' . DS . $wifi_provider['name'] . '.php';

        //sendAuthorization to unifi
        $unifi_result = sendAuthorization($wifi_hotel);

        if (array_has($unifi_result, 'error')) {

            // Log error
            $log->warning('Unifi', array('message' => array_get($unifi_result, 'error')));
            $ok = array(false, '4071', 20000);
            $trigger_integration = false;
        } else {
            $trigger_integration = true;
        }
    } elseif (array_get($wifi_provider, 'name') == "ibrowse") {

        // Load iBrowse integration file
        include_once RUTA_DIR . LIB . 'wifi_integrations' . DS . $wifi_provider['name'] . '.php';

        // Check available authentication
        $ibrowseAvailable = authAvailable($_SESSION['wifi_hotel']);

        // If check has no errors (not success or not code 200)
        if (array_get($ibrowseAvailable, 'error') == 0) {
            // If active is true and authenticated false (it means we can login the device)
            if (array_get($ibrowseAvailable, 'active') && array_get($ibrowseAvailable, 'authenticated') === false) {

                // Send auth request to iBrowse
                $ibrowse_result = sendAuth($_SESSION['user']['id'], $_SESSION['wifi_hotel']);

                // If there's an error
                if (array_get($ibrowse_result, 'status') == "error") {
                    // Show not available internet
                    $ok = array(false, '4071', 20000);
                    $trigger_integration = false;
                } else
                // If no errors, redirect to URL
                {
                    header("location: " . WIFI_REDIRECT_URL);
                    $trigger_integration = true;
                }
            } elseif (array_get($ibrowseAvailable, 'active') === false && array_get($ibrowseAvailable, 'authenticated') === false)
            // If active and authenticated are false (it means device is not connected to network)
            {
                // We cant authenticate the device
                // Show not available internet
                $ok = array(false, '4071', 20000);
                $trigger_integration = false;
            } else
            // Else, both variables are true (It means the device is already authenticated)
            {

                // Create redirection to URL
                header("location: " . WIFI_REDIRECT_URL);
                $trigger_integration = false;
            }
        } else
        // If there is an error
        {
            // Show not available internet
            $ok = array(false, '4071', 20000);
            $trigger_integration = false;
        }
    } elseif (array_get($wifi_provider, 'name') == 'tp_link') {

        //load unifi integration file
        include_once RUTA_DIR . LIB . 'wifi_integrations' . DS . $wifi_provider['name'] . '.php';

        $wifi_hotel = $_SESSION['wifi_hotel'];

        $tp_link_result = authClientTpLink($wifi_hotel);

        if (array_get($tp_link_result, 'error', false)) {

            // Log error
            $log->warning('TP-Link', array('message' => array_get($tp_link_result, 'error')));
            $ok = array(false, '4071', 20000);
            $trigger_integration = false;
        } else {
            $trigger_integration = true;
        }
    }
    //if wifi provider is tplink_omada send curl
    elseif (array_get($wifi_provider, 'name') == 'tplink_omada') {

        //load tplink_omada integration file
        include_once RUTA_DIR . LIB . 'wifi_integrations' . DS . $wifi_provider['name'] . '.php';

        $wifi_hotel = $_SESSION['wifi_hotel'];

        $tplink_omada_result = authClient_tplink_omada($wifi_hotel);

        if (array_get($tplink_omada_result, 'error', false)) {

            // Log error
            $log->warning('tplink_omada', array('message' => array_get($tplink_omada_result, 'error')));
            $ok = array(false, '4071', 20000);
            $trigger_integration = false;
        } else {
            $trigger_integration = true;
        }
    } elseif (array_get($wifi_provider, 'name') == 'huawei_imaster_nce_campus') {

        include_once RUTA_DIR . LIB . 'wifi_integrations' . DS . $wifi_provider['name'] . '.php';
        $authResult = authHuaweiImasterNceCampus();

        if (array_get($authResult, 'errcode')) {
            // Log error
            $log->error("Error authorizing huawei_imaster_nce_campus", [$authResult]);
            $ok = array(false, '4071', 20000);
            $trigger_integration = false;
        } else {
            $trigger_integration = true;
        }
    } else {
        $trigger_integration = true;
    }

    //trackeamos el numero de veces que se intenta la integracion con el hotspot en la session
    // si es mas de 5 veces paramos los intentos y pintamos un error
    if ($trigger_integration) {
        if (array_has($_SESSION, 'triggered_hotspot')) {
            $_SESSION['triggered_hotspot']++;
        } else {
            $_SESSION['triggered_hotspot'] = 1;
        }
        if ($_SESSION['triggered_hotspot'] > MAX_HOTSPOT_TRIES) {
            $trigger_integration = false;
            $logOp->error('triggered hotspot too many times in session', $_SESSION);
            $ok = array(false, '4071', 20000);
            // exit;
        }

        if (isset($_POST['stuckedBypassRetry'])) {
            $log->info("User stucked on bypass tries to reconnect to Internet", [$_SESSION]);
        }
    }

    //override room_id when user comes from bypass
    if ($user_bypassed) {
        //if user is bypassed but the room was missing, keep the one from POST, else set room to 'bypass'
        if (!$_SESSION['bypassRoomMissing']) {
            $roomNumber = 'Bypass';
        }
    }

    if (!!array_get($_SESSION, 'user.id') && array_has($_SESSION, 'hotel.id')) {
        $_SESSION['user']['id'] = (int) array_get($_SESSION, 'user.id');
        $user_info = array(
            'room_id' => $roomNumber && strlen($roomNumber) ? $roomNumber : $hotelAccessCodes,
            'user_id' => array_get($_SESSION, 'user.id', null),
            'brand_id' => $brand_id,
            'hotel_id' => array_get($_SESSION, 'hotel.id', null),
            'source' => array_get($_SESSION, 'user.source', null),
            'mac' => array_get($_SESSION, 'mac', null),
            'brandProducts' => array_get($_SESSION, 'brandProducts', null),
            'user' => array_get($_SESSION, 'user', null),
            'customer' => array_get($_SESSION, 'customer', null),
            'headers' => parseHeaders(),
        );

        $_SESSION['user']['stayTimeReconnection'] = true;
        $_SESSION['regularUser'] = $_SESSION['user'];


        if (!array_get($_SESSION, 'connectionHistorySent')) {
            $log->info('sending user info to connection history', $user_info);

            //since we timeout the curl after 100ms there is always a timeout error from the curl
            //but we don't care because we do a fire and forget
            $curl = new Curl\Curl();
            $curl->setOpt(CURLOPT_FRESH_CONNECT, true);
            $curl->setOpt(CURLOPT_TIMEOUT_MS, 5000);
            $curl->setOpt(CURLOPT_RETURNTRANSFER, true);
            $curl->post(SECURE_BASE_PATH . LIB . 'webservices/connection-history.php', $user_info);
            $_SESSION['connectionHistorySent'] = true;

            if ($curl->curl_error_message) {
                $_SESSION['connectionHistorySent'] = false;
                $log->error("curl error in connection-history", [$curl->curl_error_message, $curl->http_status_code]);
            }
        }

        $user_info['user']['is_client'] = array_get($_SESSION, 'customer', null);

        $userConnectedPayload = [
            "brand" => [
                "id" => (int)$_SESSION['brandID']
            ],
            "roomID" => array_get($user_info, 'room_id'),
            "user" => array_get($user_info, 'user'),
            "device" => [
                "mac" => array_get($user_info, 'mac'),
                "userAgent" => array_get($_SERVER, 'HTTP_USER_AGENT'),
                "acceptLanguage" => array_get($_SERVER, 'HTTP_ACCEPT_LANGUAGE')
            ],
            "pms_reservation" => array_get($_SESSION, 'portal_pro_user.pms_reservation')
        ];
        if (!array_get($_SESSION, 'eventsEmitted.user_connected')) {
            emitEvent('Users', 'user_connected', $userConnectedPayload, [], '2.0.0');
            $_SESSION['eventsEmitted']['user_connected'] = true;
        }
    } else {
        $log->warning("Did not call connection history since no user or hotel in session");
    }
}
