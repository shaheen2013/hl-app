<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

include_once LIB.'sanitize.php';

function obtenerRetVSAdq($id_hotel){
	$sql = "SELECT
	(SELECT COUNT(adq_ret) FROM hotel_oferta 
	WHERE id_hotel='".$id_hotel."' AND adq_ret='adq') AS adq,
	(SELECT COUNT(adq_ret) FROM hotel_oferta
	WHERE id_hotel='".$id_hotel."' AND adq_ret='ret') AS ret";
	$rs = mysqli_query (conectar(1), $sql);
	$row = mysqli_fetch_assoc($rs);
	$total = $row['adq']+$row['ret'];
	if($row['adq']!='0'){
		$retVSAdq['adq'] = number_format($row['adq']*100/$total, 1);
		if($retVSAdq['adq']=='100.0'){
			$retVSAdq['adq'] = '100';
		}
	}else{
		$retVSAdq['adq'] = 0;
	}
	if($row['ret']!='0'){
		$retVSAdq['ret'] = number_format($row['ret']*100/$total, 1);
		if($retVSAdq['ret']=='100.0'){
			$retVSAdq['ret'] = '100';
		}
	}else{
		$retVSAdq['ret'] = 0;
	}
	return $retVSAdq;
}

function totalCampaignsCreated($id_hotel){
	$sql = "SELECT COUNT(id) AS numCampaings FROM hotel_oferta
	WHERE id_hotel='".$id_hotel."' ";
	$rs = mysqli_query (conectar(1), $sql);
	$row = mysqli_fetch_assoc($rs);
	return $row['numCampaings'];
}

/*function averageTime ($time){
	$total_time=0;
	foreach($time as $t){
		 $total_time+=strtotime($t)-1373241600;
	}
	$average_time = ($total_time/count($time))/3600;
	$average_time = gmdate('H:i', $average_time);
	return $average_time;
}*/

function checkInOffersPreferredHour($id_hotel){
	$hora = 'XX:XX';
	$sql = "SELECT fecha
	FROM hotel_oferta
	INNER JOIN user_cupones ON user_cupones.id_oferta=hotel_oferta.id
	WHERE id_hotel='".$id_hotel."' AND id_tipo_oferta='chk' ";
	//echo $sql;
	$rs = mysqli_query (conectar(1), $sql);
	while ($row = mysqli_fetch_assoc($rs)){
		$arrayHoras[] = substr($row['fecha'], 11, -3);
	}
	liberar($rs);
	//$hora = averageTime($arrayHoras); // media de horas (NO)
	if(!empty($arrayHoras)){
		$countHoras = array_count_values($arrayHoras);
		arsort($countHoras);
		$hora = reset(array_flip($countHoras)); //Devuelve la hora mas repetida
	}
	return $hora;
}

function totalCampaignsByMonth($id_hotel){
	$meses[0] = resetArrayMeses();
	$meses[1] = resetArrayMeses();
	$sql = "SELECT fecha_publicada
	FROM hotel_oferta
	WHERE id_hotel='".$id_hotel."' ORDER BY fecha_publicada ASC";
	//echo $sql;
	$rs = mysqli_query (conectar(1), $sql);
	while($row = mysqli_fetch_assoc($rs)){
		$array_fecha = explode ("-", $row['fecha_publicada']);
		$mes = $array_fecha[1];	
		$anyo = $array_fecha[0];	
		if($mes!=0){
			if($anyo==date("Y")){
				$meses[0][$mes-1] ++;
			}else if ($anyo==date("Y")-1){
				$meses[1][$mes-1] ++;
			}
			//$meses[$anyo][$mes-1] ++;
		}
	}
	liberar($rs);
	$meses[0] = quitarUltimosMesesZero($meses[0]);
	$meses[1] = quitarUltimosMesesZero($meses[1]);
	return $meses;
}

function anoActualAnterior(){
	$anoActualAnterior[0] = date("Y");
	$anoActualAnterior[1] = date("Y")-1;
	return $anoActualAnterior;
}

