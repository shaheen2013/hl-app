<?php
//allow requests from anywhere
header('Access-Control-Allow-Origin: *');
define("INDEXCONTROLVAL", "1");

//Hotelinking mandatory libraries and methods
include_once 'librerias.php';
include_once RUTA_DIR . LIB . 'stage_helpers.php';
include_once RUTA_DIR . LIB . 'utils.php';
include_once RUTA_DIR . LIB . 'obtenerdatosHotel.php';
include_once RUTA_DIR . LIB . 'apiGateway.php';
include_once APP . 'Services/Connections/ApiGatewayConnection.php';

// TODO: Make this list dynamic, ex. query to DB to get this list
// List of ingored booking engines id, that we recieved trackings, but we don't have
$ignoredBeId = ['BHTT_2708', 'EDMIN_ESMEVILLAS', 'BDIN_NaN', 'ZFR_DG00029', 'BCTW_conventdelamissio', 'RB_aluasuitesatlantis', 'RB_aluajardintropical', 'BMIR_100376544'];

// This webservice accepts POST methods, TODO: change GET
if (!empty($_GET['v'])) {
    $params = json_decode(urldecode($_GET['v']), true);
} else {
    $log->error('STAGING BOOKING : No parameters received, exiting.', $_GET);
    exit;
}

$log->info('stage booking funnel', ['params' => $params]);


// Parse hotel_id and chain_id from cookie params
$params['hotel_id'] = parseUrlStringValue(array_get($params, 'hotel_id'), null);
$params['chain_id'] = parseUrlStringValue(array_get($params, 'chain_id'), null);
$params['brand_id'] = $params['brand_id'] ?? null;

// If hotel booking engine id is present try force this map
if (!empty($params['hotel_be_id'])) {
    $hotel = getHotelIdByBookingId($params['hotel_be_id']);
    if (array_get($hotel, 'hotel_id')) {
        $params['hotel_id'] = array_get($hotel, 'hotel_id');
        $params['chain_id'] = array_get($hotel, 'chain_id');
    } else {
        // Check if the hotel_be_id is in the ignored booking engine id array, if it is not in the list log the error
        if (!in_array($params['hotel_be_id'], $ignoredBeId)) {
            // Log error to fix the bookingEngine mapping in GTM
            $log->error('Booking engine Id doesnt exist', ['hotel_be_id' => $params['hotel_be_id']]);
        };
    }
}
// If is from pushtech and we do not have the hotelId and chainId, we map with accountKey
if (!empty($params['pushtech_account_id']) && empty($params['hotel_id']) && empty($params['chain_id'])) {
    include_once RUTA_DIR . LIB . 'hotelinking_integrations.php';
    $integrationBrands = getIntegrationBrandList('api', 'pushtech', $params['pushtech_account_id']);
    // Get first integrationBrand that bellogs to a hotel
    $integrationBrand = array_first($integrationBrands, function ($key, $integration_brand) use ($log) {
        $brandId = $integration_brand['brand_id'] ?? null;
        if ($brandId) {
            $brand = getBrandById($brandId);
            return !!($brand['hotel_id'] ?? false);
        }
        return false;
    });

    $brandId = $integrationBrand['brand_id'] ?? null;
    if ($brandId) {
        $params['brand_id'] = $brandId;
        // Get hotelId
        $brand = getBrandById($brandId);
        $params['hotel_id'] = $brand['hotel_id'] ?? null;

        // Get chainId
        $parentId = $brand['parent_id'] ?? null;
        if ($parentId) {
            $parent = getBrandById($parentId);
            $params['chain_id'] = $parent['chain_id'] ?? null;
        }
    } else {
        // Log error to fix the bookingEngine mapping in GTM
        $log->error('Pushtech account not mapped', [
            'payload' => $params
        ]);
    }
}

// get brand_id
$params['brand_id'] = $params['brand_id'] ?: array_get(getHotelBrand($params['hotel_id']), 'id');

