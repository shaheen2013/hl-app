<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

// obtenemos los datos de la oferta para mostrar en pantalla
function obtenerDatosOferta($id_oferta)
{
	$arrayDatosOferta = array();
	
	$id_oferta = mysqli_real_escape_string(conectar() , $id_oferta);
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
    else oferta_lang.nombre end AS nombre,
	hotel_oferta.estado, hotel_oferta.fecha_publicada,
	hoteles.id AS hotel_id,
	hoteles.hotelName, hoteles.estrellas, hoteles.rating, 
	hoteles.city AS ciudad, hoteles.street, 
	hoteles.telefonoReservas, hoteles.emailReserva, 
	hoteles.min_rango, hoteles.max_rango, 
	hotel_oferta.id_cadena AS cadena_id, cadena.email_contacto AS cadena_email, 
	IFNULL (cadena.logo,0) AS cadena_logo, cadena.nombre AS cadena_nombre, cadena.logo AS cadena_logo,
	IFNULL (hoteles.logo, 0) AS logo,
	categoria_oferta.categoria_es AS categoria,
	tipos_oferta.tipo_adq_ret_es AS tipo_oferta,
	subcategoria_oferta.subcategoria_oferta_es AS subcategoria,
	COUNT(user_encuestas.id) AS totalRatings,
	CASE WHEN fin!=0000-00-00 THEN DATEDIFF(fin, NOW()) 
	WHEN fin='0000-00-00' THEN '-' END AS days_left,
	
	
	FROM hotel_oferta 
	LEFT JOIN hoteles ON hoteles.id = hotel_oferta.id_hotel 
	LEFT JOIN hotel_oferta_lang as oferta_en   on hotel_oferta.id = oferta_en.id_oferta   and oferta_en.lang='en' 
    LEFT JOIN hotel_oferta_lang as oferta_lang on hotel_oferta.id = oferta_lang.id_oferta and oferta_lang.lang='". $_SESSION['userNavLang'] . "'
	INNER JOIN categoria_oferta ON categoria_oferta.id_categoria_oferta = hotel_oferta.id_categoria
	INNER JOIN tipos_oferta ON tipos_oferta.id_tipo_oferta = hotel_oferta.id_tipo_oferta
	LEFT JOIN subcategoria_oferta ON subcategoria_oferta.id = hotel_oferta.id_subcategoria
	LEFT JOIN user_encuestas ON user_encuestas.id_hotel=hotel_oferta.id_hotel
	LEFT JOIN cadena ON cadena.id=hotel_oferta.id_cadena
	WHERE hotel_oferta.id='".$id_oferta."' ";
	$row = lectura($sql);
	foreach ($row as $key=>$valor){
		if ( ($key == 'inicio' || $key == 'fin') && $valor!='' ){
			$arrayDatosOferta[$key] = girarFecha($valor);
		}else if ($key == 'fecha_publicada' && $valor!='' ){
			$arrayDatosOferta[$key] = girarFechaHora($valor);
		}else if ($key == 'descripcion' || $key == 'condiciones'){
			$arrayDatosOferta[$key] = $valor;
		}else if ($key == 'hotelName' || $key == 'nombre' || $key == 'cadena_nombre'){
			$arrayDatosOferta[$key] = $valor;
			$arrayDatosOferta[$key.'_san'] = string_sanitize($valor);
		}else if ($key == 'days_left' && $valor < '0' && $valor!='-'){
			$arrayDatosOferta[$key] = '0';
		}else{
			$arrayDatosOferta[$key] = $valor;
		}	
	}
	if ($row['cupo']!=0){ // Cupo 0 --> Cupo infinito
		$arrayDatosOferta['quedan'] = $row['cupo'] - $row['adquiridas'];
	}
	return ($arrayDatosOferta);
}

function obtenerCuponesUsuario($id_oferta, $search=0)
{
	$arrayCuponesOferta = array();
	
	$id_oferta = mysqli_real_escape_string(conectar() , $id_oferta);
	//si el usuario tiene un (o varios) cupon/es de esta oferta le mostramos todos
	$sql = "SELECT user_cupones.id, user_cupones.voucher, user_cupones.fecha, user_cupones.fecha_canj,
	oferta_referral_token.id_hotel AS id_hotel_cupon, oferta_referral_token.id_tipo_share, 
	oferta_referral_token.cookie_id
	FROM user_cupones 
	LEFT JOIN oferta_referral_token ON oferta_referral_token.id_cupon=user_cupones.id
	WHERE user_cupones.id_usuario='".$_SESSION['u_logueado']."' AND user_cupones.id_oferta='".$id_oferta."' ";
	//$sql .= " AND canjeado=0 "; 
	if( $search != '0' )
	{
		$search = mysqli_real_escape_string(conectar() , $search);
		$sql .=" AND (MATCH (user_cupones.voucher) AGAINST ('%".$search."%') ) ";
	}
	$sql .= " ORDER BY DATE(user_cupones.fecha) DESC";
	//echo $sql;
	$rs = mysqli_query (conectar(), $sql) or die(mysqli_error());
	$t=0;
	while ($row = mysqli_fetch_assoc($rs)){
		foreach($row as $key => $value){
			if ($key=='fecha') 
			{
				$arrayCuponesOferta[$t][$key] = substr(girarFechaHora($value), 0, 10);
			}else if ($key=='fecha_canj'){
				if ($value=='0000-00-00 00:00:00')
				{
					$arrayCuponesOferta[$t][$key] = '';
				}else{
					$arrayCuponesOferta[$t][$key] = substr(girarFechaHora($value), 0, 10);
				}
			}else{
				$arrayCuponesOferta[$t][$key] = $value;
			}
		}
		$t++;
	}
	liberar($rs);
	if (!empty($arrayCuponesOferta))
	{
		return ($arrayCuponesOferta);
	}
}


?>