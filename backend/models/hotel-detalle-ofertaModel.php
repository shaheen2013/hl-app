<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

function obtenerDatosOferta($id_oferta, $id_hotel, $id_cadena=0)
{
	$id_oferta = mysqli_real_escape_string(conectar() , $id_oferta);
	$sql2 = "SELECT adq_ret FROM hotel_oferta  WHERE id='".$id_oferta."' ";
	$row2 = lectura($sql2);
	
	$sql = "SELECT hotel_oferta.id, adq_ret, inicio, adquiridas, canjeadas, descuento,
	img, estado, case when oferta_lang.descripcion is null 
    then  oferta_en.descripcion 
    else oferta_lang.descripcion end as descripcion,
	case when oferta_lang.nombre is null 
    then  oferta_en.nombre 
    else oferta_lang.nombre end as nombre,
	
	tipos_oferta.tipo_adq_ret_".$_SESSION['userLang']." AS tipo_oferta, 
	categoria_oferta.categoria_".$_SESSION['userLang']." AS categoria";
	if($row2['adq_ret']!='ref'){//Si no es oferta de Referral mostramos mas campos
		$sql .= ", fin, cupo, puntos, ";
		$sql .= "CASE WHEN hotel_oferta.cupo!=0 THEN hotel_oferta.cupo-hotel_oferta.adquiridas 
		WHEN hotel_oferta.cupo=0 THEN '-' END AS quedan ";
	}else{
		if( $id_cadena != 0 ){
			$sql .= ", COUNT(cadena_oferta_referral.id_oferta) AS oferta_landing";
		}else{
			$sql .= ", COUNT(hotel_oferta_referral.id_oferta) AS oferta_landing";
		}
	}
	$sql .= " FROM hotel_oferta 
	LEFT JOIN hotel_oferta_lang as oferta_en on hotel_oferta.id = oferta_en.id_oferta  and oferta_en.lang='en' 
    LEFT JOIN hotel_oferta_lang as oferta_lang on hotel_oferta.id = oferta_lang.id_oferta and oferta_lang.lang='".$_SESSION['userNavLang'] . "'
	LEFT JOIN tipos_oferta ON tipos_oferta.id_tipo_oferta=hotel_oferta.id_tipo_oferta
	LEFT JOIN categoria_oferta ON categoria_oferta.id_categoria_oferta = hotel_oferta.id_categoria
	LEFT JOIN hoteles ON hoteles.id = hotel_oferta.id_hotel
	LEFT JOIN hotel_oferta_lang ON hotel_oferta_lang.id_oferta=hotel_oferta.id 
		AND hotel_oferta_lang.lang='".$_SESSION['userLang']."' ";
	if($row2['adq_ret']=='ref'){
		//Si oferta referral => oferta principal (landing), oferta de Goal
		$sql .= " LEFT JOIN hotel_oferta_referral ON hotel_oferta_referral.id_oferta='".$id_oferta."' 
		LEFT JOIN cadena_oferta_referral ON cadena_oferta_referral.id_oferta='".$id_oferta."' ";
	}
	$sql .= " WHERE hotel_oferta.id='".$id_oferta."' ";
	
	if( $id_cadena != 0 || hotelDeCadena($id_hotel) ){
		if($id_cadena == 0)
			$id_cadena = hotelIdCadena($id_hotel);
		$sql .= " AND (hotel_oferta.id_hotel='".$id_hotel."' OR
		 hotel_oferta.id_cadena='".$id_cadena."') ";
	}else{
		$sql .= " AND hotel_oferta.id_hotel='".$id_hotel."' ";
	}
	//echo $sql;
	$row = lectura($sql);
	$arrayDatosOferta = array();
	foreach ($row as $key=>$valor){
		if($key=='inicio' || $key=='fin'){
			if ($valor=='0000-00-00'){
				$arrayDatosOferta[$key]='-';
			}else{
				$arrayDatosOferta[$key]=girarFecha($valor);
			}
		}else{
			$arrayDatosOferta[$key]=$valor;
		}
	}
	return ($arrayDatosOferta);
}

function obtenerGoalsOferta($id_oferta)
{
	$id_oferta = mysqli_real_escape_string(conectar() , $id_oferta);

	//Obtener goals (una misma oferta puede tener varios goals, 5, 10...)
	$sql = "SELECT n_referrals FROM referral_goal WHERE id_oferta='".$id_oferta."'";

	$row = lecturaArray($sql);
	if(!empty($row))
	{
		foreach ($row as $valor)
		{
			$goals[]=$valor['n_referrals'];
		}
	}else{
		$goals[0]=0;
	}
		
	return $goals;
}	

