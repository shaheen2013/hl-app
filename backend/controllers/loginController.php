<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//DEPRECATED CONTROLLER
//Redirect to home.
header('Location: /');
exit;


include_once LIB . 'invitaciones.php';
include_once LIB . 'loguearUsuario.php';
include_once LIB . 'enviarEmail.php';
include_once LIB . 'cookieLogin.php';

// Unset de la variable de redirect de la pantalla referral-share-step-2
if(!empty($_SESSION['twitterLoginRedirect']))
{
	unset($_SESSION['twitterLoginRedirect']);
}

if (!empty ($_POST['twitterForm']))
{
	// Miramos si tiene las cookie habilitadas
	if( tieneCookiesLogin() )
	{
		include(LIB . 'twitter-async/EpiCurl.php');
		include(LIB . 'twitter-async/EpiOAuth.php');
		include(LIB . 'twitter-async/EpiTwitter.php');
		include(LIB . 'twitter-async/keyTwitter.php');//keys winhotel / hotelinking
		$twitterObj = new EpiTwitter($consumer_key, $consumer_secret);
		$authenticateUrl = $twitterObj->getAuthenticateUrl();

		//$url = explode ("=" ,$authenticateUrl);
		//$token = $url[1]; //contiene el token
		//echo 'Token: '.$token;

		/*try {
			$authenticateUrl = $twitterObj->getAuthenticateUrl( );
			echo $authenticateUrl;
		} catch ( EpiOAuthUnauthorizedException $e ) {
			echo 'Failed: ';
			echo $e->getMessage( );
			print_r( $e );
		}*/
		header('Location: '.$authenticateUrl.'');
	}else{
		// Tiene cookies deshabilitadas. Feedback para que las active.
		$ok = array (false, '4061');
	}
}

if (!empty ($_POST['facebookForm']))
{
	// Miramos si tiene las cookie habilitadas
	if( tieneCookiesLogin() )
	{
		header('Location: /'.$urlTree['facebook-login']);
	}else{
		// Tiene cookies deshabilitadas. Feedback para que las active.
		$ok = array (false, '4061');
	}
}

if(!empty($_POST['userEmail']) && !empty($_POST['userPass']) )
{
	// Miramos si tiene las cookie habilitadas
	if( tieneCookiesLogin() )
	{
		// Login normal de email & pass (No de red social)
		$email = (mysqli_real_escape_string(conectar(), $_POST['userEmail']));
		$pass = (mysqli_real_escape_string(conectar(), $_POST['userPass']));
		//Mirar si ha pasado el maximo de intentos
		if(!maximoIntentos($email)){
			$array_login = array();
			$array_login = validarLoginUsaurio($email, $pass);
			if($array_login[0]=='200'){
				//Login ok
				$id = obtenerIdUsuario($email);
				loguearUsuario($id);
				//Si se ha logueado despues de intentar acceder a una ruta, ahora lo redirigimos
				if(!empty($_SESSION['url_no_login'])){
					$url_visitada = $_SESSION['url_no_login'];
					unset ($_SESSION['url_no_login']);
					header('Location: '. $url_visitada .'');
				}else{
					$pantalla = pantallaLogin();
					//header('Location: '.BASE_PATH.$urlTree['tienda']);
					header('Location: '.BASE_PATH.$urlTree[$pantalla]);
				}
			}else{
				//Login NO ok
				$ok = array (false, $array_login[0]);
				guardarIntentoLogin($email);
			}
		}else{
			//cuenta bloqueada, debe espera para volver a intentar el login
			$ok = array (false, '4026');
		}
	}else{
		// Tiene cookies deshabilitadas. Feedback para que las active.
		$ok = array (false, '4061');
	}
}

// Recogemos del la URL el token y lo comprobamos en la BD
if (isset ($_GET['token'])){
	$token = (mysqli_real_escape_string(conectar(), $_GET['token']));
	$email = obtenerEmailInvitacionUS($token);
	if(comprobarInvitacionUsuario($token)){
		//Tiene invitación
	}else{
		//No tiene invitación o token incorrecto
		$ok = array (false, '4037');
	}
}else{
	// Login sin invitacion o usuario ya existente
	$token ='';
}
$invitado = comprobarInvitacionUsuario($token);

if ($invitado){
	// Usuario invitado
	// Guardar cookie con el email
	setcookie ('token_lg', $token, 0 ,'/', '', true, true);
}

// Viene de 'verificar-email' con token incorrecto
if (isset ($_GET['tkerror']) && ($_GET['tkerror']==1)){
	$ok = array(false, '4002');
}

// Viene de 'create-account' debe verificar email
if (isset ($_GET['ckemail']) && ($_GET['ckemail']==1)){
	$ok = array(true, '2003');
}

if (!empty ($_POST['recoveryForm'])){
	$email = mysqli_real_escape_string(conectar(), $_POST['recovery_emal']);
	$userData = recoveryPass ($email);
	// Mandar email con el nuevo pass
	$email = $userData['email'];
	$name = $userData['name'];
	include_once LANG.$_SESSION['userLang'].'/email/login.php';
	include_once LIB.'plantillasMails/login.php';
	mandarEmailMandrillPlantilla($email, $name, $asunto, $cuerpo, 'standard-template');
	$ok = array(true, '2003');
}
?>