<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

include_once LIB.'sanitize.php';

function visitedHotels($id_usuario){
	$sql = "SELECT DISTINCT id_hotel FROM user_checkin WHERE id_usuario='".$id_usuario."' ";
	$rs = mysqli_query (conectar(), $sql);
	$n_resultados = mysqli_num_rows($rs);
	liberar($rs);
	return $n_resultados;
}

function reviews($id_usuario){
	$sql = "SELECT id FROM user_encuestas WHERE id_usuario='".$id_usuario."' AND done=1";
	$rs = mysqli_query (conectar(), $sql);
	$n_resultados = mysqli_num_rows($rs);
	liberar($rs);
	return $n_resultados;
}

function nights($id_usuario){
	//$sql = "SELECT noches FROM user_checkin WHERE id_usuario='".$id_usuario."' ";
	$sql = "SELECT SUM(total_noches) AS total_noches FROM user_hotels 
		WHERE id_usuario='".$id_usuario."' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	
	return $row['total_noches'];
}

function obtenerRatingUsuario($id_usuario){
	$sql = "SELECT rate FROM hotel_user_rate WHERE id_usuario='".$id_usuario."' ";
	//echo '--------------------'.$sql;
	$rs = mysqli_query (conectar(), $sql);
	$i=0;
	$totalRatings=0;
	while ($row = mysqli_fetch_assoc($rs)){
		$totalRatings += $row['rate'];
		$i++;
	}
	if($i!=0){
		$rating = round($totalRatings/$i, 1);
	}else{
		$rating = 0;
	}
	liberar ($rs);
	return ($rating);
}

function obtenerDatosUsuario($id_usuario){
	$sql = "SELECT users.location AS pais, users.nombre, tw_followers, fb_friends, img
	FROM users
	WHERE users.id='".$id_usuario."' ";
	$rs = mysqli_query (conectar(), $sql);
	$n_resultados = mysqli_num_rows($rs);
	if ($n_resultados!=0){
		$row = mysqli_fetch_assoc($rs);
		//Rating user (no es público!!!)
		$row['rating_usuario'] = obtenerRatingUsuario($id_usuario);
		//Visited Hotels
		$row['hotels_visited'] = visitedHotels($id_usuario);
		//Reviews
		$row['reviews'] = reviews($id_usuario);
		//Nights
		$row['nights'] = nights($id_usuario);
	}else{
		$row='';
	}
	liberar ($rs);
	return ($row);
}

function guestRatesYou($id_usuario, $id_hotel){
	$sql = "SELECT rating FROM user_encuestas WHERE id_usuario='".$id_usuario."' 
	AND id_hotel='".$id_hotel."' ";
	//echo '--------------------'.$sql;
	$rs = mysqli_query (conectar(), $sql);
	$i=0;
	$totalRatings=0;
	while ($row = mysqli_fetch_assoc($rs)){
		if ($row['rating']!=0){
			$totalRatings += $row['rating'];
			$i++;
		}
	}
	if($i!=0){
		$rating = round($totalRatings/$i, 1);
	}else{
		$rating = 0;
	}
	liberar ($rs);
	return ($rating);
}

function totalSpent($id_usuario, $id_hotel){
	/*$sql = "SELECT money FROM user_money_hotel WHERE id_usuario='".$id_usuario."' 
	AND id_hotel='".$id_hotel."' ";
	$rs = mysqli_query (conectar(), $sql);
	$money=0;
	while ($row = mysqli_fetch_assoc($rs)){
		$money += $row['money'];
	}
	liberar ($rs);
	return ($money);*/
	$sql = "SELECT gasto_total FROM user_hotels WHERE id_usuario='".$id_usuario."' AND id_hotel='".$id_hotel."' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	return $row['gasto_total'];
}

function nightsHotel($id_usuario, $id_hotel){
	$sql = "SELECT noches FROM user_checkin WHERE id_usuario='".$id_usuario."' 
	AND id_hotel='".$id_hotel."' ";
	$rs = mysqli_query (conectar(), $sql);
	$noches=0;
	while ($row = mysqli_fetch_assoc($rs)){
		$noches = $noches + $row['noches'];
	}
	liberar($rs);
	return $noches;
}

function redeemsHotel($id_usuario, $id_hotel){ // Canjeados
	$sql = "SELECT user_cupones.id FROM user_cupones 
	INNER JOIN hotel_oferta ON hotel_oferta.id=user_cupones.id_oferta
	WHERE id_usuario='".$id_usuario."' 
	AND id_hotel='".$id_hotel."' ";
	$rs = mysqli_query (conectar(), $sql);
	$n_resultados = mysqli_num_rows($rs);
	liberar($rs);
	return $n_resultados;
}

