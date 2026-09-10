<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}
include_once LIB . 'obtenerdatosHotel.php';
//include_once LIB . 'twitter.php';
include_once RUTA_DIR . LIB . 'webservices/msgFeedback.php';
include_once LIB . 'socialMediaShareText.php';//social media share custom hotel text

//Inicialización
$ofertaShare = false;   //false by default
$shareScreen = false;    //false by default
//get guid and transform to hotel ID
$guid = $url['dir2'];
//Check si el hotel existe
$hotel = obtenerIdHotelGUID($guid);

$shareData = array(
	"webservice" => "hotel-booking-share-ws.php",
	"shareType" => "2"
	);
//Para facebook-iframe-dialog WS sendAdditionalShareEmail.php
$shareType = 'pre';

if(!empty($guid))
{
	if(!empty($hotel))
	{
		//datos del hotel
		$datosHotel = hotelBookingShareData($hotel);
		//Muestra la pantalla del share
		$shareScreen = true;
		//Busca la oferta de share
		$ofertaShare = hotelBookingOfertaStay($hotel);
		//Obten la oferta de la landing
		$ofertaReferralHotel = obtenerOfertaReferral ($hotel);
		//pasa la transacción
		$transaction = (!empty($_GET['transaction']) ? $_GET['transaction'] : '-');
		//social media share custom hotel text
		$socialMediaShareText = getSocialMediaShareText($hotel, $_SESSION['userNavLang'], 'pre');
		
		include LANG . $_SESSION['userLang'] . '/hotel-booking-share.php';
		include LANG . $_SESSION['userLang'] . '/referral-share.php';
	}
}
?>