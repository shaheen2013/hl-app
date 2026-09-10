<?php //Miramos si esta definida la variable de control de index.php

if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}

include_once LIB . 'sanitize.php';
include_once LIB . 'obtenerdatosHotel.php';
include_once LIB . 'idiomas.php';
include_once APP . 'Services/Connections/ApiGatewayConnection.php';

function borrarDatosLoginStaff()
{
    unset($_SESSION['staff_logueado']);
    unset($_SESSION['staff_id_hotel']);
}

// Loguear hotel
//$cad: indica si es una cadena cambiando de hotel
function loguearHotel($id, $cad = 0)
{
    // Datos hotel para el login
    // $row = obtenerDatosHotelLogin($id); // BD
    $hotel = $_SESSION['hotel'] = obtenerDatosHotelLogin($id);

    $_SESSION['hotel_activated'] = $hotel['activated'];

    borrarDatosLoginStaff();
    unset($_SESSION['u_logueado']);
    if ($cad == 0) {
        unset($_SESSION['c_logueado']);
    }

    //store hotel info in session
    //	$_SESSION['hotel'] = array();
    // $hotel_id = $_SESSION['hotel']['id'] = $id;
    // $_SESSION['hotel']['guid'] = $hotel['guid'];
    //	$_SESSION['hotel']['name'] = $row['hotelName'];
    //	$_SESSION['hotel']['logo'] = $row['logo'];

    // Cargamos los datos necesarios del hotel en variables de session
    $hotel_id = $_SESSION['h_logueado'] = $id;
    $_SESSION['loggedBrandID'] = array_get($hotel, 'brand_id');
    $_SESSION['loggedParentBrandID'] = array_get($hotel, 'parent_brand_id');
    $_SESSION['guid_logueado'] = $hotel['guid'];
    $_SESSION['hotelName'] = $hotel['name'];
    $_SESSION['nombre_san'] = string_sanitize($hotel['name']);
    $_SESSION['logoHotel'] = $hotel['logo'];
    //Guardamos los permisos del hotel (LY, RF, MK)
    $_SESSION['permisos'] = $permisos = obtenerPermisosHotel($_SESSION['loggedBrandID']);
    $_SESSION['userLang'] = mirarIdiomaPlataforma($hotel['lang']);
    //El idioma del navegador del hotelero debe ser el mismo que el idioma que tiene en su perfil
    $_SESSION['userNavLang'] = $hotel['lang'];

    if (hotelDeCadena($id) && empty($_SESSION['c_logueado'])) {
        $_SESSION['isIndependent'] = 0; //Para NO mostar pantallas de cadena
    } else {
        $_SESSION['isIndependent'] = 1; //Para mostar pantallas de cadena
    }

    $result['defaultPage'] = 'hotel-edit-profile-details';
    $result['hotel'] = $hotel;

    $_SESSION['defaultPage'] = $result['defaultPage'];

    //NEW HOTEL PRODUCTS
    $_SESSION['products'] = array_flatten(getBrandProductMappings($_SESSION['loggedBrandID']));

    return $result;
}

// Loguear Staff del Hotel
function loguearStaff($id, $hotel_id = null)
{

    $sql = "
        SELECT
            hotel_staff_hotels.hotel_id,
            hoteles.hotelName, hoteles.logo as logoHotel, 
            IFNULL(cadena_hotel.id_cadena, 0) AS id_cadena,
            id_role AS staff_role,
            hotel_staff_roles.role_en AS staff_role_type,
	        brands.id AS brand_id,
            brands.parent_id AS parent_brand_id
        FROM
            hotel_staff
        INNER JOIN
            hotel_staff_hotels
        ON
            hotel_staff.id = hotel_staff_hotels.hotel_staff_id
        INNER JOIN
            hoteles
        ON
            hoteles.id = hotel_staff_hotels.hotel_id
        LEFT JOIN
            cadena_hotel
        ON
            cadena_hotel.id_hotel = hoteles.id
        LEFT JOIN
            hotel_staff_roles
        ON
            hotel_staff_roles.id = hotel_staff.id_role
        LEFT JOIN
            brands
        ON
	        hoteles.id = brands.hotel_id
        WHERE
              hotel_staff.id = {$id} AND
              hoteles.activated = 1";

    $row = lecturaArray($sql);
    if (!$row) {
        return false;
    }
    //If the staff relogin with another hotel_id, get the position of hotel who has clicked
    $key = array_search($hotel_id, array_column($row, 'hotel_id'));
    if (!$key) {
        $key = 0;
    }

    // Borrar datos login hotel / cadena
    unset($_SESSION['h_logueado']);
    unset($_SESSION['u_logueado']);
    unset($_SESSION['c_logueado']);
    unset($_SESSION['loggedBrandID']);
    unset($_SESSION['loggedParentBrandID']);

    //Cargamos los datos necesarios del hotel en variables de session
    $_SESSION['staff_logueado'] = $id;
    $_SESSION['staff_id_hotel'] = array_get($row, $key . '.hotel_id');
    $_SESSION['loggedBrandID'] = array_get($row, $key . '.brand_id');
    $_SESSION['loggedParentBrandID'] = array_get($row, $key . '.parent_brand_id');

    // Cargamos los mismos permisos que el hotel
    $_SESSION['permisos'] = obtenerPermisosHotel(array_get($row, $key . '.brand_id'));
    $_SESSION['staff_id_cadena'] = array_get($row, $key . '.id_cadena');
    $_SESSION['staff_role'] = array_get($row, $key . '.staff_role');
    $_SESSION['staff_role_type'] = array_get($row, $key . '.staff_role_type');
    $_SESSION['hotelName'] = array_get($row, $key . '.hotelName');
    $_SESSION['logoHotel'] = array_get($row, $key . '.logoHotel');
    $_SESSION['isIndependent'] = 0; //Para NO mostar pantallas de cadena
    $_SESSION['chain']['brand_id'] = array_get($row, $key . '.parent_brand_id');
    $_SESSION['brand_id'] = array_get($row, $key . '.brand_id');


    include_once RUTA_DIR . LIB . 'obtenerDatosStaff.php';
    // Obtenemos los controladores a los que puede acceder este staff
    $_SESSION['staff_controllers'] = obtenerControladoresStaff($id);
    // Obtenemos la pagina por default del Staff
    $_SESSION['defaultPage'] = $result['defaultPage'] = $_SESSION['staff_controllers'][0];

    return $result;
}

