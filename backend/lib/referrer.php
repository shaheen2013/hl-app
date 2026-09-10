<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

include_once RUTA_DIR.LIB.'enviarEmail.php';
include_once RUTA_DIR.LIB.'generarToken.php';
//include_once RUTA_DIR.LIB.'obtenerDatosUsuario.php';
include_once RUTA_DIR.LIB.'obtenerdatosHotel.php';
include_once RUTA_DIR.LIB.'sanitize.php';
include_once RUTA_DIR.LIB.'referral-share-actions.php';
include_once RUTA_DIR.LIB.'idiomas.php';
include_once RUTA_DIR.LIB.'make_unsuscribe_hash.php';

function vincularReferrer($invitador, $invitado, $id_hotel)
{
	$con = conectar();
    $invitador = mysqli_real_escape_string($con, $invitador);
    $invitado = mysqli_real_escape_string($con, $invitado);
    $id_hotel = mysqli_real_escape_string($con, $id_hotel);

	$result['code'] = 400;
	if($invitador!=$invitado){
		$sql2 = "SELECT COUNT(id) AS n
		FROM referrer_users
		WHERE invitado='".$invitado."' AND id_hotel='".$id_hotel."' ";
		$row2 = lectura($sql2, $con, false);
		if($row2['n']=='0'){//Si no esta vinculado lo vinculamos*/
			// referrer -> invitador, referral -> invitado
			$sql = "INSERT INTO referrer_users (invitador, invitado, id_hotel) 
			VALUES ('".$invitador."', '".$invitado."', '".$id_hotel."')";
			escritura($sql, $con, false);
			$result['code'] = 200;
		}else{
			//No insertamos, ya esta referido a ese hotel
		}
	}
	desconectar($con);
	return $result;
}

function adquirirOfertaReferral($id_usuario, $id_oferta, $token/*, $con*/)
{
	$fecha = dateTimeHoy();
	//Adquirimos la oferta
		$sql = "INSERT INTO user_cupones
	(voucher, id_usuario, id_oferta, fecha, fecha_canj, fecha_last_modified) VALUES
	('".$token."', '".$id_usuario."', '".$id_oferta."', '".$fecha."', '0000-00-00 00:00:00', '".$fecha."')";
	$con = conectar();
	$id_cupon = escritura($sql, $con, false);
	// Marcar oferta como adquirida
	$sql3="UPDATE hotel_oferta SET adquiridas=adquiridas+1 WHERE id='".$id_oferta."' ";
	escritura($sql3, $con, true);
	return $id_cupon;
}

