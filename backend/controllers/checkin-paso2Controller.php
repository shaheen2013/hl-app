<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//Contenido solo visible si logueado
include LIB.'logueado.php';
hotelStaffLanding ();// Si no esta logueado lo manda a la landing

include_once LIB.'paginacion2.php';
include_once LIB.'obtenerDatosUsuario.php';
include_once LIB.'obtenerdatosHotel.php';
include_once LIB.'staff_id_hotel.php';
include_once LIB.'enviarEmail.php';
include_once LIB.'ordenacion.php';
include_once LIB.'sanitize.php';
include_once LIB.'cuponAcciones.php';//Canjear 1 cupon
// ord: voucher, fecha 

$id_hotel = obtenerIdHotel();

// Campo ORDER BY
$pant='ch-in-p2'; // Pantalla
if(!empty($_GET['ord'])){
	$order = $_GET['ord'];
	$_SESSION['ord'.$pant] = $_GET['ord'];
	$sort = toggle();
}else if(!empty($_SESSION['ord'.$pant])){
	$order = $_SESSION['ord'.$pant];
	$sort = $_SESSION['ascdesc'];
}else{
	// Orden por defecto
	$order = 'fecha';
	$sort = 'DESC';
}

if (!empty($url['dir2'])){
	$id_usuario = $url['dir2'];
}

// Canjear varios cupones a la vez
if (!empty($_POST['redeemsForm']))
{
	foreach ($_POST['redeems'] as $promo_code){
		$result = canjearPromoCode($promo_code, $id_hotel, $id_hotel, 'hotel', '0');
		$id_cupon = $result['cuponId'];
		$id_usuario = obtenerIdUsuarioCupon($id_cupon);
		$datosEmail = obtenerDatosUsuarioMail($id_usuario);

		// Obtener datos cupon para mandarlos por email
		$datosCupon[] = obtenerDatosCupon($id_cupon, $_SESSION['h_logueado'], $datosEmail['lang']);
		
		// Al canjear el promocode debemos poner un booking value default, este debe ser el precio minimo
		// por noche del hotel o 300€ si es inferior
		$reservaMinima = obtnenerReservaMinima($_SESSION['h_logueado']);
		if($reservaMinima > '300'){
			$amount = $reservaMinima;
		}else{
			$amount = '300';
		}
		$coin = 'EUR';
		if($result['code']=='200' && $amount!='' && $result['cuponId'] != ''){
			$dolares = guardarBookingValue($_SESSION['h_logueado'], $result['cuponId'], $amount, $coin);
		}
		//---
	}
	// Enviar email de canjeo de cupones
	$datosEmail = obtenerDatosUsuarioMail($datosCupon[0]['id_usuario']);
	if (!empty($datosCupon)){
		$urlHotel = obtenerUrlGUIDHotel($id_hotel);
		include_once LANG.$datosEmail['lang'].'/email/check-in22.php';
		include_once LIB.'plantillasMails/check-in22.php';
		mandarEmailMandrillPlantilla($datosEmail['email'],$datosEmail['nombre'], $asunto2, $cuerpo2, 'standard-template');
		$ok = array (true, '2006');
	}
}

// Busca cupon por id de voucher
$itemsPage = 10;
if(!empty($_GET['search']))
{
	/*$search = mysqli_real_escape_string(conectar() , $_GET['search']);
	$arrayCupones = buscarCupon($busqueda, $id_hotel, $order, $sort);*/
	$arrayCupones = obtenerCuponesUsuario($id_usuario, $id_hotel, $order, $sort, $itemsPage, $pagina, $_GET['search']);
}else{
	$arrayCupones = obtenerCuponesUsuario($id_usuario, $id_hotel, $order, $sort, $itemsPage, $pagina);
}

/*echo '<pre>';
print_r($arrayCupones);
echo '</pre>';*/
?>