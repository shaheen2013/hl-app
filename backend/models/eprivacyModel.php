<?php

function getEprivacyInfo($eprivacyGUID){
    $sql = "SELECT users.*,users.id as user_id,hoteles.id as hotel_id, hoteles.hotelName, hoteles.logo, connection_history.* FROM eprivacy_url 
left join users on users.id = eprivacy_url.user_id 
left join hoteles on hoteles.id = eprivacy_url.hotel_id 
left join connection_history on eprivacy_url.user_id = connection_history.id_user and hoteles.id = connection_history.id_hotel
  WHERE guid = '$eprivacyGUID'";
    return lectura($sql);
}

function getUserVisits($user_id, $hotel_id){
    $sql = "SELECT last_login, num_visits FROM users_visits WHERE user_id = $user_id and hotel_id = $hotel_id  ";
    return lecturaArray($sql);
}