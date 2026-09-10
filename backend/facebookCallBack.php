<?php
!defined('INDEXCONTROLVAL')? define("INDEXCONTROLVAL", "1"):'';
session_start();
include_once 'app/config.php';
include_once 'models/_indexModel.php';
include_once LIB.'loguearUsuario.php';
include_once LIB.'invitaciones.php';
include_once LIB.'guardarSpentNights.php';
include_once LIB.'crearNuevoUsuario.php';
include_once LIB.'facebook.php';
include_once LANG.'en/errores.php';
include_once LIB.'alertas.php';
include_once LANG.'en.php';
include_once LIB.'vincularRedesSociales.php';

//include_once LIB.'Facebook/keys.php';
//require_once LIB.'facebook/autoload.php';

require_once( LIB.'Facebook/Facebook/FacebookSession.php' );
require_once( LIB.'Facebook/Facebook/FacebookRedirectLoginHelper.php' );
require_once( LIB.'Facebook/Facebook/FacebookRequest.php' );
require_once( LIB.'Facebook/Facebook/FacebookResponse.php' );
require_once( LIB.'Facebook/Facebook/FacebookSDKException.php' );
require_once( LIB.'Facebook/Facebook/FacebookRequestException.php' );
require_once( LIB.'Facebook/Facebook/FacebookAuthorizationException.php' );
require_once( LIB.'Facebook/Facebook/GraphObject.php' );
require_once( LIB.'Facebook/Facebook/GraphUser.php' );
require_once( LIB.'Facebook/Facebook/GraphSessionInfo.php' );
require_once( LIB.'Facebook/Facebook/FacebookOtherException.php' );

require_once( LIB.'Facebook/Facebook/Entities/AccessToken.php' );
require_once( LIB.'Facebook/Facebook/Entities/SignedRequest.php' );

require_once( LIB.'Facebook/Facebook/HttpClients/FacebookHttpable.php' );
require_once( LIB.'Facebook/Facebook/HttpClients/FacebookCurl.php' );
require_once( LIB.'Facebook/Facebook/HttpClients/FacebookCurlHttpClient.php' );

use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\GraphUser;
use Facebook\FacebookRequestException;
use Facebook\FacebookSDKException;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\GraphSessionInfo;
use Facebook\FacebookOtherException;

use Facebook\Entities\AccessToken;
use Facebook\Entities\SignedRequest;

use Facebook\FacebookHttpable;
use Facebook\FacebookCurl;
use Facebook\FacebookCurlHttpClient;

$arrayKeysFB = getFBKeys();
$api_key = $arrayKeysFB[0];
$api_secret = $arrayKeysFB[1];
$redirect_login_url = SECURE_BASE_PATH.'facebookCallBack.php';

FacebookSession::setDefaultApplication($api_key, $api_secret);
$helper = new FacebookRedirectLoginHelper($redirect_login_url);
$sessionFB = $helper->getSessionFromRedirect();

//$token_fb_short = $sessionFB->getToken();// Guardamos el token para publicar en su muro...
$newSession = $sessionFB->getLongLivedSession();
$token_fb = $newSession->getToken();

$request = new FacebookRequest($sessionFB, 'GET', '/me?fields=email');
$response = $request->execute();
$graphObject = $response->getGraphObject();

/*echo '<!--response<pre>';
print_r($graphObject);
echo '</pre>-->';*/

$id_fb = $graphObject->getProperty('id');
$nombre = $_SESSION['username'] = $graphObject->getProperty('name');
$gender = $graphObject->getProperty('gender');
$link = $graphObject->getProperty('link');
//$location = $graphObject->getProperty('locale');
//$birthday = $graphObject->getProperty('birthday');
$email = $graphObject->getProperty('email');
/*$timezone = $graphObject->getProperty('timezone');
$updated_time = $graphObject->getProperty('updated_time');
$verified = $graphObject->getProperty('verified');*/

// Nº total amigos
$friends = new FacebookRequest($sessionFB, 'GET', '/me/friends');
$response = $friends->execute();
$graphObject = $response->getGraphObject();
$array = $graphObject->asArray();
$totalAmigos = $array['summary']->total_count;

// Img de perfil
$img = new FacebookRequest( $sessionFB, 'GET', '/me/picture?type=large&redirect=false');
$response = $img->execute();
$graphObject = $response->getGraphObject();
$array = $graphObject->asArray();
$img_usuario = $_SESSION['image'] = $array->url;

// Mirar si existe en la BD
$sql = "SELECT id_usuario, id_facebook FROM user_facebook WHERE id_facebook = '".$id_fb."' ";
$rs = mysqli_query (conectar(1), $sql);
$row = mysqli_fetch_array($rs);
liberar ($rs);

