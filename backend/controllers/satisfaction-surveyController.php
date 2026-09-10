<?php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}
include_once LIB . 'get_hotel_wifi_permissions_and_offers.php';
//$_GET[tk] user_guid (sin '-') +  hotel_guid (sin '-') + - + id_satisfaction_HL

if (!empty($_GET['tk']) )
{
	$token 			= $_GET['tk'];
    $tokenParts = explode('-', $token);

	$id_satisfaction = $tokenParts[1];
    $tokenResult  = comprobarTokenSatisfaction($id_satisfaction);

	if( str_replace('-','',$tokenResult['guid_usuario']).str_replace('-','',$tokenResult['guid_hotel']) == $tokenParts[0] )
	{
		//Obtener datos usuario
        $id_usuario_hl = $tokenResult['id_usario'];
        $user = getUserNameAndLang($id_usuario_hl);
		//Obtener nombre del usuario
        $user_name = $user['nombre'];
		//Obtener datos hotel
		$id_hotel_hl =  $hotel = $tokenResult['id_hotel'];
        //Obtener el idioma del usuario
        $_SESSION['userLang'] = $user['lang'];
		$datosHotel = obtenerDatosHotelSatisfaction($id_hotel_hl);// Datos hotel necesarios para esta pantalla. Puntuación míninma...

		$log->debug('Satisfaction hotel data is : ' . json_encode($datosHotel));

		//mirar si ya se ha rellenado esta enquesta de satisfacción anteriormente
		if($tokenResult['done']==0)
		{
			// El token es correcto
			if(!empty($_POST) && !array_has($_POST, 'relogin_hotel_id'))
			{
				$puntuacion = $_POST['rating'];
				empty($_POST['comments'])? $comment='': $comment = $_POST['comments'];

				//Si pasa el mínimo de puntuación, generar review + dias envio, alternativamente si el cliente activa la opcion de ignorar la nota de corte enviaremos siempre la review
				if($puntuacion >= $datosHotel['puntMin'] && !$datosHotel['ignoreRating'])
				{
					//creamos review
					include_once RUTA_DIR . LIB . 'obtenerdatosHotel.php';
//					$id_hotel_emails 	= obtenerIdHotelBDEmails($id_hotel_hl);
					include_once RUTA_DIR . LIB . 'obtenerDatosUsuario.php';
					// For review lib to work we need to pass user as array
					$user = [];
                    $user['id'] 	= $id_usuario_hl;
                    $log->debug('Starting Review email process');
                    include_once RUTA_DIR . LIB . 'emails-webservice-helpers.php';
                    include_once RUTA_DIR . LIB . 'create_review_survey.php';
                    $review_created = createReviewSurvey($user, $id_hotel_hl);
					$review_send=1;
				}else if($datosHotel['ignoreRating']){
                    //review was made before
					$review_send=1;
				}else{
                    //NO creamos review
                    $review_send=0;
                }
				//Guardatr satisfaction en HL
				guardarSatisfaction($id_hotel_hl, $id_usuario_hl, $puntuacion, $comment, $id_satisfaction, $review_send);

				//Generate thanks mail
				//Obtener los permisos del hotel
				include_once LIB . 'hotelinking_emails.php';
                include_once RUTA_DIR.LIB.'obtenerdatosHotel.php';
	       		$wifiOfferPermissions = getHotelWifiPermissions($hotel);
	       		$productosHotel= obtenerProductosHotel($hotel);
	       		$hotel_complete = getHotelData($id_hotel_hl, 0);
                
                //If hotel has wifi offer, give to the user
                if ($datosHotel['puntMin'] > $puntuacion) {
                    $warningData = getDataForWarning($id_satisfaction);
                    $log->debug("warningData", $warningData);
                    if ($warningData) {
                        // Get all emails on warning data
                        $emails = explode(',', $warningData['hotel_email']);
                        //find when satisfaction email was send aprox
                        $satisfactionSentAt = date('Y-m-d', strtotime($warningData['satisfaction_created'] . ' + ' . $warningData['send_days'] . ' days'));
                        //Add to array score and send date
                        $warningData['satisfaction_send'] = $satisfactionSentAt;
                        $warningData['hotel_id'] = $hotel;
                        $warningData['score'] = $puntuacion;
                        //Check if minimum data is present
                        if (!empty($warningData['min_score']) && !empty($warningData['hotel_name']) && !empty($warningData['hotel_email']) && !empty($warningData['user_name']) && !empty($warningData['user_email'])) {
                            //Send this data to email platform if cookie is not present

                            $log->debug('sending satisfaction warning data to email platform : ' . json_encode($warningData));
                            sendSatisfactionWarning($warningData, $emails);
                        }
                    }
                }
                if ($wifiOfferPermissions) {
                    $satisfiedCustomer = $datosHotel['puntMin']< $puntuacion;

                    //Get websitesReserva from brand
                    $langWebsites = getAllHotelWebsiteReservaLang();

                    //Get first websitesReserva lang available 
                    $hotelUrlLang = array_first($langWebsites,function($key) {
                        return str_contains($key,$_SESSION['userLang']);
                    }, $langWebsites['en']);
                    
                    $hotelUrlLang = $hotelUrlLang ? $hotelUrlLang : $hotel_complete['hotelUrl'];
                    $arrayParametros = array(
                        'action' => 'satisfaction_thanks',
                        'productosHotel' => json_encode($productosHotel),
                        'id_hotel' => $id_hotel_hl,
                        'id_user' => $id_usuario_hl,
                        'email'=>$hotel_complete['email_hotel'],
                        'lang' => $_SESSION['userLang'],
                        'satisfied_customer' => $satisfiedCustomer,
                        'hotel_url'=> $hotelUrlLang,
                        'satisfaction_thanks'=> $datosHotel['sendThanksMail'],
                    );
                    $log->debug('sending data to emails-webservice with action satisfaction_thanks', $arrayParametros);
                    $urlWebservice = SECURE_BASE_PATH . LIB . 'webservices/emails-webservice.php/';

                    $curl = new Curl\Curl();
                    $curl->post($urlWebservice, $arrayParametros);

                    if ($curl->error) {
                        $log->error('Error sending data to emails-webservice', array('error' => $curl->error));
                    }
					//Satisfaction guardado, redirigir a thanks
					header('Location: /' . $urlTree['satisfaction-survey-thanks'].'/?id=' . $id_hotel_hl . '&sid=' . $id_satisfaction . '&p=' . $puntuacion. '&uid='. $id_usuario_hl );
				}
			}
		}else{
			//Satisfaction ya realizado, redirigir a thanks			
			header('Location: /' . $urlTree['satisfaction-survey-thanks'].'/?id='.$id_hotel_hl. '&uid='. $id_usuario_hl);
	
		}	
	}else{
		// Token incorrecto. Exit
	    header('Location: /' . $urlTree['404']);
	   
	}
}else{
	// No token. Exit
	header('Location: /' . $urlTree['404']);

}
?>