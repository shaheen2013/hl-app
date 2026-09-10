<?php include_once 'librerias.php';// Librerias básicas

//Variable de control para saber si ha pasado por index.php
define("INDEXCONTROLVAL", "1");

/*
*	Subcode errors:($result['code'])
*	
*	4011 : missing fbid
*	4012 : missing fbname
*	4013 : offer already redeemed by user
*	4014 : missing hlid
*	4015 : email empty
*	4016 : incorrect email format
*	4017 : email already in use
*	4018 : Auto referrer attempt
*	4019 : missing referrer_id
*
*	200 : Create cookie
*		Debemos crear una cookie en el navegador con los datos:
*			$result['subcodes']['hluid'] 	: GUID usuario
*			$result['subcodes']['hlh']		: GUID hotel 
*			$result['subcodes']['hlt']		: token referrer
*			$result['subcodes']['hlcid']	: id_cadena
*			$result['subcodes']['hluid']	: tracking cookie id (único)
*/

include_once RUTA_DIR . LIB . 'crearNuevoUsuario.php';
include_once RUTA_DIR . LIB . 'facebook.php';
include_once RUTA_DIR . LIB . 'referrer.php';
include_once RUTA_DIR . LIB . 'cookies.php';
include_once RUTA_DIR . LIB . 'enviarEmail.php';
include_once RUTA_DIR . LIB . 'make_unsuscribe_hash.php';
include_once RUTA_DIR . LIB . 'obtenerdatosHotel.php';
include_once RUTA_DIR . LIB . 'obtenerDatosUsuario.php';
include_once RUTA_DIR . MODEL . 'referral-share-actionsModel.php';

$validGenders = ['male', 'female', 'other'];

