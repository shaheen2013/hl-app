<?php //Miramos si esta definida la variable de control de index.php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}

include_once RUTA_DIR . LIB . 'fecha.php';
include_once RUTA_DIR . LIB . 'generarToken.php';
include_once RUTA_DIR . LIB . 'facebook.php';
include_once RUTA_DIR . LIB . 'obtenerdatosHotel.php';
include_once RUTA_DIR . LIB . 'apiGateway.php';
const LEGAL_AGE = 18;

function guardarPassUsuario($id, $pass)
{
    // Guardar pass usaurio
    $passHasheado = sha1($pass);
    $sql = "UPDATE users SET  pass='" . $passHasheado . "'
	WHERE id='" . $id . "'";
    escritura($sql);
}

function activarUsuario($id, $lang)
{
    $con = conectar();
    $id = mysqli_real_escape_string($con, $id);
    $lang = mysqli_real_escape_string($con, $lang);

    // Activar usuario para login con usuario y pass
    // Establecemos el lang del navegador como el lang del usuario
    $sql = "UPDATE users SET verificado='1', lang='" . $lang . "'
	WHERE id='" . $id . "'";
    escritura($sql, $con);
}

function activarDatosFacebookUsuarioNuevo($name, $fecha, $lang, $img, $friends, $email, $id_usuario = '')
{
    //Insertamos user_facebook por id de usuario
    $sql2 = "UPDATE users SET
	nombre='" . $name . "', created='" . $fecha . "', verificado='1', img='" . $img . "', lang='" . $lang . "',
	fb_friends='" . $friends . "' ";
    if ($id_usuario == '') {
        $sql2 .= " WHERE email='" . $email . "' ";
    } else {
        $sql2 .= " WHERE id='" . $id_usuario . "' ";
    }
    escritura($sql2);
}

function generateUnsuscriptionHash($user_email, $user_guid)
{
    $salt = substr(base64_encode(openssl_random_pseudo_bytes(17)), 0, 22);
    $salt = str_replace("+", ".", $salt);
    $param = '$' . implode('$', array(
        "2y",
        str_pad(11, 2, "0", STR_PAD_LEFT),
        $salt,
    ));
    $digest = $user_email . $user_guid;
    return crypt($digest, $param);
}

/**
 * Create a facebook user from user
 * @param $user
 * @return int|string
 */
function upsertFacebookUser($user)
{
    global $log;
    $con = conectar();
    $user_id = mysqli_real_escape_string($con, $user['id']);
    $fb_id = mysqli_real_escape_string($con, $user['facebook_id']);
    $fb_name = mysqli_real_escape_string($con, $user['name']);
    $fb_first_name = mysqli_real_escape_string($con, $user['first_name']);
    $fb_last_name = mysqli_real_escape_string($con, $user['last_name']);
    $email = mysqli_real_escape_string($con, $user['email']);
    $fb_user_img = mysqli_real_escape_string($con, $user['facebook_picture']);
    $fb_gender = mysqli_real_escape_string($con, $user['gender']);
    $fb_link = mysqli_real_escape_string($con, $user['facebook_link']);
    $fb_friends_num = mysqli_real_escape_string($con, $user['facebook_friends']);
    $fb_locale = mysqli_real_escape_string($con, $user['locale']);
    $fb_birthday = mysqli_real_escape_string($con, !empty($user['birthday']) ? $user['birthday'] : null);
    $fb_location_name = mysqli_real_escape_string($con, !empty($user['facebook_location_name']) ? $user['facebook_location_name'] : null);
    $fb_location_id = mysqli_real_escape_string($con, !empty($user['facebook_location_id']) ? $user['facebook_location_id'] : null);

    if (empty($user_id) || empty($fb_id) || empty($email)) {
        return false;
    }

    $sql = "INSERT INTO user_facebook (id_facebook, nombre, first_name, last_name, gender, link, amigos, facebook_img, email, id_usuario, locale, birthday, locationName, locationID)
            VALUES ('$fb_id', '$fb_name', '$fb_first_name', '$fb_last_name', '$fb_gender', '$fb_link', '$fb_friends_num', '$fb_user_img', '$email', '$user_id', '$fb_locale', '$fb_birthday', '$fb_location_name', '$fb_location_id')
            ON DUPLICATE KEY UPDATE nombre = VALUES(nombre), gender = VALUES(gender), amigos = VALUES(amigos), facebook_img = VALUES(facebook_img), locationName = VALUES(locationName), locationID = VALUES(locationID)";

    $log->debug('facebookt upsert query ' . $sql);

    escritura($sql, $con, false);
    $result = mysqli_affected_rows($con);

    if ($result == 1) {
        $log->info('facebook user created');
    }

    if ($result == 2) {
        $log->info('facebook user updated');
    }

    if (mysqli_error($con) == null) {
        $result = 3;
        $log->info('facebook user nothing to update');
    }

    if (mysqli_connect_errno()) {
        $log->error('facebook user upsert failed ', array("error" => mysqli_connect_error(), "query" => $sql));
    }
    desconectar($con);
    return $result;
}