function retentionVSAdquisitionByMonth($id_hotel){
	$sql1 = $sql2 = "SELECT ";
	$i=1;
	while($i <= 12){
		$fecha_inf[$i] = date("Y").'-'.$i.'-01 00:00:00';
		$fecha_sup[$i] = date("Y").'-'.$i.'-31 23:59:59';
		$sql1 .= "(SELECT COUNT(adq_ret) FROM hotel_oferta 
		WHERE id_hotel='".$id_hotel."' AND adq_ret='adq'
		AND fecha_publicada BETWEEN '".$fecha_inf[$i]."' AND '".$fecha_sup[$i]."') 
		AS m".$i.", ";
		$sql2 .= "(SELECT COUNT(adq_ret) FROM hotel_oferta 
		WHERE id_hotel='".$id_hotel."' AND adq_ret='ret'
		AND fecha_publicada BETWEEN '".$fecha_inf[$i]."' AND '".$fecha_sup[$i]."') 
		AS m".$i.", ";
		$i++;
	}
	$sql1 = substr($sql1,0, -2);
	$sql2 = substr($sql2,0, -2);
	//echo $sql1;

	$rs1 = mysqli_query (conectar(1), $sql1);
	$row1 = mysqli_fetch_assoc($rs1);
	liberar($rs1);
	$rs2 = mysqli_query (conectar(1), $sql2);
	$row2 = mysqli_fetch_assoc($rs2);
	liberar($rs2);
	foreach($row1 as $key => $value){
		$arrayRetAdq['adq'][] = $value;
	}
	foreach($row2 as $key => $value){
		$arrayRetAdq['ret'][] = $value;
	}
	$arrayRetAdq['adq'] = quitarUltimosMesesZero($arrayRetAdq['adq']);
	$arrayRetAdq['ret'] = quitarUltimosMesesZero($arrayRetAdq['ret']);
	return $arrayRetAdq;
}

function campaignsByCategory($id_hotel){
	$arrayCategorias = array();
	$sql = "SELECT categoria_".$_SESSION['userLang']." AS categoria, COUNT(user_cupones.id) AS n
	FROM hotel_oferta
	INNER JOIN categoria_oferta 
	ON categoria_oferta.id_categoria_oferta=hotel_oferta.id_categoria
	LEFT JOIN user_cupones ON user_cupones.id_oferta=hotel_oferta.id
	WHERE id_hotel='".$id_hotel."' 
	GROUP BY id_categoria ORDER BY n DESC";
	//echo $sql;
	$rs = mysqli_query (conectar(1), $sql);
	while ($row = mysqli_fetch_assoc($rs)){
		$arrayCategorias[$row['categoria']]=$row['n'];
	}
	liberar($rs);
	return $arrayCategorias;
}

//Most campaigns redeemed by generation
function mostCampRedByGen($id_hotel){
	$sql = "SELECT categoria_".$_SESSION['userLang']." AS categoria,
	TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) AS edad
	FROM hotel_oferta
	INNER JOIN categoria_oferta 
	ON categoria_oferta.id_categoria_oferta=hotel_oferta.id_categoria
	INNER JOIN user_cupones ON user_cupones.id_oferta=hotel_oferta.id
	INNER JOIN users ON users.id=user_cupones.id_usuario
	WHERE id_hotel='".$id_hotel."' ";
	//echo $sql;
	// Inicializar array
	$sql2= "SELECT categoria_".$_SESSION['userLang']." AS categoria FROM categoria_oferta";
	$rs2 = mysqli_query (conectar(1), $sql2);
	while ($row2 = mysqli_fetch_assoc($rs2)){
		//$arrayCampRedByGen[$row2['categoria']]['1-18']=0;
		$arrayCampRedByGen[$row2['categoria']]['18-23']=0;
		$arrayCampRedByGen[$row2['categoria']]['24-36']=0;
		$arrayCampRedByGen[$row2['categoria']]['37-48']=0;
		$arrayCampRedByGen[$row2['categoria']]['49-67']=0;
		$arrayCampRedByGen[$row2['categoria']]['+68']=0;
	}
	liberar($rs2);
	
	$rs = mysqli_query (conectar(1), $sql);
	while ($row = mysqli_fetch_assoc($rs)){
		if ($row['edad']==0){ 
			//NULL
		}elseif ($row['edad']<=18){
			//$arrayCampRedByGen[$row['categoria']]['1-18']++;
		}elseif ($row['edad']<=23){
			$arrayCampRedByGen[$row['categoria']]['18-23']++;	
		}else if ($row['edad']<=36){
			$arrayCampRedByGen[$row['categoria']]['24-36']++;
		}else if ($row['edad']<=48){
			$arrayCampRedByGen[$row['categoria']]['37-48']++;
		}else if ($row['edad']<=67){
			$arrayCampRedByGen[$row['categoria']]['49-67']++;
		}else{
			$arrayCampRedByGen[$row['categoria']]['+68']++;
		}
	}
	return $arrayCampRedByGen;
}