if(!empty($_POST) && !array_has($_POST, 'relogin_hotel_id'))
{
	// Mirar si tenemos los datos mínimos 
	// -> datos FB: id, nombre e email (email == undefined?)
	// -> id_hotel 
	// -> id referral
    $result = array();

	if(!empty($_POST['fbid']) && !empty($_POST['fbname']) && (!empty($_POST['fbemail']) && $_POST['fbemail']!=='undefined')  && !empty($_POST['hlid']) && !empty($_POST['referrer_id']) )
	{
		// mirar si el email es válido
		if (!filter_var($_POST['fbemail'], FILTER_VALIDATE_EMAIL)) 
		{
			// El email de FB no tiene el formato correcto 
			$subcodes[]=array('code' => '4016','message' => 'incorrect email format');

			$result['code']		='401';
			$result['message']	='Some incorrect data';
			$result['subcodes'] = $subcodes;// array

		}else{
			// Id del hotel que realiza el action
			$id_hotel = $_POST['hlid']; 
			// Id cadena		
			!empty($_POST['cid']) ? $id_cadena = $_POST['cid'] : $id_cadena = hotelIdCadena($id_hotel);
			// Guid del hotel
			!empty($_POST['guid']) ? $guid = $_POST['guid'] : $guid = obtenerGUIDHotel($id_hotel); 		
			
			empty($_POST['lang']) ? $lang = 'en' : $lang = $_POST['lang'];
			$langUserPlatform = mirarIdiomaPlataforma($lang); //Convertimos el lang del usuario a idioma acceptado en la plataforma	
			// Array datos hotel
			!empty($_POST['arrayDatosHotel']) ? $datosHotel = (array)(json_decode($_POST['arrayDatosHotel'])) : $datosHotel = getHotelData($id_hotel);
			// Array oferta referral
			!empty($_POST['ofertaReferral']) ? $ofertaReferral = (array)(json_decode($_POST['ofertaReferral'])) : $ofertaReferral = obtenerOfertaReferral($id_hotel, $langUserPlatform);
			// id del referrer
			$id_referrer = $_POST['referrer_id'];		
			!empty($_POST['token']) ? $token = $_POST['token'] : $token = obtenerTokenShareUsuario($id_referrer, $id_hotel);

			$cookie = (empty($_POST['cookie']) || array_get($_POST, 'cookie') === 'undefined')  ? '' : $_POST['cookie'];
			empty($_POST['userAgent']) ? $userAgent = '' : $userAgent = $_POST['userAgent'];

			//user facebook public profile data
			$fbid = $_POST['fbid']; 																//facebook user ID
			$fbname = $_POST['fbname'];																//facebook name
			$fbemail = $_POST['fbemail'];															//facebook email
			empty($_POST['fbfriends']) ? $fbfriends = 0 : $fbfriends = $_POST['fbfriends'];			//facebook friends
			empty($_POST['gender']) ? $gender = '' : $gender = $_POST['gender'];					//facebook gender
			empty($_POST['locale']) ? $fbLocale = NULL : $fbLocale = $_POST['locale'];				//facebook locale
			$fbUserImage = 'https://graph.facebook.com/'.$fbid.'/picture?width=200';				//img
			$fbLink = 'https://www.facebook.com/app_scoped_user_id/'.$fbid.'/';						//link	

			empty($_POST['gender']) || !in_array($_POST['gender'], $validGenders) ? $gender = '' : $gender = $_POST['gender'];					//gender

			empty($_POST['birthday']) ? $birthday = '' : $birthday = $_POST['birthday'];			//birthday
			empty($_POST['facebook_location_id']) ? $facebook_location_id = '' : $facebook_location_id = $_POST['facebook_location_id'];			//facebook_location_id
			empty($_POST['facebook_location_name']) ? $facebook_location_name = '' : $facebook_location_name = $_POST['facebook_location_name'];			//facebook_location_name


			// mirar si es autoreferido - por email + id_facebook
			if(!autoreferido($id_referrer, $fbid))
			{

				// Check if this email is valid email
				if (filter_var($fbemail, FILTER_VALIDATE_EMAIL)) {
					if (ENV == 'production'|| VERIFY_EMAILS) {
					 		include_once RUTA_DIR . LIB . 'emailValidate.php';
							// El environment es producción, verificamos email
							$resultEmail = validateEmail($fbemail);
							if($resultEmail['valid']){
									$validEmail=true;
							} else {
								$result['code']='401';
								$result['message']='Forbidden action';;
								$result['subcodes'] = array('code' =>'4015','message'=>'Email not valid');
								echo json_encode($result);
								exit;
							}
					} else {
							// El environment NO es producción. No verificamos email, directamente permitimos envio.
							$resultEmail = array('code' => '200');
							$validEmail=true;
					}
				}

				$sendex = array_get($resultEmail, 'sendex', 0.0);
				$emailResult = array_get($resultEmail, 'result', 'risky');


				// crear usuario
				$facebook_user = array(
				    'email' =>  $fbemail ,
						'name' => $fbname,
						'lang' => $lang,
						'gender' => $gender,
						'birthday' => $birthday,
						'locale'=> $fbLocale,
						'hotel_id'=> $id_hotel,
						'facebook_id' =>$fbid,
						'facebook_link'=> $fbLink,
						'facebook_picture' => $fbUserImage,
						'facebook_friends' => $fbfriends,
						'facebook_location_id'=> $facebook_location_id,
						'facebook_location_name'=> $facebook_location_name
				);

				if (!empty($id_hotel)){
					$facebook_user['hotel_id'] = $id_hotel;
				}

				$user = createNewUser($facebook_user, $sendex, $emailResult, 'facebook');

				if($user){
					$log->debug('landing-ws user is ', $user);
				}

				if ($user['id']){
						//Create or update Facebook user
						$facebook_user['id'] = $user['id'];
						$res = upsertFacebookUser($facebook_user);

					$log->info("facebook_user data :" , [$facebook_user]);
					$id_usuario = $user['id'];

					if(!usuarioReferralHotel($id_usuario, $id_hotel)){
						// mirar si ya tiene el promocode - devolver el promocode
						$resultPromo = yaTienePromoController ($id_usuario, $ofertaReferral, $id_hotel, $fbemail, $datosHotel, $id_referrer, $fbname, $guid, $cookie, $langUserPlatform);
						$promoCodeNuevo = $resultPromo['promoCode'];
						//updatea las estadísticas
						updateStatisticsInDb($id_hotel, FALSE);
						//vincular referrer con referral
						vincularReferrer($id_referrer, $id_usuario, $id_hotel);
					}
                                    
					if(!empty($promoCodeNuevo) ) {
							//No tiene promo. 
							$promoCode = $promoCodeNuevo;
					}else{
						// Ya tiene el promo, canjeado o no
						$rsPCL = obtenerPromoCodeLandingUsuario($id_usuario, $id_referrer, $id_hotel);
						if($rsPCL['code']=='200') {
							//NO está canjeado
							$promoCode = $rsPCL['promoCode'];
						}else{
							//Está canjeado
							$result['code']		='401';
							$result['message']	='Forbidden action';
							$result['subcodes']=array('code' =>'4013','message'=>'offer already redeemed by user');//Oferta ya canjeada

							echo json_encode($result);
							exit();
						}
					}

					// Buscamos cookie existente o creamos una nueva
					// $cookieExistente = getCookieReferrerHotel($id_referrer, $id_usuario, $id_hotel);
					$tracking_cookie_id = getCookieReferrerHotel($id_referrer, $id_usuario, $id_hotel);

					if(empty($tracking_cookie_id)){
						$log->info("reference URl :" , [$reference]);
						$reference['hlt'] 	= $token;
						$tracking_cookie_id = storeCookieReference($reference, $id_hotel, $id_referrer, $id_cadena, $userAgent);
					} 

					updateCookieReferral($id_usuario, $tracking_cookie_id);

					// if(empty($cookie) && empty($cookieExistente)) {
					// 	//No existe cookie, la creamos
					// 	$reference['hlt'] 	= $token;
					// 	$hotelId 			= $id_hotel;
					// 	$referrerId 		= $id_referrer; 
					// 	$cadenaId 			= $id_cadena;

					// 	$cookie = storeCookieReference($reference, $hotelId, $referrerId, $cadenaId, $userAgent);
						
					// }else if(!empty($cookieExistente) ){
					// 	//Ya existe una cookie
					// 	//Borrar cookie actual
					// 	deleteCookieById($cookie, $id_referrer, $id_hotel, $token);
					// 	$cookie 	= $cookieExistente;
					// }

					// // Update la cookie con el referral
					// if(!empty($cookie)) updateCookieReferral($id_usuario, $cookie);

						// Result
					$result['code']					='200';
					$result['message']				='OK';
					$result['promo']				=$promoCode;
					$result['referralId']			=$id_usuario;
					// Result cookie
					$result['subcodes']=array(
						'code' =>'200',
						'message'=>'Create cookie',
						'hlr' => obtenerGUIDUsuarioId($id_usuario), //guid usuario
						'hlh' => $guid,
						'hlt' => $token,
						'hlcid' => $id_cadena,
						// 'hluid' => $cookie
						'hluid' => $tracking_cookie_id
					);
				$log->info("result :" , [$result]);
				}else{
					$result['code']		='401';
					$result['message']	='Duplicated data';
					$result['subcodes'][]=array('code' =>'4017','message'=>'email already in use');//Email ya en uso
				}
			}else{
				// Se intenta auto referir
				$subcodes[]=array('code' => '4018','message' => 'Auto referrer attempt');

				$result['code']		='401';
				$result['message']	='Forbidden action';
				$result['subcodes'] = $subcodes;// array
			}	
		}
	}else{
		//Faltan datos minimos necesarios  
		$subcodes = [];
		if (empty($_POST['fbid']))
			$subcodes[]=array('code' =>'4011','message'=>'missing fbid');//Falta fbid

		if (empty($_POST['fbname']))
			$subcodes[]=array('code' =>'4012','message'=>'missing fbname');//Falta fbname

		if (empty($_POST['hlid']))
			$subcodes[]=array('code' =>'4014','message'=>'missing hlid');//Falta hlid

		if ( $_POST['fbemail']=='undefined' || empty($_POST['fbemail']) )
			$subcodes[]= array('code' =>'4015','message'=>'email empty');//email vacío o 'undefined'

		if (empty($_POST['referrer_id']))
			$subcodes[]=array('code' =>'4019','message'=>'missing referrer_id');//Falta referrer_id
		
		$result['code']			='401';
		$result['message']		='Some missing data';	
		$result['subcodes'] 	= $subcodes;// array
	}

	// return promocode + guid o errores
	echo json_encode($result);
}