function obtenerCuponesOferta($id_oferta, $id_hotel, $order, $sort, $itemsPage, $pagina, $tipoOferta, $search=0)
{
	$id_oferta = mysqli_real_escape_string(conectar() , $id_oferta);
	$order = mysqli_real_escape_string(conectar() , $order);
	
	$sql = "SELECT DISTINCT user_cupones.id, voucher, user_cupones.fecha, fecha_canj, 
	case when oferta_lang.nombre is null 
    then  oferta_en.nombre 
    else oferta_lang.nombre end as nombre_oferta,
	canjeado, users.id AS id_usuario, users.nombre, users.img, users.email ";
	if($tipoOferta=='ref'){
		$sql .= ", IFNULL(ROUND(booking_value.conversion_current_coin, 2), 0) AS booking_value
		, current_coin ";
	}
	// Nombre canjeador (staff, staff_inactivo, hotel, cadena)
	$sql .= ", 
    CASE WHEN canjeado = '0' THEN ''
    WHEN user_cupones.tipo_canjeador='hotel' THEN hoteles.hotelName
    WHEN user_cupones.tipo_canjeador='cadena' THEN cadena.nombre
    WHEN user_cupones.tipo_canjeador='staff' THEN hotel_staff.nombre
    ELSE ''
    END AS redeemed_by ";
	$sql2 = " FROM  hotel_oferta
	INNER JOIN user_cupones ON user_cupones.id_oferta= hotel_oferta.id
	INNER JOIN users ON users.id=user_cupones.id_usuario 
	LEFT JOIN hotel_oferta_lang as oferta_en on hotel_oferta.id = oferta_en.id_oferta  and oferta_en.lang='en' 
    LEFT JOIN hotel_oferta_lang as oferta_lang on hotel_oferta.id = oferta_lang.id_oferta and oferta_lang.lang='".$_SESSION['userNavLang'] . "'
    LEFT JOIN cadena ON user_cupones.id_canjeador=cadena.id
    LEFT JOIN hotel_staff ON hotel_staff.id=user_cupones.id_canjeador
    LEFT JOIN hotel_staff_inactivo ON user_cupones.id_canjeador=hotel_staff_inactivo.id_staff
    LEFT JOIN hoteles ON hoteles.id=user_cupones.id_canjeador";
	//Para obtener el tipo de share que consiguió el cupón
	$sql2 .= "
	LEFT JOIN oferta_referral_token ON oferta_referral_token.id_cupon=user_cupones.id
	LEFT JOIN used_promocode ON used_promocode.id_cupon=user_cupones.id	
	LEFT JOIN tipos_share ON tipos_share.id=oferta_referral_token.id_tipo_share 
		OR tipos_share.id=used_promocode.id_tipo_share ";
	
	if($tipoOferta=='ref'){
		//Si oferta referral => booking value
		$sql2 .= " LEFT JOIN booking_value ON booking_value.id_cupon=user_cupones.id";
	}
	
	$sql2 .= " WHERE hotel_oferta.id='".$id_oferta."' ";
	
	if( !empty($_SESSION['c_logueado']) )
	{
		$id_cadena = hotelIdCadena($id_hotel);
		$sql2 .= " AND (hotel_oferta.id_hotel='".$id_hotel."' OR hotel_oferta.id_cadena='".$id_cadena."') ";
	}else{
		//Es un hotel o un staff
		//Solo mostramos cupones conseguidos por un share en su hotel
		$sql2 .= " AND (oferta_referral_token.id_hotel='".$id_hotel."' OR 
		used_promocode.id_hotel='".$id_hotel."') ";	
	}
	
	
	if( $search != '0' )
	{
		$search = mysqli_real_escape_string(conectar() , $search);
		$sql2 .=" AND users.nombre LIKE '%".$search."%'";
	}
	$inicio = $itemsPage*$pagina-$itemsPage;
	$sql3 = " ORDER BY ".$order." ". $sort;
	$sql3 .= " LIMIT ".$inicio.",".$itemsPage;
	//echo $sql.$sql2.$sql3;
	$rs = mysqli_query (conectar(), $sql.$sql2.$sql3);
	$arrayCuponesOferta = array();
	$i=0;
	while ($row = mysqli_fetch_assoc($rs)){
		foreach ($row as $key=>$valor){
			if($key=='fecha' || $key=='fecha_canj'){ 
				$fecha = substr($valor, 0, 10);// Quitamos la hora en la "fecha"
				$arrayCuponesOferta[$i][$key] = girarFecha($fecha);
			}else if($key=='nombre' || $key=='nombre_oferta'){
				$arrayCuponesOferta[$i][$key] = $valor;
				$arrayCuponesOferta[$i][$key.'_san'] = string_sanitize($valor);
			}else{
				$arrayCuponesOferta[$i][$key] = htmlentities($valor, ENT_QUOTES, "ISO-8859-1");
			}
		}
		$arrayCuponesOferta[$i]['urlGuid'] = obtenerUrlGUIDUsario($row['id_usuario']);
		$i++;
	}
	liberar($rs);
	
	$sql0 = "SELECT COUNT(DISTINCT(user_cupones.id)) as N ";
	paginacion2($sql0.$sql2, $pagina, $itemsPage);
	
	return ($arrayCuponesOferta);
}
?>