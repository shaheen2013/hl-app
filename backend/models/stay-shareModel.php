<?php

include_once LIB . 'get_hotel_wifi_permissions_and_offers.php';
function checkInGDPRHistory($user_id, $hotel_id, $chain_id)
{

    $stayTimeReconnectionCondition = "= $hotel_id";
    if ($chain_id) {
        $stayTimeReconnectionCondition = "in (SELECT id_hotel from cadena_hotel where id_cadena = $chain_id)";
    }

    $sql = "SELECT gdpr.id
        FROM gdpr_history gdpr
            INNER JOIN user_hotels
                ON gdpr.user_id = user_hotels.id_usuario
                    and gdpr.hotel_id = user_hotels.id_hotel
                    and unsubscribed = 0
        WHERE gdpr.user_id = $user_id
            and gdpr.hotel_id $stayTimeReconnectionCondition
            and gdpr.event like 'conditions' ";

    return lecturaArray($sql);
}

//Check if mac exists on connection history and user is customer
function checkMacInConnectionHistory($userMac, $hotel_id, $chain_id, $days = 7)
{
    //check if userMac is not well set for some reason return false
    if (empty($userMac)) {
        return false;
    }

    $con = conectar(1);
    $userMac = mysqli_real_escape_string($con, $userMac);
    $hotel_id = mysqli_real_escape_string($con, $hotel_id);

    $stayTimeReconnectionCondition = "= $hotel_id";
    if ($chain_id) {
        $stayTimeReconnectionCondition = "in (SELECT id_hotel from cadena_hotel where id_cadena = $chain_id)";
    }

    $sql = "SELECT
        CASE WHEN
            DATE_ADD(last_login, INTERVAL $days DAY) >= NOW() AND ch.id_hotel $stayTimeReconnectionCondition 
        THEN 1 ELSE 0 END AS stayTimeReconnection,
        ch.id_user,
        ch.id_room as roomNumber,
        ch.times_login,
        uh.customer
    from connection_history ch
    inner join user_hotels uh on uh.id_usuario = ch.id_user and uh.id_hotel = ch.id_hotel
    where
        ch.mac_address LIKE '$userMac' and
        ch.id_room <> 'Bypass' and
        uh.customer = 1 and
        ch.id_hotel $stayTimeReconnectionCondition
    order by ch.id desc limit 1";

    return lectura($sql, $con);
}

function getUser($user_id)
{
    global $log;
    try {
        $con = conectar(1);
        $sql = "SELECT id, email, nombre, first_name, last_name, lang, sexo, fecha_nacimiento, location, sendex, email_result, user_card FROM users WHERE id = $user_id";
        $result = lectura($sql, $con);

        return [
            'id' => $result['id'],
            'card_id' => $result['user_card'],
            'email' => $result['email'],
            'name' => $result['nombre'],
            'first_name' => $result['first_name'],
            'last_name' => $result['last_name'],
            'lang' => $result['lang'],
            'gender' => $result['sexo'],
            'birthday' => $result['fecha_nacimiento'],
            'locale' => $result['location'],
            'sendex' => $result['sendex'],
            'email_result' => $result['email_result'],
            'isNew' => false,
            'source' => 'form',
        ];
    } catch (Exeption $e) {
    }
    $log->error('Error in getUser ', $_SESSION);
}

function useChainBypass($hotel_id)
{
    $sql = "SELECT chain_bypass FROM hoteles WHERE id = $hotel_id";
    return array_get(lectura($sql), 'chain_bypass');
}

function getChainStayTime($chain_id)
{
    $con = conectar(1);
    $chain_id = mysqli_real_escape_string($con, $chain_id);
    return array_get(lectura("SELECT stay_time FROM cadena WHERE id = $chain_id", $con), 'stay_time');
}