function totalShares($id_usuario, $id_hotel){
	$sql = "SELECT id FROM user_shares WHERE id_usuario='".$id_usuario."' 
	AND id_hotel='".$id_hotel."' ";
	$rs = mysqli_query (conectar(), $sql);
	$n_resultados = mysqli_num_rows($rs);
	liberar($rs);
	return $n_resultados;
}

function obtenerDatosUsuarioHotel($id_usuario, $id_hotel){
	$sql = "SELECT users.id_tarjeta FROM users WHERE id='".$id_usuario."' ";
	//echo '---------------'.$sql;
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar ($rs);
	//----Hotel-----
	//Guest rates you
	$row['guest_rates_you']=guestRatesYou($id_usuario, $id_hotel);
	//Total spent
	$row['total_spent']=totalSpent($id_usuario, $id_hotel);
	//Total nights
	$row['nights_hotel']=nightsHotel($id_usuario, $id_hotel);
	//Total redeems
	$row['redeems']=redeemsHotel($id_usuario, $id_hotel);
	//Total shares
	$row['totalShares'] = totalShares($id_usuario, $id_hotel);
	//Total referrals
	//Referrals´ spent
	if (empty($row)){
		$row=0;	
	}
	return ($row);
}

function obtenerReferrals($id_usuario, $id_hotel){ // $id_usuario -> invitador
	/*$sql = "SELECT referrer_users.id
	FROM referrer_users
	INNER JOIN user_hotels ON user_hotels.id_usuario=referrer_users.invitador
	WHERE user_hotels.id_hotel='".$id_hotel."' 
	AND referrer_users.invitador='".$id_usuario."' ";
	$rs = mysqli_query (conectar(), $sql);
	$n_resultados = mysqli_num_rows($rs);
	if ($n_resultados == ''){
		$n_resultados = 0;
	}
	liberar($rs);
	return $n_resultados;*/
	
	$sql = "SELECT totalReferrals('".$id_usuario."', '".$id_hotel."') AS n";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	return $row['n'];
}

function obtenerReferralsSpent($id_usuario, $id_hotel){ // $id_usuario -> invitador
	$sql = "SELECT referrer_users.invitado
	FROM referrer_users
	INNER JOIN user_hotels ON user_hotels.id_usuario=referrer_users.invitador
	WHERE user_hotels.id_hotel='".$id_hotel."' 
	AND referrer_users.invitador='".$id_usuario."' ";
	$rs = mysqli_query (conectar(), $sql);
	$totalGastado = 0;
	while ($row = mysqli_fetch_assoc($rs)){
		/*$sql2 = "SELECT money FROM user_money_hotel WHERE id_usuario='".$row['id']."' 
		AND id_hotel='".$id_hotel."' ";
		$rs2 = mysqli_query (conectar(), $sql2);
		$row2 = mysqli_fetch_assoc($rs2);
		foreach ($row2 as $key => $valor){
			$totalGastado += $row2['money'];
		}*/
		$sql2 = "SELECT gasto_total FROM user_hotels 
		WHERE id_usuario='".$row['invitado']."' AND id_hotel='".$id_hotel."' ";
		$rs2 = mysqli_query (conectar(), $sql2);
		$row2 = mysqli_fetch_assoc($rs2);
		$totalGastado += $row2['gasto_total'];
		liberar($rs2);
	}
	liberar($rs);
	return $totalGastado;
}