// Loguear cadena hotelera
function loguearCadena($id, $childHotelId = null)
{
    $row = obtenerDatosCadenaLogin($id);
    include MODEL . 'chain-privacyModel.php';

    $_SESSION['chain'] = array();

    //Cargamos los datos necesarios de la cadena en variables de session
    $_SESSION['chain']['id'] = $_SESSION['c_logueado'] = $id;

    // Logueamos el hotel por defecto
    $result = loguearHotel($childHotelId ? $childHotelId : array_get($row, '0.id_hotel'), 1);

    $_SESSION['chain']['brand_id'] = $result['hotel']['parent_brand_id'];

    $chainEprivacy = getEprivacyPermisions($_SESSION['chain']['brand_id']);
    $_SESSION['chain']['eprivacy_responsable'] = $_SESSION['c_eprivacy_responsable'] = $chainEprivacy;


    $result['defaultPage'] = 'chain-management';

    return $result;
}

function getBrandChilds($brand_id, $options = [])
{
    $endPoint = "brands/{$brand_id}/childs/hotel";
    $key = md5($endPoint . $brand_id . serialize($options));
    if ($cache = getFromCache($key)) {
        return $cache->get();
    }

    $chainInfo = [];
    $gateway = new ApiGatewayConnection();
    $response = $gateway->sendRequest($options, HOTELINKING_ENDPOINT . $endPoint, 'GET');

    if (!empty($response)) {
        $chainInfo = json_decode($response, true);
        setToCache($key, $chainInfo, 900);
    }

    return $chainInfo;
}

//Datos de hotel para el Login
function obtenerDatosHotelLogin($hotel_id)
{
    $con = conectar(1);
    $hotel_id = (int)$hotel_id;
    $sql = "SELECT hoteles.id,
				hoteles.email,
				hoteles.hotelName as name,
				hoteles.city,
				hoteles.place_name,
				hoteles.place_country,
				hoteles.website,
				hoteles.telefonoReservas,
				IFNULL(hoteles.logo, 0) AS logo,
				hoteles.lang,
				hoteles.sending_email,
				hoteles.stay_time,
				hoteles.loyalty_min_visits,
				hotel_guid.guid,
                hoteles.activated,
                brands.uuid AS brand_uuid,
                brands.id AS brand_id,
                brands.parent_id AS parent_brand_id
			FROM
			    hoteles
			LEFT JOIN
                hotel_guid
            ON
                hotel_guid.id_hotel = hoteles.id
			LEFT JOIN
			    brands
			ON
			    brands.hotel_id = hoteles.id
			WHERE
                hoteles.id = {$hotel_id}";

    return lectura($sql);
}

//Datos de cadena para el Login
function obtenerDatosCadenaLogin($chain_id)
{
    $con = conectar(1);
    $chain_id = (int)$chain_id;

    $sql = "SELECT
                cadena_hotel.id_hotel,
                hoteles.hotelName,
                hoteles.logo,
                hoteles.lang,
                brands.id AS brand_id,
                brands.parent_id
            FROM
                cadena_hotel
            INNER JOIN
                hoteles
            ON
                hoteles.id = cadena_hotel.id_hotel
            LEFT JOIN
                brands
            ON
			    brands.hotel_id = cadena_hotel.id_hotel
            WHERE
                cadena_hotel.id_cadena = {$chain_id}
            AND
                hoteles.activated = 1
            ORDER BY
                cadena_hotel.id_hotel
            ASC";

    $row = lecturaArray($sql);
    return $row;
}

function getChainName($chain_id)
{
    $sql = "SELECT nombre from cadena where id = $chain_id";
    return lectura($sql);
}

function setUserCognito($data_login, $password)
{
    $username = $data_login['hotel']['email'];
    $brand_uuid = $data_login['hotel']['brand_uuid'];

    if ($username && $brand_uuid) {
        $cognito = new Cognito($username, $password, $brand_uuid);
        $cognito->setCognitoUserIfNotExists();
    }
}