if (array_get($params, 'fact') === 'booking_funnel'){
    stage($params);
}
//if this is a booking (i.e not a booking funnel action)
if (array_get($params, 'fact') == 'booking_transactions') {
    try {
        // Insert HLTI info into DB
        // If the user is from pushtech and not from hotelinking, we need to assign the booking to an hotel or chain
        // Check the chain id via the secret and assign the booking to the chain. Hotel id, is not available for sure,
        // If there is an hotel id already dont delete it, use it.
        $paramAmount = array_get($params, 'amount');
        if ($paramAmount) {
            $params['amount'] = tofloat($paramAmount);
        }

        // fallback in case param is checkin o checkout
        $checkin = $params['check_in'] ?? $params['checkin'];
        $checkout = $params['check_out'] ?? $params['checkout'];

        // Check if have check_in and check_out and parse it's format to Y-m-d if needed.
        $params['check_in'] = parseDateFormat($checkin, $params);
        $params['check_out'] = parseDateFormat($checkout, $params);

        // In case of no currency, set EUR as default
        $params['currency'] = !empty($params['currency']) ? $params['currency'] : 'EUR';

        // Check if the booking was already inserted
        if (!isBookingAlreadyInBookingsTable($params)) {
            // Case a new Booking
            $bookings_id = insertBooking($params);
            $brand_id = (int) array_get($params, 'brand_id');
            $user_id = [
                "user" => [
                    "id" => (int) array_get($params, 'user_id')
                ]
            ];

            $payload = [
                "brand" => [
                    "id" => $brand_id,
                ],
                "booking" => [
                    "amount" => array_get($params, 'amount'),
                    "currency" => array_get($params, 'currency'),
                    "transactionCode" => array_get($params, 'transaction'),
                    "checkIn" => array_get($params, 'check_in'),
                    "checkOut" => array_get($params, 'check_out'),
                ],
                "extraData" => [
                    "pushtech_account_id" => array_get($params, 'pushtech_account_id'),
                    "pushtech_campaign_id" => array_get($params, 'pushtech_campaign_id'),
                    "pushtech_user_id" => array_get($params, 'pushtech_user_id')
                ],
            ];

            // Add user id if exists
            array_get($params, 'user_id') ?? array_merge($payload, $user_id);

            // Emit event booking_created
            emitEvent('Bookings', 'booking_created', $payload, [], '2.0.2');

        } else {
            // Case booking already tracked
            $log->warning('Booking already tracked', ['params' => $params]);
        }
    } catch (Exception $e) {
        $log->error('Could not insert booking', ['params' => $params, 'error' => $e->getMessage()]);
        echo json_encode(['message' => false]);
        exit;
    }
}
echo json_encode(['message' => true]);
exit;

function getHotelIdByBookingId($bookingEngineID)
{
    global $log;
    $cacheName = "Hotel_And_Chain_IDs_from_BECode_$bookingEngineID";
    $cache = getFromCache($cacheName);
    if (!$cache) {
        $con = conectar(1);
        $bookingEngineID = mysqli_real_escape_string($con, $bookingEngineID);
        $sql = "SELECT 
                    `hotel_guid`.`id_hotel` as `hotel_id`, 
                    `cadena_hotel`.`id_cadena` as `chain_id`
                FROM `hotel_guid`
                LEFT JOIN `cadena_hotel` on `hotel_guid`.`id_hotel` = `cadena_hotel`.`id_hotel`
                WHERE FIND_IN_SET('$bookingEngineID', `affilired_hotel`)";

        $row = lectura($sql);
        $hotelId = $row['hotel_id'] ?? null;
        if ($row && $hotelId) {
            $tags = array("hotel_{$hotelId}_be_code");
            setToCache($cacheName, $row, 2592000, $tags); // 1 month
        }
    } else {
        $row = $cache->get();
    }

    return $row;
}

/**
 * Check if the booking is already in hotelinking bookings table
 *
 * @param $params
 */
function isBookingAlreadyInBookingsTable($params)
{
    global $log;

    // if no hotel_id and transaction_code and amount return true : dont continue
    if (!array_get($params, 'hotel_id') && !array_get($params, 'transaction_code') && !array_get($params, 'amount')) {
        $log->info('Stage booking: no hotel_id, transaction_code and amount', $params);
        return true;
    }

    $con = conectar(1); // 1 - Main replica

    // Construct and perform the query
    // Get params to make query
    $params = escapeArray($params, $con, false);
    $hotel_id = isset($params['hotel_id']) ? mysqli_real_escape_string($con, array_get($params, 'hotel_id')) : null;
    $transaction_code = isset($params['transaction']) ? mysqli_real_escape_string($con, array_get($params, 'transaction')) : null;
    $amount = isset($params['amount']) ? mysqli_real_escape_string($con, array_get($params, 'amount')) : null;

    // Query
    $sql = "SELECT
                count(1) AS `count`
            FROM `bookings`
            WHERE
                `hotel_id` = $hotel_id AND 
                `transaction_code` = '$transaction_code' AND 
                `amount` = $amount";

    // Perform lectura and return the check if that is a booking with the params in db
    $result = lectura($sql, $con);
    $count = array_get($result, 'count');
    return $count > 0;
}

