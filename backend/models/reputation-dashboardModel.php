<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

function surveysVSCheckouts($id_hotel){
	$sql = "SELECT 
	(SELECT COUNT(id) AS n FROM user_encuestas
	WHERE id_hotel='".$id_hotel."' AND id_checkout!=0) AS checkouts,
	(SELECT COUNT(id) AS n FROM user_encuestas
	WHERE id_hotel='".$id_hotel."' AND id_checkout!=0 AND done='1') AS surveys";
	$rs = mysqli_query (conectar(1), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	if($row['checkouts']!='0'){
		$surveysVSCheckouts[0]=number_format($row['surveys']*100/$row['checkouts'], 0);
	}else{
		$surveysVSCheckouts[0]=0;
	}
	$surveysVSCheckouts[1]=100-$surveysVSCheckouts[0];
	return $surveysVSCheckouts;
}

function socialMediaConversion($id_hotel){
	$sql = "SELECT SUM(visitas) AS visitas, COUNT(referrer_users.id) AS converted
	FROM referrer_tokens
	INNER JOIN user_encuestas ON user_encuestas.id=referrer_tokens.id_encuesta
	INNER JOIN referrer_users ON referrer_users.id_encuesta=user_encuestas.id
	WHERE user_encuestas.id_hotel='".$id_hotel."' AND done='1' ";
	//echo $sql;
	$rs = mysqli_query (conectar(1), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	if($row['visitas']!=0){
		$smConversion = number_format($row['converted']*100/$row['visitas'],0);
	}else{
		$smConversion = 0;
	}
	return $smConversion;
}

function surveysProgression($id_hotel){
	$i=0;
	while ($i <= 11){
		$surveysProgression[$i]=0;
		$i++;
	}
	$fecha_inf = date("Y").'-01-01 00:00:00';
	$fecha_sup = date("Y").'-12-31 23:59:59';
	$sql = "SELECT DATE(fecha) AS fecha FROM user_encuestas
	WHERE id_hotel='".$id_hotel."' AND done='1' 
	AND fecha BETWEEN '".$fecha_inf."' AND '".$fecha_sup."' ORDER BY fecha";
	$rs = mysqli_query (conectar(1), $sql);
	while ($row = mysqli_fetch_assoc($rs)){
		$array_fecha = explode ("-", $row['fecha']);
		$mes = $array_fecha[1];	
		$surveysProgression[$mes-1]++;
	}
	liberar($rs);
	$surveysProgression = quitarUltimosMesesZero($surveysProgression);
	return $surveysProgression;
}
?>