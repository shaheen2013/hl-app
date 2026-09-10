<?php
include_once 'librerias.php';// Librerias básicas

// Restringir ips que pueden acceder
include_once RUTA_DIR.LIB.'check_access.php';
checkIpAccess('reshpo', $_SERVER['REMOTE_ADDR']);

include_once RUTA_DIR.MODEL.'referral-share-postModel.php';
include_once RUTA_DIR.LIB.'obtenerdatosHotel.php';
include_once RUTA_DIR.LIB.'referrals-mailing.php';//Función referralsMails <----!!

$guidHotel = mysqli_real_escape_string(conectar(), $_POST['hotelId']);//GUID hotel

$id_hotel = obtenerIdHotelGUID($guidHotel);

if(!empty($_POST['email']) && !empty($_POST['name']) && idHotelCorrecto($id_hotel))
{
	//evitar varios emails en menos de 5s desde misma IP
	$ip = mysqli_real_escape_string(conectar(), $_SERVER['REMOTE_ADDR']);
	$segundos = 5;//Segundos que deben pasar hasta poder poner otro email desde misma IP
	if(!verificarIPCheckout($ip, $segundos))
	{
		$result['error']=4002;//Debe esperar unos segundos 
	}else{
		
		$email = mysqli_real_escape_string(conectar(), $_POST['email']);
		$nombre = mysqli_real_escape_string(conectar(), $_POST['name']);
		$lang = 'en'; //mysqli_real_escape_string(conectar(), $_POST['lang']);
		//$_POST['env']
		
		
		if (filter_var($email, FILTER_VALIDATE_EMAIL)) 
		{
			//Email correcto
			/*$json = */referralsMails($email, $guidHotel, '', $nombre, $lang, '', true, 'h', ENV);	
			
			/*$data = json_decode($json, true);
			
			$result['token']=$data['token'];
			$result['error']=200;*/
		}else{
			//Email incorrecto
			$result['error']=4027;
		}
		//echo json_encode($result, true);		
	}
}else{
	//Campos post vacios o id_hotel incorrecto
	$result['error']=4002;
	echo json_encode($result);
}