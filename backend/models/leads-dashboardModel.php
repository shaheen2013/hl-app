<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

function obtenerFunnel($id_hotel){
	$sql = "SELECT 
	SUM(referrer_tokens.visitas) AS visitors,
	COUNT(referrer_users.id) AS referralDB,
	COUNT(DISTINCT user_checkin.id) AS referralChkin
	FROM referrer_tokens
	INNER JOIN user_encuestas ON user_encuestas.id=referrer_tokens.id_encuesta
	INNER JOIN referrer_users ON referrer_users.id_encuesta=referrer_tokens.id_encuesta
	INNER JOIN user_checkin ON user_checkin.id_usuario=referrer_users.invitado
	WHERE user_encuestas.id_hotel='".$id_hotel."' ";
	//echo $sql;
	$rs = mysqli_query (conectar(1), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	return $row;
}

function resetArray(){
	$i=0;
	while($i <=11){
		$array['landing'][$i]=0;
		$array['hotel'][$i]=0;
		$array['offer'][$i]=0;
		$array['total'][$i]=0;
		$i++;
	}
	return $array;
}

function leadsByMonth($id_hotel){
	$leadsByMonth = resetArray();
	$fecha_inf = date("Y").'-01-01';
	$fecha_sup = date("Y").'-12-31';
	$sql1 = "SELECT COUNT(referrer_tokens_ips.id) AS visitas, referrer_tokens_ips.fecha 
	FROM referrer_tokens_ips 
	INNER JOIN referrer_tokens ON referrer_tokens.id=referrer_tokens_ips.id_token
	INNER JOIN user_encuestas ON user_encuestas.id=referrer_tokens.id_encuesta
	WHERE user_encuestas.id_hotel='".$id_hotel."' AND social_media!='-'
	AND referrer_tokens_ips.fecha BETWEEN '".$fecha_inf."' AND '".$fecha_sup."'
	GROUP BY YEAR(referrer_tokens_ips.fecha), MONTH(referrer_tokens_ips.fecha)";
	//echo $sql1;
	$rs1 = mysqli_query (conectar(1), $sql1);
	while ($row1 = mysqli_fetch_assoc($rs1)){
		$array_fecha = explode ("-", $row1['fecha']);
		$mes = $array_fecha[1];	
		$leadsByMonth['landing'][$mes-1]+=$row1['visitas'];
		$leadsByMonth['total'][$mes-1]+=$row1['visitas'];
	}
	liberar($rs1);
	$sql2 = "SELECT COUNT(id) AS visitas, DATE(fecha) AS fecha
	FROM hotel_page_ips WHERE id_hotel='".$id_hotel."'
	AND fecha BETWEEN '".$fecha_inf."' AND '".$fecha_sup."'
	GROUP BY YEAR(fecha), MONTH(fecha)";
	//echo '<br />'.$sql2;
	$rs2 = mysqli_query (conectar(1), $sql2);
	while ($row2 = mysqli_fetch_assoc($rs2)){
		$array_fecha = explode ("-", $row2['fecha']);
		$mes = $array_fecha[1];	
		$leadsByMonth['hotel'][$mes-1]+=$row2['visitas'];
		$leadsByMonth['total'][$mes-1]+=$row2['visitas'];
	}
	liberar($rs2);
	$sql3 = "SELECT COUNT(oferta_page_ips.id) AS visitas, DATE(fecha) AS fecha 
	FROM oferta_page_ips 
	INNER JOIN hotel_oferta ON hotel_oferta.id=oferta_page_ips.id_oferta
	WHERE hotel_oferta.id_hotel='".$id_hotel."' 
	AND fecha BETWEEN '".$fecha_inf."' AND '".$fecha_sup."'
	GROUP BY YEAR(fecha), MONTH(fecha)";
	//echo '<br />'.$sql3;
	$rs3 = mysqli_query (conectar(1), $sql3);
	while ($row3 = mysqli_fetch_assoc($rs3)){
		$array_fecha = explode ("-", $row3['fecha']);
		$mes = $array_fecha[1];	
		$leadsByMonth['offer'][$mes-1]+=$row3['visitas'];
		$leadsByMonth['total'][$mes-1]+=$row3['visitas'];
	}
	liberar($rs3);
	$leadsByMonth['landing'] = quitarUltimosMesesZero($leadsByMonth['landing']);
	$leadsByMonth['hotel'] = quitarUltimosMesesZero($leadsByMonth['hotel']);
	$leadsByMonth['offer'] = quitarUltimosMesesZero($leadsByMonth['offer']);
	$leadsByMonth['total'] = quitarUltimosMesesZero($leadsByMonth['total']);
	return $leadsByMonth;
	
}
?>