// FX para obtener el promocode consegido pero con canjeado anteriormente y para saber si ya fu canjeado anteriormente
function obtenerPromoCodeLandingUsuario($id_usuario, $id_referrer, $id_hotel)
{
	$con 			= conectar();
	$id_usuario 	= mysqli_real_escape_string($con, $id_usuario);
	$id_referrer 	= mysqli_real_escape_string($con, $id_referrer);
	$id_hotel 		= mysqli_real_escape_string($con, $id_hotel);

	$sql = "SELECT token AS promoCode FROM oferta_referral_token
	WHERE id_usuario=$id_usuario AND id_referrer=$id_referrer AND id_hotel=$id_hotel AND id_tipo_share=0 ORDER BY id DESC LIMIT 1";
	$row = lectura($sql, $con, false);
	desconectar($con);

	if($row['promoCode'] != NULL)
	{
		// Tiene un promocode pendiente de canjear
		$result['code'] 		= '200';
		$result['promoCode'] 	= $row['promoCode'];
	}else{
		//No tiene promocode (de este hotel y referrer) pendiente de canjear
		$result['code'] 		= '400';
		$result['promoCode'] 	= '';
	}

	return $result;
}

// Fx para evitar que un usuario se autorefiera
// Parametros:
// 		- id_usuario: id del usuario referrer 
// 		- fbid: id de facebook del usuario a referir al referrer 
//		- 
// Devuelve:
// 		- TRUE: si intenta autoreferirse. Referral y referrer son el mismo.
// 		- FALSE: Referrer y el referral son distintos
function autoreferido($id_usuario, $fbid)
{
	$con = conectar();
	$id_usuario = mysqli_real_escape_string($con, $id_usuario);
	$fbid = mysqli_real_escape_string($con, $fbid);
	
	$sql = "SELECT id_usuario FROM user_facebook WHERE id_facebook='$fbid' ";
	$row = lectura($sql, $con);
	if( $row['id_usuario'] == $id_usuario)
	{
		return true;
	}else{
		return false;
	}
}

