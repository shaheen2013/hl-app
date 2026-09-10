<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

include_once RUTA_DIR . MODEL . 'referral-share-actionsModel.php';
include_once RUTA_DIR . LIB . 'obtenerdatosHotel.php';
include_once RUTA_DIR . LIB . 'obtenerDatosUsuario.php';
include_once RUTA_DIR . LIB . 'referrer.php';
include_once RUTA_DIR . LIB . 'enviarEmail.php';
include_once RUTA_DIR . LIB . 'webservices/msgFeedback.php';
include_once RUTA_DIR . LIB . 'fecha.php';
include_once RUTA_DIR . LIB . 'protocolManagement.php';
include_once RUTA_DIR . LIB . 'apiGateway.php';


//Generamos la url con el token para
//$share: 1 es un share, 0 es una encuesta
function generarUrl($id_usuario, $id_encuesta = 0, $idHotel = 0, $share = 0)
{
	$con 			= conectar(1);
	$id_usuario 	= mysqli_real_escape_string($con, $id_usuario);
	$id_encuesta 	= mysqli_real_escape_string($con, $id_encuesta);
	$idHotel 		= mysqli_real_escape_string($con, $idHotel);
	desconectar($con);

	if( idHotelCorrecto($idHotel) && $id_usuario != '' )
	{
		include_once RUTA_DIR.LIB.'url-shortener.php';

		if($share==1){
			//Si es un share debemos mirar si el usuario ya tiene token con este hotel
			$tokenShare = obtenerTokenShareUsuario($id_usuario, $idHotel);
			$share=1;
		}else{
			//Si no es un share, inicializamos las variables
			$tokenShare='';
			$share=0;
		}
		if($tokenShare!=''){
			$token = $tokenShare;
		}else{
			// Crear token
			do{
				$token = generarTokenAN(50);
				$id_token = guardarTokenUsuario($id_usuario, $token, $id_encuesta, $idHotel, $share);
			} while ($id_token == 0);//Si el token (Único) ya existe (id_token: 0) lo volvemos a generar
		}

		//Montar URL NUEVA
		/*$guidHotel = 	obtenerGUIDHotel($idHotel);
		$guidReferrer =	obtenerGUIDUsuarioId($id_usuario);
		$redirectUrl = 	SECURE_BASE_PATH.'redirect/?r='.$guidReferrer.'&h='.$guidHotel.'&t='.$token;

		$urlCorta = get_bitly_short_url($redirectUrl);
		*/
		$result['url'] = '';
		$result['token'] = $token;

	}else{
		//Id hotel incorrecto
		$result['url'] = '';
		$result['token'] = '';
	}
	return $result;
}

//FX para guarar el share del usuario
//$media (social media): fb, tw
//$id_share: identificador unico del share que nos devuelve el social media
//id_tipo_share: id de tipo de share (pre-stay,...), ver BD tabla tipos_share
function guardarShareUsuario($id_usuario,$id_hotel,$media,$id_share=0,$id_encuesta=0, $id_tipo_share='1')
{
	$con 			= conectar();
	$id_usuario 	= mysqli_real_escape_string($con, $id_usuario);
	$id_hotel 		= mysqli_real_escape_string($con, $id_hotel);
	$id_share 		= mysqli_real_escape_string($con, $id_share);


	/*$sql2 = "SELECT COUNT(id) AS n FROM user_shares WHERE media='".$media."' AND id_share='".$id_share."' ";
	$row2 = lectura($sql2);

	if($row2['n']==0)
	{*/
		// share nuevo, lo registramos
		$fecha = dateTimeHoy();
		$id_cadena = hotelIdCadena($id_hotel);
		$sql = "INSERT INTO user_shares 
		(id_usuario, id_hotel, id_cadena, media, fecha, id_encuesta, id_share, id_tipo_share) VALUES 
		('".$id_usuario."', '".$id_hotel."', '".$id_cadena."', '".$media."', '".$fecha."', '".$id_encuesta."', 
		'".$id_share."', '".$id_tipo_share."') ";
		escritura($sql, $con, false);
		desconectar($con);
	/*}else{
		// share duplicado
	}*/

	$socialMedia = ($media == 'fb') ? 
		'Facebook' : 
		(($media == 'tw') ? 
			'Twitter' :
			null);

	$payload = [
		"brand" => [
			"id" => (int)$_SESSION['brandID']
		],
		"user" => [
			"id" => (int)$id_usuario
		],
		"share" => [
			"type" => (int) $id_tipo_share,
			"socialMedia" => $socialMedia
		]
	];

	emitEvent('Users', 'social_media_share', $payload, [], '1.0.0');

}

