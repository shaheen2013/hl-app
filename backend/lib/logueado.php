<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

include_once RUTA_DIR. LANG . $_SESSION['userLang'].'.php';

//Guardamos la url que intenta visitar el usuario antes de loguearse
if(empty($_SESSION['u_logueado']) && empty($_SESSION['h_logueado']) && empty($_SESSION['staff_logueado']) )
{
	$_SESSION['url_no_login'] = $_SERVER["REQUEST_URI"];
}

function userLanding ()
{
	global $urlTree;
	if (!isset ($_SESSION['u_logueado']))
	{
		header('Location: /' .$urlTree['login']);
		exit();
	}
}

/*function hotelLanding ()
{
	global $urlTree;
	if (!isset($_SESSION['h_logueado']) && isset($_SESSION['staff_logueado']))
	{
		header('Location: /'.$_SESSION['defaultPage'].'/?denied=1');
	}else if (!isset ($_SESSION['h_logueado']) && !isset($_SESSION['staff_logueado'])){
		header('Location: /'. $urlTree[DEFAULT_DIR1]);
	}
}*/

function hotelStaffLanding ()
{
	if (!isset($_SESSION['h_logueado']) && !isset($_SESSION['staff_logueado']))
	{
		header('Location: /');
		exit();
	}else if ( isset($_SESSION['staff_logueado']) ) {
		// Miramos si este staff puede acceder a este controlador
		global $url;
		$controladorActual = $url['dir1'];
		if( in_array($controladorActual, $_SESSION['staff_controllers']) ){
			// Tiene permisos, no realizamos ninguna acción
		}else{
			// Activamos la var para saber que esta intentando acceder a una pantalla a la que no tiene acceso
			// Si lo hacemos por $_GET el la siguiente pantalla se mostrará eñ error denied cada vez que la cargue
			$_SESSION['denied'] = '1';
			// No tiene permisos
			header('Location: /'.$_SESSION['defaultPage'].'/');
			exit();
		}
	}
}

// Feedback no tiene permisos
if( !empty($_SESSION['denied']) && $_SESSION['denied']=='1' )
{
	$ok = array(false, '4002');
	//Borramos la variable para no volver a mostrar el error
	unset ($_SESSION['denied']);
}
?>