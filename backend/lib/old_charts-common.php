<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

// Recibe un num y un campo de la tabla hotel_charts_data
function obtenerVariacion($num, $campo){
	$sql = "SELECT ".$campo." FROM hotel_charts_data 
	WHERE id_hotel='".$_SESSION['h_logueado']."' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	// Devuelve si sube, baja o se queda igual
	if ($num < $row[$campo]){ // Ha bajado
		$variacion = 1;
	}else if($num > $row[$campo]){ // Ha subido
		$variacion = 2;
	}else{// Son iguales
		$variacion = 0;
	}
	// Inserta la ultima consulta
	$sql2 = "UPDATE hotel_charts_data SET ".$campo."='".$num."'
	WHERE id_hotel='".$_SESSION['h_logueado']."' ";
	mysqli_query (conectar(), $sql2);
	return $variacion;	
}
// Quitamos los ultimos meses en 0 hasta el mes actual
function quitarUltimosMesesZero($arrayMeses){
	$i=11;
	while ($i >= date('m') && $arrayMeses[$i]==0){
		unset($arrayMeses[$i]);
		$i--;
	}
	return $arrayMeses;
}

function resetArrayMeses(){
	$i=0;
	while ($i <= 11){
		$arrayMeses[$i]=0;
		$i++;
	}
	return $arrayMeses;
}

//-------------------------------------------
function reputationByAge($id_hotel){
	$fecha_inf = date("Y").'-01-01 00:00:00';
	$fecha_sup = date("Y").'-12-31 23:59:59';
	
	$sql = "SELECT edad, rating FROM user_encuestas WHERE id_hotel='".$id_hotel."' AND fecha BETWEEN '".$fecha_inf."' AND '".$fecha_sup."'";
	//echo $sql;
	$rs = mysqli_query (conectar(), $sql);
	$ratings018=$ratingscount018=$ratings1930=$ratingscount1930=$ratings3155=$ratingscount3155=$ratings56=$ratingscount56=0;
	while($row = mysqli_fetch_assoc($rs)){
		if ($row['edad']==0){
		}elseif ($row['edad']<=18){
			$ratings018 += $row['rating'];
			$ratingscount018 ++;
		}else if ($row['edad']<=30){
			$ratings1930 += $row['rating'];
			$ratingscount1930 ++;
		}else if ($row['edad']<=55){
			$ratings3155 += $row['rating'];
			$ratingscount3155 ++;
		}else{
			$ratings56 += $row['rating'];
			$ratingscount56 ++;
		}	
	}
	if ($ratings018 != 0){
		$reputationByAge[0] = round($ratings018 / $ratingscount018, 1);
	}else{
		$reputationByAge[0]=0;
	}
	if ($ratingscount1930 != 0){
		$reputationByAge[1] = round($ratings1930 / $ratingscount1930, 1);
	}else{
		$reputationByAge[1]=0;
	}
	if ($ratingscount3155 != 0){
		$reputationByAge[2] = round($ratings3155 / $ratingscount3155, 1);
	}else{
		$reputationByAge[2]=0;
	}
	if ($ratingscount56 != 0){
		$reputationByAge[3] = round($ratings56 / $ratingscount56, 1);
	}else{
		$reputationByAge[3]=0;
	}
	liberar($rs);
	return $reputationByAge;
}

function socialMediaReach($id_hotel){
	$sql = "SELECT COUNT(user_shares.id)*twitter_followers AS smReach, 
	users.id 
	FROM user_shares
	INNER JOIN users ON users.id=user_shares.id_usuario
	INNER JOIN user_twitter ON user_twitter.id_usuario=user_shares.id_usuario
	WHERE id_hotel='".$id_hotel."' GROUP BY users.id";
	$socialMediaReach = 0;
	$rs = mysqli_query (conectar(), $sql);
	while($row = mysqli_fetch_assoc($rs)){
		$socialMediaReach += $row['smReach'];
	}
	liberar($rs);
	$arraySocialMediaReach[] = $socialMediaReach;
	$arraySocialMediaReach[] = obtenerVariacion($socialMediaReach, 'socialMediaReach');
	return $arraySocialMediaReach;
}

function ratingProgression($id_hotel){
	//Reset ratings
	$i=0;
	while ($i <= 11){
		$ratings[$i]=0;
		$ratingscount[$i]=0;
		$mediaRatings[$i]=0;
		$i++;
	}
	$fecha_inf = date("Y").'-01-01 00:00:00';
	$fecha_sup = date("Y").'-12-31 23:59:59';
	
	$sql= "SELECT ROUND(SUM(rating),1) AS sumRatings, COUNT(rating) AS nRatings
	, DATE(fecha) AS fecha FROM user_encuestas 
	WHERE id_hotel='".$id_hotel."' AND done=1
	AND fecha BETWEEN '".$fecha_inf."' AND '".$fecha_sup."'";
	$sql .= " GROUP BY YEAR(fecha), MONTH(fecha) ORDER BY fecha ASC";
	//echo $sql;
	$rs = mysqli_query (conectar(), $sql);
	$ratingsTotal=$ratingsTotalCount=0;
	while($row = mysqli_fetch_assoc($rs)){
		$array_fecha = explode ("-", $row['fecha']);
		$mes = $array_fecha[1];	
		$ratingsTotal+=$row['sumRatings'];
		$ratingsTotalCount+=$row['nRatings'];
		$ratings[$mes-1] += number_format($ratingsTotal/$ratingsTotalCount,1);
	}
	liberar($rs);
	$i=1;
	while($i <= date("m")-1){// rellenar ratings intermedios que sean 0
		if($ratings[$i]==0 && $ratings[$i-1]!=0){
			$ratings[$i]=$ratings[$i-1];
		}
		$i++;
	}
	$ratings = quitarUltimosMesesZero($ratings);
	return ($ratings);
}
?>