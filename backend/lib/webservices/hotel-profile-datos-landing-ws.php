<?php
include 'librerias.php';// Librerias básicas

// Restringir ips que pueden acceder
include_once RUTA_DIR.LIB.'check_access.php';
checkIpAccess('hoprodala', $_SERVER['REMOTE_ADDR']);

include_once RUTA_DIR.MODEL.'idiomasModel.php';
include_once RUTA_DIR.LIB.'obtenerdatosHotel.php';

if ( isset($_POST['lang']) )//-- Cambio de lang
{
	$_SESSION['lang-landing'] = $lang = $_POST['lang'];
	$langSelected = obtenerLang($lang);
	//Actualizamos la imagen actual y el country
	$_SESSION['flagActual'] = $langSelected['img'];
	$_SESSION['countryActual'] = $langSelected['country'];
	
	$result['lang'] = '<img src="'.BASE_PATH . DIR_IMG . 'flags/' . $langSelected['img'] .'" alt="'.$langSelected['lang'].' flag" class="pl flag-icon"> <strong>'.$langSelected['country'].'</strong>'; 

	if(!empty($_SESSION[$_SESSION['lang-landing'].'-landing']['tagline']))//tagline
	{
		$result['tagline'] = $_SESSION[$_SESSION['lang-landing'].'-landing']['tagline'];
	}else{
		$result['tagline'] = '';
	}
	echo json_encode($result);
}

if ( isset($_POST['tagline']) )//-- tagline
{
	$_SESSION[$_SESSION['lang-landing'].'-landing']['tagline']=$_POST['tagline'];
}

//Miramos si un lang esta OK
if(!empty($_POST['checkTagline']))
{
	$lang = $_POST['langToCheck'];
	//Si vaciamos el text area siempre queda un <br>, debemos limpiarlo
	if(!empty($_SESSION[$lang.'-landing']['tagline']))
	{
		$result['code']=200;
	}else{
		$result['code']=404;
	}
	echo json_encode($result);
}
//Miramos si todos los langs estan OK
if(!empty($_POST['checkTaglines']))
{
	$langsHotel = obtenerIdStringLangsHotel($_SESSION['h_logueado']);
	$i=0;
	foreach($langsHotel as $lang)
	{
		if(empty($_SESSION[$lang.'-landing']['tagline']))
		{
			$result[$i]['lang']=$lang;
			$result[$i]['code']='404';
		}else{
			$result[$i]['lang']=$lang;
			$result[$i]['code']='200';
		}
		$i++;
	}
	echo json_encode($result);
}	
?>