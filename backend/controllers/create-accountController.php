<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

include_once LIB.'invitaciones.php';
include_once LIB.'crearNuevoUsuario.php';
include_once LIB.'loguearUsuario.php';
include_once LIB.'verificarEmail.php';
include_once LIB.'generarToken.php';
include_once LIB.'agregarPuntos.php';

if(!empty($_POST['passwordConfirmButton'])){
	$pass = mysqli_real_escape_string(conectar(), $_POST['userPassword1']);
	$pass2 = mysqli_real_escape_string(conectar(), $_POST['userPassword2']);
	if($pass == $pass2){
		if( (strlen($pass)<6) || (strlen($pass)>12) ){
			//Longitud de pass incorrecto
			$ok = array (false, '4031');
		}else{
			//$_SESSION['pass'] = $pass;
			if (isset ($_GET['token'])){
				$token = $_SESSION['token'] = (mysqli_real_escape_string(conectar(), $_GET['token']));
				if(usuarioExiste($token)){
					//Usuario ya existe en la base de datos con ese email
				}else if(comprobarInvitacionUsuario($token)){
					//Tiene invitación
					$datosUsuario = obtenerDatosUsuario($token);
					
					//Guardar BD verificar_email
					$codVerif = generarTokenAN(30);
					registarEmailVerificar($datosUsuario['email'], $codVerif, 'us');
					//echo $codVerif;
					
					//Guardar pass usuario, sin verificar
					guardarPassUsuario($datosUsuario['id'], $pass);
					//Enviar email para verificar centa de correo
					include_once LANG.$_SESSION['userLang'].'/email/create-account.php';
					include_once LIB.'plantillasMails/create-account.php';
					mandarEmailMandrillPlantilla($datosUsuario['email'], '', $asunto, $cuerpo, 'standard-template');
					//Redirigimos a la pantalla de login
					header('Location: /'. $urlTree['login'].'/?ckemail=1');
				}else{
					//No tiene invitación o token incorrecto
				}
			}else{
				// No tiene token
			}
		}	
	}else{
		//passwords no coinciden
		$ok = array (false, '4034');
	}
}

if( !empty($_GET['codVerif']) && !empty($_GET['tokenVerif']) ){
	//Mirar codigo de verificacion en BD
	$codVerif = mysqli_real_escape_string(conectar(), $_GET['codVerif']);
	$token = mysqli_real_escape_string(conectar(), $_GET['tokenVerif']);
	
	$datosUsuario = obtenerDatosUsuario($token);
	
	if(verifCodVerifUs($codVerif, $token)){//token correcto
		//Sumamos los puntos de las invitaciones pendientes
		sumarPuntosInvitacionesUsuario($datosUsuario['email']);
		//Agregamos total_noches y total_spent de las invitaciones al usuario
		agregarNochesYSpent($datosUsuario['id']);
		
		activarUsuario($datosUsuario['id'], $_SESSION['userNavLang']);

		//Borrar invitaciones usuario
		borrarInvitacionUsuario($datosUsuario['email']);
		//Borrar codigo de verificación
		borrarVerifAnteriores($datosUsuario['email'], 'us');	
		// Loguear usuario
		loguearUsuario($datosUsuario['id']);
		$pantalla = pantallaLogin();
		//header('Location: /'. $urlTree['tienda'] .'');
		header('Location: /'.$urlTree[$pantalla]);
	}else{
		//token incorrecto
		$ok = array (false, '4038');
	}
}

// Recogemos del la URL el token y lo comprobamos en la BD
if (isset ($_GET['token'])){
	$token = (mysqli_real_escape_string(conectar(), $_GET['token']));
	$email = obtenerEmailInvitacionUS($token);
	
	if(usuarioExiste($token)){
		//Usuario ya existe en la base de datos con ese email
		$ok = array (false, '4017');
	}else if(comprobarInvitacionUsuario($token)){
		//Tiene invitación
	}else{
		//No tiene invitación o token incorrecto
		$ok = array (false, '4037');
	}
}else{
	// No tiene token
	$ok = array (false, '4037');
}

if (isset ($_GET['verif'])){
	$verif = '1';
}else{
	$verif = '0';
}
?>