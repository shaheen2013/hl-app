<?php //Miramos si esta definida la variable de control de index.php

/*
 * obtenerNombreHotelId($id) -> get hotel name from $id
 * getHotelData($id, $returnChain) -> get hotel data as array
 * obtenerGoalsHotel($id, $lang) -> return goals for hotel
 * rellenarArrayGoalsHotel
 */

if (!defined('INDEXCONTROLVAL')) {
    define("INDEXCONTROLVAL", "1");
}

// if (!defined('INDEXCONTROLVAL')) {
//     echo 'No direct access allowed.';
//     exit;
// }

include_once 'sanitize.php';
include_once RUTA_DIR . LIB . 'cache.php';

function obtenerNombreHotelId($id)
{
    //Get from cache
    $cacheName = 'obtenerNombreHotelId_' . $id;
    $cache = getFromCache($cacheName);

    if (!$cache) {
        $con = conectar(1);
        $id = mysqli_real_escape_string($con, $id);
        $sql = "SELECT hotelName FROM hoteles WHERE id='" . $id . "' ";
        $row = lectura($sql, $con, true);

        if ($row) {
            $tags = array('hotel', 'hotel_profile', 'hotel_profile_' . $id);
            setToCache($cacheName, $row, 31536000, $tags);
        }
    } else {
        $row = $cache->get();
    }

    return ($row['hotelName']);
}

// FX que utilizamos para mandar email en nombre del hotel
// Si el hotel es de cadena y la cadena tiene un email para el envio de email, el hotel manda desde la cadena
// $returnChain  : Si es un hotel de cadena pero queremos forzar que devuelva el email del hotel
function getHotelData($id, $returnChain = 1)
{

    //Get from cache
    $cacheName = 'hotelData_' . $id . '_' . $returnChain;
    $cache = getFromCache($cacheName);

    if (!$cache) {
        $con = conectar(1);
        $id = mysqli_real_escape_string($con, $id);
        $sql = "SELECT
                    hoteles.email AS email_hotel,
                    hoteles.hotelName,
                    hoteles.country,
                    hoteles.logo,
                    hoteles.fotoBg,                    
                    hoteles.place_name,
                    hoteles.fotoBg,                   
                    hoteles.twitterAccount,
                    hoteles.estrellas,
                    hoteles.stay_time,
                    cadena.stay_time as chain_stay_time,
                    cadena.email_envio AS cadena_email,
                    cadena.id AS id_cadena,
                    brands.id AS brand_id,
                    brands.parent_id, ";
        if ($returnChain == 1) {
            $sql .= " CASE WHEN cadena.email_envio!='' THEN cadena.email_envio
            ELSE hoteles.email END AS email ";
        } else {
            $sql .= " CASE WHEN hoteles.email!='' THEN hoteles.email END AS email ";
        }
        $sql .= " FROM hoteles
        LEFT JOIN cadena_hotel ON cadena_hotel.id_hotel='" . $id . "'
        LEFT JOIN cadena ON cadena.id=cadena_hotel.id_cadena
        LEFT JOIN
            brands
        ON
            brands.hotel_id = hoteles.id
        WHERE hoteles.id='" . $id . "' ";

        $row = lectura($sql, $con, true);

        if ($row) {
            $tags = array('hotel', 'hotel_profile', 'hotel_profile_' . $id, 'hotel_booking_info_' . $id);
            if ($row['id_cadena']) {
                $tags[] = 'cadena_email_' . $row['id_cadena'];
            } //Si es un hotel de cadena añadimos el tag de email de cadena

            setToCache($cacheName, $row, 31536000, $tags);
        }
    } else {
        $row = $cache->get();
    }

    return $row;
}

