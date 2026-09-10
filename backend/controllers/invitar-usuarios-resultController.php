<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//Contenido solo visible si logueado
include LIB.'logueado.php';
hotelStaffLanding ();// Si no esta logueado lo manda a la landing

include_once LIB . 'obtenerdatosHotel.php';
include_once LIB . 'borrarSession.php';
include_once LIB . 'seguridadHotel.php';

if ( !empty($_POST['idList']))
{
	
	$id_lista = $_POST['idList'];
	$type = $_POST['type']; // LY, RF
	// Miramos si el type que nos manda está en los permisos del hotel
	if(!empty($type) && $type!='none' && !empty($_SESSION['permisos'][$type]) && $_SESSION['permisos'][$type]=='1')
	{
		// type OK. Tiene los permisos correctos. Procedemos a mandar
		$n = $_SESSION['n']; //Nº de invitaciones a mandadar
		
		//Creamos una cadena para seguridad del webservice
		$wsSec = seguridadWSHotel($_SESSION['h_logueado']);
	
		$ch = curl_init();
			
		$urlCurl = BASE_PATH.'lib/webservices/invitar-usuarios-result-ws.php/?idList='.$id_lista.'&idHotel='.$_SESSION['h_logueado'].'&type='.$type.'&sc='.$wsSec;
		//$urlCurl = BASE_PATH.'lib/webservices/invitar-usuarios-2-ws.php/';
		//echo '<!--'.$urlCurl.'-->';
	
		curl_setopt($ch, CURLOPT_URL, $urlCurl);
		//curl_setopt($ch, CURLOPT_POST, 1);
		//curl_setopt($ch, CURLOPT_POSTFIELDS,"postvar1=value1&postvar2=value2&postvar3=value3");	
		curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 3);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_exec($ch);
		$info = curl_getinfo($ch);
		curl_close($ch);
		
		/*echo '<pre>';
		print_r($info);
		echo '</pre>';*/
	}else{
		// type no válido. No tiene los permisos correctos. No mandamos nada
		$ok = array(false, '4002');
	}
}
borrarSessionInvitarUsuarios();
?>