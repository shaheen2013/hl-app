<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

include_once LIB.'sanitize.php';

function mostrarAlert (){
	$sqlAlert = 'SELECT alert FROM hoteles WHERE id="'.$_SESSION['h_logueado'].'"';
	$rowAlert = mysqli_query (conectar(), $sqlAlert);
	$rsAlert = mysqli_fetch_array($rowAlert);

	if ($rsAlert['alert'] == 1){
		return (false);
	}else{
		return (true);
	}
	liberar($rowAlert);
}

function mostrarModal (){
	$sqlModal = 'SELECT modal FROM hoteles WHERE id="'.$_SESSION['h_logueado'].'"';
	$rowModal = mysqli_query (conectar(), $sqlModal);
	$rsModal = mysqli_fetch_array($rowModal);

	if ($rsModal['modal'] == 1){
		return (false);
	}else{
		return (true);
	}
	liberar($rowModal);
}

// Devuelve el total guests de un hotel
function totalGuestsHotel($id_hotel){
	$sql = "SELECT COUNT(id_usuario) AS n FROM user_checkin WHERE id_hotel='".$id_hotel."' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	return $row['n'];
}

//-----------------------------------------------------
function newGuestsProgression($id_hotel){
	$usuarios = resetArrayMeses();
	$fecha_inf = date("Y").'-01-01 00:00:00';
	$fecha_sup = date("Y").'-12-31 23:59:59';
	
	$sql = "SELECT users.id, created AS fecha FROM user_hotels
	INNER JOIN users ON users.id=user_hotels.id_usuario
	WHERE id_hotel='".$id_hotel."' 
	AND created BETWEEN '".$fecha_inf."' AND '".$fecha_sup."'";
	$rs = mysqli_query (conectar(), $sql);
	//echo $sql;
	while($row = mysqli_fetch_assoc($rs)){
		$array_fecha = explode ("-", $row['fecha']);
		$mes = $array_fecha[1];	
		$usuarios[$mes-1]++;
	}
	$usuarios = quitarUltimosMesesZero($usuarios);
	$meses = count($usuarios); // Total meses a mostrar
	$i=0;$totalUsuarios=array();
	while ($i < $meses){
		$t=$i;$totalUsuarios[$i]=0;
		while ($t > 0){
			$totalUsuarios[$i] += $usuarios[$t];
			$t--;
		}
		$i++;
	}
	liberar($rs);
	//$totalUsuarios = quitarUltimosMesesZero($totalUsuarios);
	return $totalUsuarios;
}

//User adquisition Semana
function diferencialUsuariosSemana(){
	$numeroSemana = date("W"); 
	$año = date("Y");
	$usuariosSemana=0;
	$sql="SELECT id_usuario FROM user_hotels WHERE id_hotel='".$_SESSION['h_logueado']."' ";
	$rs = mysqli_query (conectar(), $sql);
	while ($row = mysqli_fetch_assoc($rs)){
		$sql2 = "SELECT created FROM users WHERE id ='".$row['id_usuario']."' "; 
		$rs2 = mysqli_query (conectar(), $sql2);
		$row2 = mysqli_fetch_assoc($rs2);
		$semana = date('W', strtotime(substr($row2['created'],0, 10)));
		if($semana == $numeroSemana){
			$usuariosSemana ++;
		}
	}
	if (isset($rs2)){
		liberar ($rs2);
	}
	liberar ($rs);
	
	return ($usuariosSemana);
}

function reputationByGender($id_hotel){
	$fecha_inf = date("Y").'-01-01 00:00:00';
	$fecha_sup = date("Y").'-12-31 23:59:59';
	
	$sql = "SELECT
	IFNULL((SELECT ROUND(SUM(rating)/COUNT(rating),1) FROM user_encuestas WHERE id_hotel='".$id_hotel."' 
	AND sexo='m' AND fecha BETWEEN '".$fecha_inf."' AND '".$fecha_sup."' ),0) AS m,
	IFNULL((SELECT ROUND(SUM(rating)/COUNT(rating),1) FROM user_encuestas WHERE id_hotel='".$id_hotel."' 
	AND sexo='h' AND fecha BETWEEN '".$fecha_inf."' AND '".$fecha_sup."' ),0) AS h	";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	return($row);
}

function overallReputation($id_hotel){
	/*$sql = "SELECT ROUND(SUM(rating),1) AS suma_ratings, COUNT(rating) AS toral_ratings
	FROM user_encuestas
	WHERE id_hotel='".$id_hotel."' AND done=1";
	//echo $sql;
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	if($row['toral_ratings']!=0){
		$overallReputation = number_format($row['suma_ratings']/$row['toral_ratings'],1);
		$arrayOverallReputation[] = $overallReputation;
		$arrayOverallReputation[] = obtenerVariacion($overallReputation, 'overallReputation');	
	}else{
		$arrayOverallReputation[] = 0;
		$arrayOverallReputation[] = 0;
	}*/
	$sql = "SELECT rating FROM hoteles WHERE id='".$id_hotel."' ";
	//echo $sql;
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	$arrayOverallReputation[] = $row['rating'];
	$arrayOverallReputation[] = obtenerVariacion($row['rating'], 'overallReputation');	
	return $arrayOverallReputation;
}

