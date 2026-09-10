<?php //Miramos si esta definida la variable de control de index.php
if (!defined('INDEXCONTROLVAL')) {echo 'No direct access allowed.';exit;}

include_once RUTA_DIR . LIB . 'sanitize.php';

// Devuelve: email y nombre
function obtenerDatosUsuarioMail($id_usuario, $email = null)
{
    $con = conectar(1);
    $id_usuario = mysqli_real_escape_string($con, $id_usuario);
    $email = mysqli_real_escape_string($con, $email);

    $sql = "SELECT users.id, users.email, users.nombre, users.lang, users.fecha_nacimiento as birthday, users.sexo as gender, users.notif_hotelinking, user_guid.guid
	FROM users
	INNER JOIN user_guid ON user_guid.id_usuario = users.id
	WHERE ";
    if (!$email) {
        $sql .= "users.id='" . $id_usuario . "' ";
    } else {
        $sql .= "users.email='" . $email . "' ";
    }
    $row = lectura($sql, $con);
    return $row;
}

// Devuelve el id del usuario a partir de su email
function obtenerIdUsuarioMail($email)
{
    $con = conectar(1);
    $email = mysqli_real_escape_string($con, $email);

    $sql = "SELECT id FROM users WHERE email='" . $email . "' ";
    $row = lectura($sql, $con, false);
    desconectar($con);
    return $row;
}

// Devuelve solo el lang del usuario a partir de si Id de usuario
function obtenerLangUsuario($id_usuario)
{
    $con = conectar(1);
    $id_usuario = mysqli_real_escape_string($con, $id_usuario);
    $sql = "SELECT lang FROM users WHERE id='" . $id_usuario . "' ";
    $row = lectura($sql, $con, false);
    desconectar($con);
    return $row['lang'];
}

// Devuelve solo el lang del usuario a partir de su email
function obtenerLangUsuarioEmail($email)
{
    $con = conectar(1);
    $email = mysqli_real_escape_string($con, $email);
    $sql = "SELECT lang FROM users WHERE email='" . $email . "' ";
    $row = lectura($sql, $con, false);
    desconectar($con);
    return $row['lang'];
}

function obtenerIdUsuarioCupon($id_cupon)
{
    $con = conectar(1);
    $id_cupon = mysqli_real_escape_string($con, $id_cupon);

    $sql = "SELECT id_usuario FROM user_cupones WHERE id='" . $id_cupon . "' ";
    $row = lectura($sql, $con, false);
    desconectar($con);
    return $row['id_usuario'];
}

function obtenerIdUsuarioPromocode($promoCode)
{
    $con = conectar(1);
    $promoCode = mysqli_real_escape_string($con, $promoCode);

    $sql = "SELECT
	(SELECT id_usuario FROM oferta_referral_token WHERE token='" . $promoCode . "' ) AS not_used,
	(SELECT id_usuario FROM used_promocode WHERE promo_code='" . $promoCode . "' ) AS used";
    $row = lectura($sql, $con, false);
    desconectar($con);
    if (!empty($row['not_used'])) {
        return $row['not_used'];
    } else {
        return $row['used'];
    }
}

/*function obtenerIdUsuarioReferrerPromocode($promoCode)
{
$promoCode = mysqli_real_escape_string(conectar(), $promoCode);
$sql = "SELECT
(SELECT id_referrer FROM oferta_referral_token WHERE token='".$promoCode."' ) AS not_used,
(SELECT id_referrer FROM used_promocode WHERE promo_code='".$promoCode."' ) AS used";
$row = lectura($sql);
//Devolvemos el id de la tabla en la que nos devuelva resultado
if(!empty($row['not_used'])){
return $row['not_used'];
}else{
return $row['used'];
}
}*/

// Devuelve: email y nombre
function obtenerDatosBasicosUsuario($id_usuario)
{
    $con = conectar(1);
    $id_usuario = mysqli_real_escape_string($con, $id_usuario);

    $sql = "SELECT id, email, nombre, lang FROM users WHERE id='" . $id_usuario . "' ";
    $row = lectura($sql, $con, false);
    desconectar($con);

    foreach ($row as $key => $valor) {
        if ($key == 'nombre') {
            $arrayUsuarios[$key] = $valor;
            $arrayUsuarios[$key . '_san'] = string_sanitize($valor);
        } else {
            $arrayUsuarios[$key] = $valor;
        }
    }
    return $arrayUsuarios;
}

