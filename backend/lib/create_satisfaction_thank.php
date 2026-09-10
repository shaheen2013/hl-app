<?php

/**
 * @param $user
 * @param $id_hotel
 * @param $satisfied_customer
 * @param $hotel_url
 * @return bool|int|string
 */
function createSatisfactionThankOnEmailPlatform($user, $id_hotel, $satisfied_customer, $hotel_url)
{
    $id_user = $user['id'];
    $con = conectar(2);
    $sql = "INSERT INTO satisfaction_thanks (user_id, hotel_id, send_date, created_at, satisfied_customer, hotel_url) 
    VALUES ($id_user, '$id_hotel', NOW(), NOW(), '$satisfied_customer', '$hotel_url')";
    return escritura($sql, $con);
}
