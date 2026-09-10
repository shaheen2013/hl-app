<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

include_once LIB.'sanitize.php';

function referrerUsersEncuesta($id_encuesta){
	$sql="SELECT COUNT(id) AS n FROM referrer_users WHERE id_encuesta='".$id_encuesta."' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	return $row['n'];
}

function obtenerEncuestas($id_hotel, $order, $sort){
	$arrayGestionEncuestas = array();
	$sql = "SELECT user_encuestas.id AS id_encuesta, DATE(user_encuestas.fecha) AS fecha, 
	user_encuestas.positiveComment AS comentario_pos,
	user_encuestas.negativeComment AS comentario_neg, 
	user_encuestas.rating AS puntuacion, 
	user_shares.id_share, user_shares.media,
	CASE WHEN user_shares.id>=1 THEN 1
	ELSE 0 END AS shared,
	users.nombre, users.id, img,
	user_twitter.twitter_user,
	referrer_tokens.visitas
	FROM user_encuestas
	INNER JOIN users ON users.id=user_encuestas.id_usuario
	LEFT JOIN referrer_tokens ON referrer_tokens.id_encuesta=user_encuestas.id
	LEFT JOIN user_twitter ON user_twitter.id_usuario=users.id
	LEFT JOIN user_shares ON user_shares.id_encuesta=user_encuestas.id
	WHERE user_encuestas.id_hotel = '".$id_hotel."' AND user_encuestas.done=1  
	GROUP BY  user_encuestas.id ";
	if(isset($order)){
		$sql .= " ORDER BY ".$order." ".$sort;
	}else{
		$sql .= " ORDER BY user_encuestas.fecha DESC";
	}
	//echo $sql;
	$rs = mysqli_query (conectar(), $sql);
	$i=0;
	while ($row = mysqli_fetch_assoc($rs)){
		foreach ($row as $key=>$valor){
			if ($key == 'fecha'){
				$arrayGestionEncuestas[$i][$key]=girarFecha(substr($valor,0, 10));
				$arrayGestionEncuestas[$i][$key.'_ord']=str_replace('-', '', substr($valor,0, 10));
			}else if($key == 'nombre'){
				$arrayGestionEncuestas[$i][$key]=$valor;
				$arrayGestionEncuestas[$i][$key.'_san']=string_sanitize($valor);
			}else{
				$arrayGestionEncuestas[$i][$key]=$valor;
			}
		}
		$arrayGestionEncuestas[$i]['registrados']=referrerUsersEncuesta($row['id_encuesta']);
		if($row['visitas']!=0){
			$arrayGestionEncuestas[$i]['porcentaje']=round($arrayGestionEncuestas[$i]['registrados']*100/$row['visitas'], 1);
		}else{
			$arrayGestionEncuestas[$i]['porcentaje']=0;
		}
		
		$i++;
	}
	liberar($rs);
	return $arrayGestionEncuestas;
}

function obtenerEncuestas2($id_hotel, $order, $sort, $itemsPage=1, $pagina=1){
	$arrayGestionEncuestas = array();
	$sql = "SELECT DISTINCT user_encuestas.id AS id_encuesta,
	DATE(user_encuestas.fecha) AS fecha, 
	user_encuestas.fecha, 
	user_encuestas.positiveComment AS comentario_pos,
	user_encuestas.negativeComment AS comentario_neg, 
	user_encuestas.rating AS puntuacion, 
	user_shares.id_share, user_shares.media,
	
	CASE WHEN user_shares.id>=1 THEN 1
	ELSE 0 END AS shared,
	users.nombre, users.id, IFNULL(img, 0) AS img,
	user_twitter.twitter_user ";
	
	$sql2 = " FROM user_encuestas
	INNER JOIN users ON users.id=user_encuestas.id_usuario
	LEFT JOIN user_twitter ON user_twitter.id_usuario=users.id
	
	LEFT JOIN user_shares ON user_shares.id_encuesta=user_encuestas.id
	WHERE user_encuestas.id_hotel = '".$id_hotel."' AND user_encuestas.done=1 ";
	//$sql3 = " GROUP BY user_encuestas.id ";
	if(isset($order)){
		$sql3 = " ORDER BY ".$order." ".$sort;
	}else{
		$sql3 = " ORDER BY user_encuestas.fecha DESC";
	}
	$inicio = $itemsPage*$pagina-$itemsPage;
	$sql3 .= " LIMIT ".$inicio.",".$itemsPage;
	//echo '<!--'.$sql.$sql2.$sql3.'-->';
	$rs = mysqli_query (conectar(), $sql.$sql2.$sql3);
	$i=0;
	while ($row = mysqli_fetch_assoc($rs)){
		foreach ($row as $key=>$valor){
			if ($key == 'fecha'){
				$arrayGestionEncuestas[$i][$key]=girarFecha(substr($valor,0, 10));
				$arrayGestionEncuestas[$i][$key.'_ord']=str_replace('-', '', substr($valor,0, 10));
			}else if($key == 'nombre'){
				$arrayGestionEncuestas[$i][$key]=$valor;
				$arrayGestionEncuestas[$i][$key.'_san']=string_sanitize($valor);
			}else{
				$arrayGestionEncuestas[$i][$key]=$valor;
			}
			$arrayGestionEncuestas[$i]['urlGuid']=obtenerUrlGUIDUsario($row['id']);
		}
		$i++;
	}
	liberar($rs);
	$sql0 = "SELECT COUNT(DISTINCT(user_encuestas.id)) as N ";
	paginacion2($sql0.$sql2, $pagina, $itemsPage);
	return $arrayGestionEncuestas;
}
?>