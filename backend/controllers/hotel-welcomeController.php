<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

$token = mysqli_real_escape_string(conectar() , $_GET['token']);
$datosHotel = obtenerDatosHotelToken($token);
/*echo '<pre>';
print_r($datosHotel);
echo '</pre>';*/
//si ya esta insertado lo mandamos al login o a la home
if (comprobarHoteleroInsertado($token))
{
	if(isset($_SESSION['h_logueado']))
	{
		header ('location: /'. $urlTree['hotel-profile'] .'');
	}else{
		header ('location: /'. $urlTree['hotel-login'].'');
	}
}

//Si no tiene token en $_GET o el token es incorrecto lo enviamos al login
if (!isset($token) || !tokenCorrecto($token))
{
	header('Location: /'.$urlTree['hotel-login'].'');
}
include_once LIB . 'loguearHotel.php';
include_once LIB . 'crearHotel.php';
include_once LIB . 'invitaciones.php';

if (!empty ($_POST['hotelPassword']) && !empty ($_POST['RhotelPassword']) && !empty($_GET['token']) && tokenCorrecto($token))
{
	$hotelPasswordReg = mysqli_real_escape_string(conectar(), $_POST['hotelPassword']);
	$RhotelPasswordReg = mysqli_real_escape_string(conectar(), $_POST['RhotelPassword']);
	if ($hotelPasswordReg == $RhotelPasswordReg && preg_match("/^.*(?=.{6,18})(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/", $hotelPasswordReg))
	{
		//Cuota del hotel de la invitación
		$quota = $datosHotel['quota'];
		$email = $datosHotel['email'];
		$hotelName = mysqli_real_escape_string(conectar(), $datosHotel['hotelName']);
		// Insert campos si OK @BD. Nos devuelve el id insertado
		$id_hotel= insertarHotel($hotelName, '', '', '','', '', '', '','', '', SHA1($hotelPasswordReg), $email, $quota);
	
		// Borrar invitacion
		borrarInvitacionHotel($email);
	
		// Logueamos hotel
		$result = loguearHotel($id_hotel);
		
		// Redireccionar a la home del hotelero
		header('Location: /'. $result['defaultPage'].'');
	}else{
		if ( $hotelPasswordReg != $RhotelPasswordReg ){
			//echo 'new-pass y re-new-pass KO :-(';
			$ok = array (false, '4060');
		}else{
			// between 6 and 18 chars, 1 uppercase and lowercase and 1 number
			$ok = array(false, '4031');
		}
	}
}
?>