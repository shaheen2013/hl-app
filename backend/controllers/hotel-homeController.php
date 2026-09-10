<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//Contenido solo visible si logueado
include LIB.'logueado.php';
hotelStaffLanding ();// Si no esta logueado lo manda a la landing

include_once LIB.'charts-common.php';
include_once LIB.'obtenerDatosUsuario.php';

// El gestor de la cadena acaba de cambiar de hotel. Viene de 'chain-management'
if(!empty($_GET['change'])){
	$change = mysqli_real_escape_string(conectar(), $_GET['change']);
	if($change='ok'){
		$ok = array(true, '2018');
	}
}

// Si devuelve True debemos mostrar la alerta
$mostrarAlert = mostrarAlert ();
// Si devuelve True debemos mostrar el modal
$mostrarModal = mostrarModal ();

//---------------------------------------------------------------------
// Reputation by gender: 1º parametro mujeres, 2º hombres, 3º param iversa
//$reputationByGender = reputationByGender($_SESSION['h_logueado']);
$reputationByGender = reputationByGender($_SESSION['h_logueado']);

// New guests progression
$newGuestsProgression = newGuestsProgression($_SESSION['h_logueado']);

// overall reputation
$overallReputation = overallReputation($_SESSION['h_logueado']);
$overallReputationInv = 10 - $overallReputation[0];

// Top 10 guests by points
$top10Points = top10Points($_SESSION['h_logueado']);
// Top 10 guests by nights
$top10Nights = top10Nights($_SESSION['h_logueado']);
// Top 10 guests by nights
$top10Checkins = top10Checkins($_SESSION['h_logueado']);
// Top 10 guests by spendings
$top10Spendings = top10Spendings($_SESSION['h_logueado']);

//Average nights per guest
$nightsGuest = nightsGuest($_SESSION['h_logueado']);
//Average points per guest
$pointsGuest = pointsGuest($_SESSION['h_logueado']);
//Average spent per guest
$spentGuest = spentGuest($_SESSION['h_logueado']);

//Today New guests
$todayNewGuests = todayNewGuests($_SESSION['h_logueado']);

// Guests Countries
$guestsCountries = guestsCountries($_SESSION['h_logueado']);

// Ciudad del hotel para marcarlo en el mapa
$ciudad = obtenerCiudadHotel($_SESSION['h_logueado']);

//Social media reach
$socialMediaReach = socialMediaReach($_SESSION['h_logueado']);

// Reputation by age. 1º param. 0-18, 2º param. 19-30, 3º param. 30-54, 4º param. >50 
$reputationByAge = reputationByAge($_SESSION['h_logueado']);

// Rating Progression
$RatingProgression = ratingProgression($_SESSION['h_logueado']);

//Contar ofertas
$NumOfertasHotel = obternerNumOfertas($_SESSION['h_logueado']);

//echo $todayNewGuests;
/*echo '<pre>';
print_r($reputationByGender);
echo '</pre>';*/
?>