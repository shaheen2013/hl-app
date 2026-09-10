<?php
	include_once 'librerias.php';// Librerías básicas
	
	//include_once RUTA_DIR.LIB.'check_access.php';
	//checkIpAccess('refma', gethostbyaddr($_SERVER['REMOTE_ADDR']));
	
	//Variable de control para saber si ha pasado por index.php
	define("INDEXCONTROLVAL", "1");
	
	include_once RUTA_DIR.LIB.'referrals-mailing.php';//<----!! Función referralsMails 

	//Tenemos
	//Mail de usuario
	//nombre del usuario
	//Lenguaje
	//Id de cadena u hotel
	//Id de hotel (Si fuera cadena)
	//Environment

	//POST received?
	if(!empty($_POST) && !array_has($_POST, 'relogin_hotel_id')){

		$response = array();

		//If required minimum posts are not set return bad response
		if(empty($_POST['email']) || empty($_POST['userId']) || empty($_POST['category'])){

			$response['code'] = 500;
			$response['message'] = 'Check your requests';
			echo json_encode($response);
			exit();

		}else{

			//Variables
			$email = $_POST['email'];
			$userId = $_POST['userId'];
			(!empty($_POST['hotelId']) ? $hotelId = $_POST['hotelId'] : $hotelId = false);
			(!empty($_POST['nombre']) ? $nombre = $_POST['nombre'] : $nombre = 'Guest');
			(!empty($_POST['lang']) ? $lang = $_POST['lang'] : $lang = 'en');
			(!empty($_POST['env']) ? $env = $_POST['env'] : $env = 'test');
			(!empty($_POST['sendEmail']) ? $sendEmail = $_POST['sendEmail'] : $sendEmail = true);
			$category = $_POST['category'];

			//If ok start referralMail
			$referralsMail = referralsMails($email, $userId, $hotelId, $nombre, $lang, $env, $sendEmail, $category);

		}

	}else{
		echo 'Not direct exec allowed';
		exit();
	}

	?>