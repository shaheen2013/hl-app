<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

include_once LIB.'sanitize.php';
//include_once LIB.'paginacion.php';
include_once LIB.'enviarEmail.php';
include_once LIB.'obtenerDatosUsuario.php';
include_once LIB.'sanitize.php';
include_once LIB.'follow.php';
include_once LIB.'registrar_visita.php';
include_once LIB.'obtenerdatosHotel.php';
include_once LIB.'obtenerDatosUsuario.php';
include_once LIB.'fecha.php';

// Obtenemos el guid del hotel de la URL
$guid = $_SESSION['id_hotel'] = $url['dir3'];

//obtener id_hotel a partir del GUID 
$id_hotel = $_SESSION['id_hotel'] = obtenerIdHotelGUID($guid);

//Url actual sin parametros y sin '/'
$info = parse_url( $_SERVER["REQUEST_URI"] );
if(substr($info['path'], -1)=='/')
{
	$url_o = substr($info['path'],0, -1);
}else{
	$url_o = $info['path'];
}

$arrayDatosHotel = obtenerDatosHotel($id_hotel); // Rating, logo...
$arrayDatosCadena = obtenerDatosCadena($id_hotel);
$arrayTiposHabitacion = obtenerTiposHab($id_hotel);
$arrayServicios = obtenerServicios($id_hotel);
$arrayExtras = obtenerExtras($id_hotel);
$comments = obtenerNComents($id_hotel); // Nº
$rewards = obtenerNRewards($id_hotel); // Nº
$arrayBookingContact = obtenerBookingContact($id_hotel);
if (!empty($_SESSION['u_logueado'])){
	$follow = obtenerFollowHotel($_SESSION['u_logueado'], $id_hotel);
}

if(!empty($_SESSION['hotel']['brand_id']) && !empty($_SESSION['userLang']) ){
	$websiteUrlReserva = getHotelWebsiteUrl(!empty($_SESSION['hotel']['brand_id']), $_SESSION['userLang']);
}

// Condiciones (-)

if (!empty($_POST['issue']) && !empty($_SESSION['u_logueado'])){
	$issue = mysqli_real_escape_string(conectar(), $_POST['issue']);

	$arrayDatosEmail = obtenerDatosUsuarioMail($_SESSION['u_logueado']);

	$urlUsuario = obtenerUrlGUIDUsario($arrayDatosEmail['id']);
	$urlHotel = obtenerUrlGUIDHotel($arrayDatosHotel['id']);
	// Liberias con la plantilla y el texto de los emails
	include_once LANG.$_SESSION['userLang'].'/email/hotel-issue.php';
	include_once LIB.'plantillasMails/hotel-issue.php';
	$cuerpo1 .= $issue;// Añadimos el issue del usuario al final de email
	
	mandarEmailMandrill('customerservice@hotelinking.com','Hotelinking',$asunto1,$cuerpo1);
	// enviar mail al usuario
	mandarEmailMandrillPlantilla($arrayDatosEmail['email'],$arrayDatosEmail['nombre'],$asunto2,$cuerpo2, 'standard-template');
}else if(isset($_GET['issue']) && !empty($_SESSION['u_logueado'])){
	$ok = array (false, '4015');
}

// Most relevant rewards
$arrayRewards = obtenerRewards($id_hotel);

//Registrar visita (ip, tipo, id_hotel)
$remote_addr = mysqli_real_escape_string(conectar(), $_SERVER['REMOTE_ADDR']);
if(isset($_SERVER['HTTP_REFERER'])){
	$http_referer = mysqli_real_escape_string(conectar(), $_SERVER['HTTP_REFERER']);
}else{
	$http_referer='';
}
registrarVisita($remote_addr, 'hotel', $id_hotel, $http_referer);

/*echo '<pre>';
print_r($arrayRewards);
echo '</pre>';*/
?>
