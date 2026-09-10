<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

include_once LIB.'permisosUsuario.php';

function puntosNocheGratis($id_hotel){
	$sql = "SELECT IFNULL (puntos,0) AS puntos FROM hotel_oferta 
	WHERE id_tipo_oferta='ngr' AND id_hotel='".$id_hotel."' ORDER BY puntos ASC LIMIT 1" ; 
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	return ($row['puntos']);
}

function puntosNocheGratisCadena($id_cadena){
	// Si ya ha hecho checkin en algun hotel de la cadena, los puntos de adq. no cuentan
	// para la noche gratis
	$sql2 ="SELECT user_checkin.id FROM cadena_hotel
	INNER JOIN user_checkin ON user_checkin.id_hotel=cadena_hotel.id_hotel
	WHERE id_cadena='".$id_cadena."' AND id_usuario='".$_SESSION['u_logueado']."' 
	LIMIT 1";
	$rs2 = mysqli_query (conectar(), $sql2);
	$row2 = mysqli_fetch_assoc($rs2);
	liberar($rs2);

	$sql = "SELECT hotel_oferta.id, IFNULL (puntos,0) AS puntos, 
	CASE WHEN hotel_oferta.cupo!=0 THEN hotel_oferta.cupo-hotel_oferta.adquiridas 
	WHEN hotel_oferta.cupo=0 THEN 1000 END AS quedan 
	FROM cadena_hotel
	INNER JOIN hotel_oferta ON hotel_oferta.id_hotel=cadena_hotel.id_hotel
	WHERE id_tipo_oferta='ngr' ";
	if($row2['id']!=''){
		$sql .= " AND adq_ret='ret' ";
	}
	$sql .= " AND cadena_hotel.id_cadena='".$id_cadena."' AND hotel_oferta.estado=1
	AND (hotel_oferta.fin>= CURRENT_DATE OR hotel_oferta.fin=0000-00-00) 
	GROUP BY hotel_oferta.id HAVING quedan>=1 
	ORDER BY puntos ASC LIMIT 1";
	//echo $sql;
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	if(!empty($row['puntos'])){
		$puntos = $row['puntos'];
	}else{
		$puntos = '0';
	}
	return $puntos;
}

function obtenerTotalOfertasHotel($id_hotel){
	// Solo ofertas publicadas, no caducadas y con cupo restante
	$sql = "SELECT id ,
	CASE WHEN hotel_oferta.cupo!=0 THEN hotel_oferta.cupo-hotel_oferta.adquiridas 
	WHEN hotel_oferta.cupo=0 THEN 1000 END AS quedan
	FROM hotel_oferta WHERE id_hotel='".$id_hotel."' AND estado=1 
	AND (hotel_oferta.fin>= CURRENT_DATE OR hotel_oferta.fin=0000-00-00) 
	GROUP BY hotel_oferta.id HAVING quedan>=1 ";
	$rs = mysqli_query (conectar(), $sql);
	$n_resultados=mysqli_num_rows($rs);
	liberar($rs);
	return ($n_resultados);
}

function obtenerTotalOfertasCadena($id_cadena){
	// Solo ofertas publicadas, no caducadas y con cupo restante
	$sql = "SELECT hotel_oferta.id, 
	CASE WHEN hotel_oferta.cupo!=0 THEN hotel_oferta.cupo-hotel_oferta.adquiridas 
	WHEN hotel_oferta.cupo=0 THEN 1000 END AS quedan
	FROM hotel_oferta
	INNER JOIN cadena_hotel ON cadena_hotel.id_hotel=hotel_oferta.id_hotel
	WHERE cadena_hotel.id_cadena='".$id_cadena."' AND estado=1 
	 AND (hotel_oferta.fin>= CURRENT_DATE OR hotel_oferta.fin=0000-00-00) 
	 GROUP BY hotel_oferta.id HAVING quedan>=1 ";
	$rs = mysqli_query (conectar(), $sql);
	$n_resultados=mysqli_num_rows($rs);
	liberar($rs);
	return ($n_resultados);
}
// Obtiene el ratin de la cadena en base al rating de todos sus hoteles
function obtenerRatingHotelesCadena($id_cadena){
	$sql = "SELECT COUNT(rating) AS n, SUM(rating) AS ratings
	FROM cadena_hotel
	INNER JOIN hoteles ON hoteles.id=cadena_hotel.id_hotel
	WHERE cadena_hotel.id_cadena='".$id_cadena."' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);	
	liberar($rs);
	$rating = number_format($row['ratings']/$row['n'],1);
	return ($rating);
}

