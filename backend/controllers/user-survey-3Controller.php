<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//Contenido solo visible si logueado
include LIB.'logueado.php';
userLanding ();// Si no esta logueado lo manda a la landing

include_once LIB.'twitter.php';
include_once LIB.'facebookPost.php';
include_once LIB.'agregarPuntos.php';
include_once LIB.'generarToken.php';
include_once LIB.'enviarEmail.php';
include_once LIB.'obtenerDatosUsuario.php';
include_once LIB.'vincularRedesSociales.php';
include_once LIB.'referrer.php';
include_once LIB.'referral-share-actions.php';
include_once LIB.'obtenerdatosHotel.php';

if (!empty($_GET['id'])){
	$id_encuesta = mysqli_real_escape_string(conectar(), $_GET['id']);
	$arrayDatosHotel = obtenerDatosHotelIdEncuestas($id_encuesta);
}

function enviarEmailSurvey($id_usuario, $arrayDatosHotel, $tweetOK=0){
	$puntos_survey = obtenerPuntos('survey');//Puntos encuesta
	$puntos_shr_survey = obtenerPuntos('shr_survey');//Puntos compartir encuesta
	$puntos_hl = obtenerPuntosUsuarioHL($id_usuario);
	$arrayDatosEmail = obtenerDatosUsuarioMail($id_usuario);
	include_once LANG.$_SESSION['userLang'].'/email/user-survey-3.php';
	include_once LIB.'plantillasMails/user-survey-3.php';
	mandarEmailMandrillPlantilla($arrayDatosEmail['email'], $arrayDatosEmail['nombre'], $asunto, $cuerpo, 'standard-template');
}

//Puede querer compartir por pulsar el boton o por venir de FB de regenerar token caducado
if ((!empty ($_POST['tweet']) && !encuestaCompartida($id_encuesta) && encuestaDeUsuario($_SESSION['u_logueado'], $id_encuesta)) || (!empty ($_GET['post']) && !encuestaCompartida($id_encuesta) && encuestaDeUsuario($_SESSION['u_logueado'], $id_encuesta)) ){
	//Encuesta no compartida y que pertenece al usuario logueado
	//Puede acceder aqui por: 1.Ha pulsado el boton de compartir
	//2. 1 + ha renovado el token de FB ha vuelto aqui (url+$_GET['post']) y hemos forzado un post
	//a este boton para que el usuario no tenga que darle 2 veces
	if(!empty($_POST['tweet'])){
		//El usuario ha pulsado para hacer post
		$texto = mysqli_real_escape_string(conectar(), $_POST['tweet']);
	}else if(!empty($_COOKIE['txtSurvey'])){
		//No se hace post manual, viene de renovar el token de FB caducado
		$texto = $_COOKIE['txtSurvey'];
	}
	//$id_encuesta = mysqli_real_escape_string(conectar(), $_POST['id']);
	$urlShare = generarUrl($_SESSION['u_logueado'], $id_encuesta);
	//$texto .= $urlShare;

	//Si el token de FB ha expirado le pedimos debe loguearse
	if(tokenFBExpirado($_SESSION['u_logueado'])){
		$url_redirect = $_SERVER['REQUEST_URI'].'&post=1';
		setcookie ('redirect', $url_redirect, 0 ,'/', '', true, true);
		setcookie ('txtSurvey', $texto, 0 ,'/', '', true, true);
		header('Location: /'.$urlTree['facebook-login']);
	}else{
		//Token correcto
		$arrayDatosHotel = obtenerDatosHotelIdEncuestas($id_encuesta);

		if(!empty($arrayDatosHotel['fotoBg'])){
			$UrlFotoBgHotel = BASE_PATH.DIR_IMG_FICHA_HOTEL.$arrayDatosHotel['id'].'/fotoBg/'.$arrayDatosHotel['fotoBg'];
		}else{
			$UrlFotoBgHotel = BASE_PATH.DIR_IMG.'big-logo.png';// Foto por defecto
		}

		$idPostFB ='';$arrayTweet=array();
		//obtener redes sociales del usuario
		$redesSociales = obtenerRedesSociales($_SESSION['u_logueado']);
		if($redesSociales['twitter'] == '1'){
			$arrayTweet = sendTweet($_SESSION['u_logueado'], $texto.$urlShare);
		}
		if($redesSociales['facebook'] == '1'){
			$idPostFB = postFacebook($_SESSION['u_logueado'], $texto, $urlShare, $UrlFotoBgHotel);
		}

		if($arrayTweet[0]==200 || !empty($idPostFB)){//Share ok
			$puntos = obtenerPuntos('shr_survey');// nº de puntos por compartir
			// Registrar share(s)
			if($arrayTweet[0]==200){
				guardarShareUsuario($_SESSION['u_logueado'], $arrayDatosHotel['id'], 'tw', $arrayTweet[1],$id_encuesta);
			}
			if(!empty($idPostFB)){
				guardarShareUsuario($_SESSION['u_logueado'], $arrayDatosHotel['id'], 'fb', $idPostFB, $id_encuesta);
			}else if(empty($idPostFB) && $redesSociales['facebook'] == '1'){
				// Tiene cuenta de FB vinculada pero no se ha mandado el msg.
				// Posiblemente el usuario ha bloqueado nuestra aplicación
			}
			// Agregar puntos por compartir
			agregarPuntosHl($_SESSION['u_logueado'], $puntos, 2, $arrayDatosHotel['id']);
		}
		enviarEmailSurvey($_SESSION['u_logueado'], $arrayDatosHotel, $arrayTweet[0]);
		if (isset($_COOKIE['txtSurvey'])){
			setcookie ('txtSurvey', "", time() - 3600, '/', '', true, true);//Borramos la cookie
		}
		header ('Location: /'.$urlTree['user-survey-thanks']);
	}
}else if(encuestaCompartida($id_encuesta) && !encuestaDeUsuario($_SESSION['u_logueado'])){
	//encuesta ya compartida o de otro usuario
	//echo '<!--encuesta ya compartida o de otro usuario-->';
	header ('Location: /'.$urlTree['user-survey-list']);
}

if (!empty($_GET['endsur'])){//Usuario ha hech skip del survey
	$arrayDatosHotel = obtenerDatosHotelIdEncuestas($id_encuesta);
	enviarEmailSurvey($_SESSION['u_logueado'], $arrayDatosHotel);
	header ('Location: /'.$urlTree['user-survey-list']);
}

//Para vincular redes sociales
$nRedesSociales = obtenerNRedesSociales($_SESSION['u_logueado']);
$redesSociales = obtenerRedesSociales($_SESSION['u_logueado']);

//$urlShare = generarUrl($_SESSION['u_logueado'], $id_encuesta);
//$texto = $UserSurvey3Lang['My experience in the'].$arrayDatosHotel['hotelName'].$UserSurvey3Lang['has been amazing!'];
?>