//FX para mirar si un usuario ya es referrer de un hotel
function usuarioReferralHotel($id_usuario, $id_hotel)
{
	$con = conectar();
	$id_usuario = mysqli_real_escape_string($con, $id_usuario);
	$id_hotel = mysqli_real_escape_string($con, $id_hotel);

	$sql = "SELECT COUNT(id) AS n 
	FROM referrer_users 
	WHERE invitado='".$id_usuario."' AND id_hotel='".$id_hotel."' ";
	$row = lectura($sql, $con);

	if($row['n']=='0'){
		return false;
	}else{
		return true;
	}
}

function yaTienePromo($id_usuario, $id_oferta, $id_hotel)
{
	$con 		= conectar();
	$id_usuario = mysqli_real_escape_string($con, $id_usuario);
	$id_oferta 	= mysqli_real_escape_string($con, $id_oferta);
	$id_hotel 	= mysqli_real_escape_string($con, $id_hotel);
	
	//Miramos si tiene el promo en usados (used_promocode) y sin usar (oferta_referral_token)
	$sql = "SELECT 
	(SELECT COUNT(id_oferta) FROM oferta_referral_token WHERE id_usuario='".$id_usuario."' AND id_oferta='".$id_oferta."' AND id_hotel='".$id_hotel."') AS NORT,
	(SELECT COUNT(id_oferta) FROM used_promocode WHERE id_usuario='".$id_usuario."' AND id_oferta='".$id_oferta."' AND id_hotel='".$id_hotel."') AS NUPC ";
	$row = lectura($sql, $con, false);
	desconectar($con);
	// NORT: no esta en la tabla 'oferta_referral_token'
	// NUPC: no esta en la tabla 'used_promo_code'
	if($row['NORT'] == '0' && $row['NUPC'] == '0' )
	{
		return false;
	}else{
		return true;
	}
}

