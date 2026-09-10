<?php //Miramos si esta definida la variable de control de index.php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}

//Contenido solo visible si logueado
include LIB . 'logueado.php';
hotelStaffLanding();// Si no esta logueado lo manda a la landing

include_once LIB . 'obtenerdatosHotel.php';
include_once LIB . 'referral-goals-actions.php';
include_once LIB . 'get_hotel_wifi_permissions_and_offers.php';
include_once RUTA_DIR . LIB . 'portal_pro.php';
require_once __DIR__ . '/../src/Services/Connections/ApiGatewayConnection.php';

// For front purposes
$currentPage = 'offer-management';
$currentSubPage = 'goals-management';

$hotel_id = $_SESSION['h_logueado'];
$brandID = $_SESSION['loggedBrandID'] ?? 0;

if (isset($_POST['offer_wifi_action'])) {
    switch ($_POST['offer_wifi_action']) {
        case 'insert':
            $offerDefault = getDefaultOfferInsertData();
            $offersWifiData = [];
            $datesFail = false;
            $defaultFail = false;
            $overlapDatesFail = false;
            $typeFail = false;
            $offers = count($_POST['select_offer'] ?? 0);

            for ($i = 0; $i < $offers; $i ++) {
                $accommodated = !empty($_POST['accommodated'][$i]);
                $nonAccommodated = !empty($_POST['non_accommodated'][$i]);
                $default = $_POST['default'][$i] ?? "";
                $index = $_POST['index'][$i] ?? 0;
                $isDefault = !empty($default);
                $id = (int)$_POST['id'][$i] ?? $offerDefault['id'];
                $validFrom = $_POST['valid_from'][$i] ?? $offerDefault['valid_from'];
                $validTo = $_POST['valid_to'][$i] ?? $offerDefault['valid_to'];

                if (!empty($validFrom)) {
                    $validFrom = date_create_from_format('d/m/Y', $validFrom)->format('Y-m-d');
                }

                if (!empty($validTo)) {
                    $validTo = date_create_from_format('d/m/Y', $validTo)->format('Y-m-d');
                }

                if (!empty($validFrom) && !empty($validTo)) {
                    $isDefault = false;
                }

                if ($validFrom > $validTo) {
                    $datesFail = true;
                }

                if (array_get($_SESSION['permisos'], 'not_hotel')) {
                    $accommodated = true;
                }

                if (!$accommodated && !$nonAccommodated) {
                    $typeFail = true;
                }

                if (!$isDefault && (empty($validFrom) || empty($validTo))) {
                    $defaultFail = true;
                }

                $offerWifiData = [
                    'offer_id'          => (int)$_POST['select_offer'][$i] ?? $offerDefault['offer_id'],
                    'accommodated'      => $accommodated,
                    'non_accommodated'  => $nonAccommodated,
                    'condition'         => $_POST['select_condition'][$i] ?? $offerDefault['condition'],
                    'offer_type'        => $_POST['select_type'][$i] ?? $offerDefault['offer_type'],
                    'period'            => (int)$_POST['period'][$i] ?? $offerDefault['period'],
                    'valid_from'        => $validFrom,
                    'valid_to'          => $validTo,
                    'is_default'        => $isDefault
                ];

                if ($id) {
                    $offerWifiData['id'] = $id;
                }

                $offersWifiData[] = $offerWifiData;
            }

            if ($typeFail) {
                $ok = [false, '4093'];
                break;
            }

            if ($datesFail) {
                $ok = [false, '4036'];
                break;
            }

            if($defaultFail) {
                $ok = [false, '4092'];
                break;
            }

            $response = setBrandOffersWifi($brandID, $offersWifiData);
            $ok = [true, '2007'];

            if (data_get($response, 'error')) {
                $ok = [false, '4065'];
            }

            if (data_get($response, 'message') == "Offer exists") {
                $ok = [false, '4094'];
            }

            if (data_get($response, 'message') == "More than one default") {
                $ok = [false, '4095'];
            }

            break;

        case 'delete':
            $offerWifiID = $_POST['id'] ?? null;
            $result = is_numeric($offerWifiID);
            if ($offerWifiID) {
                $response = deleteBrandOffersWifi($offerWifiID, $brandID);
                $result = $response == 200;
            }

            header('Content-Type: application/json; charset=UTF-8');
            echo json_encode([
                'deleted' => $result
            ]);
            exit;
            break;
    }
}

//Ha cambiado el wifi provider
if (isset($_POST['wifiProvider'])) {
    guardarWifiProvider($hotel_id, $_POST['wifiProvider']);
    $ok = array(true, '2007');
}

