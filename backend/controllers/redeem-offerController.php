<?php //Miramos si esta definida la variable de control de index.php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}

include_once LIB . 'obtenerdatosHotel.php';     //para operaciones con GUID hotel
include_once LIB . 'obtenerDatosCadena.php';    //para operaciones con GUID cadena
include_once LIB . 'obtenerDatosUsuario.php';   //para operaciones con GUID usuario
include_once LIB . 'affilired.php';             //Affilired
include_once LIB . 'cookies.php';               //cookies


//Assign GET variables and init

//En la iteración siguiente los motores de reserva que usan directamente la web del cliente
//Tienen que ser añadidos. Si no hay URL en booking_engines se ha de coger la URL de website de reservas
//Del cliente.

//hlhid: guid del hotel
//hlcid: guid de la cadena
//hlpc: promocode Hotelinking

$isOk = false; // Tracks validation status: false = invalid, 'somewhat' = hotel ok but promo invalid, true = all valid

(!empty($_GET['hlpc']) ? $promoCode = $_GET['hlpc'] : $promoCode = false);

// Log incoming request
$log->info('Redeem offer request received', [
    'hlpc' => $promoCode,
    'hlhid' => $_GET['hlhid'] ?? null,
    'hlcid' => $_GET['hlcid'] ?? null,
    'hltr' => $_GET['hltr'] ?? null,
    'hlui' => $_GET['hlui'] ?? null,
    'all_params' => $_GET
]);

if (!empty($_GET['hlhid'])) {
    $id = obtenerIdHotelGUID($_GET['hlhid']);
    $type = 'h'; //tipo hotel
} else if (!empty($_GET['hlcid'])) {
    $id = obtenerIdCadenaGUID($_GET['hlcid']);
    $type = 'c'; //tipo cadena
} else {
    $id = false;
    $type = false;
}