if ( $row['id_facebook'] == '' ){
	if (!isset ($_COOKIE['token_lg'])){
		$_COOKIE['token_lg'] = '0';
	}
	//comprobamos si tiene invitacion
	$sql = "SELECT email, invitador, tipo_invitador, id_encuesta, user_encuestas.id_hotel
	FROM invitaciones_users
	LEFT JOIN user_encuestas ON user_encuestas.id=invitaciones_users.id_encuesta
	WHERE token = '".$_COOKIE['token_lg']."' ";
	//echo '<!--'.$sql.'-->';
	$rs = mysqli_query (conectar(1), $sql);
	$invitacion = mysqli_fetch_assoc($rs);
	$n_resultados = mysqli_num_rows($rs);
	liberar ($rs);
	if ($n_resultados == 0 ){
		//Mirar cookie vinculacion cuentas de usuario
		if (isset ($_COOKIE['token_lsm'])){
			$tokenLSM = $_COOKIE['token_lsm'];
		}else{
			$tokenLSM = '';
		}
		$sqlLSM = "SELECT id, id_usuario, red_social, ruta
		FROM vincular_redes_sociales WHERE token ='".$tokenLSM."' ";
		//echo '<!--'.$sqlLSM.'-->';
		$rsLSM = mysqli_query (conectar(1), $sqlLSM);
		$n_resultados_LSM = mysqli_num_rows($rsLSM);
		$rowLSM = mysqli_fetch_assoc($rsLSM);
		liberar($rsLSM);
		if ($n_resultados_LSM == 0 ){
			// No invitado, lo redireccionamos
			$error = $GLOBALS['error4006'];
			header('Location: /'.$urlTree['login'].'/?error='.$error.'');
		}else{
			//Esta vinculando cuentas de redes sociales
			vincularFacebookUsuario($rowLSM['id_usuario'], $id_fb, $nombre, $img_usuario, $gender, $link, $totalAmigos, $token_fb);
			//borrar cookie
			borrarCookieLSM();
			//borrar BD
			borrarLSMdeBD($rowLSM['id']);
			//Activamos la alereta, si tiene mas de X followers -> avisamos al hotel
			if($invitacion['tipo_invitador']=='hot'){
				alertaFollowers($invitacion['invitador'], $id_usuario, $_SESSION['followers'], 'twitter');
			}
			//redirigir a $rowLSM['ruta'] desde la que estaba vinculando las redes sociales
			header('Location: '.$rowLSM['ruta'].'');
		}
	}else{
		// Usuario invitado nuevo
		$id_usuario = crearNuevoUsuarioFacebook($id_fb, $nombre, $email, $img_usuario, $gender, $link, $totalAmigos, $token_fb, $_SESSION['userNavLang']);

		//Sumamos los puntos de las invitaciones pendientes
		sumarPuntosInvitacionesUsuario($invitacion['email']);
		//Agregamos total_noches y total_spent de las invitaciones al usuario
		agregarNochesYSpent($id_usuario);

		//Activamos la alereta, si tiene mas de X followers -> avisamos al hotel
		if($invitacion['tipo_invitador']=='hot'){
			alertaFollowers($invitacion['invitador'], $id_usuario, $totalAmigos, 'facebook');
		}

		//Borrar invitaciones usuario
		borrarInvitacionUsuario($invitacion['email']);

		//Logueamos al usuario nuevo
		loguearUsuario($id_usuario);
		// Lo redireccionamos
		if(!empty($_SESSION['url_visitada'])){
			$url_visitada = $_SESSION['url_visitada'];
			unset ($_SESSION['url_visitada']);
			header('Location: '. $url_visitada .'');
		}else{
			$pantalla = pantallaLogin();
			//header('Location: /'. $urlTree['tienda'] .'');
			header('Location: /'.$urlTree[$pantalla]);
		}
	}
}else{
	//Usuario ya existe en BD
	//Logueamos al usuario
	loguearUsuario($row['id_usuario']);

	//Actualizar datos facebook
	actualizarDatosFacebook ($row['id_usuario'], $nombre, $totalAmigos, $img_usuario, $email, $token_fb);
	// Lo redireccionamos
	if(!empty($_COOKIE['redirect'])){
		$redirect = $_COOKIE['redirect'];
		setcookie ('redirect', "", time() - 3600, '/', '', true, true);//Borramos la cookie
		header('Location: '. $redirect.'');
	}else if(!empty($_SESSION['url_no_login'])){
		//Si se ha logueado despues de intentar acceder a una ruta, ahora lo redirigimos
		$url_visitada = $_SESSION['url_no_login'];
		unset ($_SESSION['url_no_login']);
		header('Location: '. $url_visitada .'');
	}else{
		$pantalla = pantallaLogin();
		//header('Location: /'. $urlTree['tienda'] .'');
		header('Location: /'.$urlTree[$pantalla]);
	}
}
?>