function obtenerBookingHistory($id_usuario, $id_hotel){
	$arrayBooking = array();
	//-----booking history
	$sql = "SELECT user_checkin.id, chkin_date, chkout_date, noches,
	user_money_hotel.money,
	user_encuestas.rating AS user_rate, user_encuestas.positiveComment, 
	user_encuestas.negativeComment,
	IFNULL (hotel_user_rate.rate, 0) AS hotel_rate
	FROM user_checkin
	INNER JOIN user_money_hotel ON user_money_hotel.id_checkout=user_checkin.id
	LEFT JOIN user_encuestas ON user_encuestas.id_checkout=user_checkin.id
	LEFT JOIN hotel_user_rate ON hotel_user_rate.id_checkout=user_checkin.id
	WHERE user_checkin.id_usuario='".$id_usuario."' 
	AND user_checkin.id_hotel='".$id_hotel."' ";
	//echo '-----------------'.$sql;
	$rs = mysqli_query (conectar(), $sql);
	$i=0;
	while ($row = mysqli_fetch_assoc($rs)){
		foreach ($row as $key=>$valor){
			if($key == 'chkin_date' || $key == 'chkout_date'){
				$arrayBooking[$i][$key] = girarFecha($valor);
			}else if ($key == 'hotel_rate' || $key == 'user_rate'){
				$arrayBooking[$i][$key] = round($valor, 1);
			}else{
				$arrayBooking[$i][$key] = $valor;
			}
		}
		// Redeems
		$sql2 = "SELECT user_cupones.fecha_canj, voucher, 
		case when oferta_lang.nombre is null 
        then  oferta_en.nombre 
        else oferta_lang.nombre end as nombre_oferta,
		hotel_oferta.puntos, hotel_oferta.id
		FROM user_cupones
		INNER JOIN hotel_oferta ON hotel_oferta.id=user_cupones.id_oferta
		LEFT JOIN hotel_oferta_lang as oferta_en on hotel_oferta.id = oferta_en.id_oferta  and oferta_en.lang='en' 
        LEFT JOIN hotel_oferta_lang as oferta_lang on hotel_oferta.id = oferta_lang.id_oferta and oferta_lang.lang='".$_SESSION['userNavLang'] . "'  
		WHERE user_cupones.id_usuario='".$id_usuario."' 
		AND hotel_oferta.id_hotel='".$id_hotel."' 
		AND fecha_canj BETWEEN '".$row['chkin_date']."' AND '".$row['chkout_date']."' ";
		$rs2 = mysqli_query (conectar(), $sql2);
		$t=0;
		while ($row2 = mysqli_fetch_assoc($rs2)){
			foreach ($row2 as $key=>$valor){
				if($key == 'fecha_canj'){
					$arrayBooking[$i]['redeems'][$t][$key] = girarFecha(substr($valor, 0, 10));
				}else if($key == 'nombre_oferta'){
					$arrayBooking[$i]['redeems'][$t][$key] = $valor;
					$arrayBooking[$i]['redeems'][$t][$key.'_san'] = string_sanitize($valor);
				}else{
					$arrayBooking[$i]['redeems'][$t][$key] = $valor;
				}
			}
			$t++;
		}
		liberar($rs2);
		$i++;
	}
	liberar ($rs);
	return $arrayBooking;
}

function obtenerComentariosHoteles($id_usuario){
	$arrayComentarioHotel = array();
	$sql = "SELECT rate, coment, fecha,
	hoteles.hotelName, hoteles.logo, hoteles.id
	FROM hotel_user_rate 
	INNER JOIN hoteles ON hoteles.id=hotel_user_rate.id_hotel
	WHERE id_usuario='".$id_usuario."' ORDER BY fecha DESC";
	$rs = mysqli_query (conectar(), $sql);
	$i=0;
	while ($row = mysqli_fetch_assoc($rs)){
		foreach ($row as $key=>$valor){
			if($key == 'fecha'){
				$arrayComentarioHotel[$i][$key] = girarFecha($valor);
			}else if ($key == 'hotelName'){
				$arrayComentarioHotel[$i][$key] = $valor;
				$arrayComentarioHotel[$i][$key.'_san'] = string_sanitize($valor);
			}else if ($key == 'rate' || $key == 'user_rate'){
				$arrayComentarioHotel[$i][$key] = round($valor, 1);
			}else{
				$arrayComentarioHotel[$i][$key] = $valor;
			}
		}
		$i++;
	}
	liberar ($rs);
	return $arrayComentarioHotel;
}

function userUserFollow($id_seguidor, $id_seguido){
	$sql2 = "SELECT id FROM user_user_follow WHERE id_seguidor='".$id_seguidor."' 
	AND id_seguido='".$id_seguido."' ";
	$rs2 = mysqli_query (conectar(), $sql2);
	$n_resultados = mysqli_num_rows($rs2);
	if ($n_resultados==0){
		$sql3 = "INSERT INTO user_user_follow (id_seguidor, id_seguido, follow) 
		VALUES ('".$id_seguidor."', '".$id_seguido."', '1')";
		mysqli_query (conectar(), $sql3);
	}else{
		$sql3 = "UPDATE user_user_follow SET follow=1 WHERE id_seguidor='".$id_seguidor."'
		 AND id_seguido=''".$id_seguido." ";
		mysqli_query (conectar(), $sql3);
	}
	liberar($rs2);
}
?>