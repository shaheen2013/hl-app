<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

include_once RUTA_DIR.LIB.'referrer.php';
include_once RUTA_DIR.LIB.'enviarEmail.php'; // Librerías para mandar correo
include_once RUTA_DIR.LIB.'crearNuevoUsuario.php'; // Librerías para crear nuevos usuarios
include_once RUTA_DIR.LIB.'generarToken.php'; // Librerías para generar tokens
include_once RUTA_DIR.LIB.'url-shortener.php';
include_once RUTA_DIR.LIB.'referral-share-actions.php';
include_once RUTA_DIR.LIB.'obtenerDatosCadena.php';
include_once RUTA_DIR.LIB.'idiomas.php';
include_once RUTA_DIR.LIB. 'make_unsuscribe_hash.php';
include_once RUTA_DIR.LIB. 'obtenerDatosUsuario.php';

//REFERRALMAIL FUNCITON

	function referralsMails($email, $userId, $hotelId, $nombre, $lang, $env, $sendEmail, $category){
		
		//hacer unsuscribe
		$notifUser = obtenerDatosUsuarioMail(null, $email);

		if($notifUser['notif_hotelinking'] == '0'){
				$response['code'] = 403;
				$response['message'] = 'User don´t want to receive notifications';
				echo json_encode($response);
				exit();
		}

		global $urlTree;

		//Verificamos que el idioma está soportado por la plataforma. Default 'en'
		$lang = mirarIdiomaPlataforma($lang);

		//Define response
		$response = array();

		//Si es una cadena esta información es obligatoria
		if($category == 'c' && $hotelId != false ){
			
			//Guid hotel
			$hotelGUID = $hotelId;
			
			//Si la categoría es 'c' el userId es de cadena
			$userId = obtenerIdCadenaGUID($userId);
			$hotelId = obtenerIdHotelGUID($hotelId);

			//Si la cadena no existe salir
			checkExists($userId);

			//Si el hotel no existe salir
			checkExists($hotelId);

			//Evalua si el hotel pertenece a la cadena
			$evalUserId = hotelIdCadena($hotelId);

			if($userId !== $evalUserId){
				//Si no, salir
				returnKO();
			}

			//Con el ID del hotel recogemos la información para mandar el email
			$hotel = getHotelData($hotelId);

			//Si no hay hotel, error.
			if(empty($hotel)){
				$response['code'] = 404;
				$response['message'] = 'Hotel not found';
				echo json_encode($response);
				exit();
			}

			//Si el hotel existe, pedimos la información adicional
			$hotelLogo = $hotel['logo'];
			$hotelBg = $hotel['fotoBg'];
			// $hotelBg = BASE_PATH . DIR_IMG_FICHA_HOTEL . $hotelId . '/fotoBg/big_' . $hotel['fotoBg'];
			$hotelUrl = $hotel['hotelUrl'];


		}else if($category == 'h' && $hotelId == false ){

			
			//Guid hotel
			$hotelGUID = $userId;
			
			//Si la categoría es 'h' el userId es de hotel
			$hotelId = obtenerIdHotelGUID($userId);

			//Con el ID del hotel recogemos la información para mandar el email
			$hotel = getHotelData($hotelId);

			//Si no hay hotel, error.
			if(empty($hotel)){
				$response['code'] = 404;
				$response['message'] = 'Hotel not found';
				echo json_encode($response);
				exit();
			}

			//Si el hotel existe, pedimos la información adicional
			$hotelLogo = $hotel['logo'];
			// $hotelBg = BASE_PATH . DIR_IMG_FICHA_HOTEL . $hotelId . '/fotoBg/big_' . $hotel['fotoBg'];
				$hotelBg = $hotel['fotoBg'];
			$hotelUrl = $hotel['hotelUrl'];

		}else{
			//If marked as chain and no hotelId error is thrown
			$response['code'] = 500;
			$response['message'] = 'Error Identifying hotel or chain';
			echo json_encode($response);
			exit();
		}

		//Revisamos si el usuario existe por el email
		//Si existe recogemos el ID del usuario, si no existe creamos un nuevo usuario, retorna el ID del usuario

		if($category == 'c' && $hotelId != false){
			$nuevoUsuario = crearNuevoUsuario($email, $hotelId, $nombre);
		}else{
			$nuevoUsuario = crearNuevoUsuario($email, $userId, $nombre);
		}

		//Si el usuario no existe error.
		if(empty($nuevoUsuario)){
			$response['code'] = 500;
			$response['message'] = 'Error creating user';
			echo json_encode($response);
			exit();
		}

		//Con el ID del usuario y el ID del hotel generamos un token

		//Genera un token con ek id del usuario, el id de share, y el id del hotel
		$urlToken = generarUrl($nuevoUsuario, 0, $hotelId, 1);

		//$response['url'] = $urlToken['url'];
		$response['token'] = $urlToken['token'];

		if(empty($urlToken)){
			$response['code'] = 500;
			$response['message'] = 'Error creating URL';
			echo json_encode($response);
			exit();
		}
// TODO delete if not used, commented as part of HLK-647
//		if($sendEmail == true){
//
//			//Genera la URL para el unsuscribe
//			$urlUnsuscribe = createUrlUnsuscribe($notifUser['email'], $notifUser['guid']);
//
//			//Pedimos un listado de los Goals del hotel
//			$referralsGoals = obtenerGoalsHotel($hotelId, $lang);
//
//			//Incluir el idioma de la plantill
//			include RUTA_DIR.LANG.$lang.'/email/checkout-email.php';
//
//			$urlShareHotel = obtenerUrlGUIDHotel($hotelId, 3);
//			//Oferta poststay
//			$ofertaPoststay = obtenerOfertaStay($hotelId, 'post');
//			//Oferta referral (de la landing)
//			$ofertaReferral = obtenerOfertaReferral($hotelId);
//
//			//Creamos un listado de los goals
//			if(!empty($referralsGoals)){
//
//				$goalsHtml = '';
//				foreach ($referralsGoals as $goal) {
//
//					$goalsHtml .='<tr> <td valign="top" style="padding: 40px 0 0 0;" class="mobile-small" style="width:20%"> <img src="'.BASE_PATH . DIR_IMG_OFERTAS . $goal['id_oferta'] . '/small_' . $goal['img'].'" alt="Reward" width="105" height="105" border="0" style="display: block; font-family: Arial; color: #666666; font-size: 14px; width: 105px; height: 105px; border-radius:105px"> </td> <td style="padding: 40px 0 0 0;" class="no-padding" style="width:80%"><table border="0" cellspacing="0" cellpadding="0" width="100%"> <tr> <td align="left" style="padding: 0 0 5px 25px; font-size: 13px; font-family: Helvetica, Arial, sans-serif; font-weight: normal; color: #aaaaaa;" class="padding-meta">'.$referralEmailLang['When you refer']. ' ' .$goal['n_referrals'] . ' ' . $referralEmailLang['friend'] .'</td> </tr> <tr> <td align="left" style="padding: 0 0 5px 25px; font-size: 22px; font-family: Helvetica, Arial, sans-serif; font-weight: normal; color: #333333;" class="padding-copy">'.$goal['nombre'].'</td> </tr> <tr> <td align="left" style="padding: 10px 0 15px 25px; font-size: 16px; line-height: 24px; font-family: Helvetica, Arial, sans-serif; color: #666666;" class="padding-copy">'.$goal['descripcion'].'</td> </tr> <tr> <td style="padding:0 0 45px 25px;" align="left" class="padding"> <table border="0" cellspacing="0" cellpadding="0" class="mobile-button-container"> <tr> <td align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="mobile-button-container"> <tr> <td align="center" style="padding: 0;" class="padding-copy"> <table border="0" cellspacing="0" cellpadding="0" class="responsive-table"> <tr> <td align="center"> <a href="'.$urlShareHotel.'" target="_blank" style="font-size: 15px; font-family: Helvetica, Arial, sans-serif; font-weight: normal; color: #ffffff; text-decoration: none; background-color: #5D9CEC; border-top: 10px solid #5D9CEC; border-bottom: 10px solid #5D9CEC; border-left: 20px solid #5D9CEC; border-right: 20px solid #5D9CEC; border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px; display: inline-block;" class="mobile-button">'.$referralEmailLang['Share your experience'].' &rarr;</a> </td> </tr> </table> </td> </tr> </table> </td> </tr> </table> </td> </tr> </table> </td> </tr>';
//				}
//
//			}else{
//				$goalsHtml = 'There´s no goals set at this moment';
//			}
//
//			//Incluir el email desde las plantillas + asunto
//			include RUTA_DIR.LIB.'plantillasMails/checkout-email.php';
//
//			//If production, send real email
//			$emailResponse = mandarEmailMandrillPlantillaSoloContenido($email,$nombre,$asunto,$template_content,'checkout-email', $hotel['email'], $hotel['hotelName']);
//			$response['code'] = $emailResponse[0]['status'];
//			$response['message'] = 'Mail Sent';
//			$response['emailResponse'] = $emailResponse;
//			echo json_encode($response);
//		}else{
//			$response['code'] = '200';
//			$response['message'] = 'Url generated';
//			$response ['url'] = $urlToken['url'];
//			echo json_encode($response);
//		}
	}

/////////////////////////////////////
//////FUNCTIONS//////////////////////
/////////////////////////////////////

	function checkExists($id){
		if($id === null){
		//Return KO
			returnKO();
		}
	};

	function returnKO(){
		$response = array(
			"error" => "Invalid data sent"
			);
		echo json_encode($response);
		exit;
	}
	?>