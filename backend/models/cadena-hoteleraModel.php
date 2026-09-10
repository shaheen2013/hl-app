<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

function puntosNocheGratis($id_hotel){
	$sql = "SELECT IFNULL (puntos,0) AS puntos FROM hotel_oferta 
	WHERE id_tipo_oferta='ngr' AND id_hotel='".$id_hotel."' ORDER BY puntos ASC LIMIT 1" ; 
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	if(!empty($row['puntos'])){
		$putnos = $row['puntos'];
	}else{
		$putnos = 0;
	}
	return ($putnos);
}

function obtenerPuntosCadena($id_cadena, $id_usuario){
	$sql = "SELECT puntos FROM user_points_cadena
	WHERE id_cadena='".$id_cadena."' AND id_usuario='".$id_usuario."' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	if(!empty($row['puntos'])){
		$puntos = $row['puntos'];
	}else{
		$puntos = 0;
	}
	return $puntos;
}

function obtenerDatosCadena($id_cadena){
	$sql = "SELECT id, nombre, logo, descripcion, email_contacto, telefono_contacto
	FROM cadena WHERE id='".$id_cadena."' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	$row['nombre_san'] = string_sanitize($row['nombre']);
	$row['logo'] = 'small_'.$row['logo'];
	if(!empty($_SESSION['u_logueado'])){
		$row['puntos'] = obtenerPuntosCadena($row['id'], $_SESSION['u_logueado']);
	}else{
		$row['puntos'] = 0;
	}
	liberar($rs);
	return $row;
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

function userHotelFollow($id_hotel, $id_usuario){
	$sql = "SELECT follow FROM user_hotels 
	WHERE id_hotel='".$id_hotel."' AND id_usuario='".$id_usuario."' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	return $row['follow'];
}

function obtenerHotelesCadena($id_cadena){
	$sql = "SELECT hoteles.id, hoteles.hotelName AS nombre, IFNULL (logo,0) AS logo,
	hoteles.rating, hoteles.estrellas, city
	FROM cadena_hotel
	INNER JOIN hoteles ON hoteles.id=cadena_hotel.id_hotel
	WHERE id_cadena='".$id_cadena."' ";
	$rs = mysqli_query (conectar(), $sql);
	$i=0;
	while ($row = mysqli_fetch_assoc($rs)){
		foreach ($row as $key=>$valor){
			if($key=='nombre' || $key=='city'){
				$arrayHoteles[$i][$key] = $valor;
				$arrayHoteles[$i][$key.'_san'] = string_sanitize($valor);
			}else{
				$arrayHoteles[$i][$key] = htmlentities($valor, ENT_QUOTES, "ISO-8859-1");
			}
		}
		$arrayHoteles[$i]['ng']=puntosNocheGratis($row['id']);
		$arrayHoteles[$i]['nofertas']=obtenerTotalOfertasHotel($row['id']);
		if(!empty($_SESSION['u_logueado'])){
			$arrayHoteles[$i]['follow']=userHotelFollow($row['id'], $_SESSION['u_logueado']);
		}else{
			$arrayHoteles[$i]['follow']=0;
		}
		$arrayHoteles[$i]['urlGuid'] =  obtenerUrlGUIDHotel($row['id']);
		$i++;
	}
	liberar($rs);
	return $arrayHoteles;
}
?>