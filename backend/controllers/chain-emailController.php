<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//Contenido solo visible si logueado
include LIB.'logueado.php';
hotelStaffLanding ();// Si no esta logueado lo manda a la landing
include LIB.'isIndependent.php';
include LIB.'cadenaPantalla.php';

// For front purposes
$currentPage = 'chain-management';
$currentSubPage = 'chain-email-management';

// FX para mandar el email de verificación de cambio de email (de cuenta o de envios)
// id_cadena
// email: email al que se manda
// urlVerif: url que debe seguir el usuario para verificar esa cuenta de email
function enviarEmailCambioEmailCadena($id_cadena, $email, $urlVerif)
{
	$cadena = obtenerDatosBasicosCadena($id_cadena);
	
	//Mandamos una copia oculta a Dani para que pueda verificar la cuenta.
	$emailBCC = 'helpdesk@hotelinking.com';
	
	include LANG . $_SESSION['userLang'].'/email/chain-email.php';
	include LIB . 'plantillasMails/chain-email.php';
	mandarEmailMandrillPlantilla($email, $cadena['nombre'], $asunto, $cuerpo, 'standard-template', 'helpdesk@hotelinking.com', 'hotelinking.com', $emailBCC);
	
	// Return control
	return 'email enviado a '.$email;
}
// Obtenemos los datos de la cadena necesarios para esta pantalla antes de los cambios
$datosHotelCE = obtenerDatosCadenaCE($_SESSION['c_logueado']);
global $log;
$log->debug('cadena info' , $datosHotelCE);


// Esta cambiando algún email
// sending-email es opcional
if( !empty($_POST['chain-email']) && !empty($_POST['account-email']) )
{
	include_once LIB . 'verificarEmail.php';// Para borrar verificacion email anteriores
	include_once LIB . 'generarToken.php';
	include_once LIB . 'enviarEmail.php';
	include_once LIB . 'obtenerDatosCadena.php';// Para obtener el GUID de la cadena
	
	// Inicializamos variables
	$control = array();
	$changeEmailAccount=0;
	$changeEmailEnvios=0;
	
	$accountEmail = $_POST['account-email'];// Email de login de la cadena
	
	if (!empty($_POST['sending-email']))
	{
		$sendingEmail = $_POST['sending-email'];// Email para envios de email de la cadena y sus hoteles

	}else{
		// No hay post de sending-email
		if( !empty($datosHotelCE['email_envio']) )
		{
			// Antes tenia un email de envios pero lo ha borrado. Borramos el email de envios sin mas.
			include_once MODEL . 'verificar-emailModel.php';

			modificarEmailVerificado($_SESSION['c_logueado'], '', 'cadena', 'email_envio');
			// Borrar emails anteriores a verificar. 
			borrarVerifAnteriores2($_SESSION['c_logueado'], 'cad', 'emEnv');
			// Feedback
			$ok = array(true, '2007');
			$control[] = 'Ha borrado el email';
		}
		$sendingEmail = '';
	}
	
	if( $accountEmail != $datosHotelCE['email'] )
	{
		// Ha modificado el email de cuenta
		if(emailCadenaExiste($accountEmail, 'email'))
		{

			// El email ya existe. Feedback.
			$ok = array(false, '4025');
		}else{
			// El email no existe, procedemos
			// Borrar emails anteriores a verificar. Solo guardamos el último.
			borrarVerifAnteriores2($_SESSION['c_logueado'], 'cad', 'emAcc');
			
			// Marcamos que quiere cambiar email de cuenta
			$changeEmailAccount=1;
		}
	}else{
		// Marcamos que NO quiere cambiar email de cuenta
		$changeEmailAccount=0;
	}
	
	if( $sendingEmail != $datosHotelCE['email_envio'] && $sendingEmail != '' )
	{
		// Ha modificado el email de envios
		if(emailCadenaExiste($sendingEmail, 'email_envio'))
		{
			
			// El email ya existe. Feedback.
			$ok = array(false, '4025');
			
		}else{
			// Borrar emails anteriores a verificar. Solo guardamos el último.
			borrarVerifAnteriores2($_SESSION['c_logueado'], 'cad', 'emEnv');
			// Marcamos que quiere cambiar email de envios
			$changeEmailEnvios=1;

			//Change chain communication email and also on emails platform
			updateChainSendingEmail($_SESSION['c_logueado'], $_POST['sending-email']);
		}
	}else{
		// Marcamos que NO quiere cambiar email de envios
		$changeEmailEnvios=0;
	}
	
	// + guid cadena
	$guid_cadena = obtenerGUIDCadena($_SESSION['c_logueado']);
	
	// Generamos la URL para verificar el/los emails
	$urlVerif = BASE_PATH.$urlTree['verificar-email'].'/?tp=c'.'&guid='.$guid_cadena;
	
	// Enviar email para que verifique la cuenta/s. 
	// Existe la posibilidad de que hayan cambiado ambos y sean la misma. Ojo.
	if($changeEmailAccount=='1' && $changeEmailEnvios=='1' && $accountEmail == $sendingEmail)
	{
		// Envio 1 email para cambio email cuenta + email envios
		// Generar token
		$token = generarTokenAN(40);
		// BD. Marcar email para verificar
		registarEmailVerificar($accountEmail, $token, 'cad', $_SESSION['c_logueado'], 'em2');
		$urlVerif2 = '&token='.$token.'&e='.$accountEmail.'&ea='.$changeEmailAccount.'&ee='.$changeEmailEnvios;
		
		$control[] = 'Ha modificado ambos emails y son el mismo. Mandamos 1 solo email';
		$control[] =  $urlVerif.$urlVerif2;
		
		//Enviar email 
		$control[] = enviarEmailCambioEmailCadena($_SESSION['c_logueado'], $accountEmail, $urlVerif.$urlVerif2);
		
		// Feedback cambio de email
		$ok = array(true, '2029');
	}else{
		// Envio 2 emails para cambio email cuenta y email envios
		
		if($changeEmailAccount=='1')
		{
			// Generar token
			$token = generarTokenAN(40);
			// BD. Marcar email para verificar
			registarEmailVerificar($accountEmail, $token, 'cad', $_SESSION['c_logueado'], 'emAcc');
			$control[] = 'Ha modificado el email de cuenta';
			$urlVerif2 = '&token='.$token.'&e='.$accountEmail.'&ea=1&ee=0';
			$control[] = $urlVerif.$urlVerif2;
			
			//Enviar email 
			$control[] = enviarEmailCambioEmailCadena($_SESSION['c_logueado'], $accountEmail, $urlVerif.$urlVerif2);
			// Feedback cambio de email
			$ok = array(true, '2029');
		}
		
		if($changeEmailEnvios=='1')
		{
			// Generar token
			$token = generarTokenAN(40);
			// BD. Marcar email para verificar
			registarEmailVerificar($sendingEmail, $token, 'cad', $_SESSION['c_logueado'], 'emEnv');
			$control[] = 'Ha modificado el email de envios';
			$urlVerif2 = '&token='.$token.'&e='.$sendingEmail.'&ea=0&ee=1';
			$control[] = $urlVerif.$urlVerif2;
			
			//Enviar email 
			$control[] = enviarEmailCambioEmailCadena($_SESSION['c_logueado'], $sendingEmail, $urlVerif.$urlVerif2);
			// Feedback cambio de email
			$ok = array(true, '2029');
		}
		
	}
	// Array de control para saber como ha ido la ejecución de este script
	/*echo '<pre>';
	print_r($control);
	echo '</pre>';*/
}

