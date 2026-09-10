<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

// acciones para oferta y cupon
include_once LIB.'wishlist.php';
include_once LIB.'follow.php';
include_once LIB.'twitter.php';
include_once LIB.'enviarEmail.php';
include_once LIB.'vincularRedesSociales.php';
include_once LIB.'facebookPost.php';
include_once LIB.'obtenerDatosUsuario.php';

// ¿Any issue with this offer?
if (!empty($_POST['issue']) && !empty($_SESSION['u_logueado'])){
	$issue = mysqli_real_escape_string(conectar(), $_POST['issue']);
	$arrayDatosEmail = obtenerDatosUsuarioMail($_SESSION['u_logueado']);
	$ofertaDe = ofertaHotelCadena($id_oferta); 
	$arrayDatosOferta = obtenerDatosOferta($id_oferta, $ofertaDe);

	$urlUsuario = obtenerUrlGUIDUsario($arrayDatosEmail['id']);
	$urlOferta = BASE_PATH. $urlTree['oferta'] . '/'.$arrayDatosOferta['nombre_oferta_san'].'/'.$arrayDatosOferta['id'];
	include_once LANG.$_SESSION['userLang'].'/email/offer-actions.php';
	include_once LIB.'plantillasMails/offer-actions.php';
	$cuerpo1 .= $issue;
	mandarEmailMandrillPlantilla('customerservice@hotelinking.com','Hotelinking',$asunto1,$cuerpo1, 'standard-template');

	// enviar mail al usuario
	mandarEmailMandrillPlantilla($arrayDatosEmail['email'],$arrayDatosEmail['nombre'],$asunto2,$cuerpo2, 'standard-template');
}else if (!empty($_POST['issue']) && !empty($_SESSION['u_logueado'])){
	$ok = array (false, '4015');
}

// Share oferta
if(!empty($_GET['shr']) && !empty($_SESSION['u_logueado'])){
	// Botón "Share this offer"
	$redesSociales = obtenerRedesSociales($_SESSION['u_logueado']);
	if ($redesSociales['facebook']!=0 || $redesSociales['twitter']!=0){
		$urlCortaOferta = get_bitly_short_url($url_o);
		$text = 'Text :'.$urlCortaOferta;
		if ($redesSociales['facebook']!=0){
			$arrayDatosHotel = obtenerIdHotelOferta($id_oferta);
			if(!empty($arrayDatosHotel['fotoBg'])){
				// $UrlFotoBgHotel = BASE_PATH.DIR_IMG_FICHA_HOTEL.$arrayDatosHotel['id'].'/fotoBg/'.$arrayDatosHotel['fotoBg'];
				$UrlFotoBgHotel = $arrayDatosHotel['fotoBg'];
			}else{
				$UrlFotoBgHotel = BASE_PATH.DIR_IMG.'big-logo.png';// Foto por defecto
			}
			$idPostFB = postFacebook($_SESSION['u_logueado'], $text, $urlCortaOferta, $UrlFotoBgHotel);
		}
		if ($redesSociales['twitter']!=0){
			sendTweet($_SESSION['u_logueado'], $text);
		}
		$ok = array (true, '2011');
	}else{
		//no puede compartir si no tiene redes sociales
		$ok =  array (false, '4039');
	}
}else if(!empty($_GET['shr']) && empty($_SESSION['u_logueado'])){
	// Usuario no logueado
	$ok = array (false, '4015');
}

function obtenerFollowWishlist($id_usuario, $id, $tipo, $id_oferta){
	//Miramos si la oferta es de hotel o cadena
	if($tipo=='cad'){
		//La oferta es de cadena
		$sql = "SELECT follow FROM user_cadena_follow
		WHERE id_usuario='".$id_usuario."' AND id_cadena='".$id."' ";
	}else{
		//La oferta es de hotel
		$sql = "SELECT follow FROM user_hotels
		WHERE id_usuario='".$id_usuario."' AND id_hotel='".$id."' ";
	}
	$rs = mysqli_query (conectar(), $sql);
	$n_resultados = mysqli_num_rows($rs);
	if ($n_resultados==0){
		$follow = false;
	}else{
		$row = mysqli_fetch_assoc($rs);
		if ($row['follow']=='1'){
			$follow = true;
		}else{
			$follow = false;
		}
	}
	liberar($rs);

	$sql2 = "SELECT id FROM user_wishlist
	WHERE id_oferta='".$id_oferta."' AND id_usuario='".$id_usuario."' ";
	$rs2 = mysqli_query (conectar(), $sql2);
	$n_resultados = mysqli_num_rows($rs2);
	liberar($rs2);
	if ($n_resultados==0){
		$wishlist = false;
	}else{
		$wishlist = true;
	}
	$arrayFollowWishlist['follow']=$follow;
	$arrayFollowWishlist['wishlist']=$wishlist;
	return $arrayFollowWishlist;
}
?>