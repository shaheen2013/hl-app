
<?php
// Basic libraries
include_once 'librerias.php';
include_once APP . 'Services/Connections/ApiGatewayConnection.php';

// include_once RUTA_DIR . LIB . 'check_access.php';
// checkIpAccess('ch-pos', $_SERVER['REMOTE_ADDR']);

// Disable indexcontrolval to be accesible as a webservice
define("INDEXCONTROLVAL", "1");

// Receive a post with some actions to perform & user_id and $hotel_id or die
if (empty($_POST['user']) || empty($_POST['hotel_id'])) {
    $log->error('Access to chain loyalty email webservice without all data needed, exiting', [$_POST]);
    exit;
}

include_once RUTA_DIR . LIB . 'hotelinking_emails.php';
include_once RUTA_DIR . LIB . 'emails-webservice-helpers.php';

$log->debug('starting chain loyalty ws');

$user = array_get($_POST, 'user', null);
$hotel_id = array_get($_POST, 'hotel_id', null);
$brandID = array_get($_POST, 'brand_id', null);
$offer = null;
//check if hotel has a chain loyalty offer active

if ($user['num_visits'] > 1) {
    $offer = getChainLoyaltyOffer($brandID, $user['lang'], $user['num_visits']);
    $nextOffer = getChainLoyaltyOffer($brandID, $user['lang'], $user['num_visits'] + 1);
}

//if offer
//TODO: should refactor this using a new token system
if (!empty($offer)) {
    include_once RUTA_DIR . LIB . 'send_wifi_offer.php';

    $log->debug('chain loyalty offer is ', $offer);
    $user_id = $user['id'];

    // Check if user is elegible for an offer
    try {
        //3 is the id of origen_cupon needed to integrate with old offer system
        $status = setOffer($user_id, $hotel_id, $offer[0], 3, true);

    } catch (Exception $e) {
        $log->error($e->getMessage());
    }

    //Send offer email always if there is offer and user is elegible
    if (array_get($status, 'token')) {
        createChainLoyaltyOfferEmail($hotel_id, $user_id, $status, $offer[0], $nextOffer[0]);
    }
} else {
    // exit
    $log->debug('Hotel has no chain loyalty offer selected : exiting');
    exit;
}

function getChainLoyaltyOffer($brandID, $lang, $numVisits)
{
    try {

        $gateway = new ApiGatewayConnection();

        return json_decode($gateway->sendRequest([
            "lang" => $lang, 
            "number_visits" => $numVisits
        ], HOTELINKING_ENDPOINT . 'brand/' . $brandID . '/loyalty-offers', 'GET'), true);

    } catch (Exception $e) {
        global $log;
        $log->error("Error getting  Offers on Loyalty WS", [$e]);

        exit;
    }
}

function createChainLoyaltyOfferEmail($hotel_id, $user_id, $status, $offer, $nextOffer)
{
    global $urlTree;
    global $log;

    $con = conectar(2);
    $offer_name = mysqli_real_escape_string($con, array_get($offer, 'offer.title'));
    $next_offer_name = mysqli_real_escape_string($con, array_get($nextOffer, 'offer.title'));
    $img = array_get($offer, 'offer.image');
    $url = SECURE_BASE_PATH . $urlTree['create-offer-from-token'] . '/?tk=' . $status['token'];
    $sql = "INSERT INTO chain_loyalty_offers (user_id, hotel_id, offer_name, offer_image, send_date, token, token_type, state, created_at, next_offer)
            VALUES ('$user_id', $hotel_id, '$offer_name', '$img', '" . date('Y-m-d') . "', '$url', '{$status['type']}','{$status['status']}', NOW(), '$next_offer_name')
            ON DUPLICATE KEY UPDATE state = VALUES(state)";
    $log->debug($sql);
    $log->debug("inserting ChainLoyaltyOfferEmail in Email Platform");
    escritura($sql, $con);
}
