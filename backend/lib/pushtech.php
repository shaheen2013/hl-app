<?php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

include_once 'cache.php';

/**
 * Set and Get pushtech cache Key
 * @param $entity
 * @param $hotel_id
 * @return string
 */
function pushtechCacheKey($entity, $hotel_id)
{
    return 'pushtech_info_' . $entity .'_'. $hotel_id;
}

/**
 * Check if params are valid
 * @param $id
 * @param $entity
 * @param $method
 */
function checkParams($id, $entity, $method)
{
    if(!isset($id)){
        error_log(''. $method .' needs an entity ID, ' . $id . ' given.');
        exit;
    }
    if($entity != "chain" && $entity != 'hotel'){
        error_log(''. $method .' needs a valid entity string, ' . $entity . ' given.');
        exit;
    }
}

/**
 * Create or update pushtech_user_updates tables to send info to pushtech platform
 * @param $user_id
 * @param $hotel_id
 * @param $action (update / none)
 */
function update_pushtech_user_updates($user_id, $hotel_id, $action)
{
    $con = conectar();
    $uid = mysqli_real_escape_string($con, $user_id);
    $hid = mysqli_real_escape_string($con, $hotel_id);
    $sql = "INSERT INTO pushtech_user_updates (user_id, hotel_id, action)
            VALUES ('$uid', '$hid', '$action')
            WHERE user_id = '$uid' AND hotel_id = '$hid'
            ON DUPLICATE KEY UPDATE action = '$action'";
    escritura($sql, $con);
}
