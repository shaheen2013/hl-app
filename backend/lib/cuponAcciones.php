<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

include_once RUTA_DIR . LIB . 'obtenerDatosStaff.php';
include_once RUTA_DIR . MODEL . 'cuponAccionesModel.php';

// Canjear cupon 1 a 1
if (!empty($_GET['redeem']))
{
	$promo_code = $_GET['redeem'];
	
	// Obtenemos el id_hotel de la session del hotel o de staff
	include_once LIB . 'obtenerDatosStaff.php';
	$id_hotel = obtenerIdHotelStaff();
	
	$datosCanjeador = obtenerDatosStaffCanjeo();
	$result = canjearPromoCode($promo_code, $id_hotel, $datosCanjeador['id'], $datosCanjeador['tipo'], '0');

	$id_cupon = $result['cuponId'];
	if($result['code']=='200')
	{
		$ok = array (true, '2006');
		
		// Al canjear el promocode debemos poner un booking value default, este debe ser el precio minimo
		// por noche del hotel o 300€ si es inferior
		$reservaMinima = obtnenerReservaMinima($id_hotel);
		if($reservaMinima > '300'){
			$amount = $reservaMinima;
		}else{
			$amount = '300';
		}
		$coin = 'EUR';
		if($result['code']=='200' && $amount!='' && $result['cuponId'] != ''){
			$dolares = guardarBookingValue($id_hotel, $result['cuponId'], $amount, $coin);
		}
		//---
		//include_once RUTA_DIR . LIB . 'obtenerDatosUsuario.php';
		//include_once RUTA_DIR . LIB . 'enviarEmail.php';
		//include_once RUTA_DIR . LIB . 'idiomas.php';
		/*$id_usuario = obtenerIdUsuarioCupon($id_cupon);
		$datosEmail = obtenerDatosUsuarioMail($id_usuario);
		$lang = mirarIdiomaPlataforma($datosEmail['lang']);
		// Obtener datos cupor para mandarlos por email
		$datosCupon = obtenerDatosCupon($id_cupon, $id_hotel, $lang);*/
		
		// Enviar email de canjeo de cupón (desactivamos momentaniamente)
		/*$link_oferta=BASE_PATH.'oferta/'.string_sanitize($datosCupon['nombre_oferta']).'/'.$datosCupon['id_oferta'];
		$link_hotel = BASE_PATH.'hotel/'.string_sanitize($datosCupon['nombre_hotel']).'/'.$datosCupon['id_hotel'];
		include_once LANG.$lang.'/email/check-in2.php';
		include_once LIB.'plantillasMails/check-in2.php';
		mandarEmailMandrillPlantilla ($datosEmail['email'], $datosEmail['nombre'], $asunto1, $cuerpo1, 'standard-template');*/	
	}else{
		//El cupon no se ha canjeado
		$ok = array (false, '4002');
	}
}

// FX para obtener los datos del staff para el canjeo de cupones
function obtenerDatosStaffCanjeo()
{
	if( !empty($_SESSION['staff_logueado']) )	{
		$result['id'] = $_SESSION['staff_logueado'];
		$result['tipo'] = 'staff';
		$result['code'] = '200';
	}else if ( !empty($_SESSION['c_logueado']) ){
		$result['id'] = $_SESSION['c_logueado'];
		$result['tipo'] = 'cadena';
		$result['code'] = '200';
	}else if ( !empty($_SESSION['h_logueado']) ){
		$result['id'] = $_SESSION['h_logueado'];
		$result['tipo'] = 'hotel';
		$result['code'] = '200';
	}else{
		// Esta canjeando desde la API
	}
	return $result;
}
?>