// El orden de idiomas para devolver los goals es:
// 1- El idioma de parametro
// 2- El idioma del hotel (plataforma)
// 3- Default 'en'
function obtenerGoalsHotel($hotel_id, $lang = 0)
{
    $arrayLangs = array();
    if ($lang != '0') {
        $arrayLangs[] = $lang;
        $arrayLangs[] = obtenerLangHotel($hotel_id); //langHotel
        $arrayLangs[] = 'en'; // Default
    } else {
        $arrayLangs[] = $_SESSION['userNavLang'];
        $arrayLangs[] = 'en'; // Default
    }
    $arrayLangs = array_keys(array_flip($arrayLangs)); //Eliminamos duplicados (NO array_unique)
    $nLangs = count($arrayLangs);

    $goals = array();
    $arrayIdOferta = array();

    //Get from cache
    $cacheName = 'hotelOfertasGoal_' . $hotel_id;
    $cache = getFromCache($cacheName);

    $con = conectar(1);

    if (!$cache) {
        $hotel_id = mysqli_real_escape_string($con, $hotel_id);
        // Select id_oferta + n_referrals de las ofertas de goal del hotel
        $sql2 = "SELECT DISTINCT(referral_goal.id_oferta) AS id_oferta, n_referrals
    FROM referral_goal
    INNER JOIN hotel_oferta_lang ON hotel_oferta_lang.id_oferta=referral_goal.id_oferta
    WHERE id_hotel='" . $hotel_id . "' AND hotel_oferta_lang.lang_ok=1 AND referral_goal.id_oferta!=0
    ORDER BY referral_goal.n_referrals ASC";
        $rows2 = lecturaArray($sql2, $con, false);

        $i = 0;
        foreach ($rows2 as $row2) {
            $arrayIdOferta[$i] = $row2['id_oferta'];
            //Prerellenamos el array de goals para evitar que una oferta con varios goals
            //salga con el n_referrals mas bajo varias veces
            $goals[$i]['n_referrals'] = $row2['n_referrals'];
            $goals[$i]['id_oferta'] = $row2['id_oferta'];
            // Inicializamos el resto de campos en blanco
            $goals[$i]['img'] = '';
            $goals[$i]['nombre'] = '';
            $goals[$i]['descripcion'] = '';
            $i++;
        }

        $tags = array('hotel', 'hotel_goals', 'hotel_goals_' . $hotel_id);
        setToCache($cacheName, $goals, 31536000, $tags);
    } else {
        $goals = $cache->get();
        foreach ($goals as $goal) {
            $arrayIdOferta[] = $goal['id_oferta'];
        }
    }

    // Por cada id miramos si esta en los langs por orden de prioridad
    $i = 0;
    $goalsData = array();
    foreach ($arrayIdOferta as $idOferta) {
        $t = 0;
        while ($t < $nLangs) {
            //Get from cache
            $cacheName2 = 'hotelGoalData_' . $idOferta . '_' . $arrayLangs[$t];
            $cache2 = getFromCache($cacheName2);

            if (!$cache2) {
                $sql = 'SELECT hotel_oferta.img,hotel_oferta_lang.nombre, hotel_oferta_lang.descripcion
            FROM referral_goal
            INNER JOIN hotel_oferta ON hotel_oferta.id=referral_goal.id_oferta
            INNER JOIN hotel_oferta_lang ON hotel_oferta_lang.id_oferta=hotel_oferta.id
            AND lang_ok=1 ';
                $sql .= ' AND lang="' . $arrayLangs[$t] . '" ';
                $sql .= ' WHERE hotel_oferta.id="' . $idOferta . '" ';
                $goalsData[$idOferta] = lectura($sql, 0, $con, false);

                if (!empty($goalsData[$idOferta]['nombre'])) {
                    $tags = array('hotel', 'hotel_oferta', 'oferta_' . $idOferta);
                    setToCache($cacheName2, $goalsData[$idOferta], 31536000, $tags);
                    break;
                }
            } else {
                $goalsData[$idOferta] = $cache2->get();
                break;
            }
            $t++;
        }
        $i++;
    }

    desconectar($con);

    //Rellenar $goalsResult con data + ids
    $goalsResult = rellenarArrayGoalsHotel($goals, $goalsData);

    return $goalsResult;
}

function rellenarArrayGoalsHotel($goals, $arrayDatosGoals)
{
    $arrayGoalsFinal = array();
    $i = 0;
    foreach ($goals as $goal) {
        foreach ($goal as $key => $valor) {
            if ($key == 'descripcion') {
                // Debemos cortar la descripción a 120 caracteres sin contar los tags HTML
                $descripcion = strip_tags($arrayDatosGoals[$goal['id_oferta']][$key]);
                $arrayGoalsFinal[$i][$key] = substr($descripcion, 0, 120);
            } elseif ($key == 'img' || $key == 'nombre') {
                $arrayGoalsFinal[$i][$key] = $arrayDatosGoals[$goal['id_oferta']][$key];
            } else {
                $arrayGoalsFinal[$i][$key] = $valor;
            }
        }
        $i++;
    }
    return $arrayGoalsFinal;
}

function getHotelWebsiteUrl($id, $langName)
{

    if (!$id || !$langName) {
        return '';
    }

    $endPoint = HOTELINKING_ENDPOINT . "brands/{$id}/languages/{$langName}/url";
    $gateway = new ApiGatewayConnection();

    return safeJsonParser($gateway->sendRequest([], $endPoint, 'GET'))[0]->url ?? '';
}

// Function that updates ALL the chain and hotel Websites.
function actualizarWebsitesReserva($brand_id, $langUrlArray)
{
    $endPoint = HOTELINKING_ENDPOINT . "brands/languages/url";
    $gateway = new ApiGatewayConnection();

    $bodyListUrlsUpdate = [];

    foreach ($langUrlArray as $name => $url) {
        $bodyListUrlsUpdate[] = [
            'brand_id' => $brand_id,
            'name' => $name,
            'url' => $url
        ];
    }
    $gateway->sendRequest($bodyListUrlsUpdate, $endPoint, 'PUT');
}

function getAllLanguagesFromBrand($brand_id)
{

    $endPoint = HOTELINKING_ENDPOINT . "brands/{$brand_id}/url";

    $gateway = new ApiGatewayConnection();

    $brandLanguages = safeJsonParser($gateway->sendRequest([], $endPoint, 'GET'), false);

    foreach ($brandLanguages as $brandLanguage) {
        if (strlen($brandLanguage->name) > 2)
            $brandLanguage->name = substr($brandLanguage->name, 0, 2);
    }

    return $brandLanguages;
}

