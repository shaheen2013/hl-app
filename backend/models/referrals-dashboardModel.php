<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

include_once LIB.'sanitize.php';

function checkinsReferrals($id_hotel){
	$checkinsReferrals=resetArrayMeses();
	$fecha_inf = date("Y").'-01-01';
	$fecha_sup = date("Y").'-12-31';
	$sql = "SELECT chkin_date FROM referrer_users
	INNER JOIN user_checkin ON user_checkin.id_usuario=referrer_users.invitado
	WHERE user_checkin.id_hotel='".$id_hotel."'
	AND chkin_date BETWEEN '".$fecha_inf."' AND '".$fecha_sup."' ";
	//echo $sql;
	$rs = mysqli_query (conectar(1), $sql);
	while($row = mysqli_fetch_assoc($rs)){
		$array_fecha = explode ("-", $row['chkin_date']);
		$mes = $array_fecha[1];	
		$checkinsReferrals[$mes-1]++;
	}
	liberar($rs);
	$checkinsReferrals = quitarUltimosMesesZero($checkinsReferrals);
	return $checkinsReferrals;
}

function landingConversionMonth($id_hotel){
	$landingConversion=resetArrayMeses();
	$fecha_inf = date("Y").'-01-01';
	$fecha_sup = date("Y").'-12-31';
	$sql = "SELECT DATE(created) as created FROM referrer_users
	INNER JOIN users ON users.id=referrer_users.invitado
	INNER JOIN user_hotels ON user_hotels.id_usuario=referrer_users.invitado
	WHERE user_hotels.id_hotel='".$id_hotel."'
	AND created BETWEEN '".$fecha_inf."' AND '".$fecha_sup."' ";
	$rs = mysqli_query (conectar(1), $sql);
	while($row = mysqli_fetch_assoc($rs)){
		$array_fecha = explode ("-", $row['created']);
		$mes = $array_fecha[1];	
		$landingConversion[$mes-1]++;
	}
	liberar($rs);
	$landingConversion = quitarUltimosMesesZero($landingConversion);
	return $landingConversion;
}

function referralsBySocialMedia($id_hotel){
	$arrayReferrals = array();
	$sql2 = "SELECT SUM(visitas) AS total_visitas
	FROM referrer_tokens_ips
	INNER JOIN referrer_tokens ON referrer_tokens.id=referrer_tokens_ips.id_token
	INNER JOIN user_encuestas ON user_encuestas.id=referrer_tokens.id_encuesta
	WHERE id_hotel='".$id_hotel."' ";
	$rs2 = mysqli_query (conectar(1), $sql2);
	$row2 = mysqli_fetch_assoc($rs2);
	liberar($rs2);
	$sql = "SELECT social_media, SUM(visitas) AS visitas
	FROM user_hotels
	INNER JOIN referrer_users ON referrer_users.invitado=user_hotels.id_usuario
	INNER JOIN user_encuestas ON user_encuestas.id_usuario=referrer_users.invitador
	INNER JOIN referrer_tokens ON referrer_tokens.id_encuesta=user_encuestas.id
	INNER JOIN referrer_tokens_ips ON referrer_tokens_ips.id_token=referrer_tokens.id
	WHERE user_hotels.id_hotel='".$id_hotel."' AND social_media!='-' 
	GROUP BY social_media";
	$rs = mysqli_query (conectar(), $sql);
	while($row = mysqli_fetch_assoc($rs)){
		$arrayReferrals[$row['social_media']]=number_format($row['visitas']*100/$row2['total_visitas'], 1);
	}
	liberar($rs);
	return $arrayReferrals;
}

function landingConversion($id_hotel){
	$sql = "SELECT COUNT(users.id) AS n FROM referrer_users
	INNER JOIN users ON users.id=referrer_users.invitado
	INNER JOIN user_hotels ON user_hotels.id_usuario=referrer_users.invitado
	WHERE user_hotels.id_hotel='".$id_hotel."' AND referrer_users.id_hotel='".$id_hotel."'";
	$rs = mysqli_query (conectar(1), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	return $row['n'];
}

function top10Referrers($id_hotel){
	$top10Referrers = array();
	$sql = "SELECT users.id, nombre, users.location, 
	COUNT(referrer_users.id) AS referrals,
	IFNULL(user_twitter.twitter_img,0) AS img
	FROM users
	INNER JOIN referrer_users ON referrer_users.invitador=users.id
	INNER JOIN user_hotels ON user_hotels.id_usuario=users.id
	LEFT JOIN user_twitter ON user_twitter.id_usuario=users.id
	WHERE user_hotels.id_hotel='".$id_hotel."'  AND referrer_users.id_hotel='".$id_hotel."'
	 GROUP BY user_hotels.id_usuario ORDER BY referrals";
	//echo $sql;
	$rs = mysqli_query (conectar(1), $sql);
	$i=0;
	while ($row = mysqli_fetch_assoc($rs)){
		foreach ($row as $key=>$valor){
			$top10Referrers[$i][$key] = $valor;
			if($key=='nombre'){
				$top10Referrers[$i][$key.'_san'] = string_sanitize($valor);
			}
		}
		$top10Referrers[$i]['urlGuid'] = obtenerUrlGUIDUsario($row['id']);
		$i++;
	}
	return $top10Referrers;
}

function top10referrersByEarnings($id_hotel){
	$sql = "SELECT users.id, nombre, users.location, SUM(money) AS earnings,
	IFNULL(user_twitter.twitter_img,0) AS img
	FROM users
	INNER JOIN referrer_users ON referrer_users.invitador=users.id
	INNER JOIN user_hotels ON user_hotels.id_usuario=users.id
	INNER JOIN user_money_hotel ON user_money_hotel.id_usuario=referrer_users.invitado
	LEFT JOIN user_twitter ON user_twitter.id_usuario=users.id
	WHERE user_hotels.id_hotel='".$id_hotel."' 
	AND user_money_hotel.id_hotel='".$id_hotel."' ORDER BY earnings";
	//echo $sql;
	$rs = mysqli_query (conectar(1), $sql);
	$i=0;
	while ($row = mysqli_fetch_assoc($rs)){
		foreach ($row as $key=>$valor){
			$top10referrersByEarnings[$i][$key] = $valor;
			if($key=='nombre'){
				$top10referrersByEarnings[$i][$key.'_san'] = string_sanitize($valor);
			}
			$top10referrersByEarnings[$i]['urlGuid'] = obtenerUrlGUIDUsario($row['id']);
		}
		$i++;
	}
	return $top10referrersByEarnings;
}
?>