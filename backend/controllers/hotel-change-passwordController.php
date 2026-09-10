<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//Contenido solo visible si logueado
include LIB.'logueado.php';
hotelStaffLanding ();// Si no esta logueado lo manda a la landing

// For front purposes
$currentSubPage = "hotel-change-password";

if (!empty($_POST['hotelConfirmButton'])){
	$pass1 = mysqli_real_escape_string(conectar(), $_POST['password']);
	$pass2 = mysqli_real_escape_string(conectar(), $_POST['passwordR']);
	$oldpass = mysqli_real_escape_string(conectar(), $_POST['passwordOld']);//Password anterior

	if (!empty($oldpass)){
		$hotelPassowrd = getPasswordHotel(array_get($_SESSION, 'h_logueado'));
		$passwordTrimed = rtrim($hotelPassowrd['password']);
		if(sha1($oldpass) == $passwordTrimed ){
			if ($pass1 == $pass2 && preg_match("/^.*(?=.{6,18})(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/", $pass1))
			{
				cambiarContrasenaHotel($pass1, $_SESSION['h_logueado']);
				$ok = array (true, '2007');
			}else{
				if ( $pass1 != $pass2 ){
					//echo Contraseñas no coinciden;
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
}
?>