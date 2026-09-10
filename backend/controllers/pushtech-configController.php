<?php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}
//Get api data library
include_once LIB . 'apiData.php';
//Get pushtech library
include_once LIB . 'pushtech_api.php';

//Get hotel data or chain data
include_once RUTA_DIR . LIB . 'obtenerDatosCadena.php';
include_once RUTA_DIR . LIB . 'obtenerdatosHotel.php';

//is this hotel from chain?
$id_chain = (!empty($_SESSION['c_logueado']) ? $_SESSION['c_logueado'] : NULL);
$id_hotel = $_SESSION['h_logueado'];

// For front purposes
$currentSubPage = 'pushtech-config';

//check if post has valid days set
$days_valid = (!empty($_POST['days_valid']) ? $_POST['days_valid'] : NULL);

//Get pushtech credentials for this hotel
// $credentials = getApiCredentials($id_hotel, 'pushtech');

//if no credentials then show an error msg
if (!$credentials) $ok = array(false, '4066');

//get the current list of pushtech campaigns for the hotel filtered by status
$pushtech_response = getPushtechCampaigns($credentials, array());

//If no campaigns, show an error
if (!$pushtech_response) {
    $ok = array(false, '4067');
} else {
    //Update campaign and/or offer from Hotelinking dashboard
    if (!empty($_POST['action'])) {
        $result = array();
        //Update mapping
        if ($_POST['action'] === 'updateCampaign') {
            //If not minimum data is available
            if (empty($_POST['pushtech_campaign']) || empty($_POST['hotelinking_offer'])) {
                //Send feedback error insufficient data
                $ok = array(false, '4069');
            } else {
                $id_mapping = (!empty($_POST['id_mapping']) ? $_POST['id_mapping'] : NULL);
                $updated = upsertCampaign($_POST['pushtech_campaign'], $_POST['hotelinking_offer'], $days_valid , $id_hotel, $id_chain, $id_mapping);
                if (!$updated) {
                    //Send a feedback error duplicated
                    $ok = array(false, '4068');
                } else {
                    //Send Ok feedback
                    $ok = array(true, '2007');
                }
            }
        }
        //Delete mapping
        if ($_POST['action'] === 'deleteMapping') {
            $deleted = deleteMapping($_POST['id_mapping'], $id_hotel, $id_chain);
            if (!$deleted){
                $ok = array(false, '4070');
            } else {
                $ok = array(true, '2007');
            }
        }
    }
    //Convert campaigns to array
    $campaigns = $pushtech_response;
    $campaigns = $campaigns['campaigns'];

    //get chain website (if any)
    if (!empty($id_chain)) {
        $chain_website = getChainWebsite($id_chain, $_SESSION['userLang']);
    }

    //get hotel website
    if(!empty($_SESSION['hotel']['brand_id'])) {
        $hotel_website = getHotelWebsiteUrl($_SESSION['hotel']['brand_id'], $_SESSION['userLang']);
    }
    
    //get booking engine for hotel
    $booking_engine = getBookingEngine($id_hotel);
    
    //Get offers for this hotel and chain
    $offers = ObtainOfferList($id_hotel, $id_chain);

    //Get mappings for this hotel and chain
    $mappings = getMappedOffers($id_hotel, $id_chain);

}

