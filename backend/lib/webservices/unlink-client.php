<?php
define("INDEXCONTROLVAL", "1");

include_once 'librerias.php';
include_once RUTA_DIR . LIB .'obtenerdatosHotel.php';
include_once RUTA_DIR . LIB .'apiGateway.php';
require_once __DIR__ . '/../pushtech_api.php';

header('Content-Type: application/json');

//POST values
$user_id = $_POST['user_id'];
$hotel_id = $_POST['hotel_id'];

$chain_id = array_get($_POST, 'chain_id', null);

$log->info('Deleting user', [
    'user_id'  => $user_id,
    'hotel_id' => $hotel_id,
    'chain_id' => $chain_id
]);

if (!is_null($chain_id)) {
    //Get the Hotels from getHotelIDsForChainID and convert to String
    $hotels_id = getHotelIDsForChainID($chain_id);
    $brandId = getChainBrand($chain_id)['id'];

    $hotel_ids = implode(",", $hotels_id);

    $whereHotelId = " hotel_id in ($hotel_ids)";
    $whereIdHotel = " id_hotel in ($hotel_ids)";
    $customizedDeletes = ' AND (' . $whereHotelId . ' OR chain_id = ' . $chain_id . ')';
    $offerDeletes = ' (' . $whereIdHotel . ' OR id_cadena = ' . $chain_id . ')';

} else {
    $brandId = getHotelBrand($hotel_id)['id'];
    $whereHotelId = " hotel_id = $hotel_id ";
    $whereIdHotel = " id_hotel = $hotel_id ";
    $customizedDeletes = ' AND ' . $whereHotelId;
    $offerDeletes = $whereIdHotel;
}

$deleteApp = [
    'users_visits'                  => "DELETE FROM users_visits WHERE user_id=$user_id AND $whereHotelId",
    'connection_history'            => "DELETE FROM connection_history  WHERE id_user=$user_id AND $whereIdHotel",
    'user_hotels'                   => "DELETE FROM user_hotels WHERE id_usuario=$user_id AND $whereIdHotel",
    'user_survey_answers'           => "DELETE user_survey_question_answer FROM 
                                            user_survey_question_answer
                                        INNER JOIN 
                                            user_survey ON user_survey.id = user_survey_question_answer.user_survey_id
                                        INNER JOIN 
                                            brands ON user_survey.brand_id = brands.id
                                        WHERE $whereHotelId AND user_survey.user_id = $user_id",
    'user_satisfaction'             => "DELETE FROM user_satisfaction WHERE id_usuario=$user_id AND $whereIdHotel",
    'oferta_referral_token'         => "DELETE FROM oferta_referral_token WHERE id_usuario=$user_id AND $whereIdHotel",
    'used_promocode'                => "DELETE FROM used_promocode WHERE id_usuario=$user_id AND $whereIdHotel",
    'user_cupones'                  => "DELETE FROM user_cupones  WHERE id_usuario=$user_id AND id_oferta in (SELECT id FROM hotel_oferta WHERE $offerDeletes)",
];
$deleteEmails = [
    'birthdays'                => "DELETE FROM birthdays WHERE user_id = $user_id AND $whereHotelId",
    'chain_loyalty_offers'     => "DELETE FROM chain_loyalty_offers WHERE user_id = $user_id AND $whereHotelId",
    'reviews'                  => "DELETE FROM reviews WHERE user_id = $user_id AND $whereHotelId",
    'satisfaction_thanks'      => "DELETE FROM satisfaction_thanks WHERE user_id = $user_id AND $whereHotelId",
    'satisfactions'            => "DELETE FROM satisfactions WHERE user_id = $user_id AND $whereHotelId",
    'customized_satisfactions' => "DELETE FROM customized_satisfactions WHERE user_id = $user_id" . $customizedDeletes,
    'satisfactions_follow_up'  => "DELETE FROM satisfactions_follow_up WHERE user_id = $user_id AND $whereHotelId",
    'birthday_alarms'          => "DELETE FROM birthday_alarms WHERE user_id = $user_id AND $whereHotelId",
    'regular_customer'         => "DELETE FROM regular_customer WHERE user_id = $user_id AND $whereHotelId",
    'stay_offers'              => "DELETE FROM stay_offers WHERE user_id = $user_id AND $whereHotelId"
];

$brand = getHotelBrand($hotel_id);

//open the connection for the app database
$con = conectar();

//open the connection for the emails database
$con2 = conectar(2);

$deleted = true;

startTransaction($con);
startTransaction($con2);

try {
    foreach ($deleteApp as $app) {
        escritura($app, $con, false);
    }
    foreach ($deleteEmails as $emails) {
        escritura($emails, $con2, false);
    }

    commitTransaction($con);
    commitTransaction($con2);

    desconectar($con);
    desconectar($con2);

    // Emit user_deleted event
    $payload = [
        "user"   => [
            "id"    => intval($user_id),
        ],
        "brand"   => [
            "id"    => intval($brand['id'])
        ],
    ];

} catch (Exception $e) {
    rollbackTransaction($con);
    rollbackTransaction($con2);

    $deleted = false;
    $log->error("Error deleting user", [$e->getMessage()]);
}

if ($deleted) {
    $gateway = new ApiGatewayConnection();
    $gateway->sendRequest(null, HOTELINKING_ENDPOINT . 'brands/' . $brandId . '/clients/' . $user_id, 'DELETE');
    emitEvent('Users', 'user_deleted', $payload, []);
    echo json_encode(true);
}