function top10Points($id_hotel){
	$arrayTop10Points = array();
	$sql = "SELECT DISTINCT users.id, nombre, puntos, users.location,
	IFNULL(twitter_img, 0) AS img
	FROM user_hotels
	INNER JOIN users ON users.id=user_hotels.id_usuario 
	LEFT JOIN user_twitter ON user_twitter.id_usuario=users.id ";
	if(hotelDeCadena($id_hotel)){
		$sql .= " INNER JOIN user_points_cadena ON user_points_cadena.id_usuario=users.id ";
	}else{
		$sql .= " INNER JOIN user_points ON user_points.id_usuario=users.id ";
	}
	$sql .= " WHERE id_hotel='".$id_hotel."' GROUP BY users.id ORDER BY puntos 
	DESC LIMIT 10";
	$rs = mysqli_query (conectar(), $sql);
	$i=0;
	while ($row = mysqli_fetch_assoc($rs)){
		foreach ($row as $key=>$valor){
			if($key=='nombre'){
				$arrayTop10Points[$i][$key] = $valor;
				$arrayTop10Points[$i][$key.'_san'] = string_sanitize($valor);
			}else{
				$arrayTop10Points[$i][$key] = $valor;
			}
			$arrayTop10Points[$i]['urlGuid'] = obtenerUrlGUIDUsario($row['id']);
		}
		$i++;
	}
	liberar($rs);
	return $arrayTop10Points;
}

function top10Nights($id_hotel){
	$top10Nights = array();
	$sql = "SELECT DISTINCT users.id, nombre, users.location, total_noches AS noches,
	IFNULL(twitter_img, 0) AS img
	FROM user_hotels
	LEFT JOIN users ON users.id=user_hotels.id_usuario 
	LEFT JOIN user_twitter ON user_twitter.id_usuario=users.id";
	$sql .= " WHERE user_hotels.id_hotel='".$id_hotel."' ORDER BY noches DESC LIMIT 10";
	$rs = mysqli_query (conectar(), $sql);
	$i=0;
	while ($row = mysqli_fetch_assoc($rs)){
		foreach ($row as $key=>$valor){
			if($key=='nombre'){
				$top10Nights[$i][$key] = $valor;
				$top10Nights[$i][$key.'_san'] = string_sanitize($valor);
			}else{
				$top10Nights[$i][$key] = $valor;
			}
			$top10Nights[$i]['urlGuid'] = obtenerUrlGUIDUsario($row['id']);
		}
		$i++;
	}
	liberar($rs);
	return $top10Nights;
}

function top10Checkins($id_hotel){
	$top10Checkins = array();
	$sql = "SELECT DISTINCT users.id, nombre, users.location, total_checkins AS checkins,
	IFNULL(twitter_img, 0) AS img
	FROM user_hotels
	LEFT JOIN users ON users.id=user_hotels.id_usuario 
	LEFT JOIN user_twitter ON user_twitter.id_usuario=users.id";
	$sql .= " WHERE user_hotels.id_hotel='".$id_hotel."' ORDER BY checkins DESC LIMIT 10";
	$rs = mysqli_query (conectar(), $sql);
	$i=0;
	while ($row = mysqli_fetch_assoc($rs)){
		foreach ($row as $key=>$valor){
			if($key=='nombre'){
				$top10Checkins[$i][$key] = $valor;
				$top10Checkins[$i][$key.'_san'] = string_sanitize($valor);
			}else{
				$top10Checkins[$i][$key] = $valor;
			}
			$top10Checkins[$i]['urlGuid'] = obtenerUrlGUIDUsario($row['id']);
		}
		$i++;
	}
	liberar($rs);
	return $top10Checkins;
}

function top10Spendings($id_hotel){
	$top10Spendings = array();
	$sql = "SELECT DISTINCT users.id, nombre, users.location, gasto_total AS spendings,
	IFNULL(twitter_img, 0) AS img
	FROM user_hotels
	LEFT JOIN users ON users.id=user_hotels.id_usuario 
	LEFT JOIN user_twitter ON user_twitter.id_usuario=users.id";
	$sql .= " WHERE user_hotels.id_hotel='".$id_hotel."' ORDER BY spendings DESC LIMIT 10";
	$rs = mysqli_query (conectar(), $sql);
	$i=0;
	while ($row = mysqli_fetch_assoc($rs)){
		foreach ($row as $key=>$valor){
			if($key=='nombre'){
				$top10Spendings[$i][$key] = $valor;
				$top10Spendings[$i][$key.'_san'] = string_sanitize($valor);
			}else{
				$top10Spendings[$i][$key] = $valor;
			}
			$top10Spendings[$i]['urlGuid'] = obtenerUrlGUIDUsario($row['id']);
		}
		$i++;
	}
	liberar($rs);
	return $top10Spendings;
}

