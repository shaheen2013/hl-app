<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

function obtenerUserOfertas($id_usuario){
	$arrayUserOfertas = array();
	// Puede haber varios cupones de una misma oferta
	// No mostramos ofertas repetidas
	$sql = "SELECT DISTINCT user_cupones.id_oferta, 
	case when oferta_lang.nombre is null 
    then   oferta_en.nombre 
    else oferta_lang.nombre end AS nombre,
	hotel_oferta.puntos , hotel_oferta.inicio, hotel_oferta.fin,
	hotel_oferta.img, hotel_oferta.descuento, hotel_oferta.requerimientos,
	hotel_oferta.adq_ret, hotel_oferta.id, hotel_oferta.id_tipo_oferta,
	categoria_oferta.categoria_es AS categoria, categoria_oferta.id_categoria_oferta,
	hoteles.hotelName, hoteles.estrellas, hoteles.rating, hoteles.city, 
	hoteles.id AS id_hotel,
	IFNULL (hoteles.logo,0) AS logo, 
	cadena.id AS id_cadena, cadena.nombre AS chainName, IFNULL (cadena.logo,0) AS logo_cadena, ";
	
	$sql .= "cuponesOfertaUsuario(id_usuario, user_cupones.id_oferta) AS nOfertas ";
	
	$sql .= " FROM user_cupones 
	LEFT JOIN hotel_oferta ON user_cupones.id_oferta=hotel_oferta.id
	LEFT JOIN hotel_oferta_lang as oferta_en   on hotel_oferta.id = oferta_en.id_oferta   and oferta_en.lang='en' 
    LEFT JOIN hotel_oferta_lang as oferta_lang on hotel_oferta.id = oferta_lang.id_oferta and oferta_lang.lang='". $_SESSION['userNavLang'] . "'
	LEFT JOIN categoria_oferta ON 	hotel_oferta.id_categoria=categoria_oferta.id_categoria_oferta
	LEFT JOIN hoteles ON hotel_oferta.id_hotel=hoteles.id
	LEFT JOIN cadena ON cadena.id=hotel_oferta.id_cadena
	WHERE user_cupones.id_usuario='".$id_usuario."' ";
	//$sql .= " AND canjeado=0";
	$row = lecturaArray($sql);
	$i=0;
	foreach($row as $cupon){
		foreach ($cupon as $key=>$valor){
			if ($key == 'inicio' || $key == 'fin'){
				$arrayUserOfertas[$i][$key] = girarFecha($valor);
			}else if ($key == 'fecha'){
				$arrayUserOfertas[$i][$key] = girarFecha($valor);
			}else if ($key == 'nombre'){
				$arrayUserOfertas[$i][$key] = $valor;
				$arrayUserOfertas[$i][$key.'_san'] = string_sanitize($valor);
			}else{
				$arrayUserOfertas[$i][$key] = $valor;
			}
		}
		$i++;
	}
	return ($arrayUserOfertas);
}
?>