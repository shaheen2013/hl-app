<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

function isUserHotelLoyalty($user_id, $hotel_id){
    global $log;
    $hotelLoyaltyInfo = getHotelStayTime($hotel_id);
    $hotel_stay_time = array_get($hotelLoyaltyInfo, 'stay_time', null);
    $hotel_min_visits = array_get($hotelLoyaltyInfo, 'loyalty_min_visits', null);
    if($hotel_stay_time){

        $chain_connections = checkUserChainHistory($user_id, $hotel_id);
        $hotel_connections = checkUserHotelHistory($user_id, $hotel_id);

        $loyalCustomer = ($hotel_min_visits <= $chain_connections) || ($hotel_min_visits <= $hotel_connections );
        $log->debug($loyalCustomer);
        return $loyalCustomer;


    } else {
        $log->warning('Chain $chain_id does not have a stay_time configured, so we cannot validate if user in chain loyalty');
        return false;
    }
}
function isUserLoyalty($userId, $chainId, $hotelId){
    global $log;
    
    if(!$chainId && !$hotelId) return false;

    if($chainId) {
        $loyaltyInfo = getChainStayTime($chainId);
    } else {
        $loyaltyInfo = getHotelStayTime($hotelId);
    }
    
    $stayTime = array_get($loyaltyInfo, 'stay_time', null);
    $minVisits = 2;

    if($stayTime){
        if($chainId) {
            $connections = checkUserChainHistory($userId, $chainId);
        } else {
            $connections = checkUserHotelHistory($userId, $hotelId);
        }

        if($minVisits <= $connections){
            return $connections;
        }

        return false;

    } else {
        $log->warning('Chain $chainId or Hotel $hotelId does not have a stay_time configured, so we cannot validate if user is loyalty');
        return false;
    }
}


function checkUserChainHistory($user_id, $chain_id){
    global $log;
    $con = conectar(1);
    $user_id = mysqli_real_escape_string($con, $user_id);
    $chain_id = mysqli_real_escape_string($con, $chain_id);

    $sql = "SELECT num_visits FROM users_visits WHERE chain_id = $chain_id and user_id = $user_id";

    $log-> debug($sql);
    
    return array_get(lectura($sql, $con), 'num_visits');
   
}
function checkUserHotelHistory($user_id, $hotel_id){
    global $log;
    $con = conectar(1);
    $user_id = mysqli_real_escape_string($con, $user_id);
    $hotel_id = mysqli_real_escape_string($con, $hotel_id);

    $sql = "SELECT num_visits FROM users_visits WHERE hotel_id = $hotel_id and user_id = $user_id";

    $log-> debug($sql);

    return array_get(lectura($sql, $con), 'num_visits');

}
function getHotelStayTime($hotel_id){
    $con = conectar();
    $hotel_id = mysqli_real_escape_string($con, $hotel_id);
    return lectura("SELECT stay_time, loyalty_min_visits FROM hoteles WHERE id = $hotel_id", $con);
}

function getChainStayTime($chain_id){
    $con = conectar();
    $chain_id = mysqli_real_escape_string($con, $chain_id);
    return lectura("SELECT stay_time, loyalty_min_visits FROM cadena WHERE id = $chain_id", $con);
}


?>