//FX para verificar si un usuario puede adquirir una oferta de post-stay (id_tipo_share=4)
//No debemos dar la oferta si no ha canjeado la anterior
//Además si un usuario tiene una oferta de post-stay sin canjear y el hotel cambia la oferta de post-stay, tampoco puede adqurirla al hacer share en post-stay
function puedeAdquirirPostStay($id_usuario, $id_hotel)
{
	$con 			= conectar(1);
	$id_usuario 	= mysqli_real_escape_string($con, $id_usuario);
	$id_hotel 		= mysqli_real_escape_string($con, $id_hotel);

	$sql = "SELECT COUNT(id_oferta) AS n 
	FROM oferta_referral_token 
	WHERE id_usuario='".$id_usuario."' AND id_hotel='".$id_hotel."' AND id_tipo_share='4' LIMIT 1 ";
	$row = lectura($sql, $con, false);
	desconectar($con);
	if($row['n']=='0'){
		return true;
	}else{
		return false;
	}
}

//FX asignar (si procede) una oferta de stay (pre, post, ...) de un hotel a un usuario
//id_tipo_share: id de tipo de share (pre-stay,...), ver BD tabla tipos_share
//Devuelve:
//	$code: codigo de resultado. 200 ok, 401 no ha asignado el goal(error)
//	$promoCode: si $code=200 contiene el promoCode (HLXXXXX)
function asignarGoalStay($id_usuario, $id_hotel, $id_tipo_share, $transaction)
{
	//Inicializamos las variables del result
	$code='';
	$promoCode='';
	$url='';
	$msg='';
	//Obtener ofertas stay (pre, post, ...) del hotel
	$ofertasStay = obtenerOfertasStay($id_hotel);

	if($id_tipo_share == '2'){
		//pre-stay. Debemos dar solo una oferta por reserva
		if($ofertasStay['prestay']!='0'){


			//Debemos guardar en BD el nº de reserva para no dar varias ofertas en varios shares !!!!!

			//if(isset($_SESSION['reserva']) && $_SESSION['reserva']==true){
			//Asignamos oferta pre-stay
			$promoCode = asignarOfertaReferral($id_usuario, $ofertasStay['prestay'], $id_hotel, 0, '2', $transaction);
			//Enviar email con promo code post-stay
			emailGoalsCuponPreStay($promoCode, $id_usuario, $id_hotel);
			$code = '200';
			$msg = msgFeedbackWs('2033', $_SESSION['userLang']);
			/*}else{
				$code = '401';
			}*/
			//Una vez obtenido la oferta de pre-stay marcamos la var de session
			//$_SESSION['reserva']=false;
		}else{
			//El hotel NO tiene oferta pre-stay
			$code = '402';
		}
	}else if($id_tipo_share == '3'){
		//stay. Devolvemos la URL de acceso a internet
		$url = obtenerWifiStayHotel($id_hotel);
		if( !empty($url) ){
			// Url de free Wifi correcta
			$code = '200';
		}else{
			// Hotel no ha puesto free wifi en su panel.
			$code = '404';
			//$msg = msgFeedbackWs('4058', $_SESSION['userLang']);
		}
	}else if($id_tipo_share == '4'){
		//post-stay.
		if($ofertasStay['poststay']!='0'){
			//El hotel tiene oferta post-stay
			if(puedeAdquirirPostStay($id_usuario, $id_hotel)){
				//Usuario puede adquirir la oferta
				$promoCode = asignarOfertaReferral($id_usuario, $ofertasStay['poststay'], $id_hotel, 0, '4');
				//Enviar email con promo code post-stay
				emailGoalsCuponPostStay($promoCode, $id_usuario, $id_hotel);
				$code = '200';
				$msg = msgFeedbackWs('2033', $_SESSION['userLang']);
			}else{
				//Usuario no puede adquirir ofertas post-stay de este hotel
				$code = '401';
				$msg = msgFeedbackWs('4055', $_SESSION['userLang']);
			}
		}else{
			//El hotel NO tiene oferta post-stay
			$code = '404';
			$msg = msgFeedbackWs('2033', $_SESSION['userLang']);
		}
	}
	//Montamos el array de resultado
	$result = array(
		'promoCode' => $promoCode,
		'code' => $code,
		'msg' => $msg,
		'url' => $url,
		'shType' => $id_tipo_share
	);
	return $result;
}