function getAllLanguages()
{

    $endPoint = HOTELINKING_ENDPOINT . "languages";

    $gateway = new ApiGatewayConnection();

    $languages = safeJsonParser($gateway->sendRequest([], $endPoint, 'GET'), false);

    return $languages;
}

if (!function_exists('safeJsonParser')) {
    function safeJsonParser($object, $assoc = false)
    {
        if ($object) {
            try {
                return \GuzzleHttp\json_decode($object, $assoc);
            } catch (\Exception $e) {
                return [];
            }
        }

        return [];
    }
}

function obtenerMonedaHotel($hotel_id)
{
    //Get from cache
    $cacheName = 'obtenerMonedaHotel_' . $hotel_id;
    $cache = getFromCache($cacheName);

    if (!$cache) {
        $con = conectar(1);
        $hotel_id = mysqli_real_escape_string($con, $hotel_id);
        $sql = "SELECT moneda FROM hoteles WHERE id='" . $hotel_id . "' ";
        $row = lectura($sql, $con, true);

        if ($row) {
            $tags = array('hotel', 'hotel_profile', 'hotel_profile_' . $hotel_id);
            setToCache($cacheName, $row, 31536000, $tags);
        }
    } else {
        $row = $cache->get();
    }

    return $row['moneda'];
}

//FX para mirar si el id_hotel es correcto
function idHotelCorrecto($hotel_id)
{
    //Get from cache
    $cacheName = 'idHotelCorrecto_' . $hotel_id;
    $cache = getFromCache($cacheName);

    if (!$cache) {
        $con = conectar(1);
        $hotel_id = mysqli_real_escape_string($con, $hotel_id);
        $sql = "SELECT IFNULL(COUNT(id),0) AS n FROM hoteles WHERE id='" . $hotel_id . "' ";
        $row = lectura($sql, $con, true);

        if ($row) {
            setToCache($cacheName, $row, 31536000);
        }
    } else {
        $row = $cache->get();
    }

    if ($row['n'] == 0) {
        return false;
    } else {
        return true;
    }
}

//Obtiene los permisos que tiene el hotel
function obtenerPermisosHotel($brandId)
{
    $con = conectar(1);
    $brandId = mysqli_real_escape_string($con, $brandId);

    $sql2 = "SELECT producto FROM products";
    $rs2 = mysqli_query($con, $sql2);

    $sql = "SELECT ";

    while ($row = mysqli_fetch_assoc($rs2)) {
        //$productos[] = $row['producto'];
        $sql .= "CASE WHEN (SELECT CASE WHEN `active`=1 THEN 1 ELSE 0 END FROM `brand_product` LEFT JOIN `products` ON `brand_product`.`product_id`=`products`.`id` WHERE `brand_id`=$brandId AND `producto`='"  . $row['producto'] .  "') = 1 THEN 1 ELSE 0 END AS `" . $row['producto'] . "`, ";
    }

    liberar($rs2);
    //Quitamos última coma
    $sql = substr($sql, 0, -2);

    $row = lectura($sql, $con);
    return $row;
}

// FX para mirar si un hotel tiene contratado un solo producto
// En caso afirmativo lo devuelve, en caso de tener varios devuelve un 0
function mirarUnProductoHotel()
{
    $ly = $_SESSION['permisos']['LY'];
    $rf = $_SESSION['permisos']['RF'];
    $mk = $_SESSION['permisos']['MK'];

    if ($ly == 0 && $rf == 0 && $mk == 1) {
        //Solo tiene MK
        return 'MK';
    } elseif ($ly == 0 && $rf == 1 && $mk == 0) {
        //Solo tiene RF
        return 'RF';
    } elseif ($ly == 1 && $rf == 0 && $mk == 0) {
        //Solo tiene LY
        return 'LY';
    } else {
        //Tiene 2 o mÃ¡s
        return '0';
    }
}

//Devuelve id string de todos los lang que tiene el hotel
function obtenerIdStringLangsHotel($hotel_id)
{
    $langs = array();

    //Get from cache
    $cacheName = 'obtenerIdStringLangsHotel_' . $hotel_id;
    $cache = getFromCache($cacheName);

    if (!$cache) {
        $con = conectar(1);
        $hotel_id = mysqli_real_escape_string($con, $hotel_id);

        $sql = "SELECT lang.lang
        FROM lang_hotel
        INNER JOIN lang ON lang.id=lang_hotel.id_lang
        WHERE lang_hotel.id_hotel='" . $hotel_id . "' ";
        $rs = mysqli_query($con, $sql);
        desconectar($con);
        while ($row = mysqli_fetch_assoc($rs)) {
            $langs[] = $row['lang'];
        }
        liberar($rs);

        if ($langs) {
            $tags = array('hotel', 'hotel_langs_' . $hotel_id);
            setToCache($cacheName, $langs, 31536000, $tags);
        }
    } else {
        $langs = $cache->get();
    }

    return $langs;
}

