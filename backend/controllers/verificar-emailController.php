<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

$token = $_GET['token'];// Token de control
$email = $_GET['e'];// Email a verificar
$tipo = $_GET['tp'];// Tipo: h (hotel), u (usuario), c (cadena)

// La cadena puede cambiar ambos emails (de cuenta y de envio) y pueden ser el mismo. 
// Si son el mismo, solo se le manda un email para verificar ambos
// (opcional) ee (email envio) = 1, 0

// (opcional) ec (email cuenta) = 1, 0


// Miramos si el token es correcto
if(tokenOK($token, $email))
{
	if($tipo=='h')
	{
		// Hotel
		include_once LIB . 'obtenerdatosHotel.php';
		$id = obtenerIdHotelGUID($_GET['guid']);
		$result = modificarEmailVerificado($id, $email,  'hoteles', 'email');
	}else if($tipo=='c'){
		// Cadena
		include_once LIB . 'obtenerDatosCadena.php';
		//cambiar email de cuenta/envio de emails
		$id = obtenerIdCadenaGUID($_GET['guid']);
		if( !empty($_GET['ea']) && $_GET['ea']==1 )
		{
			// Verificamos email de cuenta (email account)
			$result = modificarEmailVerificado($id, $email, 'cadena', 'email');
		}
		if( !empty($_GET['ee']) && $_GET['ee']==1 )
		{
			// Verificamos email de envios
			$result = modificarEmailVerificado($id, $email, 'cadena', 'email_envio');
		}
	}else if($tipo=='stf'){
		//Staff
		// Verificamos email de staff (email account)
		$id = obtenerIdStaff($email);
		$result = verificarStaff($id);
	}
	require RUTA_DIR . LANG . $_SESSION['userLang'] . '/feedback.php';
	if( $result['code'] == '200' )
	{
		borrarEmailVerificar($token);
		$_SESSION['flashMessage'] = ['status' => 'success', 'message'=>array($msg2006)];
		// Redirigimos a login + msg cambio email correcto
		header ('location: /'); //msg error 2006
	}else{
		$_SESSION['flashMessage'] = ['status' => 'errors', 'message'=>array($msg4002)];
		// Redirigimos a login + error cambio de email
		header ('location: /'); //msg 4002		
	}
}else{
	$_SESSION['flashMessage'] = ['status' =>'errors', 'message'=> array($msg4002)];
	// Redirigimos a login
	header ('location: /'); //msg 4002
	//header ('location: /'. $urlTree['login'].'/?tkerror=1');
}
?>