//FX para mandar email con el cupon conseguido + listado de goals que puede conseguir por compartir
//$cupon: cupón conseguido (HLXXXXX)
//$id_usuario: id de usuario que ha conseguido el cupón
//$id_hotel: id hotel en el que ha conseguido el cupón
function emailGoalsCuponPostStay($cupon, $id_usuario, $id_hotel)
{
	//Datos del usuario para el email
	$arrayDatosEmail = obtenerDatosUsuarioMail($id_usuario);
	//Comprobamos si el usuario quiere recibir notificaciones de hotelinking
	if($arrayDatosEmail['notif_hotelinking'] == '0'){
		return false;
	}
	// Filtramos el lang para que sea un lang correcto.
	$lang = mirarIdiomaPlataforma($arrayDatosEmail['lang']);
	//Datos del hotel para el email
	$arrayDatosHotel = getHotelData($id_hotel);
	//Pedimos un listado de los Goals del hotel--------------
	$referralsGoals = obtenerGoalsHotel($id_hotel, $lang);
	$goal = obtenerDatosOfertaCupon($cupon, $id_hotel, $lang);

	global $urlTree;
	//Libreria para generar la URl del unsuscribe
	include_once RUTA_DIR . LIB . 'make_unsuscribe_hash.php';
	//Genera la URL para el unsuscribe
	$urlUnsuscribe = createUrlUnsuscribe($arrayDatosEmail['email'], $arrayDatosEmail['guid']);

	$urlLogin = BASE_PATH . $urlTree['login'];

	//Datos hotel
	$hotel = getHotelData($id_hotel);
	// $hotelLogo = BASE_PATH . DIR_IMG_FICHA_HOTEL . $id_hotel . '/logo/small_' . $hotel['logo'];
	$hotelLogo = $hotel['logo'];
	//$hotelBg = BASE_PATH . DIR_IMG_OFERTAS . $goal['id_oferta'] . '/big_' . $goal['img'];
	$urlHotel = $hotel['hotelUrl'];
	$nombre = $arrayDatosEmail['nombre'];
	$hotelBg = $goal['img'];
	$guid = obtenerGUIDHotel($id_hotel);
	$redeemOfferUrl = BASE_PATH . $urlTree['redeem-offer'].'/?hlhid='.$guid.'&hlpc='.$cupon;
    $treatment = getTreatment($id_hotel);

	//Lang del email
	include RUTA_DIR.LANG.$lang.'/email/goal-poststay-email.php';
	include RUTA_DIR.LIB.'plantillasMails/goal-poststay-email.php';
	mandarEmailMandrillPlantillaSoloContenido($arrayDatosEmail['email'],$arrayDatosEmail['nombre'],$goalEmailLang['asunto'],$template_content,'goal-email-poststay', $hotel['email'], $hotel['hotelName']);

}