//Langs de ofertas de hotel
//Por defecto devuelte el lang del hotel como primer elemento
function obtenerLangsHotel($hotel_id)
{
    $con = conectar(1);
    $hotel_id = (int)$hotel_id;
    $sql = "
        SELECT
            lang.id,
            lang.lang,
            lang.img,
            lang.country,
            hoteles.lang AS langHotel,
            IF(hoteles.lang = lang.lang, 1, 0) AS defaultLang
        FROM
            lang_hotel
        INNER JOIN
            lang
        ON
            lang.id = lang_hotel.id_lang
        INNER JOIN
            hoteles
        ON
            hoteles.id = lang_hotel.id_hotel
        WHERE
            lang_hotel.id_hotel = {$hotel_id} AND
            lang.content = 1
        GROUP BY
            lang.id,
            lang.lang,
            lang.img,
            lang.country,
            hoteles.lang
        ORDER BY
            defaultLang
        DESC";

    return lecturaArray($sql, $con, true);
}

//Lang (1) por defecto del hotel (plataforma)
function obtenerLangHotel($hotel_id)
{
    //Get from cache
    $cacheName = 'obtenerLangHotel_' . $hotel_id;
    $cache = getFromCache($cacheName);

    if (!$cache) {
        $con = conectar(1);
        $hotel_id = mysqli_real_escape_string($con, $hotel_id);
        $sql = "SELECT hoteles.lang
        FROM hoteles WHERE hoteles.id='" . $hotel_id . "' ";
        $row = lectura($sql, $con, false);

        if ($row) {
            $tags = array('hotel', 'hotel_default_lang_' . $hotel_id);
            setToCache($cacheName, $row, 31536000, $tags);
        }
    } else {
        //Get result from cache
        $row = $cache->get();
    }

    return $row['lang'];
}

//Obtener la oferta de la landing
function obtenerOfertaReferral($hotel_id, $lang = null)
{
    if (!$lang && empty($_SESSION['userLang'])) {
        $lang = 'en';
    } elseif (!empty($_SESSION['userLang'])) {
        $lang = $_SESSION['userLang'];
    } else {
        $lang = $lang;
    }
    $con = conectar(1);
    if (hotelDeCadena($hotel_id)) {
        $id_cadena = hotelIdCadena($hotel_id);

        //Get from cache
        $cacheName = 'OOR_hotelDeCadena_' . $hotel_id;
        $cache = getFromCache($cacheName);

        if (!$cache) {
            $hotel_id = mysqli_real_escape_string($con, $hotel_id);
            $sql = "SELECT
            (SELECT id_oferta FROM cadena_oferta_referral WHERE id_cadena='" . $id_cadena . "') AS id_oferta_cadena,
            (SELECT id_oferta FROM hotel_oferta_referral WHERE id_hotel='" . $hotel_id . "') AS id_oferta_hotel ";
            $row = lectura($sql, $con, false);

            $row['id_oferta_cadena'] != '' ? $id_oferta = $row['id_oferta_cadena'] : $id_oferta = $row['id_oferta_hotel'];

            if ($row) {
                $tags = array('hotel', 'hotel_oferta_landing_' . $hotel_id, 'cadena_oferta_landing_' . $id_cadena, 'hotel_oferta_' . $id_oferta);
                setToCache($cacheName, $row, 31536000, $tags);
            }
        } else {
            //Get result from cache
            $row = $cache->get();
            $row['id_oferta_cadena'] != '' ? $id_oferta = $row['id_oferta_cadena'] : $id_oferta = $row['id_oferta_hotel'];
        }
    } else {

        //Get from cache
        $cache = getFromCache('OOR_' . $hotel_id);

        if (!$cache) {
            $hotel_id = mysqli_real_escape_string($con, $hotel_id);
            $sql = "SELECT id_oferta FROM hotel_oferta_referral WHERE id_hotel='" . $hotel_id . "' ";
            $row = lectura($sql, $con, false);
            if ($row) {
                $tags = array('hotel', 'hotel_oferta_landing_' . $hotel_id);
                setToCache('OOR_' . $hotel_id, $row, 31536000, $tags);
            }
        } else {

            //Get result from cache
            $row = $cache->get();
        }

        $id_oferta = array_get($row, 'id_oferta');
    }

    if (!empty($id_oferta)) {
        //Get from cache
        $cache2 = getFromCache('OOR_oferta_' . $id_oferta . '_' . $lang);

        if (!$cache2) {
            $sql2 = "SELECT hotel_oferta.id,  CASE WHEN oferta_lang.nombre IS NULL
            THEN   oferta_en.nombre
            ELSE oferta_lang.nombre END AS nombre, img,
            booking_engine_code AS bookingEngineCode
            FROM hotel_oferta
            LEFT JOIN hotel_oferta_lang AS oferta_en   ON hotel_oferta.id = oferta_en.id_oferta   AND oferta_en.lang='en'
            LEFT JOIN hotel_oferta_lang AS oferta_lang ON hotel_oferta.id = oferta_lang.id_oferta AND oferta_lang.lang='" . $_SESSION['userLang'] . "'
            WHERE hotel_oferta.id='" . $id_oferta . "' ";
            $oferta = lectura($sql2, $con, false);
            $oferta['nombre_san'] = string_sanitize($oferta['nombre']);

            if ($oferta) {
                setToCache('OOR_oferta_' . $id_oferta . '_' . $lang, $oferta, 31536000);
            }
        } else {
            //Get result from cache
            $oferta = $cache2->get();
        }
    } else {
        //Cargamos una oferta vacia
        $oferta = ofertaReferralVacia();
    }
    desconectar($con);

    return $oferta;
}

