<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//Contenido solo visible si logueado
include LIB . 'logueado.php';
hotelStaffLanding ();// Si no esta logueado lo manda a la landing

// For front purposes
$currentPage = 'staff-management';
$currentSubPage = 'staff-invite';

include_once LIB . 'generarPass.php';
include_once LIB . 'enviarEmail.php';
include_once LIB . 'verificarEmail.php';

if ( !empty($_SESSION['c_logueado']) )
{
	// Si esta logueado como cadena debemos pedirle a que hotel añade un staff
	// Obtenemos datos para select de hoteles (id, nombe)
	include_once LIB . 'obtenerDatosCadena.php';
	$arrayHotelesCadena = obtenerHotelesCadenaBasico($_SESSION['c_logueado']);
}

if (!empty($_SESSION['h_logueado']) && !empty($_POST['staffEmail']) && !empty($_POST['staffName']) )
{
	$staffEmail = $_POST['staffEmail'];
	$staffName = $_POST['staffName'];
	$staffRole = $_POST['staffRole'];
	$staffLang = $_POST['staffLang'];
	$mfaRequired = $_POST['mfaRequired'];
	$staffHotel = array_get($_POST, 'staffHotel');
	
	$checkEmail = mirarSiEmailExisteStaff($staffEmail);
	
	if( $checkEmail['staff']=='0' && $checkEmail['hotel']=='0' && $checkEmail['cadena']=='0' )
	{
		// Email no existe en otras tablas (hotel, cadena o staf) (puede existir en staff_inactivo)
		//generar pass
		$pass = generaPass();
		$userCreated = invitarStaff($staffEmail, $staffName, $staffLang, $mfaRequired, $staffRole, $staffHotel, $pass);

		// Staff creado
		if ($userCreated) {
			$ok = array (true, '2015');
		} else {
			header('Location: /'.$urlTree['staff-management'].'/?error=4113');
            exit();
		}
	}else{
		// Email ya existe en otra tabla (hotel, cadena o staff)
		if( $checkEmail['staff']>0 || $checkEmail['staff_inact']>0 )
		{
			//Ya existe como staff o staff_inactivo
			$ok =  array (false, '4017');
		}else{
			//Ya existe como hotel o cadena
			$ok =  array (false, '4016');
		}
	}
}
// Obtenemos todos los roles
$roles = obtenerRoles();

/*echo '<pre>';
print_r($hoteles);
echo '</pre>';*/
?>