// FX que devuelve el id_usuario a partir de un GUID de usuario
function obtenerIdUsuarioGUID($guid)
{
    //Get from cache
    $cacheName = 'obtenerIdUsuarioGUID_' . $guid;
    $cache = getFromCache($cacheName);

    if (!$cache) {
        $con = conectar(1);
        $guid = mysqli_real_escape_string($con, $guid);
        $sql = "SELECT id_usuario FROM user_guid WHERE guid='" . $guid . "' ";
        $row = lectura($sql, $con, false);
        desconectar($con);

        if ($row) {
            setToCache($cacheName, $row, 31536000);
        }

    } else {
        //Get result from cache
        $row = $cache->get();
    }

    return $row['id_usuario'];
}

// FX que devuelve el GUID a partir del id del usuario
function obtenerGUIDUsuarioId($id)
{
    //Get from cache
    /*$cacheName = 'obtenerGUIDUsuarioId_' . $id;
    $cache = getFromCache($cacheName);

    if(!$cache)
    {*/
    $con = conectar(1);
    $id = mysqli_real_escape_string($con, $id);
    $sql = "SELECT guid FROM user_guid WHERE id_usuario='" . $id . "' ";
    $row = lectura($sql, $con, false);
    desconectar($con);

    /*if($row)
    setToCache($cacheName, $row, 31536000);
    }else{
    //Get result from cache
    $row = $cache->get();
    }*/

    return $row['guid'];
}

//Generamos la URL del Hotel a partir de su id
function obtenerUrlGUIDUsario($id_usuario)
{
    $con = conectar(1);
    $id_usuario = mysqli_real_escape_string($con, $id_usuario);

    $sql = "SELECT nombre, guid
	FROM users
	INNER JOIN user_guid ON user_guid.id_usuario=users.id
	WHERE users.id='" . $id_usuario . "' ";
    $row = lectura($sql, $con);
    global $urlTree;
    $urlHotel = BASE_PATH . $urlTree['user'] . '/' . string_sanitize($row['nombre']) . '/' . $row['guid'];
    return $urlHotel;
}

//Miramos que la cuenta de usuario no exista
function userNoExiste($email)
{
    $con = conectar(1);
    $email = mysqli_real_escape_string($con, $email);

    $sql = "SELECT COUNT(id) AS n FROM users WHERE email='" . $email . "' ";
    $row = lectura($sql, $con);
    if ($row['n'] == 0) {
        return true;
    } else {
        return false;
    }
}

function isUserUnsubscribed($user_id, $id_hotel)
{
    $con = conectar(1);
    $user_id = mysqli_real_escape_string($con, $user_id);
    $sql = "SELECT unsubscribed FROM user_hotels where id_usuario = $user_id and id_hotel = $id_hotel";
    $row = lectura($sql, $con);
    return data_get($row, 'unsubscribed') == 1 ? true : false;
}

// Datamatch
function getDatamatchUserBasicData($userIdArrayList, $brand_id)
{
    if (!empty($userIdArrayList)) {
        $userIdList = json_encode($userIdArrayList);
        $userIdList = str_replace('[', '(', $userIdList);
        $userIdList = str_replace(']', ')', $userIdList);

        $cacheName = "Datamatch_user_data_for_idlist_" . str_replace(")", "", str_replace("(", "", $userIdList)) . "_brand_{$brand_id}"; 
        $cache = getFromCache($cacheName);
        
        if (!$cache) {
            $con = conectar(1);
            $sql = "SELECT 
                user_id,
                user_email as email,
                user_name as nombre,
                DATE_FORMAT(user_birthdate,'%d-%m-%Y') as fecha_nacimiento,
                user_gender as sexo,
                user_country as pais,
                CASE 
                    WHEN new_user_brand.unsubscribed = 1 THEN 0 
                    ELSE 1 
                END as subscribed
            FROM new_user_brand
            WHERE user_id IN $userIdList and brand_id = $brand_id
            ORDER BY nombre asc";

            $rows = lecturaArray($sql, $con);

            if ($rows) {
                setToCache($cacheName, $rows, 31536000);
            }
        } else {
            $rows = $cache->get();
        }

        return $rows;
    }
    return [];
}