// FX que devuelve el id_hotel a partir de un GUID de hotel
function obtenerIdHotelGUID($guid)
{
    //Get from cache
    $cachedGuid = getFromCache('idByGuid_' . $guid);

    //If not in cache
    if (!$cachedGuid) {
        $con = conectar(1);
        $guid = mysqli_real_escape_string($con, $guid);
        //Get result from DDBB
        $sql = "SELECT id_hotel FROM hotel_guid WHERE guid='" . $guid . "' ";
        $row = lectura($sql, $con, true);
        $hotel_id = array_get($row, 'id_hotel');

        if ($hotel_id) {
            //Store it in cache
            $array = [
                "id_hotel" => $hotel_id,
            ];

            setToCache('idByGuid_' . $guid, $array, 31536000);
        }
    } else {

        //Get result from cache
        $result = $cachedGuid->get();
        $hotel_id = $result['id_hotel'];
    }
    return $hotel_id;
}

//ESPECIAL de affilired
//Obtener el ID del hotel a travÃ©s del nombre
function obtenerIdHotelName($name)
{
    //Get from cache
    $cacheName = 'obtenerIdHotelName_' . $name;
    $cachedGuid = getFromCache($cacheName);

    //If not in cache
    if (!$cachedGuid) {
        $con = conectar(1);
        $name = mysqli_real_escape_string($con, $name);
        $sql = "SELECT id_hotel, guid FROM hotel_guid WHERE affilired_hotel='" . $name . "' ";
        $row = lectura($sql, $con, true);

        if ($row) {
            $tags = array('hotel', 'hotel_affilired', 'hotel_affilired_' . $row['guid']);
            setToCache($cacheName, $row, 31536000, $tags);
        }
    } else {
        $row = $cachedGuid->get();
    }

    return $row;
}

//Generamos la URL del Hotel a partir de su id
//$tipo: 0 -> url del hotel,
//      1 -> url de la landing del hotel,
//      3 -> url de referral-share-step-2 del hotel
function obtenerUrlGUIDHotel($hotel_id, $tipo = 0)
{
    $urlHotel = '';

    //Get from cache
    $cacheName = 'obtenerUrlGUIDHotel_' . $hotel_id;
    $cache = getFromCache($cacheName);

    if (!$cache) {
        $con = conectar(1);
        $hotel_id = mysqli_real_escape_string($con, $hotel_id);
        $tipo = mysqli_real_escape_string($con, $tipo);

        $sql = "SELECT hotelName, guid
        FROM hoteles
        INNER JOIN hotel_guid ON hotel_guid.id_hotel=hoteles.id
        WHERE hoteles.id='" . $hotel_id . "' ";

        $row = lectura($sql, $con, true);

        if ($row) {
            $tags = array('hotel', 'hotel_profile', 'hotel_profile_' . $hotel_id);
            setToCache($cacheName, $row, 31536000, $tags);
        }
    } else {
        $row = $cache->get();
    }

    global $urlTree;
    if ($tipo == 0) {
        $urlHotel = BASE_PATH . $urlTree['hotel'] . '/' . string_sanitize($row['hotelName']) . '/' . $row['guid'];
    } elseif ($tipo == 1) {
        $urlHotel = BASE_PATH . $urlTree['digital-loyalty-program'] . '/' . string_sanitize($row['hotelName']) . '/' . $row['guid'];
    } elseif ($tipo == 3) {
        $urlHotel = BASE_PATH . $urlTree['referral-share-step-2'] . '/' . $row['guid'];
    }

    return $urlHotel;
}

// FX que devuelve el GUID a partir de un id de hotel
function obtenerGUIDHotel($hotel_id)
{
    //Get from cache
    $cacheName = 'obtenerGUIDHotel' . $hotel_id;
    $cache = getFromCache($cacheName);

    if (!$cache) {
        $con = conectar(1);
        $hotel_id = mysqli_real_escape_string($con, $hotel_id);
        $sql = "SELECT guid FROM hotel_guid WHERE id_hotel='" . $hotel_id . "' ";
        $row = lectura($sql, $con, true);

        if ($row) {
            setToCache($cacheName, $row, 31536000);
        }
    } else {
        //Get result from cache
        $row = $cache->get();
    }

    return $row['guid'];
}

function ofertaReferralVacia()
{
    $ofertaReferral = array(
        'id' => '',
        'nombre' => '',
        'img' => '',
        'nombre_san' => '',
    );
    return $ofertaReferral;
}

