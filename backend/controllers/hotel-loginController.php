<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

include_once LIB . 'enviarEmail.php';
include_once LIB . 'cookieLogin.php';

//funcion validar login hotelero
function validarLoginHotel ($email, $password)
{
	global $log;

	$result = array();

	//verificamos que el email tiene formato valido
	$validar_mail = filter_var($email, FILTER_VALIDATE_EMAIL);
	if ($validar_mail == false)
	{
		$result['error'] = '4027'; //email incorrecto
	}else {
		$result = consultaBDLoginHotel($email, $password) ;
	}

	$log->debug('resutl', $result);
	return $result;
}

if (!empty ($_POST['loginForm']))
{
	// Miramos si tiene las cookie habilitadas
	if( tieneCookiesLogin() )
	{
		$email_login = $_POST['email_login'];
		// Quitamos los espacios en blanco de inicio y fin del pass por si copia/pega de un email de cambio de pass
		$pass_login = trim($_POST['pass_login']);

		// Mirar si ha pasado el maximo de intentos
		if(!maximoIntentos($email_login))
		{
			$array_login = array();
			$array_login = validarLoginHotel($email_login,$pass_login );

			if($array_login['error']=='200')
			{
				// Login OK
				include_once LIB.'loguearHotel.php';
				if($array_login['type']=='hotel'){
					$resultLogin = loguearHotel($array_login['id']);
					setUserCognito($resultLogin, $pass_login);
				}else if($array_login['type']=='cadena'){
					$resultLogin = loguearCadena($array_login['id']);
                    setUserCognito($resultLogin, $pass_login);
				}else if($array_login['type']=='staff'){
					$resultLogin = loguearStaff($array_login['id']);
					if(!$resultLogin){
                        $ok = array (false, '4076');
					}
				}
				// Borrar cookie de login (login ok)
				borrarCookieLogin();

				//Borrar intentos login
				borrarIntentosLogin($email_login);

				if(!empty($_SESSION['url_visitada']))
				{
					// Ha intentado acceder a alguna parte de su panel, despues del login lo mandamos alli
					$url_visitada = $_SESSION['url_visitada'];
					unset ($_SESSION['url_visitada']);
					header('Location: '. $url_visitada .'');
				}else{
					//Redireccionamos a $resultLogin['defaultPage'], contiene la ruta a la que debe redirigirse al hotel/cadena/staff
					header('Location: '.BASE_PATH.$resultLogin['defaultPage'].'/');
				}
			}else{
				//Login NO ok
				$ok = array (false, $array_login['error']);
				guardarIntentoLogin($email_login);
			}
		}else{
			// cuenta bloqueada, debe espera para volver a intentar el login
			$ok = array (false, '4026');
		}
	}else{
		// Tiene cookies deshabilitadas. Feedback para que las active.
		$ok = array (false, '4061');
	}
}

if (!empty ($_POST['recoveryForm']))
{
	$email = $_POST['recovery_emal'];
	$userData = recoveryPass ($email);
	if( $userData['error'] == '200' )
	{
		// Mandar email con el nuevo pass
		$email = $userData['email'];
		$name = $userData['name'];
		include_once LANG.$_SESSION['userLang'].'/email/hotel-login.php';
		include_once LIB.'plantillasMails/hotel-login.php';
		mandarEmailMandrillPlantilla($email, $name, $asunto, $cuerpo, 'standard-template');
	}else{
		// Email no existe en la BD como hotel, cadena o staff.
		// No mostramos feedback distinto para no dar información de nuestra BD.
	}
	$ok = array(true, '2003');
}

// Para mostrar msg de error / exito en esta pantalla
// Vienen de otra pantalla
if( !empty($_GET['msg']) && ($_GET['msg']=='2034' || $_GET['msg']=='4002' || $_GET['msg']=='2006') )
{
	$msg = $_GET['msg'];
	// Miramos que tipo de msg es
	if( substr($msg, 0, 1) =='2' )
	{
		$ok = array(true, $msg);
	}else{
		$ok = array(false, $msg);
	}

}
/*echo '<pre>';
print_r($urlTree);
echo '</pre>';*/
?>