function emailGoalsCuponPreStay($cupon, $id_usuario, $id_hotel)
{
	//Datos del usuario para el email
	$arrayDatosEmail = obtenerDatosUsuarioMail($id_usuario);
	if($arrayDatosEmail['notif_hotelinking'] == '0'){
		return false;
	}
	// Filtramos el lang para que sea un lang correcto.
	$lang = mirarIdiomaPlataforma($arrayDatosEmail['lang']);
	//Datos del hotel para el email
	$arrayDatosHotel = getHotelData($id_hotel);

	$goal = obtenerDatosOfertaCupon($cupon, $id_hotel, $lang);

	global $urlTree;
	//Libreria para generar la URl del unsuscribe
	include_once RUTA_DIR . LIB . 'make_unsuscribe_hash.php';
	//Genera la URL para el unsuscribe
	$urlUnsuscribe = createUrlUnsuscribe($arrayDatosEmail['email'], $arrayDatosEmail['guid']);

	$urlLogin = BASE_PATH . $urlTree['login'];

	//Datos hotel
	$hotel = getHotelData($id_hotel);
	$hotelLogo = $hotel['logo'];
	$hotelBg = $goal['img'];
	$urlHotel = $hotel['hotelUrl'];
	$nombre = $arrayDatosEmail['nombre'];
	$guid = obtenerGUIDHotel($id_hotel);
	//$redeemOfferUrl = BASE_PATH . $urlTree['redeem-offer'].'/?hlhid='.$guid.'&hlpc='.$goal['promo_code'];
	$treatment = getTreatment($id_hotel);

	//Lang del email
	include RUTA_DIR.LANG.$lang.'/email/goal-prestay-email.php';
	include RUTA_DIR.LIB.'plantillasMails/goal-prestay-email.php';

	mandarEmailMandrillPlantillaSoloContenido($arrayDatosEmail['email'],$arrayDatosEmail['nombre'],$goalEmailLang['asunto'],$template_content,'goal-email-prestay', $hotel['email'], $hotel['hotelName']);
}

//FX que unifica:
//	- guardado del share
//  - asignar goal stay
// 	- envio de email de goal stay con el promo code
function shareStayGoalActions($id_usuario, $hotelId, $sm, $shId, $idTSh, $transaction)
{
	guardarShareUsuario($id_usuario, $hotelId, $sm, $shId, 0, $idTSh);
	$resultGoal = asignarGoalStay($id_usuario, $hotelId, $idTSh, $transaction);

	return $resultGoal;
}

//FX para devolver los datos de ua oferta a partir de su cupón (HLXXXX)
function obtenerDatosOfertaCupon($cupon, $id_hotel, $lang)
{
    //Get from cache
    $cacheName = 'obtenerDatosOfertaCupon_' . $cupon . '_' . $lang;
    $cache = getFromCache($cacheName);

    if(!$cache)
    {
    	$con = conectar();
	    $cupon = mysqli_real_escape_string($con, $cupon);
	    $id_hotel = mysqli_real_escape_string($con, $id_hotel);
	    $lang = mysqli_real_escape_string($con, $lang);
        $sql = "SELECT user_cupones.id_oferta , 
        case when oferta_lang.nombre is null 
        then   oferta_en.nombre 
        else oferta_lang.nombre end AS nombre_oferta, img, 
        voucher AS promo_code, hotel_oferta.booking_engine_code 
        FROM user_cupones 
        INNER JOIN hotel_oferta ON hotel_oferta.id=user_cupones.id_oferta
        LEFT JOIN hotel_oferta_lang as oferta_en   on hotel_oferta.id = oferta_en.id_oferta   and oferta_en.lang='en' 
        LEFT JOIN hotel_oferta_lang as oferta_lang on hotel_oferta.id = oferta_lang.id_oferta and oferta_lang.lang='". $lang . "'
        WHERE voucher='" . $cupon . "' LIMIT 1";
        $row = lectura($sql, $con, false);
        desconectar($con);
        if($row){
            $tags = array('hotel', 'oferta', 'oferta_'.$row['id_oferta']);
            setToCache($cacheName, $row, 31536000, $tags);
        }
    }else{
        $row = $cache->get();
    }

    return $row;
}

function getTreatment($hotelId)
{
    $brandId = $_SESSION['hotel']['brand_id'] ?? null;
    $parentId = $_SESSION['hotel']['parent_id'] ?? null;

    if (!$brandId || !$parentId) {
        $brand = getHotelBrand($hotelId);
        $brandId = $brand['id'] ?? null;
        $parentId = $brand['parent_id'] ?? null;

        $_SESSION['hotel']['brand_id'] = $brandId;
        $_SESSION['hotel']['parent_id'] = $parentId;
    }

    $treatment = getBrandProtocols($brandId)['emails']['treatment'] ?? 'formal';

    return $treatment;
}