if (isset($_POST['brandAccesType'])) {
    $brandAccessType = $_POST['brandAccesType'];
    $codes = ["codes" => array_values(
        array_filter(
            array_map(
                'trim',
                explode(",", $_POST[$brandAccessType]['textarea'])
            ),
            'strlen'
        )
    )];

    $codes = $brandAccessType == "premium" ?
        array_merge($codes, ["byParent" => isset($_POST['premiumCodesByChain'])]) :
        $codes;

    try {
        $gateway = new ApiGatewayConnection();
        $gateway->sendRequest([
            "type" => $brandAccessType,
            "codes" => $codes
        ], HOTELINKING_ENDPOINT . 'brands/' . $brandID . '/access', 'PUT');

        deleteCacheByKey('brand_access' . $brandID . '_' . $brandAccessType);

        $ok = array(true, '2007');
    } catch (Exception $e) {
        $ok = array(false, '4065');
    }
}



if (!empty($_GET['newOffer'])) {
    createOffer('newOffer');
}

if (!empty($_GET['newStay'])) {
    createOffer('newStay');
}

if (!empty($_GET['offer'])) {
    //Viene de crear una oferta de Referral nueva y debemos asignarla al goal
    if (isset($_COOKIE['goal'])) {
        AsignOffer('goal', $hotel_id);
    }

    //Viene de crear una oferta de Referral nueva y debemos asignarla al stay
    if (isset($_COOKIE['stay'])) {
        AsignOffer('stay', $hotel_id);
    }
}

//Goals para el select de oferta pre-stay, stay, post-stay
$offersAvailable = getAvailableOffers($hotel_id);

//obtener los permisos del hotel
$permisosHotel = obtenerPermisosHotel($_SESSION['loggedBrandID']);

$hotelHasRoomRequire = array_get($permisosHotel, 'require_room_num') ? true : false;
$portalProActivated = array_get($permisosHotel, 'portal_pro') ? true : false;

//List of rooms for hotel if it has one
if ($hotelHasRoomRequire || $portalProActivated) {
    $gateway = new ApiGatewayConnection();

    $brandAccess = json_decode($gateway->sendRequest([], HOTELINKING_ENDPOINT . 'brands/' . $brandID . '/access', 'GET'));

    $roomAccess = getAccessByType($brandAccess, 'room');
    $guestAccess = getAccessByType($brandAccess, 'guest');
    $premiumAccess = getAccessByType($brandAccess, 'premium');
    $premiumByParent = getAccessByType($brandAccess, 'premium', 'byParent');

    $premiumEditable = array_get($_SESSION, 'c_logueado') || !$premiumByParent;

    // Get portalProConfig when have portalPro activated
    $portalProConfig = array_get($permisosHotel, 'portal_pro') ? getPortalProConfiguration($brandID) : [];
}

//Array con las ofertas de pre-stay, stay y post-stay
$ofertasStay = obtenerOfertasStay($hotel_id);

//Oferta asignadas al wifi
$brandOffersWifi = getBrandOffersWifi($brandID, $_SESSION['userLang']);
$defaultOfferConditions = getDefaultConditions();
$defaultOfferTypes = getDefaultOfferTypes();

//Get Iframe state
$showIframe = getIframeState($hotel_id, 'iframe');

//Get Landing Iframe state
$showLandingIframe = getIframeState($hotel_id, 'landing_iframe');
$urlWifiStay = obtenerWifiStayHotel($hotel_id);


// Get Wifi Providers
$wifiProviders = selectWifiProviders();

!empty($urlWifiStay['wifi_name']) ? $wifiForm = $urlWifiStay['wifi_name'] : $wifiForm = '';



/*
*
*Helpers
*
*/

function getAccessByType($brandAccess, $type, $field="codes")
{
    return data_get(
        data_get($brandAccess, 'codes'),
        $type . '.' . $field
    )  ?? [];
}

function AsignOffer($oferta, $hotel_id)
{
    $id_oferta = mysqli_real_escape_string(conectar(), $_GET['offer']);

    if ($oferta == 'goal') {
        $id_goal = mysqli_real_escape_string(conectar(), $_COOKIE[$oferta]);
        asignarGoalOferta($id_oferta, $id_goal);
    } else {
        $type = mysqli_real_escape_string(conectar(), $_COOKIE[$oferta]);
        guardarOfertaStay($id_oferta, $hotel_id, $type);
    }

    unset($_COOKIE[$oferta]);

    //Borramos la cookie
    setcookie($oferta, "", time() - 3600, '/', '', true, true);

    //Feedback
    $ok = array(true, '2007');
}

function createOffer($oferta)
{
    global $urlTree;

    if ($oferta == 'newStay' ? $id_goal = mysqli_real_escape_string(conectar(), $_GET[$oferta]) : $type = mysqli_real_escape_string(conectar(), $_GET[$oferta])) ;

    $_SESSION['offerMethod'] = 'ref';
    $_SESSION['offertype'] = 'ngr';

    //Guardamos una cookie con los datos de la oferta de referral para asignarle la oferta al goal un vez creada
    if ($oferta == 'newStay' ? setcookie('goal', $id_goal, 0, '/', '', true, true) : setcookie('stay', $type, 0, '/', '', true, true)) ;

    //Redireccionamos a crear offerta
    header('Location: /' . $urlTree['hotel-crear-detalle-oferta'] . '/');
}