if ($id) {
    $log->info('Hotel/Chain ID resolved', ['id' => $id, 'type' => $type]);
    
    //GET hotel data
    if ($type == 'h') {
        $info = ObtainHotelInfo($id);
    } else if ($type == 'c') {
        $info = ObtainChainInfo($id);
    }
    
    $log->info('Hotel/Chain info obtained', ['has_info' => !empty($info), 'info' => $info ?? null]);

    $website = '';
    if ($type == 'h' && !empty($info)) {

        $isBirthdayOffer = (!empty($_GET['hltr']) && $_GET['hltr'] == 'hotelinking_birthday_email');

        if ($isBirthdayOffer) {

            $userLang = !empty($_GET['lang']) ? $_GET['lang'] : 'en';
            if (!empty($_GET['hlui'])) {
                $con = conectar(1);
                $user_id = mysqli_real_escape_string($con, $_GET['hlui']);
                $userSql = "SELECT lang FROM users WHERE id = '$user_id'";
                $userData = lectura($userSql, $con, true);
                if (!empty($userData['lang'])) {
                    $userLang = $userData['lang'];
                }
            }

            if (!empty($info['brand_id'])) {
                $website = getHotelWebsiteUrl($info['brand_id'], $userLang);
            }

            if (empty($website) && !empty($info['booking_engine'])) {
                $con = conectar(1);
                $booking_engine_id = mysqli_real_escape_string($con, $info['booking_engine']);
                $sql = "SELECT url FROM booking_engines WHERE id = '" . $booking_engine_id . "' LIMIT 1";
                $bookingEngineData = lectura($sql, $con, true);
                if (!empty($bookingEngineData['url'])) {
                    $website = $bookingEngineData['url'];
                }
            }
        } else {
            if (!empty($_SESSION['hotel']['brand_id']) && !empty($_SESSION['userLang'])) {
                $website = getHotelWebsiteUrl($_SESSION['hotel']['brand_id'], $_SESSION['userLang']);
            }
        }

        if (empty($website) && !empty($info['website'])) {
            $website = $info['website'];
        }
    }

    $param = !empty($info['getParam']) ? $info['getParam'] : (isset($info['promo_code_param']) ? $info['promo_code_param'] : false);

    $log->info('Website and promo validation', [
        'has_website' => !empty($website),
        'website' => $website,
        'has_promo' => !empty($promoCode),
        'promo' => $promoCode
    ]);

    // Validar que tenemos website y promocode
    if (!empty($website) && $promoCode) {
            //Obtain promoCode info - searches in oferta_referral_token table by token
            // The token (hlpc) is stored in user_cupones.voucher and linked to oferta_referral_token.token
            $promoResponse = ObtainPromoCodeDataRO($promoCode, $id, $type, $userLang);
            
            $log->info('Promo code validation result', [
                'promo_code' => $promoCode,
                'response_code' => $promoResponse['code'] ?? null,
                'response' => $promoResponse
            ]);
            
            //If code is valid
            if ($promoResponse['code'] === '200') {

                //Hotel and Promo are OK - $isOk = true means valid offer found
                $isOk = true;
                
                // Get username from user_cupones table where voucher = $promoCode (the token)
                $username = ObtainUserName($promoCode);
                
                $log->info('Valid offer found', [
                    'username' => $username,
                    'offer_name' => $promoResponse['offerName'] ?? null,
                    'booking_engine_code' => $promoResponse['bookingEngineCode'] ?? null
                ]);
                if (isset($_GET['cid'])) {
                    //si hay un id de cookie hay que recomponerla
                    //recupera todos los datos de la cookie
                    $cookie = getAllCookieById($_GET['cid']);
                    //hay que volverla a crear
                    $referrerGuid = obtenerGUIDUsuarioId($cookie['referrer_id']);
                    $hotelGuid = obtenerGUIDHotel($cookie['hotel_id']);
                    $cadenaGuid = obtenerGUIDCadena($cookie['cadena_id']);
                    $token = $cookie['cookie_id'];
                    //genera array
                    $cookieArray = createCookieArray($referrerGuid, $hotelGuid, $token, $cookie['id'], $cadenaGuid);
                    //crea la cookie
                    createCookie(ENV, $cookieArray, $cookie['hotel_id'], $cookie['referrer_id'], $cookie['cadena_id'], $cookie['id']);
					$log->info("cookie creada :" , [$cookie]);

                }
                // FIX: For birthday offers (and all offers), use the unique promo code
                // If booking_engine_code exists and is different, it's for reference only
                // The actual code to send to booking engine should be the unique token
                $codeForBookingEngine = !empty($promoResponse['bookingEngineCode']) ? $promoResponse['bookingEngineCode'] : $promoCode;
                $bookingEngineUrl = createUrl($website, $param, $codeForBookingEngine, $promoCode);
                
                $log->info('Booking engine URL created', [
                    'code_for_booking_engine' => $codeForBookingEngine,
                    'param' => $param,
                    'url' => $bookingEngineUrl
                ]);

                // Add HLTI parameters
                if(!empty($_GET['hltr'])){
                    $query_array = [
                        'hlho' => !empty($_GET['hlho']) ? $_GET['hlho'] : NULL,
                        'hlre' => !empty($_GET['hlre']) ? $_GET['hlre'] : NULL,
                        'hlch' => !empty($_GET['hlch']) ? $_GET['hlch'] : NULL,
                        'hltr' => !empty($_GET['hltr']) ? $_GET['hltr'] : NULL,
                        'hlui' => !empty($_GET['hlui']) ? $_GET['hlui'] : NULL
                    ];
                    // Construye la URL final agregando parámetros de tracking de Hotelinking
                    $finalUrl = $bookingEngineUrl . '&' . http_build_query($query_array);
                    
                    $log->info('Redirecting to booking engine', [
                        'final_url' => $finalUrl,
                        'tracking_params' => $query_array
                    ]);
                    
                    // Redirige al usuario a la página del hotel con promocode y parámetros de tracking
                    header("Location: " . $finalUrl);
                    die();
                }
            } else {
                //Hotel is OK, but promo not - $isOk = 'somewhat' means hotel found but promo invalid
                $isOk = 'somewhat';
                
                $log->warning('Invalid promo code for valid hotel', [
                    'promo_code' => $promoCode,
                    'hotel_id' => $id,
                    'type' => $type,
                    'response' => $promoResponse
                ]);
            }
    }
} else {
    //Hotel not exists - $isOk = false means hotel/chain ID not found
    $isOk = false;
    
    $log->warning('Hotel/Chain not found', [
        'hlhid' => $_GET['hlhid'] ?? null,
        'hlcid' => $_GET['hlcid'] ?? null
    ]);
}

$log->info('Redeem offer final status', [
    'isOk' => $isOk,
    'status_meaning' => $isOk === true ? 'Valid offer' : ($isOk === 'somewhat' ? 'Hotel valid, promo invalid' : 'Hotel not found or invalid')
]);

//Crea la URL del hotel
function createUrl($url, $param, $bookingEngineCode, $promoCode)
{
    $query = parse_url($url, PHP_URL_QUERY);
    $bookingEngineCode = urlencode($bookingEngineCode);
    if ($query) {
        $url .= '&' . $param . '=' . $bookingEngineCode . '&hlpc=' . $promoCode . '&utm_source=hotelinking&utm_medium=email&utm_campaign=hotelinking_guest_loyalty';
    } else {
        $url .= '?' . $param . '=' . $bookingEngineCode . '&hlpc=' . $promoCode . '&utm_source=hotelinking&utm_medium=email&utm_campaign=hotelinking_guest_loyalty';
    }
    return $url;
}

?>