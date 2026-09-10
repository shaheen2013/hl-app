<?php //Miramos si esta definida la variable de control de index.php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}

include_once LIB . 'hotelinking_emails.php';
include_once LIB.'generarUrlCorrecta.php';
include_once LIB.'loguearHotel.php';

// Old way to update hoteles table
function actualizarHotelero($hotel_id, $hotelStreet, $city, $web, $verif, $place_name, $place_adm_area, $hotelMoneda, $lat, $lng, $place_id, $stars, $rooms, $stay_time, $chain_bypass, $country_name = '')
{
    global $log;

    $con = conectar();

	$hotelStreet = mysqli_real_escape_string($con, $hotelStreet);
	$city = mysqli_real_escape_string($con, $city);
	$web = generarURLCorecta(mysqli_real_escape_string($con, $web));
	$hotelMoneda = mysqli_real_escape_string($con, $hotelMoneda);//Moneda
	$verif = mysqli_real_escape_string($con, $verif);
    $stars = mysqli_real_escape_string($con, $stars);
    $rooms = mysqli_real_escape_string($con, $rooms);

    $place_name = mysqli_real_escape_string($con, $place_name);
	$place_adm_area = mysqli_real_escape_string($con, $place_adm_area);
	$lat = round(mysqli_real_escape_string($con, $lat), 7);
	$lng = round(mysqli_real_escape_string($con, $lng), 7);
    $place_id = mysqli_real_escape_string($con, $place_id);
    
    $sql = "UPDATE hoteles SET
                street='$hotelStreet',
                city='$city',
                website='$web',
                verificado='$verif' ,
                place_name='$place_name',
                place_adm_area='$place_adm_area',
                moneda='$hotelMoneda',
                lat='$lat',
                lng='$lng',
                place_id='$place_id',
                estrellas='$stars',
                n_habitaciones='$rooms',
                stay_time=$stay_time, 
                country = '$country_name',
                chain_bypass = $chain_bypass WHERE id='$hotel_id' ";
    
    escritura($sql, $con);

    //Delete from cache
    deleteCacheByTag('hotel_profile_' . $hotel_id);
    deleteCacheByKey('time_zone_hotel_id_hotel_'.$hotel_id);
    deleteCacheByKey('time_zone_hotel_' . $hotel_id);
}

// New way to update hoteles table (not all fields available yet)
function updateHotelProfile($payload) {
    global $log;
    $gateway = new ApiGatewayConnection();
    $endPoint = HOTELINKING_ENDPOINT . "brands/{$payload['brand']['id']}/info";
    try {
        $gateway->sendRequest($payload, $endPoint, 'PUT');
        $log->debug('Updating hotel profile', $payload);
        //Delete from cache
        deleteCacheByTag('hotel_profile_' . $payload['brand']['hotel_id']);
        deleteCacheByKey('time_zone_hotel_id_hotel_'.  $payload['brand']['hotel_id']);
        deleteCacheByKey('time_zone_hotel_' . $payload['brand']['hotel_id']);
    }catch (Exception $e) {
        $log->error('Error updating hotel profile', ['message' => $e->getMessage(), 'error'=> $e]);
        return [];
    }
}

function obtenerDatosHotelProfile($hotel_id)
{
    $sql = "SELECT 
                id,
                email,
                name,
                hotelName,
                street,
                logo,
                city,
                website,
                verificado ,
                place_name,
                place_country,
                country,       
                place_adm_area,
                moneda,
                lat,
                lng,
                place_id,
                estrellas,
                n_habitaciones,
                sending_email,
                stay_time,
                chain_bypass,
                activated
            FROM hoteles WHERE id=$hotel_id";
    $row = lectura($sql, conectar(), true);
    $row['hotelName_san'] = string_sanitize($row['hotelName']);
    $row['campos_obligatorios'] = 1;
    foreach ($row as $clave => $dato) {
        //Si no tiene moneda, le ponemos por defecto USD
        if ($clave == 'moneda' && $dato == NULL) {
            $row['moneda'] = 'USD';
        }
        //place_adm_area puede estar en blanco
        if ($dato == '' && $clave != 'place_adm_area') {
            $row['campos_obligatorios'] = 0;
            break;
        }

    }
    //update para el onboarding
    if ($row['campos_obligatorios'] == 1) {
        $sql2 = ("UPDATE onboarding SET basic_info = '1' WHERE id_hotel =" . $hotel_id . "");
        $query2 = mysqli_query(conectar(), $sql2);
    }
    return $row;
}

