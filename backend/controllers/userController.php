<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

include_once LIB.'obtenerDatosUsuario.php';
include_once LIB.'staff_id_hotel.php';
include_once LIB.'agregarPuntos.php';
include_once LIB.'enviarEmail.php';
include_once LIB.'sanitize.php';
include_once LIB.'fecha.php';

$id_hotel = obtenerIdHotel();

// Follow de usuario a usuario
if (!empty($_GET['fol']) && !empty($_SESSION['u_logueado'])){
	$id_usuario = mysqli_real_escape_string(conectar(), $_GET['fol']);
	// Seguidor -> seguido
	userUserFollow($_SESSION['u_logueado'], $id_usuario);
}else if (!empty($_GET['fol']) && empty($_SESSION['u_logueado'])){
	$ok = array (false, '4015');
}

// Obtenemos el guid del usuario de la URL
$guid = $url['dir3'];

//obtener id_hotel a partir del GUID 
$id_usuario = obtenerIdUsuarioGUID($guid);

$arrayDatosUsuario = obtenerDatosUsuario($id_usuario);

if(!empty($_SESSION['h_logueado']) || !empty($_SESSION['staff_id_hotel'])){
	// Contenido solo visible para hoteles
	$arrayDatosUsuarioHotel = obtenerDatosUsuarioHotel($id_usuario, $id_hotel);
	//User points (Total rubies)--
	$arrayDatosUsuarioHotel['puntos'] = obtenerPuntosUsuarioHotel($id_usuario, $id_hotel);
	// Total Referrals
	$arrayDatosUsuarioHotel['totalReferrals'] = obtenerReferrals($id_usuario, $id_hotel);
	// Referrals´ spent
	$arrayDatosUsuarioHotel['referralsSpent'] = obtenerReferralsSpent($id_usuario, $id_hotel);
	// Booking history
	$arrayBookingHistory = obtenerBookingHistory($id_usuario, $id_hotel);
	// Comentarios de otros hoteles sobre usuario
	$arrayComentariosHoteles = obtenerComentariosHoteles($id_usuario);
}

/*echo '<pre>';
print_r($arrayDatosUsuario);
echo '</pre>';*/
?>