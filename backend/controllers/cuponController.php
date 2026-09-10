<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

include_once LIB.'sanitize.php';
include_once LIB.'offer_actions.php';
include_once LIB.'regalarCupon.php';
include_once LIB.'ofertaHotelCadena.php';
include_once LIB.'obtenerdatosHotel.php';
include_once LIB.'obtenerDatosCadena.php';
//include_once LIB.'acqOffer.php'; // Acciones para adquirir oferta
include_once LIB.'fecha.php';


$id_oferta = $url['dir3'];
$arrayDatosOferta = obtenerDatosOferta($id_oferta);

if(!empty($_SESSION['hotel']['brand_id']) && !empty($_SESSION['userLang'])){
	$websiteReservaUrl = getHotelWebsiteUrl($_SESSION['hotel']['brand_id'], $_SESSION['userLang']);
}
//Url actual sin parametros
$info = parse_url( $_SERVER["REQUEST_URI"] );
$url_o = $info['path'];

$ofertaDe = ofertaHotelCadena($id_oferta); //Miramos si la oferta es de hotel o de cadena
//Creamos la URL+GUID según si es un hotel o una cadena
if($ofertaDe['tipo']=='hot')
{
	$urlGUID = obtenerUrlGUIDHotel($ofertaDe['id']);
}else{
	$urlGUID = obtenerUrlGUIDCadena($ofertaDe['id']);
}
//Id de la oferta
$id = $arrayDatosOferta['id'];

// array con los cupones del usuario
$arrayCuponesOferta = array();
if (!empty($_SESSION['u_logueado']))
{
	if(!empty($_GET['search']))
	{
		// Cupón búscado
		$arrayCuponesOferta = obtenerCuponesUsuario($id_oferta, $_GET['search']);
	}else{
		// Cupones del usuario logueado
		$arrayCuponesOferta = obtenerCuponesUsuario($id_oferta);
	}
	// Follow / Unfollow, Wishlist
	$arrayFolWlst = obtenerFollowWishlist($_SESSION['u_logueado'], $ofertaDe['id'], $ofertaDe['tipo'], $id_oferta);
	// Generamos la URL de canjeo de cada cupon
	// La URL de las ofertas de cadena van al hotel al que se han adquirido el cupón
	// Los cupones ya canjeados no tienen URL de canjeo
	$nCupones = count($arrayCuponesOferta);
	$i = 0;
	while($i < $nCupones){
		if(!empty($arrayCuponesOferta[$i]['id_hotel_cupon'])){
			$guid = obtenerGUIDHotel($arrayCuponesOferta[$i]['id_hotel_cupon']);
			$arrayCuponesOferta[$i]['redeemOfferUrl'] = BASE_PATH . $urlTree['redeem-offer'].'/?hlhid='.$guid.'&hlpc='.$arrayCuponesOferta[$i]['voucher'];
			if(!empty($arrayCuponesOferta[$i]['cookie_id']))
				$arrayCuponesOferta[$i]['redeemOfferUrl'] .= '&cid='.$arrayCuponesOferta[$i]['cookie_id'];

		}else{
			$arrayCuponesOferta[$i]['redeemOfferUrl'] = '';
		}
		$i++;
	}
}

/*echo '<pre>';
print_r($arrayDatosOferta);
echo '</pre>';*/
?>