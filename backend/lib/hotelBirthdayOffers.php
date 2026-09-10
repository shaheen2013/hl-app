<?php //Miramos si esta definida la variable de control de index.php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}

//Get Birthday by hotel ID offer @return offer ID
function getBirthdayOffer($id)
{
    $cacheName = 'birthdayOfferFromHotel_' . $id;
    $cache = getFromCache($cacheName);
    if (!$cache) {
        $con = conectar(1); // 1 significa que lee de la read réplica
        $id_hotel = mysqli_real_escape_string($con, $id);
        $sql = "SELECT oferta_id, sendWarningFromNonUsers FROM hotel_oferta_birthday WHERE hotel_id = '$id_hotel'";
        $result = lectura($sql, $con, false);
        if ($result) {
            $tags = array("hotel", "hotel_goals", "hotel_goals_" . $id_hotel, "birthdayOffersFromHotel_" . $id_hotel);
            setToCache($cacheName, $result, 31536000, $tags);
        }
    } else {
        $result = $cache->get();
    }
    return $result;
}

//Set new birthday offer @return boolean
function setBirthdayOffer($id_hotel, $id_oferta, $nonUsersWarning)
{
    global $log;
    $con = conectar();
    $id_hotel = mysqli_real_escape_string($con, $id_hotel);
    $id_oferta = mysqli_real_escape_string($con, $id_oferta);

    $sql = "INSERT INTO hotel_oferta_birthday (oferta_id, hotel_id, sendWarningFromNonUsers) VALUES($id_oferta, $id_hotel, $nonUsersWarning) ON DUPLICATE KEY UPDATE   oferta_id='$id_oferta', sendWarningFromNonUsers = $nonUsersWarning";
    $log->debug('hotel oferta birthday -> ' . $sql);
    $query = mysqli_query(conectar(), $sql);

    if ($query) {
        deleteCacheByTag("birthdayOffersFromHotel_" . $id_hotel);
    }

    return $query;
}

function setBirthdayAlarm($brandID, $birthdayAlarm)
{
    include_once APP . 'Services/Connections/ApiGatewayConnection.php';
    
    $birthdayProductID = getIdByProductName('birthday_emails');
    
    $gateway = new ApiGatewayConnection();
    $gateway->sendRequest([
        "birthday_alarm" => $birthdayAlarm,
    ], HOTELINKING_ENDPOINT . "brands/$brandID/products/$birthdayProductID/configuration", 'PUT');
    
    return true;
}

function setMailsFromBirthdayAlarm($id_hotel, $birthdayAlertEmails, $birthdayAlertRange = 3)
{

    $con = conectar();
    $id_hotel = mysqli_real_escape_string($con, $id_hotel);
    $birthdayAlertEmails = mysqli_real_escape_string($con, $birthdayAlertEmails);

    $sql = "UPDATE hoteles SET birthdayAlertEmails='$birthdayAlertEmails', birthday_alarm_days_range=$birthdayAlertRange WHERE id = $id_hotel ";
    $query = escritura($sql, $con);

    return true;
}

function getBirthdayAlarm($brandID)
{
    $sql = "SELECT value as birthday_alarm 
        FROM
	        brand_product_config
        INNER JOIN 
            brand_product ON brand_product.id = brand_product_config.brand_product_id
        INNER JOIN 
            product_config ON product_config.id = brand_product_config.product_config_id
        WHERE 
            label = 'birthday_alarm' AND brand_id=$brandID";
    
    $query = lectura($sql);

    return $query;
}

function getMailsFromBirthdayAlarm($id_hotel)
{
    $con = conectar();
    $id_hotel = mysqli_real_escape_string($con, $id_hotel);

    $sql = "SELECT birthdayAlertEmails, birthday_alarm_days_range from hoteles WHERE id=$id_hotel";
    $row = lectura($sql, $con);

    return $row;
}