//FX que devuelve el id de las ofertas de pre-stay, stay y post-stay del hotel
function obtenerOfertasStay($hotel_id)
{
    //Get from cache
    $cacheName = 'hotelOfertasStay_' . $hotel_id;
    $cache = getFromCache($cacheName);

    if (!$cache) {
        $con = conectar(1);
        $hotel_id = mysqli_real_escape_string($con, $hotel_id);
        $sql = "SELECT
        IFNULL((SELECT id_oferta FROM hotel_oferta_prestay WHERE id_hotel='" . $hotel_id . "'), 0) AS prestay,
        IFNULL((SELECT url FROM hotel_oferta_stay WHERE id_hotel='" . $hotel_id . "'), 0) AS stay,
        IFNULL((SELECT id_oferta FROM hotel_oferta_poststay WHERE id_hotel='" . $hotel_id . "'), 0) AS poststay;";
        $row = lectura($sql, $con, true);

        if ($row) {
            $tags = array("hotel", "hotel_goals", "hotel_goals_" . $hotel_id);
            setToCache($cacheName, $row, 31536000, $tags);
        }
    } else {
        //Get result from cache
        $row = $cache->get();
    }

    return $row;
}

//Fx para obtener oferta pre/post stay
//$tipo: 'pre' (pre-stay), 'post' (post-stay)
function obtenerOfertaStay($hotel_id, $tipo)
{
    $idsOfertasStay = obtenerOfertasStay($hotel_id);
    $lang = $_SESSION['userLang'];

    //Get from cache
    $cacheName = 'ofertaStay_' . $hotel_id . '_' . $tipo . '_' . $lang;
    $cache = getFromCache($cacheName);

    if (!$cache) {
        $con = conectar(1);
        $hotel_id = mysqli_real_escape_string($con, $hotel_id);
        $sql = "SELECT
          CASE WHEN oferta_lang.nombre IS NULL
          THEN  oferta_en.nombre
          ELSE oferta_lang.nombre END AS nombre_oferta
          FROM hotel_oferta_" . $tipo . "stay
          LEFT JOIN hotel_oferta_lang AS oferta_en ON " . $idsOfertasStay[$tipo . 'stay'] . " = oferta_en.id_oferta  AND oferta_en.lang='en'
          LEFT JOIN hotel_oferta_lang AS oferta_lang ON " . $idsOfertasStay[$tipo . 'stay'] . " = oferta_lang.id_oferta AND oferta_lang.lang='" . $lang . "'
          WHERE id_hotel='" . $hotel_id . "' ";
        $row = lectura($sql, $con, true);

        if ($row) {
            $tags = array('hotel', 'hotel_goals', 'hotel_goals_' . $hotel_id, 'oferta_' . $idsOfertasStay[$tipo . 'stay']);
            setToCache($cacheName, $row, 31536000, $tags);
        }
    } else {

        //Get result from cache
        $row = $cache->get();
    }

    return $row;
}

//Check if Iframe must be shown
// $landing: true / false
function checkShowIframe($hotel_id, $landing)
{
    //Get from cache
    $cacheName = 'hotelIframe_' . $hotel_id . '_' . $landing;
    $cache = getFromCache($cacheName);

    if (!$cache) {
        $con = conectar(1);
        $hotel_id = mysqli_real_escape_string($con, $hotel_id);
        if ($landing == false) {
            $sql = "SELECT iframe FROM hoteles WHERE id='$hotel_id'";
        } else {
            $sql = "SELECT landing_iframe FROM hoteles WHERE id='$hotel_id'";
        }
        $row = lectura($sql, $con, true);

        if ($row) {
            $tags = array('hotel', 'hotel_iframe', 'hotel_iframe_' . $hotel_id . '_' . $landing);
            setToCache($cacheName, $row, 31536000, $tags);
        }
    } else {
        //Get result from cache
        $row = $cache->get();
    }

    if ($landing == false) {
        return $row['iframe'];
    } else {
        return $row['landing_iframe'];
    }
}

//Obtener toda la informaciÃ³n de wifi de la pÃ¡gina del usuario
function obtenerWifiStayHotel($hotel_id)
{
    $cache = getFromCache('wifiStay_' . $hotel_id);
    if (!$cache) {
        $con = conectar(1);
        $hotel_id = mysqli_real_escape_string($con, $hotel_id);
        $sql = "SELECT hotel_oferta_stay.*, hotel_wifi_integrations.wifi_id, wifi_providers.name AS wifi_name
        FROM hotel_wifi_integrations
        LEFT JOIN hotel_oferta_stay ON hotel_wifi_integrations.hotel_id=hotel_oferta_stay.id_hotel
        LEFT JOIN wifi_providers ON wifi_providers.id=hotel_wifi_integrations.wifi_id
        WHERE hotel_wifi_integrations.hotel_id='" . $hotel_id . "' ";
        $row = lectura($sql, $con, true);
    } else {
        //Get results from cache
        $row = $cache->get();
        if ($row) {
            $tags = array('hotel', 'hotel_wifi', 'hotel_wifi_' . $hotel_id);
            setToCache('wifiStay_' . $hotel_id, $row, 31536000, $tags);
        }
    }
    return $row;
}

