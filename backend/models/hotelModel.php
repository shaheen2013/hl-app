<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

function obtenerDatosHotel($id_hotel){
	$sql ="SELECT hoteles.id, hotelName, street, city AS pais, website, 
	emailReserva, telefonoReservas, estrellas, n_habitaciones,
	tipo_hotel, decoracion, descripcion, condiciones, rating, min_rango, max_rango,
	IFNULL(logo, 0) AS logo
	FROM hoteles 
	WHERE hoteles.id='".$id_hotel."' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	$row['hotelName_san']=string_sanitize($row['hotelName']);
	liberar ($rs);
	return ($row);
}

function obtenerTiposHab($id_hotel){
	$arrayTiposHab = array();
	$sql = "SELECT tipo_hab_".$_SESSION['userLang']." AS tipo_hab 
	FROM hotel_tipos_hab
	INNER JOIN tipos_hab_hotel ON tipos_hab_hotel.id_tipo_hab=hotel_tipos_hab.id_tipo_hab
	WHERE id_hotel='".$id_hotel."' ";
	$rs = mysqli_query (conectar(), $sql);
	$i=0;
	while($row = mysqli_fetch_assoc($rs)){
		$arrayTiposHab[$i] = htmlentities($row['tipo_hab'], ENT_QUOTES, "ISO-8859-1");
		$i++;
	}
	liberar ($rs);
	return ($arrayTiposHab);
}

function obtenerServicios($id_hotel){
	$arrayHotelServicios = array();
	$sql = "SELECT servicio_".$_SESSION['userLang']." AS servicio	
	FROM hotel_servicios
	INNER JOIN servicios_hotel ON servicios_hotel.id_servicio=hotel_servicios.id_servicio
	WHERE id_hotel='".$id_hotel."' ";
	$rs = mysqli_query (conectar(), $sql);
	$i=0;
	while($row = mysqli_fetch_assoc($rs)){
		$arrayHotelServicios[$i] = htmlentities($row['servicio'], ENT_QUOTES, "ISO-8859-1");
		$i++;
	}
	liberar ($rs);
	return ($arrayHotelServicios);
}

function obtenerExtras($id_hotel){
	$arrayHotelExtras = array();
	$sql = "SELECT extra_".$_SESSION['userLang']." AS extra
	FROM hotel_extras 
	INNER JOIN extras_hotel ON extras_hotel.id_extra=hotel_extras.id_extra
	WHERE id_hotel='".$id_hotel."' ";
	$rs = mysqli_query (conectar(), $sql);
	$i=0;
	while($row = mysqli_fetch_assoc($rs)){
		$arrayHotelExtras[$i] = htmlentities($row['extra'], ENT_QUOTES, "ISO-8859-1");
		$i++;
	}
	liberar ($rs);
	return ($arrayHotelExtras);
}

function obtenerNComents($id_hotel){
	$sql = "SELECT COUNT(id) AS nComents FROM user_encuestas 
	WHERE id_hotel='".$id_hotel."' AND done=1";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar ($rs);
	return $row['nComents'];
}

function obtenerNRewards($id_hotel){
	$sql = "SELECT id FROM hotel_oferta 
	WHERE id_hotel='".$id_hotel."' ";
	$rs = mysqli_query (conectar(), $sql);
	$n_resultados = mysqli_num_rows($rs);
	liberar ($rs);
	return $n_resultados;
}

function obtenerBookingContact($id_hotel){
	$sql = "SELECT emailReserva, telefonoReservas 
	FROM hoteles WHERE id='".$id_hotel."' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar ($rs);
	return $row;
}

//Devuelve las fichas de las 3 últimas ofertas creadas y publicadas (estado=1)
function obtenerRewards($id_hotel){
	$arrayValoraciones = array();
	$sql = "SELECT hotel_oferta.id, 
	case when oferta_lang.nombre is null 
    then  oferta_en.nombre 
    else oferta_lang.nombre end as nombre, 
	fin, cupo, adquiridas, img, puntos, adq_ret 
	FROM hotel_oferta
	LEFT JOIN hotel_oferta_lang as oferta_en on hotel_oferta.id = oferta_en.id_oferta  and oferta_en.lang='en' 
    LEFT JOIN hotel_oferta_lang as oferta_lang on hotel_oferta.id = oferta_lang.id_oferta and oferta_lang.lang='".$_SESSION['userNavLang'] . "'  
	WHERE id_hotel='".$id_hotel."' AND hotel_oferta.estado=1 AND  adq_ret!='ref'
	ORDER BY hotel_oferta.id DESC LIMIT 3";
	$rs = mysqli_query (conectar(), $sql);
	$i=0;
	while ($row = mysqli_fetch_assoc($rs)){
		foreach ($row as $key=>$valor){
			if ($key=='fin'){
				$arrayCupones[$i][$key] = girarFecha($valor);
			}else if ($key=='nombre'){
				$arrayValoraciones[$i][$key] = $valor;
				$arrayValoraciones[$i][$key.'_san'] = string_sanitize($valor);
			}else{
				$arrayValoraciones[$i][$key] = $valor;
			}
		}
		if ($row['cupo']!=0){
			$arrayValoraciones[$i]['left'] = $row['cupo']-$row['adquiridas'];
		}else{
			$arrayValoraciones[$i]['left'] = '-';
		}
		$i++;
	}
	liberar ($rs);
	return ($arrayValoraciones);
}

function obtenerFollowHotel($id_usuario, $id_hotel){
	$sql = "SELECT follow FROM user_hotels
	WHERE id_usuario='".$id_usuario."' AND id_hotel='".$id_hotel."' ";
	$rs = mysqli_query (conectar(), $sql);
	$n_resultados = mysqli_num_rows($rs);
	if ($n_resultados==0){
		$follow = false;
	}else{
		$row = mysqli_fetch_assoc($rs);
		if ($row['follow']=='1'){
			//$follow = true;
			$follow = '1';
		}else{
			//$follow = false;
			$follow = '0';
		}
	}
	liberar($rs);
	return $follow;
}

function obtenerDatosCadena($id_hotel){
	$sql = "SELECT cadena.id, cadena.nombre
	FROM cadena_hotel
	INNER JOIN cadena ON cadena.id=cadena_hotel.id_cadena
	WHERE cadena_hotel.id_hotel='".$id_hotel."' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	$row['nombre_san'] = string_sanitize($row['nombre']);
	return $row;
}

/*function followHotel($id_hotel, $id_usuario){
	$sql2 = "SELECT id FROM user_hotels_follow WHERE id_usuario='".$id_usuario."' 
	AND id_hotel='".$id_hotel."' ";
	$rs2 = mysqli_query (conectar(), $sql2);
	$n_resultados = mysqli_num_rows($rs2);
	if ($n_resultados==0){
		$sql3 = "INSERT INTO user_hotels_follow (id_usuario, id_hotel, follow) 
		VALUES ('".$id_usuario."', '".$id_hotel."', '1')";
		mysqli_query (conectar(), $sql3);
	}else{
		$sql3 = "UPDATE user_hotels_follow SET follow=1 WHERE id_usuario='".$id_usuario."'
		 AND id_hotel=''".$id_hotel." ";
		mysqli_query (conectar(), $sql3);
	}
	liberar($rs2);
}*/


?>