// Esta cambiando el pass
if(!empty($_POST['chain-pass']))
{
	//Datos post
	$oldpass = $_POST['old-pass'];//Password anterior
	$newpass = $_POST['new-pass'];//Password nuevo
	$renewpass = $_POST['re-new-pass'];//Repetición del password nuevo
	
	// Comparar old pass con el pass de la BD
	$passwordTrimed = rtrim($datosHotelCE['password']);//Limpiamos de blancos el pass de la BD
	if(!empty($oldpass) && sha1($oldpass) == $passwordTrimed )
	{
		//old pass OK
		//Comparar new-pass con re-new-pass
		if ( $newpass == $renewpass && preg_match("/^.*(?=.{6,18})(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/", $newpass))
		{
			//new-pass y re-new-pass OK
			//convertimos nuevo pass a sha1
			$pass = sha1($newpass);
			global $log;
			$log->info("password changed", ["session"=>$_SESSION,"old_pass"=> sha1($oldpass), "newPass"=>sha1($newpass)]);
			//Guardamos pass
			guardarPassCadena($_SESSION['c_logueado'], $pass);
			//Feedback
			$ok = array(true, '2007');
		}else{
			if ( $newpass != $renewpass ){
				//echo 'new-pass y re-new-pass KO :-(';
				$ok = array (false, '4060');
			}else{
				// between 6 and 18 chars, 1 uppercase and lowercase and 1 number
				$ok = array(false, '4031');
			}
		}
	}else{
		//echo 'old pass KO :-(';
		$ok = array (false, '4013');
	}
}

// Obtenemos los datos de la cadena necesarios para esta pantalla después de los cambios
$datosHotelCE = obtenerDatosCadenaCE($_SESSION['c_logueado']);
?>