function getGuidFromToken($token)
{
    //Get from cache
    $cacheName = 'getGuidFromToken_' . $token;
    $cache = getFromCache($cacheName);

    if (!$cache) {
        $con = conectar(1);
        $token = mysqli_real_escape_string($con, $token);
        $sql = 'SELECT guid FROM referrer_tokens
        INNER JOIN hotel_guid ON referrer_tokens.id_hotel = hotel_guid.id_hotel
        WHERE referrer_tokens.token="' . $token . '" LIMIT 1';
        $row = lectura($sql, $con);

        if ($row) {
            setToCache($cacheName, $row, 31536000);
        }
    } else {
        //Get result from cache
        $row = $cache->get();
    }

    return array_get($row, 'guid');
}

//Get hotel facebook page TODO DELETE when not used
function getFacebookPage($id)
{
    //Get from cache
    $cacheName = 'fbPage_' . $id;
    $cache = getFromCache($cacheName);

    if (!$cache) {
        $con = conectar(1);
        $id = mysqli_real_escape_string($con, $id);
        $sql = "SELECT facebook_page FROM hoteles WHERE id = $id";
        $row = lectura($sql, $con, true);

        if ($row) {
            $tags = array("hotel", "hotel_facebook", "hotel_facebook_" . $id);
            setToCache($cacheName, $row, 31536000, $tags);
        }
    } else {
        //Get result from cache
        $row = $cache->get();
    }

    return $row['facebook_page'];
}

//Get wifi Id and name;
function getWifiProvider($id)
{
    //Get from cache
    $cache = getFromCache('wifiProv_' . $id);

    if (!$cache) {
        $con = conectar(1);
        $id = mysqli_real_escape_string($con, $id);
        $sql = "SELECT wifi_id, wifi_providers.name FROM hotel_wifi_integrations
        INNER JOIN wifi_providers ON wifi_providers.id = hotel_wifi_integrations.wifi_id
        WHERE hotel_id = $id";
        $row = lectura($sql, $con, true);

        if ($row) {
            $tags = array("hotel", "hotel_wifi", "hotel_wifi_" . $id);
            setToCache('wifiProv_' . $id, $row, 31536000, $tags);
        }
    } else {
        //Get result from cache
        $row = $cache->get();
    }

    return $row;
}

// get social media share text by hotel and lang
function obtenerSMTextShareIdioma($hotel_id, $lang)
{
    $con = conectar(1);
    $hotel_id = mysqli_real_escape_string($con, $hotel_id);
    $lang = mysqli_real_escape_string($con, $lang);

    $sql = "SELECT pre, stay, post FROM hotel_share_text WHERE id_hotel=$hotel_id AND lang='$lang' ";
    $row = lectura($sql, $con, true);
    empty($row) ? $row = array('pre' => '', 'stay' => '', 'post' => '') : '';
    return $row;
}

// FX para extraer el hotel_id de la BD de emails por el id_hotel de HL
function obtenerIdHotelBDEmails($hotel_id_HL)
{
    $sql = "SELECT id FROM hotels WHERE id_hotelinking=$hotel_id_HL";
    $row = lectura($sql, '', true, 2);
    return ($row['id']);
}

/*
 * Get a list of all offers for given Hotel ID
 * @param Hotel ID
 * @param Lang for name format
 * @return Array(id, img, name)
 */
function getHotelOfferList($id, $lang)
{
    $con = conectar(1); // 1 significa que lee de la read réplica
    $hotel_id = mysqli_real_escape_string($con, $id);
    $lang = mysqli_real_escape_string($con, $lang);

    $where = "hotel_oferta.id_hotel = $hotel_id";

    //Datos de la cadena si aplica
    if (hotelDeCadena($hotel_id)) {
        $chain_id = hotelIdCadena($hotel_id);
        $where = "hotel_oferta.id_cadena= $chain_id";
    }

    $sql = "SELECT
                    distinct hotel_oferta.id,
                    hotel_oferta.img,
                    case when oferta_lang.nombre is null
          then  oferta_en.nombre
          else oferta_lang.nombre end as nombre
                FROM hotel_oferta
                LEFT JOIN hotel_oferta_lang as oferta_en on hotel_oferta.id = oferta_en.id_oferta  and oferta_en.lang='en'
                LEFT JOIN hotel_oferta_lang as oferta_lang on hotel_oferta.id = oferta_lang.id_oferta and oferta_lang.lang='" . $lang . "'
                LEFT JOIN hotel_oferta_lang ON hotel_oferta.id = hotel_oferta_lang.id_oferta
                WHERE $where";

    $result = lecturaArray($sql, $con);

    return $result;
}

/*
 * Get an offer detail for given offer ID
 * @param Offer ID
 * @param Lang for name format
 * @return Array(id, name, description, conditions, img)
 */