function guardarTokenOfertaReferral($id_usuario, $id_oferta, $id_referrer, $token, $id_hotel, $id_cupon, $id_tipo_share, $transaction, $cookie_id, $id_origen_oferta=NULL)
{
	//Fecha
	$fecha = dateHoy();
	$sql = "INSERT INTO oferta_referral_token 
	(id_oferta, id_usuario, id_referrer, id_hotel, token, fecha, id_tipo_share, id_cupon, transaction, cookie_id, id_origen_oferta) 
	VALUES 
	(
      '".$id_oferta."',
	  '".$id_usuario."', 
	  '".$id_referrer."', 
	  '".$id_hotel."', 
	  '".$token."', 
	  '".$fecha."', 
	  ".$id_tipo_share.",
	  '".$id_cupon."',
	  '".$transaction."',
	  '".$cookie_id."',
	";
	$sql .=( (empty($id_origen_oferta))? " NULL)" : "'".$id_origen_oferta."')" );
	escritura($sql);
}

//FX para asignar una oferta de  referral (cupon + token HL) a un usuario
//$id_tipo_share: 1, 2, 3...()
function asignarOfertaReferral($id_usuario, $id_oferta, $id_hotel, $id_referrer=0, $id_tipo_share='null', $transaction = '-', $cookieId='', $id_origen_oferta=NULL)
{
	$token = '';
	if(!empty($id_oferta))
	{
		$con 				= conectar();
	    $id_usuario 		= mysqli_real_escape_string($con, $id_usuario);
	    $id_oferta 			= mysqli_real_escape_string($con, $id_oferta);
	    $id_hotel 			= mysqli_real_escape_string($con, $id_hotel);
	    $id_referrer 		= mysqli_real_escape_string($con, $id_referrer);
	    $transaction 		= mysqli_real_escape_string($con, $transaction);
	    $id_origen_oferta 	= mysqli_real_escape_string($con, $id_origen_oferta);
		desconectar($con);

		//General promo code (token)
		do{
		    // Si el token ya existe lo volvemos a generar
            //Todos los promocodes empiezan por 'HL'
			$token = 'HL'.generarTokenANMayus(6);
		} while (tokenPromoNoRepetido($token));
		//Agregar oferta a usuario
		$id_cupon = adquirirOfertaReferral($id_usuario, $id_oferta, $token);
		//Guardar en oferta_referral_token
		guardarTokenOfertaReferral(
		    $id_usuario,
            $id_oferta,
            $id_referrer,
            $token,
            $id_hotel,
            $id_cupon,
            $id_tipo_share,
            $transaction,
            $cookieId,
            $id_origen_oferta);
	}
	return $token;
}

function asignarGoalReferral($promo_code, $id_hotel)
{
	$con = conectar();
    $promo_code = mysqli_real_escape_string($con, $promo_code);
    $id_hotel = mysqli_real_escape_string($con, $id_hotel);

	// inicializamos array $datosAsigOferRef
	$datosAsigOferRef['id_usuario'] 		= '';
	$datosAsigOferRef['id_oferta'] 			= '';
	$datosAsigOferRef['promo_code'] 		= '';
	$datosAsigOferRef['websiteReserva']		= '';
	$datosAsigOferRef['hotelName']			= '';
	$datosAsigOferRef['nombre_oferta']		= '';
	$datosAsigOferRef['n_referrals_oferta']	= '';
	$datosAsigOferRef['img']				= '';
	$datosAsigOferRef['bookingEngineCode']	= '';

	//obtenemos el id_referrer
	$sql = "SELECT used_promocode.id_referrer, users.email, users.nombre
	FROM used_promocode
	LEFT JOIN users ON users.id=used_promocode.id_referrer
	WHERE promo_code='".$promo_code."' LIMIT 1";
	$row = lectura($sql, $con, false);

	if($row['id_referrer']!='0'){
		//Obtenemos la cantidad de promocodes (1 por persona ) utilizados
		$sql2 = "SELECT COUNT(DISTINCT used_promocode.id_usuario) AS n
		FROM used_promocode
    	INNER JOIN referrer_users ON referrer_users.invitado=used_promocode.id_usuario
		WHERE referrer_users.invitador='".$row['id_referrer']."' AND referrer_users.id_hotel='".$id_hotel."' ";
		$row2 = lectura($sql2, $con, false);

		//Mirar los goals del hotel
		$sql3 = "SELECT id_oferta FROM referral_goal 
		WHERE id_hotel='".$id_hotel."' AND n_referrals='".$row2['n']."' ";
		$row3 = lectura($sql3, $con, false);
		//si cumple un goal, asignarle la oferta
		if($row3['id_oferta'] != ''){
			$datosOferta 	= obtenerDatosBasicosOferta($row3['id_oferta']);
			$token 			= asignarOfertaReferral($row['id_referrer'], $row3['id_oferta'], $id_hotel);
			//datos para el email
			$datosAsigOferRef['id_usuario'] 		= $row['id_referrer'];
			$datosAsigOferRef['id_oferta'] 			= $row3['id_oferta'];
			$datosAsigOferRef['promo_code'] 		= $token;		
			$datosAsigOferRef['websiteReserva']		= obtenerWebsiteReservaHotel();
			$datosAsigOferRef['hotelName']			= obtenerNombreHotelId($id_hotel);
			$datosAsigOferRef['nombre_oferta']		= $datosOferta['nombre'];
			$datosAsigOferRef['n_referrals_oferta']	= $datosOferta['n_referrals'];
			$datosAsigOferRef['img']				= $datosOferta['img'];
			$datosAsigOferRef['bookingEngineCode']	= $datosOferta['booking_engine_code'];
		}else{
			//No hay oferta de referral para ese numero de goals
		}
	}else{
		//Oferta de referral obtenida por goal, no a través del stay
	}
	desconectar($con);

	return $datosAsigOferRef;
}

function guardarTokenUsuario($id_usuario, $token, $id_encuesta, $id_hotel, $share=0){
	$fecha = dateHoy();
	$sql = "INSERT INTO referrer_tokens (id_usuario, token, fecha, id_encuesta, share, id_hotel) 
	VALUES 
	('".$id_usuario."', '".$token."', '".$fecha."', '".$id_encuesta."', '".$share."', '".$id_hotel."')";
	$id_token = escritura($sql);
	return $id_token;
}

function obtenerDatosBasicosOferta($id_oferta)
{
    empty($_SESSION['userLang'])? $lang='en' : $lang=$_SESSION['userLang'] ;
    
    //Get from cache
    $cacheName = 'obtenerDatosBasicosOferta_' . $id_oferta . '_' . $lang;
    $cache = getFromCache($cacheName);

    if(!$cache)
    {
    	$con = conectar(1);
    	$id_oferta = mysqli_real_escape_string($con, $id_oferta);
        $sql = "SELECT n_referrals, id_oferta, 
        case when oferta_lang.nombre is null 
    then   oferta_en.nombre 
    else oferta_lang.nombre end AS nombre   , img, hotel_oferta.booking_engine_code 
        FROM referral_goal
        INNER JOIN hotel_oferta ON hotel_oferta.id=referral_goal.id_oferta
        LEFT JOIN hotel_oferta_lang as oferta_en on referral_goal.id_oferta = oferta_en.id_oferta  and oferta_en.lang='en' 
  LEFT JOIN hotel_oferta_lang as oferta_lang on referral_goal.id_oferta = oferta_lang.id_oferta and oferta_lang.lang='". $lang . "'
        WHERE hotel_oferta.id='" . $id_oferta . "' ORDER BY n_referrals ASC LIMIT 1";
        $row = lectura($sql, $con, false);
        desconectar($con);
        if($row){
            $tags = array ('hotel', 'hotel_oferta', 'oferta_' . $id_oferta, );
            setToCache($cacheName, $row, 31536000, $tags);
        }
    }else{
        //Get result from cache
        $row = $cache->get();
    }

    return $row;
}


//FX para mirar si un usuario ha conseguido un goal a partir del canjeo de un promo_code por parte de una tercera persona
function accionesReferralPromoCode($promo_code, $id_hotel, $api)
{
	$con = conectar(1);
    $promo_code = mysqli_real_escape_string($con, $promo_code);
    $id_hotel = mysqli_real_escape_string($con, $id_hotel);
    desconectar($con);
	
	//Mirar referral, si cumple un goal, asignarselo
	$goal = asignarGoalReferral($promo_code, $id_hotel);

	//obtenemos el id_usuario REFERRER del promo code 
	$id_usuario = $goal['id_usuario'];//obtenerIdUsuarioReferrerPromocode($promo_code);
	//Miramos el Lang del usuario para el email
	if(!empty($id_usuario) )
	{
		//Ha conseguido un goal, madar email con la oferta de goal + puntos por referral
		$arrayDatosEmail = obtenerDatosUsuarioMail($id_usuario);//Datos usuario (lang!!)

		//Datos hotel
		$hotel = getHotelData($id_hotel);
		$hotelLogo = $hotel['logo'];
		$hotelBg = BASE_PATH . DIR_IMG_OFERTAS . $goal['id_oferta'] . '/big_' . $goal['img'];
		$urlHotel = $hotel['hotelUrl'];
		$nombre = $arrayDatosEmail['nombre'];
		
		global $urlTree;
		//Si no tiene invitacion, crearla
		/*if(userNoExiste($arrayDatosEmail['email']) || userNoActivo($arrayDatosEmail['email']) ){
			//$invitacion = crearInvitacionUsuario($arrayDatosEmail['email'],'hot',$id_hotel,0,0,0,0,$arrayDatosEmail['nombre'],1);
			$urlLogin = $invitacion['urlInvitacion'];
		}else{*/
			$urlLogin = BASE_PATH . $urlTree['login'];
		//}
		
		if($arrayDatosEmail['notif_hotelinking'] == '1' ){
			//Genera la URL para el unsuscribe
			$urlUnsuscribe = createUrlUnsuscribe($arrayDatosEmail['email'], $arrayDatosEmail['guid']);
			// El usuario puede tener un lang no soportado por la plataforma. 
			// Filtramos el lang para que sea un lang correcto. 
			$lang = mirarIdiomaPlataforma($arrayDatosEmail['lang']);
			//Creamos la url de 
			$urlToken = generarUrl($id_usuario, 0 , $id_hotel, 1);
			// Generamos la url para el canjeo del promocode a traves de nuestra pagina redeem-offer
			$guid = obtenerGUIDHotel($id_hotel);
			$redeemOfferUrl = BASE_PATH . $urlTree['redeem-offer'].'/?hlhid='.$guid.'&hlpc='.$goal['promo_code'];
			
			//Pedimos un listado de los Goals del hotel--------------
			$referralsGoals = obtenerGoalsHotel($id_hotel, $lang);
			//Incluir el idioma de la plantilla
			include_once RUTA_DIR.LANG.$lang.'/email/goal-email.php';
			
			//Creamos un listado de los goals (HTML)
			if(!empty($referralsGoals)){
	
				$goalsHtml = '';
				foreach ($referralsGoals as $goals) 
				{
						$goalsHtml .='<tr> <td valign="top" style="padding: 40px 0 0 0;" class="mobile-hide"> <img src="'.BASE_PATH . DIR_IMG_OFERTAS . $goals['id_oferta'] . '/small_' . $goals['img'].'" alt="Reward" width="105" height="105" border="0" style="display: block; font-family: Arial; color: #666666; font-size: 14px; width: 105px; height: 105px; border-radius:105px"> </td> <td style="padding: 40px 0 0 0;" class="no-padding"><table border="0" cellspacing="0" cellpadding="0" width="100%"> <tr> <td align="left" style="padding: 0 0 5px 25px; font-size: 13px; font-family: Helvetica, Arial, sans-serif; font-weight: normal; color: #aaaaaa;" class="padding-meta">'.$goalEmailLang['When you refer']. ' ' .$goals['n_referrals'] . ' ' . $goalEmailLang['friend'] .'</td> </tr> <tr> <td align="left" style="padding: 0 0 5px 25px; font-size: 22px; font-family: Helvetica, Arial, sans-serif; font-weight: normal; color: #333333;" class="padding-copy">'.$goals['nombre'].'</td> </tr> <tr> <td align="left" style="padding: 10px 0 15px 25px; font-size: 16px; line-height: 24px; font-family: Helvetica, Arial, sans-serif; color: #666666;" class="padding-copy">A free bootle of wine specially selected from our Somelliere. Waiting for you at your room. </td> </tr> <tr> <td style="padding:0 0 45px 25px;" align="left" class="padding"> <table border="0" cellspacing="0" cellpadding="0" class="mobile-button-container"> <tr> <td align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="mobile-button-container"> <tr> <td align="center" style="padding: 0;" class="padding-copy"> <table border="0" cellspacing="0" cellpadding="0" class="responsive-table"> <tr> <td align="center"> <a href="'.BASE_PATH.$urlTree['referral-share-step-2'].'/'.$guid.'" target="_blank" style="font-size: 15px; font-family: Helvetica, Arial, sans-serif; font-weight: normal; color: #ffffff; text-decoration: none; background-color: #5D9CEC; border-top: 10px solid #5D9CEC; border-bottom: 10px solid #5D9CEC; border-left: 20px solid #5D9CEC; border-right: 20px solid #5D9CEC; border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px; display: inline-block;" class="mobile-button">Start sharing &rarr;</a> </td> </tr> </table> </td> </tr> </table> </td> </tr> </table> </td> </tr> </table> </td> </tr>';
				}
			}else{
				$goalsHtml = 'There´s no goals set at this moment';
			}
			//-----------------------------------------------fin listado goals hotel
			include_once RUTA_DIR.LIB.'plantillasMails/goal-email.php';
			mandarEmailMandrillPlantillaSoloContenido($arrayDatosEmail['email'],$arrayDatosEmail['nombre'],$goalEmailLang['asunto'],$template_content,'goal-email', $hotel['email'], $hotel['hotelName']);
		}
	}
}
?>
