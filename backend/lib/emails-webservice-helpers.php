<?php
/**
 * @param $user
 * @return string
 */
function generateUnsuscriptionHash($user)
{
    $salt = substr(base64_encode(openssl_random_pseudo_bytes(17)), 0, 22);
    $salt = str_replace("+", ".", $salt);
    $param = '$' . implode('$', array(
            "2y",
            str_pad(11, 2, "0", STR_PAD_LEFT),
            $salt
        ));
    $digest = $user['email'] . $user['guid'];
    return crypt($digest, $param);
}

/**
 * @param $id
 * @return array|null
 */
//function getUserFromEmailPlatform($id)
//{
//    global $log;
//    $con = conectar(2);
//    $id_user = mysqli_real_escape_string($con, $id);
//    $sql = "SELECT * FROM users WHERE id_hotelinking = $id_user";
//    $row = lectura($sql, $con);
//    return $row['id'];
//}

/**
 * @param $user
 * @return int|string
 */
function createUserInEmailPlatform($user)
{

    global $log;
    $con = conectar(2);
    $id = intval($user['id']);
    $name = mysqli_real_escape_string($con, $user['name']);
    $email = mysqli_real_escape_string($con, $user['email']);
    $lang = mysqli_real_escape_string($con, $user['lang']);
    $birthday = mysqli_real_escape_string($con, $user['birthday']);
    $sql = "INSERT INTO users (id_hotelinking, name, email, lang, birthday, created_at) 
            VALUES ($id, '$name', '$email', '$lang', '$birthday', NOW())";
    $user_id = escritura($sql, $con, false);
    // Generate unsuscription code
    if ($user_id) {
        $hash = generateUnsuscriptionHash($user);
        $sql2 = "INSERT unsuscribes (user_id, status, token) VALUES ('$user_id', 'suscribed', '$hash') ";
        escritura($sql2, $con);
        $log->debug('User created in email platform:', array('user_id' => $user_id));
    } else {
        $log->debug('user could not be created on email platform');
    }

    return $user_id;
}

/**
 * @param $id_hotel
 * @param $product
 * @return false|string
 */
function getSendDate($id_hotel, $product)
{
    global $log;
    $con = conectar();

    if ($product == 'satisfaction') {
        $sql = "SELECT diasEnvio, send_hour FROM hotel_$product WHERE id_hotel = $id_hotel";
        $row = lectura($sql, $con);
        //return a date with GMT0 format
        $Date = gmdate('Y-m-d H:i:s', time() + date("Z"));
        return gmdate('Y-m-d H:i:s', strtotime($Date. ' + ' . $row['diasEnvio'] . ' day' . ' + ' . $row['send_hour'] . 'hours'));
    } else {
        $sql = "SELECT diasEnvio FROM hotel_$product WHERE id_hotel = $id_hotel";
        $row = lectura($sql, $con);
        //return a date
        $Date = date('Y-m-d');
        return $row['diasEnvio'] == 0 ? date('Y-m-d') : date('Y-m-d', strtotime($Date. ' + ' . $row['diasEnvio'] . ' day'));
    }
    
}

/**
 * @param $id_user
 * @param $id_hotel
 * @return false|string
 */
function getReviewUser($id_user, $id_hotel)
{
    global $log;
    $con = conectar(2);
    $sql = "SELECT id, user_id, hotel_id, send_date FROM reviews WHERE user_id = $id_user AND hotel_id = $id_hotel";
    $row = lectura($sql, $con);
    //return a date
    return $row ? $row : FALSE;
}

/**
 * @param $id_hotel
 * @return array|mixed|null
 */
function getHotelFromEmailPlatform($id_hotel)
{
    //Get from cache
    $cacheName = 'id_hotel_email_platform_' . $id_hotel;
    $cache = getFromCache($cacheName);

    if (!$cache) {
        $con = conectar(2);
        $sql = "SELECT hotels.id AS hotel_id FROM hotels WHERE hotels.id_hotelinking=$id_hotel ";
        $row = lectura($sql, $con);

        if ($row) {
            setToCache($cacheName, $row, 31536000);
        }
    } else {
        $row = $cache->get();
    }
    return $row;
}