function getUserBrand($user_id, $brand_id)
{
    if (!$user_id || !$brand_id) {
        return false;
    }
    $con = conectar(1);
    $user_id = mysqli_real_escape_string($con, $user_id);
    $brand_id = mysqli_real_escape_string($con, $brand_id);

    $sql = "SELECT *
        FROM new_user_brand
        WHERE user_id=$user_id
        AND brand_id=$brand_id";

    return lectura($sql, $con);
}

/**
 * @param $user
 * @return array | boolean
 * @throws Exception
 */
function createNewUser($user, $sendex, $emailResult, $origin)
{
    global $log;
    $hotel_id = array_get($user, 'hotel_id');
    $brand_id = array_get($_SESSION, 'brandID') ?? array_get(getHotelBrand($hotel_id), 'id');
    $userBypass = array_get($_SESSION, 'regularUser.stayTimeReconnection', false);
    $newBrandVisitInSession = getUserBrand(array_get($_SESSION, 'user.id'), array_get($_SESSION, 'brandID')) === null ? true : false;
    $first_name = array_get($user, 'first_name');
    $last_name = array_get($user, 'last_name');
    $phone_number = array_get($user, 'phone_number');
    $email = array_get($user, 'email');
    $birthday = array_get($user, 'birthday');
    $gender = empty(array_get($user, 'gender')) ? null : array_get($user, 'gender');

    $payload = [
        "email" => $email,
        "first_name" => $first_name,
        "last_name" => $last_name,
        "phone_number" => $phone_number,
        "lang" => empty(array_get($user, 'lang')) ? "en" : array_get($user, 'lang'),
        "gender" => $gender,
        "birthday" => $birthday,
        "locale" => empty(array_get($user, 'locale')) ? "en" : array_get($user, 'locale'),
        "document_number" => empty(array_get($user, 'card_id')) ? null : array_get($user, 'card_id'),
        "email_result" => $emailResult,
        "country" => getCountryName(array_get($user, 'locale')),
        "sendex" => $sendex,
        "unsubscribed" => array_get($_SESSION, 'unsubscribed') ? 1 : 0,
        "customer" => empty(array_get($_SESSION, 'customer')) ? null : array_get($_SESSION, 'customer'),
        "pms_id" => empty(array_get($_SESSION, 'user_hotel_id')) ? null : array_get($_SESSION, 'user_hotel_id'),
        "origin" => $origin,
        "commercial_profile" => array_get($_SESSION, 'commercial_profile') ? 1 : 0,
    ];

    if ($origin === "facebook") {
        $payload['facebook_id'] = array_get($user, 'id');
        $payload['facebook_img'] = 'https://graph.facebook.com/' . array_get($user, 'id') . '/picture?width=200';
        $payload['facebook_friends'] = array_get($user, 'friends.summary.total_count', 0);
    }

    include_once APP . 'Services/Connections/ApiGatewayConnection.php';
    $gateway = new ApiGatewayConnection();

    if (!$userBypass || $newBrandVisitInSession) {
        try {
            $log->debug("Calling API to create or update user", [
                'payload' => $payload,
                'brand_id' => $brand_id,
            ]);

            $response = $gateway->sendRequest(
                ["data" => $payload],
                HOTELINKING_ENDPOINT . "brands/$brand_id/users",
                'POST'
            );
        } catch (Exception $e) {
            $log->error("Failed to create user", [
                'brand_id' => $brand_id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
        }
        $response = json_decode($response, true);
        $response_user = $response['user'];

        if (!$response_user) {
            $log->error("Failed to create user", ['user' => $user, 'brand_id' => $brand_id]);
            return false;
        }
    }

    $return_user = [
        "isNew" => $response['isNew'] ?? false,
        "id" => $response_user['id'] ?? array_get($_SESSION, 'user.id'),
        'nombre' => $response_user['name'] ?? "$first_name $last_name",
        'first_name' => $response_user['first_name'] ?? $first_name,
        'last_name' => $response_user['last_name'] ?? $last_name,
        'email' => $response_user['email'] ?? $email,
        'fecha_nacimiento' => $response_user['birthday'] ?? $birthday,
        'sexo' => $response_user['gender'] ?? $gender
    ];

    $log->debug("return_user", ['user' => $return_user, 'brand_id' => $brand_id]);

    if (!empty($return_user['id']) && $return_user['isNew'] === true) {
        $user['id'] = $return_user['id'];
    }

    if (
        !empty($hotel_id) &&
        array_has($_SESSION, 'gdpr_events') &&
        !array_has($_SESSION, 'gdpr_inserted')
    ) {
        insertGDPREvents($return_user['id'], $hotel_id, $_SESSION['gdpr_events']);
    }

    return $return_user;
}

//-----------------------------------------------------------------------------------------------------
//Ahora verificamos si el usuario existe por el id de facebook/twitter, antes lo haciamos por email,
//pero los emails de las redes sociales pueden ser distintos y creariamos 2 cuentas al mismo usuario
//-----------------------------------------------------------------------------------------------------
//Miramos en user_facebook o user_twitter si el usuario existe
//$socialMedia: facebook, twitter
//$idSocailMedia: id de usuario de red social ($socialMedia)
//-----------------------------------------------------------------------------------------------------
function nuevoUsuarioSocialMedia($socialMedia, $idSocailMedia, $email, $name, $idHotel, $lang)
{
    //Mirar si el usuario existe en social media
    $result = usuarioExisteSocialMedia($socialMedia, $idSocailMedia);

    global $urlTree;
    $urlInvite = SECURE_BASE_PATH . $urlTree['login'];

    if ($result['code'] == '200') {
        //echo '<!----existe en SM---->';
        $id_usuario = $result['id_usuario'];
    } else {
        //echo '<!----no existe en SM---->';
        //Mirar si el usuario existe por email
        $id_usuario = comprobarSiUsuarioNuevo($email);

        //Si no existe crear cuenta de usuario
        if ($id_usuario == '0') {
            //echo '<!--<br>--tampoco existe en HL--<br>-->';
            $id_usuario = crearNuevoUsuario($email, $idHotel, $name, $lang);

            //Generamos la invitación
            $urlInvite = SECURE_BASE_PATH . 'login'; //crearInvitacionUsuario($email, 'hot', $idHotel, 0, 0, 0, 0, $name, 1);
        } else {
            //echo '<!--<br>--pero existe en HL---->';
            //Creamos la invitación SI el usuario no está activo
            if (userNoActivoId($id_usuario)) {
                //echo 'PERO NO ESTÁ ACTIVO ---></br>';
                $urlInvite = SECURE_BASE_PATH . 'login'; //crearInvitacionUsuario($email, 'hot', $idHotel, 0, 0, 0, 0, $name, 1);
            } else {
                //echo 'Y ESTÁ ACTIVADO ---></br>';
            }
        }
    }
    //Vinculamos el usuario con el hotel
    vincularUsuarioHotelero($id_usuario, $idHotel);

    //Creamos el array a devolver
    $return['id_usuario'] = $id_usuario;
    $return['urlInvite'] = $urlInvite;

    return $return;
}

function usuarioExisteSocialMedia($socialMedia, $idSocailMedia)
{
    $con = conectar(1);
    $socialMedia = mysqli_real_escape_string($con, $socialMedia);
    $idSocailMedia = mysqli_real_escape_string($con, $idSocailMedia);
    desconectar($con);

    $sql = "SELECT id_usuario FROM user_" . $socialMedia . "
	WHERE id_" . $socialMedia . "='" . $idSocailMedia . "' ";
    $row = lectura($sql);
    if ($row['id_usuario'] == '') {
        $result['code'] = '404';
        $result['id_usuario'] = '0';
    } else {
        $result['code'] = '200';
        $result['id_usuario'] = $row['id_usuario'];
    }
    return $result;
}

//Fx para mirar si un usuario existe en la tabla usuarios desde los datos de FB, para crear o actualizar
function saveUpdateUserFromFB($email, $name, $idHotel, $lang, $id_fb)
{
    //Mirar si el usuario existe por email en users y por id_fb en user_facebook
    $con = conectar(1);
    $email = mysqli_real_escape_string($con, $email);
    $name = mysqli_real_escape_string($con, $name);
    $idHotel = mysqli_real_escape_string($con, $idHotel);
    $lang = mysqli_real_escape_string($con, $lang);
    $id_fb = mysqli_real_escape_string($con, $id_fb);
    desconectar($con);

    $sql = "SELECT COUNT(users.id) AS n, users.id,
    (SELECT id_usuario FROM user_facebook WHERE id_facebook='" . $id_fb . "') AS id_usuario
    FROM users WHERE users.email='" . $email . "' ";
    $row = lectura($sql);

    if ($row['n'] == 0 && $row['id_usuario'] == null || $row['id'] == $row['id_usuario']) {
        //Usuario nuevo o mismo usuario con ese email
        $id_usuario = crearNuevoUsuario($email, $idHotel, $name, $lang);
        $result['code'] = '200';
        $result['id_usuario'] = $id_usuario;
    } elseif (($row['n'] == 0 || $row['id'] == $row['id_usuario'])) {
        $result['code'] = '300';
        $result['id_usuario'] = $row['id_usuario'];
    } else {
        //Email ya en uso
        $result['code'] = '400';
        $result['id_usuario'] = '';
    }

    return $result;
}

function checkFacebookUserEmail($facebook_id)
{
    $con = conectar(1);
    $facebook_id = mysqli_real_escape_string($con, $facebook_id);
    $sql = "SELECT email, id_usuario, gender from user_facebook WHERE id_facebook = '$facebook_id'";
    $row = lectura($sql, $con);
    return $row;
}

function getFacebookUser($facebook_id)
{
    $con = conectar(1);
    $facebook_id = mysqli_real_escape_string($con, $facebook_id);
    $sql = "SELECT users.id, users.email_result as emailResult, users.sendex as sendex, user_facebook.email, user_facebook.gender 
        FROM users 
        LEFT JOIN user_facebook ON user_facebook.id_usuario = users.id 
        WHERE user_facebook.id_facebook='$facebook_id'";
    $row = lectura($sql, $con);
    return $row;
}

function updateFacebookUserData($updateData)
{
    global $log;
    $log->debug('Updating old user facebook', $updateData);
    escritura("UPDATE user_facebook SET email = '{$updateData['email']}' WHERE id_facebook = " . $updateData['fb_id']);

    try {
        escritura("
            UPDATE IGNORE
                users
            SET
                email = '{$updateData['email']}',
                sendex = '{$updateData['sendex']}',
                email_result = '{$updateData['email_result']}'
            WHERE
                id = " . $updateData['user_id']);
    } catch (Exception $exception) {
        $log->warning("There is already a user with the same email account used on facebook", $updateData);
    }
}

function insertGDPREvents($user_id, $hotel_id, $gdpr_events)
{
    global $log;
    $brand_id = $_SESSION['brandID'];
    $payload = [
        'user_id' => $user_id,
        'hotel_id' => $hotel_id,
        'gdpr_events' => $gdpr_events,
    ];
    $gateway = new ApiGatewayConnection();
    try {
        $log->debug("Calling API to insert GDPR events", ['payload' => $payload, 'brand_id' => $brand_id]);
        $gateway->sendRequest(
            ["data" => $payload],
            HOTELINKING_ENDPOINT . "brands/$brand_id/gdpr/events",
            'POST'
        );
        $_SESSION['gdpr_inserted'] = true;
    } catch (Exception $e) {
        $log->error("Failed insert GDPR events", ['error' => $e->getMessage(), 'file' => $e->getFile(), 'line' => $e->getLine()]);
    }
}

function getCountryName($locale)
{
    $locale = str_replace("_", "-", $locale);

    $con = conectar(1);
    $locale = mysqli_real_escape_string($con, $locale);
    $sql = "SELECT country FROM country_langs WHERE FIND_IN_SET('{$locale}', locale) > 0";
    $cache_name = 'country_name_' . md5($sql);

    if ($countryName = getFromCache($cache_name)) {
        return $countryName->get();
    }

    $row = lectura($sql, $con);

    if ($country_name = ($row['country'] ?? null)) {
        $country_name = trim($country_name);
        setToCache($cache_name, $country_name, 31536000);
    }

    return $country_name ?? 'Unknown';
}

function getAge($birthday)
{
    $now = new DateTime('now');
    $birth = new DateTime($birthday);
    $diff = $birth->diff($now);

    return $diff->y;
}

function getNamePattern()
{
    return $_SESSION['userNavLang'] === "zh" ? "^([^0-9]*)$" : "^(?!.*?(.)\\1\\1)[^\\d]{2,}$";
}

function getNameMinLength()
{
    return $_SESSION['userNavLang'] === "zh" ? "1" : "2";
}

function validateName($name)
{
    $nameMinLength = getNameMinLength();
    $namePattern = getNamePattern();

    if (strlen($name) < $nameMinLength || !preg_match('/' . $namePattern . '/', $name)) {
        return false;
    }

    if ($_SESSION['userNavLang'] !== "zh") {
        if (preg_match('/[bcdfghjklmnpqrstvwxyzñçBCDFGHJKLMNPQRSTVWXYZÑÇ]{8}/i', $name)) {
            return false;
        }
    }
    
    return true;
}

function validateEmailString($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validateGender($gender)
{
    return in_array($gender, ['male', 'female', 'other', null]);
}

function validateDate($date)
{
    return DateTime::createFromFormat('Y-m-d', $date);
}

