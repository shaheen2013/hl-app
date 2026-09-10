<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//Contenido solo visible si logueado
include LIB.'logueado.php';
hotelStaffLanding ();// Si no esta logueado lo manda a la landing

include_once LIB.'agregarPuntos.php';
include_once LIB.'obtenerDatosUsuario.php';
include_once LIB.'obtenerdatosHotel.php';
include_once LIB.'enviarEmail.php';
include_once LIB.'invitaciones.php';
include_once LIB.'crearNuevoUsuario.php';
include_once LIB.'staff_id_hotel.php';
include_once LIB.'sanitize.php';
include_once LIB.'generarToken.php';

function enviarEmailCheckin($puntos, $id_hotel, $id_usuario){
	// Obtener los datos del usuario para mandarle un email
	$arrayDatosEmail = obtenerDatosUsuarioMail($id_usuario);
	$hotelName = obtenerNombreHotelId($id_hotel);
	// Mandar email
	include_once LANG.$_SESSION['userLang'].'/email/check-in.php';
	include_once LIB.'plantillasMails/check-in.php';
	mandarEmailMandrillPlantilla($arrayDatosEmail['email'], $arrayDatosEmail['nombre'], $asunto, $cuerpo, 'standard-template');
}

function enviarEmailReferral($id_usuario, $id_hotel, $puntosRef){
	// Obtener los datos del usuario para mandarle un email
	$arrayDatosEmail = obtenerDatosUsuarioMail($id_usuario);
	$hotelName = obtenerNombreHotelId($id_hotel);
	$urlHotel = obtenerUrlGUIDHotel($id_hotel);
	// Mandar email
	include_once LANG.$_SESSION['userLang'].'/email/check-in-ref.php';
	include_once LIB.'plantillasMails/check-in-ref.php';
	mandarEmailMandrillPlantilla($arrayDatosEmail['email'], $arrayDatosEmail['nombre'], $asunto2, $cuerpo2, 'standard-template');
}

//Acciones de referral checkin (Agregar puntos, )
function accionesCheckinReferral($id_usuario, $id_hotel){
	// Mirar si es usuario referido (-> Puntos )
	$id_usuario_invitador = usuarioReferido($id_usuario);
	if ($id_usuario_invitador!=''){
		// El usuario que esta haciendo check-in viene de Social media invitado
		// Solo agregar puntos la primera vez y si no ha usado un promocode de landing de este hotel
		if (primerCheckin($id_usuario) && noUsadoPromoCode($id_usuario, $id_hotel)){ 
			// Agregar puntos a invitador
			$puntosRef = obtenerPuntos('referral');
			agregarPuntosHl($id_usuario_invitador, $puntosRef, 3, $id_hotel, '0', $id_usuario);
			// Enviar mail invitador
			enviarEmailReferral($id_usuario_invitador, $id_hotel, $puntosRef);
		}
	}
}

$id_hotel = obtenerIdHotel();

// Búsqueda de usuario por email o por tarjeta
if(!empty($_POST['search'])){
	$busqueda = mysqli_real_escape_string(conectar() ,$_POST['search']);
	$usuarioBuscado = buscarUsuario($busqueda);
	if (empty($usuarioBuscado['id'])){// Usuario no encontrado
		$ok = array (false, '4001');
	}
}

// Check-in usuario
if(!empty($_POST['userId']) && $_POST['checkIn']){
	$id_usuario = mysqli_real_escape_string(conectar(), $_POST['userId']);
	$puntos = mysqli_real_escape_string(conectar(), $_POST['givePoints']);
	$fechaChIn=girarFecha(mysqli_real_escape_string(conectar(), $_POST['checkIn']));
	
	// Mirar si usuario ya ha hecho checkin
	$usuarioYaCheckin = usuarioYaCheckin($id_usuario, $id_hotel);
	
	if ($usuarioYaCheckin){
		//echo '--------------Este usuario ya ha hecho Check-in ';
		$ok = array (false, '4005');
	}else{
		//Acciones checkin referral
		accionesCheckinReferral($id_usuario, $id_hotel);
		// Mostrar modal si el usuario tiene cupones
		checkinUsuario ($id_usuario, $id_hotel, $fechaChIn);
		$ok = array (true, '2005');
		// agregar puntos
		if ($puntos>0){
			agregarPuntos($id_usuario, $id_hotel, $puntos, 6);
		}
		// email al usuario
		enviarEmailCheckin($puntos, $id_hotel, $id_usuario);
		$tieneCupones = comprobarSiUsuarioTieneCupones($id_usuario, $id_hotel);
	}
	unset ($_POST['checkInBtn']);
}

// Check-in invitar usuario
if(!empty($_POST['inviteUser'])){
	$email = mysqli_real_escape_string(conectar() ,$_POST['inviteUser']);
	$puntos = mysqli_real_escape_string(conectar() ,$_POST['givePoints']);
	$fecha_checkin = girarFecha(mysqli_real_escape_string(conectar() ,$_POST['checkIn']));
	if (!usuarioYaCheckinEmail($email, $id_hotel)){
		$ok = array (true, '2005');
		if(!empty($email)){
			$esNuevo = comprobarSiUsuarioNuevo($email);
			if ($esNuevo==0){// El usuario es nuevo
				// Crear usuario
				$id_usuario = crearNuevoUsuario($email, $id_hotel);
				$hotelName = obtenerNombreHotelId($id_hotel);
				// invitar. Email con invitación + datos de check-in
				include_once LANG.$_SESSION['userLang'].'/email/check-in-inv.php';
				include_once LIB.'plantillasMails/check-in-inv.php';
				crearInvitacionUsuario($email, 'hot', $id_hotel, $puntos, 0, $txtExtra, 1);
			}else{ 
				$ok[] = '3005';// El usuario no es nuevo
				$id_usuario = $esNuevo;
				// email al usuario, solo si ya existe el usuario
				enviarEmailCheckin($puntos, $id_hotel, $id_usuario);
			}
			// Acciones checkin referral
			accionesCheckinReferral($id_usuario, $id_hotel);
			checkinUsuario ($id_usuario, $id_hotel, $fecha_checkin);
			// agregar puntos
			if ($puntos>0){
				agregarPuntos($id_usuario, $id_hotel, $puntos, 6);
			}
			// Mostrar modal si el usuario tiene cupones
			$tieneCupones = comprobarSiUsuarioTieneCupones($id_usuario, $id_hotel);
		}
	}else{
		$ok = array (false, '4005');
	}
	unset ($_POST['inviteUser']);
}
/*echo '<pre>';
print_r($_POST);
echo '</pre>';*/
?>