function nightsGuest($id_hotel){
	//$sql2 = "SELECT SUM(total_noches) AS total_noches FROM user_hotels WHERE id_hotel='".$id_hotel."' ";// Total noches
	//Solo contamos las noches de checkins, no los de la invitación
	$sql2 = "SELECT SUM(noches) AS total_noches 
	FROM user_checkin WHERE id_hotel='".$id_hotel."' ";// Total noches
	//echo $sql2;
	$rs2 = mysqli_query (conectar(), $sql2);
	$row2 = mysqli_fetch_assoc($rs2);
	liberar($rs2);
	$totalGuests = totalGuestsHotel($id_hotel);
	if($totalGuests!=0){
		$nightsGuest=$arrayNightsGuest[]=round(($row2['total_noches']/$totalGuests), 1);
	}else{
		$nightsGuest=$arrayNightsGuest[]=0;
	}
	$arrayNightsGuest[] = obtenerVariacion($nightsGuest, 'nightsGuest');
	return $arrayNightsGuest;
}

function pointsGuest($id_hotel){
	$sql = "SELECT SUM(puntos) AS total_puntos, COUNT(users.id) AS total_guests ";
	if(hotelDeCadena($id_hotel)){
		$id_cadena = hotelIdCadena($id_hotel);
		$sql .= " FROM user_points_cadena 
		INNER JOIN user_hotels ON user_hotels.id_usuario=user_points_cadena.id_usuario 
		AND user_hotels.id_hotel='".$id_hotel."'
		INNER JOIN users ON users.id=user_points_cadena.id_usuario
		WHERE user_points_cadena.id_cadena='".$id_cadena."' ";// Total puntos
	}else{
		$sql .= " FROM user_points 
		INNER JOIN users ON users.id=user_points.id_usuario
		WHERE id_emisor='".$id_hotel."' ";// Total puntos
	}
	global $log;
	//echo $sql;
    $log->debug($sql);
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	//$totalGuests = totalGuestsHotel($id_hotel);
	if($row['total_guests']!=0){
		$arrayPointsGuest[]=$pointsGuest=number_format($row['total_puntos']/$row['total_guests'], 0);
	}else{
		$arrayPointsGuest[]=$pointsGuest=0;
	}
	$arrayPointsGuest[] = obtenerVariacion($pointsGuest, 'pointsGuest');
	return $arrayPointsGuest;
}

function spentGuest($id_hotel){
	$sql = "SELECT SUM(gasto_total) AS total_spent FROM user_hotels WHERE id_hotel='".$id_hotel."' ";// Total noches
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	$totalGuests = totalGuestsHotel($id_hotel);
	if($totalGuests!=0){
		$arraySpentGuest[]=$spentGuest=number_format($row['total_spent']/$totalGuests, 0);
	}else{
		$arraySpentGuest[]=$spentGuest = 0;
	}
	$arraySpentGuest[] = obtenerVariacion($spentGuest, 'spentGuest');
	return $arraySpentGuest;
}

function todayNewGuests($id_hotel){
	$sql = "SELECT COUNT(users.id) AS usuarios 
	FROM user_hotels
	INNER JOIN users ON users.id=user_hotels.id_usuario
	WHERE id_hotel='".$id_hotel."' AND created = CURRENT_DATE";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	$arrayTodayNewGuests[] = $todayNewGuests = $row['usuarios'];
	$arrayTodayNewGuests[] = obtenerVariacion($todayNewGuests, 'todayNewGuests');
	return $arrayTodayNewGuests;
}

function guestsCountries($id_hotel){
	$ciudades = array();
	// Devolver array con ciudades
	// En un futuro deberá devolver ciudades + cantidad usuarios en esa ciudad
	$sql = "SELECT DISTINCT location
	FROM user_hotels
	INNER JOIN users ON users.id=user_hotels.id_usuario
	WHERE id_hotel='".$id_hotel."' AND location!='' ";
	$rs = mysqli_query (conectar(), $sql);
	while ($row = mysqli_fetch_assoc($rs)){
		$ciudades[]="'".$row['location']."'";
	}
	liberar($rs);
	return $ciudades;
}

function obtenerCiudadHotel($id_hotel){
	$sql = "SELECT city 
	FROM hoteles 
	WHERE id='".$id_hotel."' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	return ($row['city']);
}

function obternerNumOfertas($id_hotel){
	$request = 'SELECT COUNT(hotel_oferta.id) AS n, alert 
	FROM hotel_oferta 
	INNER JOIN hoteles ON hoteles.id=hotel_oferta.id_hotel
	WHERE id_hotel = '.$id_hotel.'';
	$rs = mysqli_query (conectar(), $request);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	if($row['alert'] != 1){
		if($row['n'] >= 10){
			$sql = 'UPDATE hoteles SET alert = 1 WHERE id="'.$id_hotel.'"';
			mysqli_query (conectar(), $sql);
		}
	}
	$ofertas = 10 - $row['n'];
	return $ofertas;
}
?>