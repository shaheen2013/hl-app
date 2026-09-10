<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//Contenido solo visible si logueado
include LIB . 'logueado.php';
hotelStaffLanding ();// Si no esta logueado lo manda a la landing

$datosStaff = obtenerdatosStaff($_SESSION['staff_logueado']);

if( !empty ($_POST['hotelConfirmButton']) )
{
	$oldPass = $_POST['oldPass'];
	$pass = $_POST['password'];
	$pass2 = $_POST['repassword'];
	
	//$nombre = $_POST['staffname'];
	
	// if( !empty($nombre) && $nombre != $datosStaff['nombre'] )
	// {
	// 	// Ha cambiado el nombre. Guardamos
	// 	guardarNombreStaff($nombre, $_SESSION['staff_logueado']);
	// 	$ok =  array (true, '2007');
	// }
	
	if( !empty($oldPass) )
	{
		if( sha1(rtrim($oldPass)) == $datosStaff['password'] )
		{
			if ($pass == $pass2 && preg_match("/^.*(?=.{6,18})(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/", $pass))
			{
					// Guardamos nuevo pass (en sha1)
					guardarPassStaff(sha1($pass), $_SESSION['staff_logueado']);
					$ok =  array (true, '2007');
			}else{
				if ( $pass != $pass2 ){
					//Contraseñas no coinciden;
					$ok = array (false, '4060');
				}else{
					// between 6 and 18 chars, 1 uppercase and lowercase and 1 number
					$ok = array(false, '4031');
				}
			}
		}else{
			// El pass antiguo introducido no es correcto
			$ok =  array (false, '4013');		
		}
	}
}

// Volvemos a mirar los datos del staff por si han cambiado durante el POST anterior, para mostrar por pantalla
$datosStaff = obtenerdatosStaff($_SESSION['staff_logueado']);

/*echo '<pre>';
print_r($datosStaff);
echo '</pre>';*/
?>