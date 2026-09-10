<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//Contenido solo visible si logueado
include LIB.'logueado.php';
hotelStaffLanding ();// Si no esta logueado lo manda a la landing

include_once LIB.'staff_id_hotel.php';
include_once LIB.'agregarPuntos.php';
include_once LIB.'obtenerDatosUsuario.php';
include_once LIB.'obtenerdatosHotel.php';
include_once LIB.'enviarEmail.php';
include_once LIB.'convertirDivisas.php';
include_once LIB.'dolaresPuntos.php';

$id_hotel = obtenerIdHotel();

//Amount y divisa vienen del checkout. divisa es la moneda del hotel
//$booking_value[0] : contiene el amount del booking value
//$booking_value[1] : contiene la moneda del booking value
function compararAmountCheckoutBookingValue($amount, $divisa, $booking_value){
	//Miramos si la moneda del booking value es la del hotel, en caso contrario la convertimos
	if($divisa == $booking_value[1]){
		//
		$bookingValueMonedaHotel = convertirDivisas($booking_value[0], $booking_value[1], $divisa);	
	}else{
		$bookingValueMonedaHotel = $booking_value;
	}
	//Comparamos ambos valores y devolvemos el mas alto
	if($amount >= $bookingValueMonedaHotel){
		return $amount;
	}else{
		return $bookingValueMonedaHotel;
	}
}

// La variables $_SESSION['checkout'] viene de la pantalla anterior
// contiene el id del usuario al que se le ha hecho checkout

if (!empty($_POST['userId'])){
	$id_usuario = mysqli_real_escape_string(conectar(), $_POST['userId']);
	$amount = mysqli_real_escape_string(conectar(), $_POST['amount']);
	$divisa = mysqli_real_escape_string(conectar(), $_POST['divisas']);
	
	//Si tiene booking value lo asociamos con este checkin, 
	//devuelve array con el booking value en la moneda del hotel y la moneda del hotel
	$booking_value = obtenerBookingValueCheckout($id_usuario, $id_hotel);
	
	//Mirar si el booking value es superior al amount del checkout
	//Guardamos el que sea superior
	$amount = compararAmountCheckoutBookingValue($amount, $divisa, $booking_value);
	
	// Equivalencia Puntos
	if($divisa=='USD'){
		$puntos = calcularDolarPuntos($amount);
		$usd = $amount;
	}else{
		$usd = convertirDivisas($amount, $divisa, 'USD');
		$usd = round($usd);
		$puntos = calcularDolarPuntos($usd);
	}
	// Hacer check-out
	$id_checkout = $_SESSION['id_checkout'] = checkoutUsuario($id_usuario, $id_hotel, $puntos);
	
	asociarBookingValueCheckout($id_usuario, $id_hotel, $id_checkout);
	
	// Registrar gasto usuario en USD
	registrarGastoUsuario($usd, $id_usuario, $id_hotel, $id_checkout, $amount, $divisa, $booking_value);
	
	// Asignar puntos al usuario
	agregarPuntos($id_usuario, $id_hotel, $puntos, 7);
	
	$datosUsuario = obtenerDatosUsuario($id_usuario, $id_hotel);
	
	// Agregar encuesta pendiente
	crearEncuesta($id_usuario, $id_hotel, $id_checkout);
	
	// Enviar email de checkout + encuesta 
	$datosEmail = obtenerDatosUsuarioMail($id_usuario);
	$datosHotel = obtenerDatosHotelChkin($id_hotel, $id_usuario);
	$goalsHotel = obtenerGoalsHotel($id_hotel);
	//$asunto='Hotelinking - Encuesta';
	//$cuerpo='Hola '.$datosEmail['nombre'].'<br />Tienes una encuesta pendiente: ';
	include_once LANG.$_SESSION['userLang'].'/email/check-out.php';
	include_once LIB.'plantillasMails/check-out.php';
	mandarEmailMandrillPlantilla ($datosEmail['email'], $datosEmail['nombre'], $asunto, $cuerpo, 'standard-template');
	
	$_SESSION['checkout'] = $id_usuario;
}

/*echo '<pre>';
print_r($datosUsuario);
echo '</pre>';*/
/*echo '<pre>';
print_r($_POST);
echo '</pre>';*/
?>