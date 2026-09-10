<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//--Se puede acceder sin estar logueado--
include_once LIB.'sanitize.php';
include_once LIB.'obtenerDatosUsuario.php';
include_once LIB.'url-shortener.php';
include_once LIB.'acqOffer.php'; // Acciones para adquirir oferta, issue
include_once LIB.'regalarCupon.php';
include_once LIB.'registrar_visita.php';
include_once LIB.'ofertaHotelCadena.php';
include_once LIB.'obtenerdatosHotel.php';
include_once LIB.'obtenerDatosCadena.php';
include_once LIB.'fecha.php';

function mirarSiCanjeable ($id_oferta){
	$error[0]='';
	if (empty($_SESSION['u_logueado'])){
		$error[] = '4003';
	}else{
		if (!quedaCupo($id_oferta)){
			$error[] = '4007';
		}
		if (!tienePuntosSuficientes ($id_oferta, $_SESSION['u_logueado'])){
			$error[] = '4008';
		}
		if (!checkinAnteriormente ($id_oferta, $_SESSION['u_logueado'])){
			$error[] = '4009';
		}
		if (!requiereChk($id_oferta, $_SESSION['u_logueado'])){
			$error[] = '4010';
		}
		if (!ofertaPublicada($id_oferta)){
			$error[] = '4011';
		}
		if (!ofertaAdqYaAdquirida($id_oferta, $_SESSION['u_logueado'])){
			$error[] = '4012';
		}
	}
	$nErrores = count ($error);
	if ($nErrores == 1){
		$error[0]=true;
	}else{
		$error[0]=false;
	}
	return $error;
}
$id_oferta = mysqli_real_escape_string(conectar() , $url['dir3']);
// Solo debemos mostrar la modal de checks la primera vez
/*if (empty($_SESSION['modal_'.$id_oferta])){
	$_SESSION['modal_'.$id_oferta] = '10';
}*/

$id = $id_oferta;
$ofertaDe = ofertaHotelCadena($id_oferta);//Miramos si la oferta es de hotel o de cadena
//Creamos la URL+GUID según si es un hotel o una cadena
if($ofertaDe['tipo']=='hot')
{
	$urlGUID = obtenerUrlGUIDHotel($ofertaDe['id']);
}else{
	$urlGUID = obtenerUrlGUIDCadena($ofertaDe['id']);
}
$arrayDatosOferta = obtenerDatosOferta($id_oferta, $ofertaDe);

if (!empty($_SESSION['u_logueado'])){
	$puntosUsuarioOferta = obtenerPuntosUsuario($_SESSION['u_logueado'], $id_oferta);
}else{
	$puntosUsuarioOferta = 0;
}
$url_o=BASE_PATH.$urlTree['oferta'].'/'.string_sanitize($arrayDatosOferta['nombre_oferta']).'/'.$arrayDatosOferta['id'];

include_once LIB.'offer_actions.php';

//$arrayDatosOferta = obtenerDatosOferta($id_oferta);

// array con los cupones del usuario
$arrayCuponesOferta = array();
if (!empty($_SESSION['u_logueado'])){
	// Cupones del usuario logueado
	//$arrayCuponesOferta = obtenerCuponesUsuario($id_oferta);
	// Follow / Unfollow, Wishlist
	$arrayFolWlst = obtenerFollowWishlist($_SESSION['u_logueado'], $ofertaDe['id'], $ofertaDe['tipo'], $id_oferta);
}

// Mostramos la modal de checks solo una vez
/*if(!empty($_SESSION['u_logueado']) && !empty($_SESSION['modal_'.$id_oferta]) && $_SESSION['modal_'.$id_oferta] == '10'){
	$checks = mirarSiCanjeable ($id_oferta);
	$_SESSION['modal_'.$id_oferta] = '20';
}*/

$checks = mirarSiCanjeable ($id_oferta);

//Registrar visita (ip, tipo, id_oferta)
$remote_addr = mysqli_real_escape_string(conectar(), $_SERVER['REMOTE_ADDR']);
if(isset($_SERVER['HTTP_REFERER'])){
	$http_referer = mysqli_real_escape_string(conectar(), $_SERVER['HTTP_REFERER']);
}else{
	$http_referer='';
}
registrarVisita($remote_addr, 'oferta', $id_oferta, $http_referer);

if(!empty($_SESSION['hotel']['brand_id'])) {
	$websiteReservaUrl = getHotelWebsiteUrl($_SESSION['hotel']['brand_id'], $_SESSION['userLang']);
}


/*echo '<pre>';
print_r($ofertaDe);
echo '</pre>';*/
?>