function getHotelOfferDetails($id, $lang = 'en')
{
    global $log;
    $cacheName = 'OfferDetails_' . $id . '_' . $lang;
    $cache = getFromCache($cacheName);
    if (!$cache) {
        $con = conectar(1);
        $offer_id = mysqli_real_escape_string($con, $id);
        $lang = mysqli_real_escape_string($con, $lang);

        if ($lang != 'en') {
            $count = "SELECT lang, COUNT(*) FROM hotel_oferta_lang WHERE hotel_oferta_lang.id_oferta = '$id'";
        }

        if (!empty($count) && $count == 0) {
            $lang = 'en';
        }

        $sql = "SELECT
                    hotel_oferta.id,
                    hotel_oferta_lang.nombre,
                    hotel_oferta_lang.descripcion,
                    hotel_oferta_lang.condiciones,
                    hotel_oferta.img
                FROM hotel_oferta
                INNER JOIN hotel_oferta_lang ON hotel_oferta_lang.id_oferta = hotel_oferta.id
                WHERE hotel_oferta.id = '$offer_id'
                AND hotel_oferta_lang.lang = '$lang'";
        $result = lectura($sql, $con);
        if ($result) {
            $tags = array("hotel", "hotel_goals", "birthdayOffersFromHotel_" . $id);
            setToCache($cacheName, $result, 31536000, $tags);
        }
    } else {
        $result = $cache->get();
    }
    return $result;
}

/*
 * Check if hotel is part of hotel globales group
 * @param Hotel ID
 * @return Boolean
 */
function isHotelesGlobales($id)
{
    $cadenaId = hotelIdCadena($id);
    if ($cadenaId === '9') {
        return true;
    } else {
        return false;
    }
}

// NEW HOTEL PRODUCTS

function getBrandProductMappings($brandID)
{
    $sql = "SELECT producto FROM products LEFT JOIN brand_product ON products.id=brand_product.id WHERE brand_id=$brandID AND active=1;";
    return lecturaArray($sql);
}

function getHotelBrand($hotel_id)
{
    if (!is_null($hotel_id)) {
        $cacheName = "Brand_id_for_hotel_$hotel_id";
        $cache = getFromCache($cacheName);

        if (!$cache) {
            $con = conectar(1);
            $sql = "SELECT *
                    FROM brands
                    WHERE hotel_id = $hotel_id";
            $row = lectura($sql, $con, false);

            if ($row) {
                setToCache($cacheName, $row, 31536000);
            }
        } else {
            $row = $cache->get();
        }

        return $row;
    }
    return null;
}

function getChainBrand($chainID)
{
    if (!is_null($chainID)) {
        $cacheName = "Brand_id_for_chain_$chainID";
        $cache = getFromCache($cacheName);

        if (!$cache) {
            $con = conectar(1);
            $sql = "SELECT *
                    FROM brands
                    WHERE chain_id = $chainID";
            $row = lectura($sql, $con, false);

            if ($row) {
                setToCache($cacheName, $row, 31536000);
            }
        } else {
            $row = $cache->get();
        }

        return $row;
    }
    return null;
}

function getBrandById($brandId)
{
    if (is_null($brandId)) {
        return null;
    }

    $cacheName = "getBrandId-$brandId";
    $cache = getFromCache($cacheName);

    if (!$cache) {
        $sql = "SELECT * FROM brands WHERE id = $brandId";
        $row = lectura($sql);
        if ($row) {
            setToCache($cacheName, $row, 31536000);
        }
    } else {
        $row = $cache->get();
    }

    return $row;
}


function getHotelDataByListIds($arrayHotelIdList)
{
    if (!empty($arrayHotelIdList)) {
        $hotelIdList = json_encode($arrayHotelIdList);
        $hotelIdList = str_replace('[', '(', $hotelIdList);
        $hotelIdList = str_replace(']', ')', $hotelIdList);

        $cacheName = "Datamatch_hotel_data_for_idlist_" . str_replace(")", "", str_replace("(", "", $hotelIdList));
        $cache = getFromCache($cacheName);

        if (!$cache) {
            $con = conectar(1);
            $sql = "SELECT id,
                            hoteles.hotelName as name
                    FROM hoteles
                    WHERE hoteles.id IN $hotelIdList
                    ORDER BY name asc";

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

function getIdByProductName($product_name)
{
    $key = 'get_id_product_by_name_' . $product_name;
    $id = null;
    $cache = getFromCache($key);

    if (!$cache) {
        $con = conectar(1);
        $sql = "SELECT id FROM products WHERE producto = '{$product_name}'";
        $id = lecturaArray($sql, $con)[0]['id'] ?? null;

        if ($id) {
            setToCache($key, $id, 31536000);
        }
    } else {
        $id = $cache->get();
    }

    return (int)$id;
}

function getProductConfig(int $brandID, string $productName)
{
    $cacheName = 'ProductConfig_' . $brandID . '_' . $productName;
    $cache = getFromCache($cacheName);

    if (!$cache) {
        $gateway = new ApiGatewayConnection();
        $endPoint = HOTELINKING_ENDPOINT . "brands/{$brandID}/products/" . getIdByProductName($productName) . "/configuration";

        return safeJsonParser($gateway->sendRequest([], $endPoint, 'GET'), true);
    } else {
        return $cache->get();
    }
}
