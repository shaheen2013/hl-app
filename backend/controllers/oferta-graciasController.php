<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

include_once LIB.'twitter.php';
include_once LIB.'sanitize.php';
include_once LIB.'url-shortener.php';
include_once LIB.'agregarPuntos.php';
include_once LIB.'vincularRedesSociales.php';
include_once LIB.'facebookPost.php';
include_once LIB.'obtenerdatosHotel.php';
include_once LANG.$_SESSION['userLang'].'/oferta-gracias.php';

$id_oferta = mysqli_real_escape_string(conectar(),$_GET['id']);
$arrayDatosOferta = obtenerDatosOferta(mysqli_real_escape_string(conectar(),$id_oferta));
$points = obtenerPuntos('shr_oferta'); // De momento no se dan puntos por compartir
$urlHotel = obtenerUrlGUIDHotel($arrayDatosOferta['id_hotel']);

// Si no existe la oferta o si no ha sido adquirida por el usuario logueado
if (empty($arrayDatosOferta)){
	header ('Location: /'.$urlTree['tienda'].'/?error=4014');
}else if(!empty($_GET['id-cupon']) && !empty($_GET['id']) && !empty($_GET['nombre'])){
	$id_cupon = mysqli_real_escape_string(conectar(),$_GET['id-cupon']);
	
	$redesSociales = obtenerRedesSociales($_SESSION['u_logueado']);
	if ($redesSociales['facebook']!=0 || $redesSociales['twitter']!=0){
		// Compartir en Twitter
		$id_oferta = mysqli_real_escape_string(conectar(),$_GET['id']);
		$nombreOferta = mysqli_real_escape_string(conectar(),$_GET['nombre']);
		$urlOferta = BASE_PATH.'oferta/'.$nombreOferta.'/'.$id_oferta;
		$urlCortaOferta = get_bitly_short_url($urlOferta);
		$texto = $ofertaGraciasLang['tweet'].' '.$urlCortaOferta ;
		if ($redesSociales['facebook']!=0){
			//Logo del hotel
			$arrayDatosHotel = obtenerIdHotelOferta($id_oferta);
			if(!empty($arrayDatosHotel['fotoBg'])){
				$UrlFotoBgHotel = BASE_PATH.DIR_IMG_FICHA_HOTEL.$arrayDatosHotel['id'].'/fotoBg/'.$arrayDatosHotel['fotoBg'];
			}else{
				$UrlFotoBgHotel = BASE_PATH.DIR_IMG.'big-logo.png';// Foto por defecto
			}
			$idPostFB = postFacebook($_SESSION['u_logueado'], $texto, $urlCortaOferta, $UrlFotoBgHotel);
		}
		if ($redesSociales['twitter']!=0){
			sendTweet($_SESSION['u_logueado'], $texto);
		}
		// marcar en BD como compartida
		marcarCuponCompartido($id_cupon);
		// oferta compartida
		$ok =  array (true, '2011');
	}else{
		//no puede compartir si no tiene redes sociales
		$ok =  array (false, '4039');
	}
}

/*echo '<pre>';
print_r($arrayDatosOferta);
echo '</pre>';*/
?>