function insertBooking($params)
{
    global $log;
    $con = conectar(0);
    $hotel_id = isset($params['hotel_id']) ? mysqli_real_escape_string($con, array_get($params, 'hotel_id')) : null;
    $chain_id = isset($params['chain_id']) ? mysqli_real_escape_string($con, array_get($params, 'chain_id')) : null;
    $user_id = isset($params['user_id']) ? mysqli_real_escape_string($con, array_get($params, 'user_id')) : null;
    $check_in = isset($params['check_in']) ? "'" . mysqli_real_escape_string($con, array_get($params, 'check_in')) . "'" : 'null'; // Format date to insert into mysql
    $check_out = isset($params['check_out']) ? "'" . mysqli_real_escape_string($con, array_get($params, 'check_out')) . "'" : 'null'; // Format date to insert into mysql
    $amount = isset($params['amount']) ? mysqli_real_escape_string($con, array_get($params, 'amount')) : null;
    $currency = isset($params['currency']) ? mysqli_real_escape_string($con, array_get($params, 'currency')) : null;
    $transaction_code = isset($params['transaction']) ? mysqli_real_escape_string($con, array_get($params, 'transaction')) : null;
    $booking_engine_code = isset($params['hotel_be_id']) ? mysqli_real_escape_string($con, array_get($params, 'hotel_be_id')) : null;
    $promocode = isset($params['promocode']) ? mysqli_real_escape_string($con, array_get($params, 'promocode')) : null;
    $source = isset($params['source']) ? mysqli_real_escape_string($con, array_get($params, 'source')) : null;
    $source_action = isset($params['source_action']) ? mysqli_real_escape_string($con, array_get($params, 'source_action')) : null;
    $token = isset($params['token']) ? mysqli_real_escape_string($con, array_get($params, 'token')) : null;
    $pushtech_user_id = isset($params['pushtech_user_id']) ? mysqli_real_escape_string($con, array_get($params, 'pushtech_user_id')) : null;
    $pushtech_account_id = isset($params['pushtech_account_id']) ? mysqli_real_escape_string($con, array_get($params, 'pushtech_account_id')) : null;
    $pushtech_campaign_id = isset($params['pushtech_campaign_id']) ? mysqli_real_escape_string($con, array_get($params, 'pushtech_campaign_id')) : null;

    $created_at = date("Y-m-d H:i:s");

    $sql = "INSERT IGNORE INTO `bookings` (
                `hotel_id`,
                `chain_id`,
                `user_id`,
                `amount`,
                `check_in`,
                `check_out`,
                `currency`,
                `transaction_code`,
                `booking_engine_code`,
                `promocode`,
                `source`,
                `source_action`,
                `token`,
                `pushtech_user_id`,
                `pushtech_account_id`,
                `pushtech_campaign_id`,
                `created_at`
            ) VALUES (
                '$hotel_id',
                '$chain_id',
                '$user_id',
                '$amount',
                $check_in,
                $check_out,
                '$currency',
                '$transaction_code',
                '$booking_engine_code',
                '$promocode',
                '$source',
                '$source_action',
                '$token',
                '$pushtech_user_id',
                '$pushtech_account_id',
                '$pushtech_campaign_id',
                '$created_at'
            )
        ";

    return escritura($sql, $con);
}

function parseUrlStringValue($string, $default = 'NULL')
{
    return (!$string || $string == 'undefined') ? $default : $string;
}

function stage($params)
{
    global $log;
    include_once RUTA_DIR . LIB . 'stage_helpers.php';

    // Some values could be empty, use NULL if this happen
    $user_id = parseUrlStringValue(array_get($params, 'user_id'));
    $session_id = parseUrlStringValue(array_get($params, 'session_id'));
    $hotel_id = parseUrlStringValue(array_get($params, 'hotel_id'));

        $referrer_id = parseUrlStringValue(array_get($params, 'referrer_id')); // int
        $source_action = parseUrlStringValue(array_get($params, 'source_action')); // string

        // Send the booking funnel payload to hl_api
        $gateway = new ApiGatewayConnection();
        $brand = getHotelBrand($hotel_id);

        if ($brand) {
            $payload = [
                "user_id" => $user_id,
                "referrer_id" => $referrer_id == "NULL" ? null : $referrer_id,
                "session" => $session_id,
                "booking_action" => $params['booking_action'],
                "source" => $params['source'],
                "source_action" => $source_action
            ];

        $gateway->sendRequest($payload, HOTELINKING_ENDPOINT . "brands/" . array_get($brand, 'id') . "/bookings/funnel", 'POST');
        $log->info('BOOKING FUNNEL STAGED');
    }
}
