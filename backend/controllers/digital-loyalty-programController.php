<?php //Miramos si esta definida la variable de control de index.php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}
//Librerias
include_once LIB . 'isBot.php'; // crea $isFacebook. Si es un bot -> exit

include_once LIB . 'obtenerdatosHotel.php';
include_once LIB . 'obtenerDatosUsuario.php';
include_once LIB . 'cookies.php';


$ofertaShare['nombre_oferta'] = null;

include_once LANG . $_SESSION['userLang'] . "/iframe-landing.php";

// Init
!empty($url['dir3']) ? $guid = $url['dir3'] : $guid = false;    // Hotel Guid
!empty($url['args'][0]) ? $token = $url['args'][0] : $token = false;    // Tracking token
$hotel_id = false;        //ID hotel
$referrer_id = false;        //ID referrer
$referrer = false;        //Referrer
$referral_id = false;        //ID referral
$dataError = false;        //No errors
$chain_id = false;        //ID cadena

/////////////
/////BL//////
/////////////
// If token get all data from it
if ($token) {
    $log->debug('DIGITAL LOYALTY PROGRAM : Seems GUID and TOKEN exists, starting');
    // Get token info
    $ids = tokenUsuario($token);

    // Check info from token
    if (!empty($ids['id_hotel']) && !empty($ids['id_usuario'])) {
        $log->debug('DIGITAL LOYALTY PROGRAM : There is an hotel_id and a referrer_id');
        //assign variables
        $hotel_id = $ids['id_hotel'];
        $referrer_id = $ids['id_usuario'];
        $chain_id = checkChain($hotel_id);
        $referrer_guid = obtenerGUIDUsuarioId($referrer_id);

    } else {
        // There is not enought info to put cookie
        $log->debug('DIGITAL LOYALTY PROGRAM : No hotel_id and referrer_id, exiting');
        $dataError = true;
        exit;
    }

    //Check hotel_guid
    if (!empty($guid)) {
        $hotel_id_toCheck = obtenerIdHotelGUID($guid);
        if ($hotel_id != $hotel_id_toCheck) {
            // This Guid is not from this hotel
            $dataError = true;
            exit;
        }
    } else {
        $dataError = true;
        exit;
    }

    // Store Guid in session
    $_SESSION['guid'] = $guid;

    // Get hotel info
    $arrayDatosHotel = getHotelData($hotel_id);
    // Get offer info
    $ofertaReferral = obtenerOfertaReferral($hotel_id);
    // Get referrer info
    $referrer = obtenerDatosUsuarioId($referrer_id);

    if(!empty($_SESSION['hotel']['brand_id'])){
        $brand_id = $_SESSION['hotel']['brand_id'];
    } else if(!empty($arrayDatosHotel['brand_id'])) {
        $brand_id = $arrayDatosHotel['brand_id'];
    }
    
    $websiteUrlReserva = getHotelWebsiteUrl($brand_id, $_SESSION['userLang']); 

} else {
    //No hay token
    $dataError = true;
}//Si hay TOKEN Obtener los datos del HOTEL y REFERRER


/////////////
///HELPERS///
/////////////


function checkChain($hotelId)
{
    //check si el hotel es de cadena
    $esDeCadena = hotelDeCadena($hotelId);

    if ($esDeCadena) {
        $idCadena = hotelIdCadena($hotelId);
        return $idCadena;
    }
    return false;
}