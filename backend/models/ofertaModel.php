<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

include_once LIB.'fecha.php';
include_once LIB.'sanitize.php';

function total_ratings_hotel ($id_hotel){
	$sql = "SELECT id FROM user_encuestas WHERE id_hotel='".$id_hotel."' ";
	$rs = mysqli_query (conectar(), $sql);
	$n_total_ratings = mysqli_num_rows($rs);
	liberar ($rs);
	return $n_total_ratings;
}

// obtenemos los datos de la oferta para mostrar en pantalla
function obtenerDatosOferta($id_oferta, $ofertaDe){
	$sql = "SELECT hotel_oferta.id_hotel, hotel_oferta.estado,
	hotel_oferta.adq_ret, hotel_oferta.inicio, hotel_oferta.fin, hotel_oferta.cupo,
	hotel_oferta.adquiridas, hotel_oferta.descuento , hotel_oferta.coste ,hotel_oferta.id,
	hotel_oferta.requerimientos , hotel_oferta.puntos, hotel_oferta.img, 
	
	case when oferta_lang.descripcion is null 
      then   oferta_en.descripcion 
      else oferta_lang.descripcion end AS descripcion,
	case when oferta_lang.condiciones is null 
    then   oferta_en.condiciones 
    else oferta_lang.condiciones end AS condiciones,
	 case when oferta_lang.nombre is null 
      then   oferta_en.nombre 
      else oferta_lang.nombre end AS nombre_oferta,
	
	hotel_oferta.estado, hotel_oferta.fecha_publicada, ";
	
	if($ofertaDe['tipo']=='hot'){//La oferta es de hotel
		$sql .= "hoteles.id AS hotel_id, hoteles.city AS ciudad,
		hoteles.hotelName, hoteles.estrellas, hoteles.rating, 	
		hoteles.street, 
		hoteles.telefonoReservas, hoteles.emailReserva, 
		hoteles.min_rango, hoteles.max_rango, 
		hoteles.jan, hoteles.feb, hoteles.mar, hoteles.apr, hoteles.may, hoteles.jun, 
		hoteles.jul, hoteles.ago, hoteles.sep, hoteles.oct, hoteles.nov, hoteles.dece, 
		IFNULL (hoteles.logo, 0) AS logo, ";
	}else{
		$sql .= " cadena.id AS id_cadena, cadena.nombre AS hotelName, IFNULL (cadena.logo, 0) AS logo, 
		email_contacto AS email, ";
	}
	$sql .= " categoria_oferta.categoria_es AS categoria,
	tipos_oferta.tipo_adq_ret_es AS tipo_oferta,
	COUNT(user_encuestas.id) AS totalRatings
	FROM hotel_oferta ";
	
	if($ofertaDe['tipo']=='hot'){//La oferta es de hotel 
		$sql .= "LEFT JOIN hoteles ON hoteles.id = hotel_oferta.id_hotel";
	}else{
		$sql .= "LEFT JOIN cadena ON cadena.id=hotel_oferta.id_cadena";
	}
	$sql .= " INNER JOIN categoria_oferta ON categoria_oferta.id_categoria_oferta = hotel_oferta.id_categoria
	INNER JOIN tipos_oferta ON tipos_oferta.id_tipo_oferta = hotel_oferta.id_tipo_oferta
	LEFT JOIN user_encuestas ON user_encuestas.id_hotel=hotel_oferta.id_hotel
	LEFT JOIN hotel_oferta_lang as oferta_en   on hotel_oferta.id = oferta_en.id_oferta   and oferta_en.lang='en' 
    LEFT JOIN hotel_oferta_lang as oferta_lang on hotel_oferta.id = oferta_lang.id_oferta and oferta_lang.lang='". $_SESSION['userNavLang'] . "'
	WHERE hotel_oferta.id='".$id_oferta."' AND user_encuestas.done=1 ";
	$row = lectura($sql);
	foreach ($row as $key=>$valor){
		if ($key == 'inicio' || $key == 'fin'){
			$arrayDatosOferta[$key] = girarFecha($valor);
		}else if ($key == 'fecha_publicada'){
			$arrayDatosOferta[$key] = girarFechaHora($valor);
		}else if ($key == 'descripcion' || $key == 'condiciones'){
			$arrayDatosOferta[$key] = $valor;
		}else if ($key == 'hotelName' || $key == 'nombre'|| $key == 'nombre_oferta'){
			$arrayDatosOferta[$key] = $valor;
			$arrayDatosOferta[$key.'_san'] = string_sanitize($valor);
		}else{
			$arrayDatosOferta[$key] = $valor;
		}	
	}
	
	if ($row['cupo']!=0){ // Cupo 0 --> Cupo infinito
		$arrayDatosOferta['quedan'] = $row['cupo'] - $row['adquiridas'];
	}
	//$arrayDatosOferta['totalRatings']=total_ratings_hotel($row['id_hotel']);
	return ($arrayDatosOferta);
}

/*function obtenerCuponesUsuario($id_oferta){
	//si el usuario tiene un (o varios) cupon/es de esta oferta le mostramos el/los codigos 
	$sql = "SELECT voucher, canjeado FROM user_cupones WHERE id_usuario='".$_SESSION['u_logueado']."' AND id_oferta='".$id_oferta."' ";
	//echo '------------------'.$sql;
	$rs = mysqli_query (conectar(), $sql);
	$t=0;
	while ($row = mysqli_fetch_assoc($rs)){
		$arrayCuponesOferta[$t]['voucher']=$row['voucher'];
		$arrayCuponesOferta[$t]['canjeado']=$row['canjeado'];
		$t++;
	}
	liberar($rs);
	if (!empty($arrayCuponesOferta)){
		return ($arrayCuponesOferta);
	}
}*/


?>