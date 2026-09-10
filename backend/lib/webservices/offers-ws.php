<?php
include 'librerias.php';// Librerias básicas
// Restringir ips que pueden acceder
include_once RUTA_DIR . LIB . 'check_access.php';
checkIpAccess('rego', $_SERVER['REMOTE_ADDR']);

include_once RUTA_DIR . LIB . 'webservices/msgFeedback.php';

if ($_POST){

    $con = conectar(1);

    $user_lang = $_SESSION['userLang'];
    $hotel_id = $_SESSION['hotel']['id'];
    $offer_id = mysqli_real_escape_string($con, data_get($_POST, 'offer_id', null));
    $hotel_oferta_id = mysqli_real_escape_string($con, data_get($_POST, 'hotel_oferta_id', null));
    $products_id = mysqli_real_escape_string($con, data_get($_POST, 'product_id', null));
    $offer_triggers_id = mysqli_real_escape_string($con, data_get($_POST, 'offer_triggers_id', null));
    $offer_platforms_id = mysqli_real_escape_string($con, data_get($_POST, 'offer_platforms_id', null));
    $offer_duration = mysqli_real_escape_string($con, data_get($_POST, 'offer_duration', null));

    try {
        if ($offer_id){
            if ($hotel_oferta_id == "0"){
                deActivateOffer($offer_id);
            } else {
                upsertOffer($offer_id, $hotel_id, $hotel_oferta_id, $products_id, $offer_triggers_id, $offer_platforms_id, $offer_duration);
            }
        } else {
            insertOffer($hotel_oferta_id, $hotel_id, $products_id, $offer_triggers_id, $offer_platforms_id, $offer_duration);
        }

        //send response to client
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode(array(
            'code' => msgFeedbackWs('2007', $user_lang)
        )));

    } catch (Exception $e){

        $log->error('Offer WS : ', [$e->getMessage()]);

        //send error to client
        header('HTTP/1.1 500 Internal Server');
        header('Content-Type: application/json; charset=UTF-8');

        die(json_encode(array(
            'code' => msgFeedbackWs('4065', $user_lang),
            'error' => 'Could not create or update offer'
        )));
    }

}


function insertOffer($hotel_oferta_id, $hotel_id, $products_id, $offer_triggers_id, $offer_platforms_id, $offer_duration){
    $sql = "INSERT INTO offers 
                (hotel_oferta_id, hotels_id, products_id, offer_triggers_id, offer_platforms_id, duration) 
            VALUES ($hotel_oferta_id, $hotel_id, $products_id, $offer_triggers_id, $offer_platforms_id, $offer_duration)";

    return escritura($sql);
}

function upsertOffer($offer_id, $hotel_id, $hotel_oferta_id, $products_id, $offer_triggers_id, $offer_platforms_id, $offer_duration){
    $sql = "INSERT INTO offers 
                (id, hotels_id, hotel_oferta_id, products_id, offer_triggers_id, offer_platforms_id, duration) 
            VALUES ($offer_id, $hotel_id, $hotel_oferta_id, $products_id, $offer_triggers_id, $offer_platforms_id, $offer_duration) 
            ON DUPLICATE KEY UPDATE hotel_oferta_id = $hotel_oferta_id , products_id = $products_id, offer_triggers_id = $offer_triggers_id, offer_platforms_id = $offer_platforms_id, duration = $offer_duration, active = 1";

    return escritura($sql);
}

function deActivateOffer($offer_id){
    $sql = "UPDATE offers SET active = 0 WHERE id = $offer_id";
    return escritura($sql);
}
