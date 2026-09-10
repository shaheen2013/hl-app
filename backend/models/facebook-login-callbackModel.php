<?php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}

include_once LIB . 'getHotelDataLite.php';
include_once LIB . 'get_hotel_wifi_permissions_and_offers.php';

//// para mirar si un email ya existe
//// FB puede retornar un email 'undefined', no se puede repetir el email si ya existe de otro usuario
//function emailNoExiste($email, $id_fb){
//    $email = mysqli_real_escape_string(conectar(), $email);
//    $id_fb = mysqli_real_escape_string(conectar(), $id_fb);
//    $sql = "SELECT COUNT(users.id) AS n, users.id FROM users WHERE users.email='".$email."' ";
//    $row = lectura($sql);
//
//    $sql2 = "SELECT id_usuario FROM user_facebook WHERE id_facebook='".$id_fb."' ";
//    $row2 = lectura($sql2);
//
//    if( ($row['n']==0 || $row['id'] == $row2['id_usuario']) ){
//        return true;
//    }else{
//        return false;
//    }
//}

/**
 * Check if email exists and return user id
 * @param $email
 * @return bool | int
 */
function checkIfEmailExists($email)
{
    $con = conectar(1);
    $email = mysqli_real_escape_string($con, $email);
    $sql = "SELECT id FROM users WHERE email = '$email'";
    $user = lectura($sql, $con);
    if (empty($user['id']))
        return false;
    return $user['id'];
}

/**
 * Return facebook user id given user id
 * @param $user_id
 * @return bool | int
 */
function checkIfFacebookAccountExists($user_id)
{
    $con = conectar(1);
    $user_id = mysqli_real_escape_string($con, $user_id);
    $sql = "SELECT id FROM user_facebook WHERE id_usuario = $user_id";
    $facebook_user = lectura($sql, $con);
    if (empty($facebook_user['id']))
        return false;
    return $facebook_user['id'];
}