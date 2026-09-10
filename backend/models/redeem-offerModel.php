<?php //Miramos si esta definida la variable de control de index.php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}

//Add promocode Library Model
include_once 'cuponAccionesModel.php';

//Obtain hotel data with booking engine JOIN
function ObtainHotelInfo($id)
{
    global $log;
    $log->info('Obtaining hotel info');

    // Get user language form query string and set $lang variable
    $availableWebsiteLanguages = ['es', 'de', 'fr'];
    $lang                      = $_SESSION['userNavLang'] ?? '';
    $lang                      = in_array($lang, $availableWebsiteLanguages) ? '_' . $lang : '';

    //Get from cache
    $isBirthdayOffer = (!empty($_GET['hltr']) && $_GET['hltr'] == 'hotelinking_birthday_email');
    $cacheName = $isBirthdayOffer ? 'ObtainHotelInfo_RO_v2_' . $id : 'ObtainHotelInfo_RO_' . $id;
    $cache = getFromCache($cacheName);

    if(!$cache) 
    {
    	$con = conectar(1);
    	$id = mysqli_real_escape_string($con, $id);
    	//Hay que añadir otra iteracion con CASE WHEN, cuando el booking engine sea NULL coja la url de websiteReserva
    	$sql = "SELECT hoteles.id,hoteles.fotoBg, hoteles.hotelName AS name, hoteles.lang, hoteles.promo_code_param,
    		booking_engines.getParam, hoteles.logo AS logo, cadena.id AS id_cadena";

    	// Campos adicionales solo para birthday offers
    	if ($isBirthdayOffer) {
    	    $sql .= ",
    		hoteles.booking_engine,
    		hoteles.website,
    		brands.id AS brand_id";
    	}

    	// JOINs base comunes a todos los flujos
    	$sql .= " FROM hoteles
            LEFT JOIN booking_engines ON hoteles.booking_engine = booking_engines.id
            LEFT JOIN cadena_hotel ON hoteles.id = cadena_hotel.id_hotel
            LEFT JOIN cadena ON cadena_hotel.id_cadena = cadena.id";

    	if ($isBirthdayOffer) {
    	    $sql .= "
            LEFT JOIN brands ON brands.hotel_id = hoteles.id";
    	}

    	$sql .= "
            WHERE hoteles.id = '".$id."' LIMIT 1";
    	$row = lectura($sql, $con, false);
        desconectar($con);

        if ($row) {
            // Determina ID de cadena para tags de cache
            empty($row['id_cadena']) ? $id_cadena = '' : $id_cadena = $row['id_cadena'];
            // Guarda en cache con tags para invalidación selectiva (hotel, cadena, idioma, booking)
            $tags = array('hotel', 'hotel_profile', 'hotel_profile_' . $id, 'hotel_default_lang_' . $id, 'cadena_profile_' . $id_cadena, 'hotel_booking_info_' . $id);
            setToCache($cacheName, $row, 31536000, $tags);
            $log->info('Cache created: ', array($cacheName));
        }
    } else {
    $row = $cache->get();
}

    return $row;
  }
  
  function ObtainChainInfo($id)
  {
	//Get from cache
    $cacheName = 'ObtainChainInfo_RO_' . $id;
    $cache = getFromCache($cacheName);

    if(!$cache) 
    {
    	$con = conectar(1);
    	$id = mysqli_real_escape_string($con, $id);
		$sql = " SELECT cadena.id, cadena.nombre AS name,
		booking_engines.getParam, cadena.logo
	    FROM cadena 
	    LEFT JOIN booking_engines ON cadena.booking_engine = booking_engines.id
	    WHERE cadena.id = '".$id."'  LIMIT 1";
		$row = lectura($sql, $con, false);
        desconectar($con);

        if ($row) {
            $tags = array('cadena', 'cadena_profile', 'cadena_profile_' . $id);
            setToCache($cacheName, $row, 31536000, $tags);
            global $log;
            $log->info('Cache created: ', array($cacheName));
        }
    } else {
        $row = $cache->get();
    }

    return $row;
}

//Obtenemos los datos necesarios del promocode. RO = redeem offer
// Busca ofertas activas por booking_engine_code (promocode configurado)
function ObtainPromoCodeDataRO($promo_code, $id, $type, $lang)
{
    $con        = conectar(1);
    $promo_code = mysqli_real_escape_string($con, $promo_code);
    $id         = mysqli_real_escape_string($con, $id);

    $sql = "SELECT 
	          case
	              when oferta_lang.nombre is null then  oferta_en.nombre 
                  else oferta_lang.nombre end as offerName,
	          case
	              when oferta_lang.condiciones is null then   oferta_en.condiciones 
                  else oferta_lang.condiciones
              end AS conditions,
	          hotel_oferta.booking_engine_code AS bookingEngineCode 
	        FROM
	          oferta_referral_token 
	              INNER JOIN
	                hotel_oferta ON hotel_oferta.id=oferta_referral_token.id_oferta 
	              LEFT JOIN
	                hotel_oferta_lang as oferta_en on hotel_oferta.id = oferta_en.id_oferta  and oferta_en.lang='en' 
	              LEFT JOIN
	                hotel_oferta_lang as oferta_lang on hotel_oferta.id = oferta_lang.id_oferta and oferta_lang.lang='" . $lang . "'
	        WHERE ";

    // FIX: Always search by unique token, not booking_engine_code
    // Birthday offers now use unique HL tokens just like other offers
    // The booking_engine_code is shared across all users and causes conflicts
    $sql .= "oferta_referral_token.token='" . $promo_code . "' AND hotel_oferta.estado=1 AND ";
    if ($type == 'h') {//Hotel
        if (hotelDeCadena($id)) {//Si es hotel de cadena tb mostramos ofertas de cadena
            $id_cadena = hotelIdCadena($id);
            $sql       .= " (hotel_oferta.id_hotel='" . $id . "' OR hotel_oferta.id_cadena='" . $id_cadena . "' )";
        } else {
            $sql .= " hotel_oferta.id_hotel='" . $id . "'";
        }
    } else {//Cadena
        $sql .= " hotel_oferta.id_cadena='" . $id . "' ";
    }
    $sql    .= "LIMIT 1";
    $result = lectura($sql, $con, true);

    if ($result) {
        $result['conditions'] = strip_tags($result['conditions']);
        //$result = array('status'=>'200','message' =>'OK'); //OK - canjeado
        $result['code']    = '200';
        $result['message'] = 'OK';
    } else {
        //Promocode no existe o no pertenece a este hotel
        $result['code']    = '404';
        $result['message'] = 'Promo code not found';
    }
    return $result;
}

function ObtainUserName($promoCode)
{
    $con       = conectar(1);
    $promoCode = mysqli_real_escape_string($con, $promoCode);

    //Search for user name
    $sql = "SELECT users.nombre
			FROM user_cupones
			INNER JOIN users ON users.id=user_cupones.id_usuario
	        WHERE voucher ='" . $promoCode . "' LIMIT 1";
    $row = lectura($sql, $con, true);

    return $row['nombre'];
}


?>