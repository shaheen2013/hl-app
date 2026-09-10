<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//contenido solo disponible si logueado
include LIB.'logueado.php';
include LIB . 'obtenerdatosHotel.php';

hotelStaffLanding ();// Si no esta logueado lo manda a la landing
// For front purposes
$currentSubPage = "hotel-profile-2";

include_once LIB.'generarUrlCorrecta.php';

// API CONNECTION
include_once APP . 'Services/Connections/ApiGatewayConnection.php';

function camposObligatoriosReserva($arrayDatosReserva){
	if($arrayDatosReserva['emailReserva']=='' || $arrayDatosReserva['telefonoReservas']==''){
		return false;
	}else{
		return true;
	}
}
$hotel_id = $_SESSION['h_logueado'];
$arrayCountryLangs = getCountryLangs();
if (!empty ($_POST['hotelConfirmButton']) ){

	$emailReserva = mysqli_real_escape_string(conectar(), $_POST['emailReserva']);
    $countryIds=array_column ($arrayCountryLangs, 'id');
	$hotelCountryIds = array_values (array_intersect($countryIds,array_keys ( $_POST)));
	
//    If the hotel has configuration for more countries we save them too
	if(sizeof($hotelCountryIds) > 0){
	    foreach($hotelCountryIds as $hotelCountryId){
            updateHotelCountryLangUrl($hotel_id,$hotelCountryId, $_POST[$hotelCountryId]);
        }
	}
	$telefonoReservas= mysqli_real_escape_string(conectar(), $_POST['telefonoReservas']);
	$bookingEngine= mysqli_real_escape_string(conectar(), $_POST['bookingEngine']);
	$promoCodeParam= mysqli_real_escape_string(conectar(), $_POST['promoCodeParam']);

	// Actualizamos campos
	actualizarDatosReserva($emailReserva, $telefonoReservas, $bookingEngine, $promoCodeParam);

	actualizarWebsitesReserva(intval($_SESSION['hotel']['brand_id']), $_POST['hotelWebsite']); 
		
	$ok = array (true, '2007');
}
elseif(!empty ($_POST['hotelAddUrlLang'])){
    insertHotelCountryLangUrl($hotel_id, $_POST['country']);
    $ok = array (true, '2007');
}
elseif(!empty ($_POST['deleteLang'])){
    updateHotelCountryLangUrl($hotel_id, $_POST['country_lang_id'],'',0);
    $ok = array (true, '2007');
}


if(!empty($_SESSION['h_logueado']) && !empty($_SESSION['hotel']['brand_id'])) {

	$arrayDatosReserva = obtenerDatosHotelReserva($_SESSION['h_logueado']);

	$brandLanguages = getAllLanguagesFromBrand($_SESSION['hotel']['brand_id']);
	$languages = getAllLanguages();

}

$bookingEngines = getAllBookingEngines();
$arrayHotelCountryLangs=getHotelCountryLangUrl($hotel_id);

// Miramos si tiene rellenados todos los campos obligatorios
if (!camposObligatoriosReserva($arrayDatosReserva)){
	$ok = array (false, '4024');
}else{
    hotelProfile2Onboarding($_SESSION['h_logueado']);
}

?>