<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

include_once LIB.'crearNuevoUsuario.php';
include_once LIB.'invitaciones.php';
include_once LIB.'referrer.php';
include_once LIB.'obtenerDatosUsuario.php';
include_once MODEL.'regalarCuponModel.php';



// Email al regalador del cupón
function emailRegaladorCupon($id_usuario, $datosCupon){
	$arrayDatosEmail = obtenerDatosUsuarioMail($id_usuario);
	include_once LANG.$_SESSION['userLang'].'/email/regalarCupon1.php';
	include_once LIB.'plantillasMails/regalarCupon1.php';
	mandarEmailMandrillPlantilla($arrayDatosEmail['email'], $arrayDatosEmail['nombre'], $asunto, $cuerpo, 'standard-template');
}

// Email al usuario existente que recibe el cupón
function emailCuponRegalado($id_usuario, $datosCupon, $regalador){
	$arrayDatosEmail = obtenerDatosUsuarioMail($id_usuario);
	include_once LANG.$_SESSION['userLang'].'/email/regalarCupon3.php';
	include_once LIB.'plantillasMails/regalarCupon3.php';
	mandarEmailMandrillPlantilla($arrayDatosEmail['email'], $arrayDatosEmail['nombre'], $asunto, $cuerpo, 'standard-template');
}



// Si tiene estos 2 POST viene de cupon 
/*if(!empty($_POST['friendEmail']) && !empty($_POST['cuponId'])){
	$email = $_POST['friendEmail'];
	$id_cupon = $_POST['cuponId'];
	
	$id_hotel = obtenerIdHotelDeCupon($id_cupon);

	// mirar si pertenece a u_log
	if (propietarioCupon($_SESSION['u_logueado'], $id_cupon)){
		// mirar si ya esta canjeada
		if(!cuponCanjeado($id_cupon)){//cupon canjeable
			//datos de usuario regalador
			$regalador = obtenerDatosRegalador($_SESSION['u_logueado']);
			$datosCupon = obtenerDatosCuponRC($id_cupon);
			$id_usuario = obtenerIdUsuarioEmail($email);
			if ($id_usuario==''){
				// Crearlo
				$id_usuario = crearNuevoUsuario($email, $id_hotel);
				// Invitarlo
				include_once LANG.$_SESSION['userLang'].'/email/regalarCupon2.php';
				include_once LIB.'plantillasMails/regalarCupon2.php';
				crearInvitacionUsuario ($email,'us',$_SESSION['u_logueado'],0,0, $txtExtra);
				// Cuando haga login se vinculara como referrer 
			}else{
				// Mandar email a usuario existente con el cupón
				emailCuponRegalado($id_usuario, $datosCupon, $regalador);
			}
			regalarCupon($id_cupon, $id_usuario);
			// email a regalador
			emailRegaladorCupon($_SESSION['u_logueado'], $datosCupon);
			$ok = array (true, '2021');
		}else{
			$ok = array (false, '4022');
		}
	}else{
		$ok = array (false, '4021');
	}
}*/

// Si tiene estos 2 POST viene de oferta (debemos adquirirla primero)
/*if(!empty($_POST['friendEmail']) && !empty($_POST['offerId'])){
	$email = $_POST['friendEmail'];
	$id_oferta = $_POST['offerId'];

	$id_hotel = obtenerIdHotelDeOferta($id_oferta);
	
	if (adquirirOferta($id_oferta)){
		$id_cupon = ultimoIdInsertado('user_cupones');// <------cupón
		$datosCupon = obtenerDatosCuponRC($id_cupon);
		//datos de usuario regalador
		$regalador = obtenerDatosRegalador($_SESSION['u_logueado']);
		$id_usuario = obtenerIdUsuarioEmail($email);
		if ($id_usuario==''){
			// Crearlo
			$id_usuario = crearNuevoUsuario($email, $id_hotel);
			// Invitarlo
			include_once LANG.$_SESSION['userLang'].'/email/regalarCupon2.php';
			include_once LIB.'plantillasMails/regalarCupon2.php';
			crearInvitacionUsuario ($email,'us',$_SESSION['u_logueado'],0,0,$txtExtra);
			// Cuando haga login se vinculara como referrer 
		}else{
			// Mandar email a usuario existente con el cupón
			emailCuponRegalado($id_usuario, $datosCupon, $regalador);
		}
		regalarCupon($id_cupon, $id_usuario);
		// email a regalador
		emailRegaladorCupon($_SESSION['u_logueado'], $datosCupon);
		$ok = array (true, '2021');
	}else{
		// No puede adquirir esta oferta
		$ok = array (false, '4023');
	}
}*/
/*echo '<pre>';
print_r($_POST);
echo '</pre>';*/
?>