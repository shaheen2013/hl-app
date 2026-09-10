<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

include_once RUTA_DIR.LIB.'fecha.php';
include_once RUTA_DIR.LIB.'obtenerdatosHotel.php';
include_once RUTA_DIR.LIB.'referrer.php';

//api : si se canjea a traves de "API PROMO CODE" 1, si se hace desde la web 0
// Si no viene de la API solo puede canjear ofertas de stay y pre-stay
// FX llamada internamente desde canjearPromoCode (no hace falta escape_string)
function canjearCupon($promo_code, $id_hotel, $id_canjeador, $tipo_canjeador, $api, $con)
{
	//echo 'canjearCupon: '.$promo_code.' - '.$id_hotel.' - '.$id_canjeador.' - '.$tipo_canjeador;
	/*$con = conectar(1);
    $promo_code = mysqli_real_escape_string($con, $promo_code);
    $id_hotel = mysqli_real_escape_string($con, $id_hotel);
    desconectar($con);*/

	// obtenemos el id de oferta
	$sql = "SELECT oferta_referral_token.id_cupon AS id, hotel_oferta.id AS id_oferta, token AS voucher 
	FROM oferta_referral_token
	INNER JOIN hotel_oferta ON hotel_oferta.id=oferta_referral_token.id_oferta ";
	$sql .= " WHERE oferta_referral_token.token='".$promo_code."' ";

	if($api=='1')
	{
		if (hotelDeCadena($id_hotel) )
		{
			//Si es hotel de cadena tb mostramos ofertas de cadena
			$id_cadena = hotelIdCadena($id_hotel);
			$sql .= " AND (hotel_oferta.id_hotel='".$id_hotel."' OR  hotel_oferta.id_cadena='".$id_cadena."')";
		}else{
			$sql .= " AND hotel_oferta.id_hotel='".$id_hotel."' ";
		}
	}else{
		// Si no viene de la api, solo debe poder canjear ofertas de pre-stay y stay 
		if($tipo_canjeador=='cadena')
		{
			$id_cadena = hotelIdCadena($id_hotel);
			$sql .= " AND (hotel_oferta.id_hotel='".$id_hotel."' OR  hotel_oferta.id_cadena='".$id_cadena."')";
		}else{
			$sql .= " AND (oferta_referral_token.id_hotel='".$id_hotel."' )";
		}
		$sql .= " AND (oferta_referral_token.id_tipo_share='2' OR oferta_referral_token.id_tipo_share='3') ";
	}
	$row = lectura($sql, $con, false);
	
	if (!empty($row))
	{
		$fechaHoy = dateTimeHoy();
		// Canjeamos cupon + Incrementamos el contador de ofertas canjeadas
		$sql2 = "UPDATE user_cupones, hotel_oferta
		SET user_cupones.canjeado='1', user_cupones.fecha_canj='".$fechaHoy."', user_cupones.tipo_canjeador='".$tipo_canjeador."', 
		user_cupones.id_canjeador='".$id_canjeador."',  
		hotel_oferta.canjeadas=canjeadas+1 
		WHERE user_cupones.id='".$row['id']."' AND hotel_oferta.id='".$row['id_oferta']."' ";
		escritura($sql2, $con, false);

		$id_transaccion = borrarPromoCode($row['voucher'], $id_hotel, $api, $con);
		$result['code']='200';
		$result['id_transaccion']=$id_transaccion;

	}else{
		$result['code']= '404';
		$result['id_transaccion']= '';

	}
	$result['cuponId']=$row['id'];

	return $result;
}

