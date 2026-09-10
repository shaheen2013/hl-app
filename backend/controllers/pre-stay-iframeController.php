<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}
include_once LIB . 'obtenerdatosHotel.php';
include_once LIB . 'socialMediaShareText.php';//social media share custom hotel text
include LANG . $_SESSION['userLang'].'/pre-stay-iframe.php';

//Inicialización
$ofertaShare = false;   //false by default
$shareScreen = false;    //false by default

//get guid
$guid = $url['dir2'];

if(!empty($guid))
{
    //Check si el hotel existe by GUID, intercambia por ID
    $hotel_id = obtenerIdHotelGUID($guid);

    if(!empty($hotel_id))
    {
        //datos del hotel
        //Get hotel data (name, img...)
        $datosHotel = getHotelData($hotel_id);

        $chain_id = hotelIdCadena($hotel_id);

        // Determine wich website use for share
        include_once RUTA_DIR . LIB . 'idiomas.php';
        $userLang = mirarIdiomaPlataforma($_SESSION['userLang']);

        //Select the correct website based on lang of browser
        $brand_id = intval($_SESSION['hotel']['brand_id']);

        if (!empty($userLang)) {
            $shared_website = getHotelWebsiteUrl($brand_id, $userLang);
        } else {
            // If it is empty, we call the default URL which is English.
            $shared_website = getHotelWebsiteUrl($brand_id, 'en');
        }

        //Create the correct url
        $query = parse_url($shared_website, PHP_URL_QUERY);

        // Returns a string if the URL has parameters or NULL if not
        if ($query) {
            $shared_website .= '&utm_source=hotelinking&utm_medium=facebook&utm_campaign=hotelinking_pre_stay_share';
        } else {
            $shared_website .= '?utm_source=hotelinking&utm_medium=facebook&utm_campaign=hotelinking_pre_stay_share';
        }
        //Busca la oferta de share
        $ofertaShare = hotelBookingOfertaStay($hotel_id);
        //Obten la oferta de la landing
        $ofertaReferralHotel = obtenerOfertaReferral ($hotel_id);


    }else{
        $log->error('No hotel_id in pre_stay_iframe : redirecting');
        header('Location: /' . $urlTree['404']);
    }
}

?>