function obtenerPuntosUsuario($id_usuario){
	$arrayPuntosUsuario = array();
	$sql = "SELECT DISTINCT puntos, hotelName AS nombre,
	IFNULL (logo,0) AS logo, hoteles.id,
	hoteles.rating, hoteles.estrellas,
	city, IFNULL (follow,0) AS follow
	FROM user_points
	INNER JOIN hoteles ON user_points.id_emisor=hoteles.id
	LEFT JOIN user_hotels ON user_hotels.id_hotel=hoteles.id
	WHERE user_points.id_usuario='".$id_usuario."' 
	AND hoteles.id NOT IN ( SELECT id_hotel FROM cadena_hotel WHERE cadena_hotel.id_hotel=hoteles.id)
	GROUP BY user_points.id_emisor
	ORDER BY puntos DESC ";
	//echo $sql;
	$rs = mysqli_query (conectar(), $sql);
	$i=0;
	while ($row = mysqli_fetch_assoc($rs)){
		foreach ($row as $key=>$valor){
			$arrayPuntosUsuario[$i][$key] = $valor;
		}
		$arrayPuntosUsuario[$i]['ng']=puntosNocheGratis($row['id']);
		$arrayPuntosUsuario[$i]['nofertas']=obtenerTotalOfertasHotel($row['id']);
		$arrayPuntosUsuario[$i]['nivelFideliz']=consultarNivelFidelizacion($row['id'], $id_usuario, 'hot');
		$arrayPuntosUsuario[$i]['tipo']='hot';
		$arrayPuntosUsuario[$i]['urlGuid']=obtenerUrlGUIDHotel($row['id']);
		$i++;
	}
	liberar($rs);
	return ($arrayPuntosUsuario);
}

function obtenerPuntosUsuarioCadena($id_usuario){
	$arrayPuntosUsuario = array();
	$sql = "SELECT DISTINCT puntos, cadena.nombre, IFNULL (logo,0) AS logo, cadena.id,
	IFNULL (follow,0) AS follow
	FROM user_points_cadena
	LEFT JOIN cadena ON cadena.id=user_points_cadena.id_cadena
	LEFT JOIN user_cadena_follow ON user_cadena_follow.id_cadena=cadena.id
		AND user_cadena_follow.id_usuario=user_points_cadena.id_usuario
	WHERE user_points_cadena.id_usuario='".$id_usuario."' 
	
	GROUP BY user_points_cadena.id_cadena
	ORDER BY puntos DESC ";
	//echo $sql;
	$rs = mysqli_query (conectar(), $sql);
	$i=0;
	while ($row = mysqli_fetch_assoc($rs)){
		foreach ($row as $key=>$valor){
			$arrayPuntosUsuario[$i][$key] = $valor;
		}
		$arrayPuntosUsuario[$i]['rating']=obtenerRatingHotelesCadena($row['id']);
		$arrayPuntosUsuario[$i]['estrellas']='-';
		$arrayPuntosUsuario[$i]['city']='-';
		$arrayPuntosUsuario[$i]['ng']=puntosNocheGratisCadena($row['id']);
		$arrayPuntosUsuario[$i]['nofertas']=obtenerTotalOfertasCadena($row['id']);
		$arrayPuntosUsuario[$i]['nivelFideliz']=consultarNivelFidelizacion($row['id'], $id_usuario, 'cad');
		$arrayPuntosUsuario[$i]['tipo']='cad';
		$arrayPuntosUsuario[$i]['urlGuid']=obtenerUrlGUIDcadena($row['id']);
		$i++;
	}
	liberar($rs);
	return ($arrayPuntosUsuario);
}

function obtenerPuntosHotelinking($id_usuario){
	$sql = "SELECT puntos FROM user_points_hl WHERE id_usuario='".$id_usuario."'  ";
	//echo '<!--'.$sql.'-->';
	$rs = mysqli_query (conectar(), $sql);
	$n_resultados=mysqli_num_rows($rs);
	if ($n_resultados==0){
		$puntos = 0;
	}else{
		$row = mysqli_fetch_assoc($rs);
		$puntos = $row['puntos'];
	}
	liberar($rs);
	return $puntos;
}

/*function followHotel($id_hotel){
	$sql = "UPDATE user_hotels SET follow=1 WHERE id_hotel='".$id_hotel."' AND id_usuario='".$_SESSION['u_logueado']."' ";
	mysqli_query (conectar(), $sql);
}*/
?>