<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

include_once LIB.'fecha.php';
include_once LIB.'sanitize.php';

function dateDiff($dateStart, $dateEnd){
    $start = strtotime($dateStart);
    $end = strtotime($dateEnd);
    $days = $end - $start;
    $days = ceil($days/86400);
    return $days;
}

function obtenerDatosUsuario($id_usuario, $id_hotel){
	$sql = "SELECT nombre, users.id AS id_usuario, img
	FROM users
	INNER JOIN user_hotels ON user_hotels.id_usuario=users.id
	WHERE user_hotels.id_usuario='".$id_usuario."' 
	AND user_hotels.id_hotel='".$id_hotel."' ";
	//echo '--------------------'.$sql;
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar ($rs);
	$row['nombre_san']=string_sanitize($row['nombre']);
	$sql2 = "SELECT puntos FROM user_points
	WHERE id_usuario='".$id_usuario."' AND id_emisor='".$id_hotel."' ";
	$rs2 = mysqli_query (conectar(), $sql2);
	$row2 = mysqli_fetch_assoc($rs2);
	liberar($rs2);
	$row['puntos']=$row2['puntos'];
	return $row;
}

function obtenerDatosHotelChkin($id_hotel, $id_usuario){
	$sql = "SELECT hotelName, chkin_date, chkout_date
	FROM user_checkin 
	INNER JOIN hoteles ON hoteles.id=user_checkin.id_hotel
	WHERE user_checkin.id_hotel='".$id_hotel."' 
	AND user_checkin.id_usuario='".$id_usuario."' 
	ORDER BY user_checkin.id DESC LIMIT 1";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar ($rs);
	return $row;
}

function checkoutUsuario($id_usuario, $id_hotel, $puntos){
	$sql = "SELECT id, chkin_date FROM user_checkin 
	WHERE id_usuario='".$id_usuario."' AND id_hotel='".$id_hotel."' 
	AND chkout_date='0000-00-00' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	
	$fechaHora = dateTimeHoy();
	$fecha = dateHoy();
	$noches = dateDiff($row['chkin_date'], $fecha);
	
	$sql2 = "UPDATE user_checkin SET  
	noches='".$noches."', chkout_date='".$fecha."', checkout_fechahora='".$fechaHora."',
	puntos='".$puntos."'
	WHERE id='".$row['id']."' ";
	mysqli_query (conectar(), $sql2);
	
	// Actualizamos el total noches y el total checkins
	$sql3 = "UPDATE user_hotels SET total_noches=total_noches+'".$noches."', 
	total_checkins=total_checkins+1
	WHERE id_usuario='".$id_usuario."' AND id_hotel='".$id_hotel."' ";
	mysqli_query (conectar(), $sql3);
	
	return $row['id'];
}

function registrarGastoUsuario($usd, $id_usuario, $id_hotel, $id_checkout, $amount, $divisa, $booking_value){
	$fecha = dateHoy();
	$sql = "INSERT INTO user_money_hotel 
	(id_usuario, id_hotel, money, fecha, id_checkout, dollars, coin) VALUES 
	('".$id_usuario."', '".$id_hotel."', '".$amount."', '".$fecha."', '".$id_checkout."'
	, '".$usd."', '".$divisa."')";
	mysqli_query (conectar(), $sql);
	//echo $sql.'<br>';
	// Actualizamos el gasto total
	$sql2 = "UPDATE user_hotels SET gasto_total=gasto_total+'".$amount."' 
	WHERE id_usuario='".$id_usuario."' AND id_hotel='".$id_hotel."' ";
	mysqli_query (conectar(), $sql2);
	//echo $sql2.'<br>';
}

function crearEncuesta($id_usuario, $id_hotel, $id_checkout){
	$fecha = dateHoy();
	$sql = "INSERT INTO user_encuestas (id_usuario, id_hotel, id_checkout, fecha_creada) 
	VALUES ('".$id_usuario."', '".$id_hotel."', '".$id_checkout."', '".$fecha."')";
	mysqli_query (conectar(), $sql);
}

//Asocia el booking value con el checkin
//Devuelve el booking value en la moneda del hotel
function asociarBookingValueCheckout($id_usuario, $id_hotel, $id_checkout){
	//Obtenemos el ultimo booking value no asociado a checkout de esta persona en este hotel
	$sql = "SELECT booking_value.id AS id_booking_value
	FROM booking_value
	INNER JOIN user_cupones ON user_cupones.id=booking_value.id_cupon
	INNER JOIN hotel_oferta ON hotel_oferta.id=user_cupones.id_oferta
	WHERE user_cupones.id_usuario='".$id_usuario."' AND id_checkout='0' ";
	if(hotelDeCadena($id_hotel)){
		$id_cadena = hotelIdCadena($id_hotel);
		$sql .= " AND (hotel_oferta.id_hotel='".$id_hotel."' OR hotel_oferta.id_cadena='".$id_cadena."' )";
	}else{
		$sql .= " AND hotel_oferta.id_hotel='".$id_hotel."' ";
	}
	$sql .= " ORDER BY booking_value.id DESC LIMIT 1";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar ($rs);
	if($row['id_booking_value']!=''){
		$sql2 = "UPDATE booking_value SET id_checkout='".$id_checkout."' WHERE id=".$row['id_booking_value'];
		mysqli_query (conectar(), $sql2);
	}
}

function obtenerBookingValueCheckout($id_usuario, $id_hotel){
	$arrayBookingValue = array();
	$sql = "SELECT booking_value.id AS id_booking_value, conversion_current_coin, current_coin
	FROM booking_value
	INNER JOIN user_cupones ON user_cupones.id=booking_value.id_cupon
	INNER JOIN hotel_oferta ON hotel_oferta.id=user_cupones.id_oferta
	WHERE user_cupones.id_usuario='".$id_usuario."' AND id_checkout='0' ";
	if(hotelDeCadena($id_hotel)){
		$id_cadena = hotelIdCadena($id_hotel);
		$sql .= " AND (hotel_oferta.id_hotel='".$id_hotel."' OR hotel_oferta.id_cadena='".$id_cadena."' )";
	}else{
		$sql .= " AND hotel_oferta.id_hotel='".$id_hotel."' ";
	}
	$sql .= " ORDER BY booking_value.id DESC LIMIT 1";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar ($rs);
	if($row['id_booking_value']!=''){
		$arrayBookingValue[0]=$row['conversion_current_coin'];
		$arrayBookingValue[1]=$row['current_coin'];
	}else{
		$arrayBookingValue[0]=0;
		$arrayBookingValue[1]=0;
	}
	return $arrayBookingValue;
}


?>