//Mira si un usuario ya tiene una oferta promocional, en caso contrario, se lo asigna.
//Result: 3007, si ya tiene el promo
//		  guid + promoCode, si no lo tiene
function yaTienePromoController($id_referral, $ofertaReferral, $id_hotel, $email, $datosHotel, $id_referrer, $userName, $guid, $cookieId, $lang){

    global $urlTree;

    $retornoDatos = array();
    //logo
    $hotelLogo = 	$datosHotel['logo'];
    //Background
    $hotelBg = 			SECURE_BASE_PATH . DIR_IMG_OFERTAS . $ofertaReferral['id'] . '/big_' . $ofertaReferral['img'];
    if(yaTienePromo($id_referral, $ofertaReferral['id'], $id_hotel))
    {
        //Feedback
        $retornoDatos['code'] = '3007';
    }else{
        //enviar email
        //Obtenemos los datos del usuario
        $datosUsuario = obtenerDatosUsuarioMail('', $email);
        //Generemos el promocode
        $promoCode = asignarOfertaReferral($id_referral, $ofertaReferral['id'], $id_hotel, $id_referrer, 'null', '', $cookieId);
        //Url de la oferta de redeem
        $redeemOfferUrl = 	SECURE_BASE_PATH . $urlTree['redeem-offer'].'/?hlhid='.$guid.'&hlpc='.$promoCode . '&cid=' . $cookieId;

        $urlLogin = SECURE_BASE_PATH .'login';
        $urlHotel = '';
        //Generemos un array promoCode
        $retornoDatos = array('promoCode' => $promoCode);
        if($datosUsuario['notif_hotelinking'] === '1'){
            //Creamos enlace UNSUSCRIBE
            $hash = generate_hash($email, 'promoCodeHash');
            //URL generada
            $urlUnsuscribe = createUrlUnsuscribe($hash,$email);
            //includes
            $asunto = null;
            $template_content = null;
            include_once RUTA_DIR.LANG.$lang.'/email/promo-email.php';
            include_once RUTA_DIR.LIB.'plantillasMails/promo-email.php';
            //Se envia el email
            mandarEmailMandrillPlantillaSoloContenido($email, $userName,$asunto ,$template_content,'landing-email', $datosHotel['email'], $datosHotel['hotelName']);
        }
    }//Si el referral ya tiene una oferta de referral del mismo tipo
    return $retornoDatos;
}

//update statistics
function updateStatisticsInDb($id_hotel, $showReturn = TRUE)
{
	//Save Statistics
	$sql = "INSERT INTO hotel_statistics (id_hotel, landing_fb_success) VALUES ($id_hotel, 1) ";
	$sql .= "ON DUPLICATE KEY UPDATE landing_fb_success = landing_fb_success + 1";
	escritura($sql);
	$send = array('done' => 'landing_fb_success');
	
	if($showReturn){
		echo json_encode($send);
	}else{
		return;
	}
}
?>