function mostRedeemedCampaignsLastMonth($id_hotel){
	$arrayMostRedeemed = array();
	$sql = "SELECT hotel_oferta.id, 
	case when oferta_lang.nombre is null 
    then  oferta_en.nombre 
    else oferta_lang.nombre end as nombre,
	COUNT(hotel_oferta.id) AS n, 
	adq_ret AS method, categoria_".$_SESSION['userLang']." AS categoria, inicio, fin
	FROM hotel_oferta
	LEFT JOIN hotel_oferta_lang as oferta_en on hotel_oferta.id = oferta_en.id_oferta  and oferta_en.lang='en' 
    LEFT JOIN hotel_oferta_lang as oferta_lang on hotel_oferta.id = oferta_lang.id_oferta and oferta_lang.lang='".$_SESSION['userNavLang'] . "'  
	INNER JOIN user_cupones ON user_cupones.id_oferta=hotel_oferta.id
	INNER JOIN categoria_oferta 
	ON categoria_oferta.id_categoria_oferta=hotel_oferta.id_categoria
	WHERE id_hotel='".$id_hotel."' GROUP BY nombre 
	ORDER BY n DESC LIMIT 10";
	//echo $sql;
	$rs = mysqli_query (conectar(1), $sql);
	$i=0;
	while ($row = mysqli_fetch_assoc($rs)){
		foreach ($row as $key=>$valor){
			if($key=='inicio' || $key=='fin'){
				if ($valor=='0000-00-00'){
					$arrayMostRedeemed[$i][$key] = '-';
				}else{
					$arrayMostRedeemed[$i][$key] = girarFecha($valor);
				}
			}else if($key=='nombre'){
				$arrayMostRedeemed[$i][$key] = $valor;
				$arrayMostRedeemed[$i][$key.'_san'] = string_sanitize($valor);
			}else{
				$arrayMostRedeemed[$i][$key] = $valor;
			}
		}
		$i++;
	}
	return $arrayMostRedeemed;
}

function mostWishlisted($id_hotel){
	$arrayMostWishlisted = array();
	$sql = "SELECT hotel_oferta.id, 
	case when oferta_lang.nombre is null 
    then  oferta_en.nombre 
    else oferta_lang.nombre end as nombre,
	COUNT(hotel_oferta.id) AS n, 
	adq_ret AS method, categoria_".$_SESSION['userLang']." AS categoria, inicio, fin
	FROM hotel_oferta
	INNER JOIN user_wishlist ON user_wishlist.id_oferta=hotel_oferta.id
	LEFT JOIN hotel_oferta_lang as oferta_en on hotel_oferta.id = oferta_en.id_oferta  and oferta_en.lang='en' 
    LEFT JOIN hotel_oferta_lang as oferta_lang on hotel_oferta.id = oferta_lang.id_oferta and oferta_lang.lang='".$_SESSION['userNavLang'] . "'
	INNER JOIN categoria_oferta 
	ON categoria_oferta.id_categoria_oferta=hotel_oferta.id_categoria
	WHERE id_hotel='".$id_hotel."' GROUP BY nombre 
	ORDER BY n DESC LIMIT 10";
	//echo $sql;
	$rs = mysqli_query (conectar(1), $sql);
	$i=0;
	while ($row = mysqli_fetch_assoc($rs)){
		foreach ($row as $key=>$valor){
			if($key=='inicio' || $key=='fin'){
				if ($valor=='0000-00-00'){
					$arrayMostWishlisted[$i][$key] = '-';
				}else{
					$arrayMostWishlisted[$i][$key] = girarFecha($valor);
				}
			}else if($key=='nombre'){
				$arrayMostWishlisted[$i][$key] = $valor;
				$arrayMostWishlisted[$i][$key.'_san'] = string_sanitize($valor);
			}else{
				$arrayMostWishlisted[$i][$key] = $valor;
			}
		}
		$i++;
	}
	return $arrayMostWishlisted;
}
?>