function guardarNombreLogo($hotel_id, $logoUrl)
{
//    $logoUrl = SECURE_BASE_PATH . DIR_IMG_FICHA_HOTEL . $hotel_id . '/logo/med_' . $logoName;
    $sql = "UPDATE hoteles SET logo='$logoUrl' WHERE id='$hotel_id' ";
    // mysqli_query(conectar(), $sql);
    escritura($sql);
    //Borramos de cache
    deleteCacheByTag('hotel_profile_' . $hotel_id);

    //actualizamos el nombre del hotel en la BD de emails
//    actualizarDatosHotelPlataformaEmails($id, 'logo', $nombreImg);
}

function obtenerLogoHotel($hotel_id)
{
    $sql = 'SELECT logo FROM hoteles WHERE id = "' . $hotel_id . '"';
    $row = lectura($sql);
    return $row['logo'];
}

function get_time_zones()
{
    global $log;
    include_once RUTA_DIR . LIB . 'cache.php';
    include_once RUTA_DIR . LIB . 'dashboards_helpers.php';

    //Cache all time zones (always the same for all hotels)
    $cacheName = 'total_time_zones';
    $cache = getFromCache($cacheName);

    if (!$cache) {
        $sql = "SELECT id, gmt, time_zone, description FROM time_zone";
        $time_zones = lecturaArray($sql);

        if ($time_zones) {
            setToCache($cacheName, $time_zones);
        }
    } else {
        $time_zones = $cache->get();
    }

    return $time_zones;
}

function get_hotel_time_zone($hotel_id) {
    global $log;

    include_once RUTA_DIR . LIB . 'dashboards_helpers.php';

    $cacheName = createCacheName('time_zone_hotel_id',NULL, $hotel_id);
    $cache = getFromCache($cacheName);

    if (!$cache) {
        $sql = "SELECT time_zone_id FROM hoteles WHERE id = $hotel_id";
        $time_zone_hotel_id = lectura($sql);

        if ($time_zone_hotel_id) {
            setToCache($cacheName, $time_zone_hotel_id['time_zone_id']);
            // $log->debug('time zone hotel id cache created with name: ' . $cacheName);
        }


    } else {
        $time_zone_hotel_id['time_zone_id'] = $cache->get();
        // $log->debug('time zone hotel id cache already exists, retrieving: ' . $cacheName);
    }

    return $time_zone_hotel_id['time_zone_id'];

}

//Borrar logo anterior del disco duro
function borrarLogoAnterior($hotel_id, $img)
{
    $ruta = DIR_IMG_FICHA_HOTEL . $hotel_id . "/logo/";
    if (file_exists($ruta . $img)) {
        unlink($ruta . $img);
        borrarThumbnail($ruta, $img);
    }
}

function cadenaCambioHotel($id_hotel)
{
	//obtener datos hotel
	$sql = "SELECT hoteles.id
	FROM hoteles 
	INNER JOIN cadena_hotel ON cadena_hotel.id_hotel=hoteles.id
	WHERE hoteles.id='".$id_hotel."' 
		AND cadena_hotel.id_cadena='".$_SESSION['c_logueado']."' ";
	$rs = mysqli_query (conectar(), $sql);
	$n_resultados=mysqli_num_rows($rs);
	if($n_resultados==1){
		$row = mysqli_fetch_assoc($rs);
		liberar($rs);
		loguearCadena($_SESSION['c_logueado']);
		return true;
	}else{
		return false; // El hotel no pertenece a la cadena
	}
}

?>
