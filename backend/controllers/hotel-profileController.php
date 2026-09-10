<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//contenido solo disponible si logueado
include LIB.'logueado.php';
hotelStaffLanding ();// Si no esta logueado lo manda a la landing

include_once LIB.'subirArchivos.php';
include_once LIB.'sanitize.php';
include_once LIB.'generarToken.php';
include_once LIB.'enviarEmail.php';
include_once LIB.'verificarEmail.php';
include_once LIB.'worldCurrency.php';
include_once LIB.'obtenerdatosHotel.php';

// For front purposes
$currentPage = "hotel-profile";
$currentSubPage = "hotel-profile";
$hotel_id =  $_SESSION['h_logueado'];

$options = '';
$brandID = array_get($_SESSION, 'loggedBrandID');
if (!empty ($_POST['hotelConfirmButton']) )
{
	$arrayErrores = array ( true );

	$hotelNameReg=$_SESSION['hotelName']= $_POST['hotelName'];
	$transactional_email = $_POST['sending_email'];

	// Old way to update hoteles table
	actualizarHotelero(
		$hotel_id,
		$_POST['hotelStreet'],
		$_POST['hotelCity'],
		$_POST['hotelWebsite'],
		$_POST['verificado'],
		$_POST['place_name'],
		$_POST['place_adm_area'],
		$_POST['currency'],
		$_POST['lat'],
		$_POST['lng'],
		$_POST['place_id'],
		$_POST['hotelStars'],
		$_POST['hotelRooms'],
		$_POST['stay_time'],
        array_get($_POST, 'chain_bypass')?1:0,
        $_POST['country_name']
	);

	// New way to update hoteles table (not all fields available yet)
	$payload = [
		'brand' => [
			'id' => $brandID,
			'hotel_id' => $hotel_id,
			'hotelName' => $_SESSION['hotelName'],
			'place_country' => $_POST['place_country'],
			'sending_email' => $transactional_email,
			'time_zone_id' => array_get($_POST, 'timeZone', 50),
		]
	];

	updateHotelProfile($payload);

	$errores[] = '2007';

	//Generamos un array de errores
	foreach ($errores as $er){
		$arrayErrores[]=$er;
	}
	//Mostramos todos los errores
	$ok = $arrayErrores;
}

// Obtenemos los datos a mostrar
$arrayDatosHotel = obtenerDatosHotelProfile($hotel_id);
$_SESSION['hotelVerif']=$arrayDatosHotel['verificado'];
$hotelName = $arrayDatosHotel['hotelName'];
$hotelCity = /*$_SESSION['hotelCity'] =*/ $arrayDatosHotel['city'];
$hotelWebsite = $arrayDatosHotel['website'];
$time_zones_array = get_time_zones();
$hotel_time_zone_id = get_hotel_time_zone($hotel_id);
$hotelLogo = obtenerLogoHotel($hotel_id);

if(empty($emailRepetido))
{
	$emailRepetido=0;
}

if(array_get($_GET, 'ok')){
    $ok = array(false, array_get($_GET, 'ok'));
}
if(!empty($_GET['change']) && $_GET['change']!=$_SESSION['h_logueado']){
	$id_hotel = $_GET['change'];

	// Borramos datos de session
	include_once LIB.'borrarSession.php';
	borrarSessionProfileLanding($id_hotel);
	borrarSessionCrearOferta($id_hotel);

	if (cadenaCambioHotel($id_hotel)){
		header('Location: /'.$urlTree['clients'].'/?change=ok');

	}else{
		$ok = array(false, '4002');
	}
}

?>