//"Borrar promo code": mueve promocode de oferta_referral_token a used_promocode
// + id de transaccion
// FX llamada internamente desde canjearCupon (no hace falta escape_string)
function borrarPromoCode($promo_code, $id_hotel, $api, $con)
{
	/*$con = conectar(1);
    $promo_code = mysqli_real_escape_string($con, $promo_code);
    $id_hotel = mysqli_real_escape_string($con, $id_hotel);
    desconectar($con);*/

	$id_transaccion = '';

	//Los promo codes usados los movemos de oferta_referral_token a used_promocode, no se borran
	$sql = "SELECT oferta_referral_token.id AS id_oferta_referral_token, id_oferta, id_usuario, id_referrer, id_encuesta,
	token, fecha, id_cupon, transaction, id_tipo_share, id_origen_oferta
	FROM oferta_referral_token
	INNER JOIN hotel_oferta ON hotel_oferta.id=oferta_referral_token.id_oferta
	WHERE oferta_referral_token.token='".$promo_code."' ";
	if (hotelDeCadena($id_hotel))
	{//Si es hotel de cadena tb mostramos ofertas de cadena
		$id_cadena = hotelIdCadena($id_hotel);
		$sql .= "AND (hotel_oferta.id_hotel='".$id_hotel."' or  hotel_oferta.id_cadena='".$id_cadena."')";
	}else{
		$sql .= "AND hotel_oferta.id_hotel='".$id_hotel."' ";
	}
	$sql .= " LIMIT 1 ";
	$row = lectura($sql, $con, false);
	if(!empty($row))
	{
		//Generamos un id de transacción unico a partir del promo_code + unix time
		$id_transaccion = sha1(time().$row['token']);
		$shareType = !empty($row['id_tipo_share']) ? 
			$row['id_tipo_share'] :
			'NULL';
		//Promocode existe y pertenece a este hotel, procedemos al "borrado"
		//insertamos en used_promocode
		$sql2 = "INSERT INTO used_promocode 
		(id_oferta, id_usuario, id_referrer, id_encuesta, promo_code, fecha, api, id_hotel, id_transaccion, id_cupon, id_tipo_share,
		be_transaction, id_origen_oferta) 
		VALUES 
		('".$row['id_oferta']."', '".$row['id_usuario']."', '".$row['id_referrer']."', '".$row['id_encuesta']."', '".$row['token']."', 
		'".$row['fecha']."', '".$api."', '".$id_hotel."', '".$id_transaccion."', '".$row['id_cupon']."', $shareType,'".$row['transaction']."', ";
		$sql2 .=( (empty($id_origen_oferta))? " NULL)" : "'".$id_origen_oferta."')" );
		escritura($sql2, $con, false);
		//Borramos de oferta_referral_token
		$sql3 = "DELETE FROM oferta_referral_token WHERE id='".$row['id_oferta_referral_token']."' ";
		escritura($sql3, $con, false);
	}else{
		//Promocode no existe o no pertenece a este hotel	
	}
	return $id_transaccion;
}

function obtenerDatosPromoCode($promo_code, $id_hotel, $api=1)
{
	$con 		= conectar();
    $promo_code = mysqli_real_escape_string($con, $promo_code);
    $id_hotel 	= mysqli_real_escape_string($con, $id_hotel);
    
	$lang = obtenerLangHotel($id_hotel);

	//echo 'obtenerDatosPromoCode';
	$sql = "SELECT hotel_oferta.id AS offerId, ";

	//Idiomas:
	$sql .= " case when oferta_lang.nombre is null 
	 then  oferta_en.nombre 
     else oferta_lang.nombre end as offerName, ";
	$sql .= " case when oferta_lang.descripcion is null 
	 then  oferta_en.descripcion 
     else oferta_lang.descripcion end as description, ";
	$sql .= " case when oferta_lang.condiciones is null 
    then   oferta_en.condiciones 
    else oferta_lang.condiciones end AS conditions, ";

	$sql .= " hotel_oferta.inicio AS begins, 
	tipo_adq_ret_en AS offerType, 
	categoria_en AS offerCategory, subcategoria_oferta_en AS offerSubcategory, 
	hotel_oferta.booking_engine_code AS bookingEngineCode 
	 
	 FROM oferta_referral_token 
	INNER JOIN hotel_oferta ON hotel_oferta.id=oferta_referral_token.id_oferta 
	INNER JOIN tipos_oferta ON tipos_oferta.id_tipo_oferta=hotel_oferta.id_tipo_oferta
	INNER JOIN categoria_oferta ON categoria_oferta.id_categoria_oferta=hotel_oferta.id_categoria
	LEFT JOIN hotel_oferta_lang as oferta_en on hotel_oferta.id = oferta_en.id_oferta  and oferta_en.lang='en' 
	LEFT JOIN hotel_oferta_lang as oferta_lang on hotel_oferta.id = oferta_lang.id_oferta and oferta_lang.lang='".$lang. "'
	LEFT JOIN subcategoria_oferta ON subcategoria_oferta.id_categoria_oferta=hotel_oferta.id_subcategoria ";

	$sql .= " WHERE oferta_referral_token.token='".$promo_code."' ";
	if (hotelDeCadena($id_hotel)){//Si es hotel de cadena tb mostramos ofertas de cadena
		$id_cadena = hotelIdCadena($id_hotel);
		$sql .= " AND (hotel_oferta.id_hotel='".$id_hotel."' or  hotel_oferta.id_cadena='".$id_cadena."')";
	}else{
		$sql .= " AND hotel_oferta.id_hotel='".$id_hotel."' ";
	}
	$sql .= " LIMIT 1 ";
	$row = lectura($sql, $con, false);
	desconectar($con);

	if(!empty($row) && $id_hotel !='error')
	{
		foreach ($row as $key => $valor)
		{
			if($key == 'description' || $key == 'conditions')
			{
				$result[$key] = strip_tags($valor);
			}else{
				$result[$key] = $valor;
			}
		}
		//$result = array('status'=>'200','message' =>'OK'); //OK - canjeado
		$result['code']='200';
		$result['message']='OK';
	}else{
		//Promocode no existe o no pertenece a este hotel
		$result = array('code'=>'404','message' =>'Promo code not found'); //OK - canjeado
	}
	if($api === 1){
		echo json_encode($result);
	}else{
		return $result;
	}
}

// FX para canjear promo codes
// $promo_code: 
// $id_hotel: id de hotel al que debe pertenecer el promo code. También puede pertenecer a la cedena de este hotel. En caso contrario no lo canjea y devuelve un code '410'
// $tipo_canjeador: tipo de usuario que ha canjeado el cupon. hotel, staff
// $id_canjeador: id del tipo de canjeador (hotel / staff) que ha canjeado el cupon
function canjearPromoCode($promo_code, $id_hotel, $id_canjeador, $tipo_canjeador='hotel', $api=1)
{
	$con 		= conectar();
    $promo_code = mysqli_real_escape_string($con, $promo_code);
    $id_hotel 	= mysqli_real_escape_string($con, $id_hotel);
    

	//FX canjearCupon($id_cupon, $id_hotel) (esta función se encarga de borrar el Promo Code)
	$result = canjearCupon($promo_code, $id_hotel, $id_canjeador, $tipo_canjeador, $api, $con);
	if($result['code'] =='200'){
		//accionesReferralPromoCode: asignar Goal y asignar Puntos Referral PromoCode
		accionesReferralPromoCode($promo_code, $id_hotel, $api);
		$result = array("code"=>"200","message" =>"OK","transactionId"=>$result["id_transaccion"],"cuponId"=>$result["cuponId"]); //OK - canjeado
	}else{
		$result = array("code"=>"410","message" =>"Promo code not found","transactionId" => $result["id_transaccion"],"cuponId" => ""); //Ya no disponible
	}
	desconectar($con);

	//echo json_encode($result);
	//$arrayResult ['code']=$result['code'];
	//$arrayResult ['cuponId']=$row['id'];

	return $result;
}

// Devuelve los datos del cupon: oferta + hotel.
// Si la oferta es de cadena no tenemos el id_hotel en la oferta 
function obtenerDatosCupon($id_cupon, $id_hotel, $lang='en')
{
	$con = conectar();
	$id_cupon = mysqli_real_escape_string($con, $id_cupon);

	$sql = "SELECT user_cupones.id_usuario, user_cupones.voucher, ";
	//Idioma oferta
	$sql .= " case when oferta_lang.nombre is null 
	 then  oferta_en.nombre 
     else oferta_lang.nombre end as nombre_oferta, ";
	$sql .= " hotel_oferta.id AS id_oferta,
	hoteles.hotelName AS nombre_hotel, hoteles.id AS id_hotel
	FROM user_cupones 
	INNER JOIN hotel_oferta ON hotel_oferta.id=user_cupones.id_oferta
	LEFT JOIN hotel_oferta_lang as oferta_en on hotel_oferta.id = oferta_en.id_oferta  and oferta_en.lang='en' 
	LEFT JOIN hotel_oferta_lang as oferta_lang on hotel_oferta.id = oferta_lang.id_oferta and oferta_lang.lang='".$lang. "'
	LEFT JOIN hoteles ON hoteles.id='".$id_hotel."'
	WHERE user_cupones.id='".$id_cupon."' ";
	$row = lectura($sql, $con, false);
	desconectar($con);
	return ($row);
}

function obtenerVoucherCupon($id_cupon)
{
	$con = conectar();
	$id_cupon = mysqli_real_escape_string($con, $id_cupon);

	$sql = "SELECT user_cupones.id_usuario, user_cupones.voucher 
	FROM user_cupones 
	INNER JOIN hotel_oferta ON hotel_oferta.id=user_cupones.id_oferta
	WHERE user_cupones.id='".$id_cupon."' ";
	$row = lectura($sql, $con, false);
	desconectar($con);
	return ($row);
}

//Devuelve el id del ultimo promocode usado con ese $promo_code
function obtenerIdUsedPromocode($promo_code)
{
	$con = conectar();
	$promo_code = mysqli_real_escape_string($con, $promo_code);

	$sql = "SELECT id
	FROM used_promocode
	WHERE promo_code='".$promo_code."' ORDER BY id DESC LIMIT 1";
	$row = lectura($sql, $con, false);
	desconectar($con);
	return ($row['id']);
}

//Recibe el id_cupon, el booking_value y la moneda del booking_value
function guardarBookingValue($id_hotel, $id_cupon, $booking_value, $coin)
{
	$con 			= conectar();
    $id_hotel 		= mysqli_real_escape_string($con, $id_hotel);
    $id_cupon 		= mysqli_real_escape_string($con, $id_cupon);
    $booking_value 	= mysqli_real_escape_string($con, $booking_value);
    $coin 			= mysqli_real_escape_string($con, $coin);
    

	$monedaHotel = obtenerMonedaHotel($id_hotel);
	include_once RUTA_DIR.LIB.'convertirDivisas.php';
	//Si no ha puesto coin o el coin es incorrecto, asumimos la moneda del hotel
	if($coin=='' || !divisaOk($coin))
	{
		$coin = $monedaHotel;
	}

	$dollars = $booking_value;

	$conversion_moneda_hotel = $booking_value;

	//Redondeamos a 2 decimales: dolares, la conversion a la moneda del hotel y el booking value
	$dollars = round($dollars, 2);
	$conversion_moneda_hotel = round($conversion_moneda_hotel, 2);
	$booking_value = round($booking_value, 2);

	$datosCupon = obtenerVoucherCupon($id_cupon);
	$id_usuario = $datosCupon['id_usuario'];

	//Obtener id_used_promocode
	$id_used_promocode = obtenerIdUsedPromocode($datosCupon['voucher']);

	if($dollars != '' && $dollars != '0')
	{
		$fechaHoy = dateHoy();
		$sql = "INSERT INTO booking_value (id_cupon, id_hotel, id_usuario, dollars, amount, coin, conversion_current_coin, current_coin, id_used_promocode, fecha) 
		VALUES ('".$id_cupon."', '".$id_hotel."', '".$id_usuario."','".$dollars."', '".$booking_value."' , '".$coin."', '".$conversion_moneda_hotel."', 
		'".$monedaHotel."', '".$id_used_promocode."', '".$fechaHoy."')";
		escritura($sql, $con, false);
	}
	desconectar($con);
	//return $dollars;
}

function obtnenerReservaMinima($id_hotel)
{
	$sql = "SELECT min_rango FROM hoteles WHERE id='".$id_hotel."' ";
	$row = lectura($sql);
	return ($row['min_rango']);
}
?>