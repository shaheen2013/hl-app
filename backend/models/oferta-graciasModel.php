<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

include_once LIB.'sanitize.php';

function obtenerDatosOferta($id_oferta){
	$sql = "SELECT hotel_oferta.id AS id_oferta, 
	case when oferta_lang.nombre is null 
    then  oferta_en.nombre 
    else oferta_lang.nombre end as nombre, 
	hotel_oferta.puntos, hotel_oferta.adq_ret,
	hoteles.id AS id_hotel, hoteles.hotelName ,
	user_cupones.id AS id_cupon
	FROM hotel_oferta 
	INNER JOIN hoteles ON hoteles.id = hotel_oferta.id_hotel
	LEFT JOIN hotel_oferta_lang as oferta_en on hotel_oferta.id = oferta_en.id_oferta  and oferta_en.lang='en' 
    LEFT JOIN hotel_oferta_lang as oferta_lang on hotel_oferta.id = oferta_lang.id_oferta and oferta_lang.lang='".$_SESSION['userNavLang'] . "'  
	INNER JOIN user_cupones ON user_cupones.id_oferta=".$id_oferta."
	INNER JOIN users ON users.id = user_cupones.id_usuario
	WHERE hotel_oferta.id='".$id_oferta."' AND id_usuario='".$_SESSION['u_logueado']."'
	AND user_cupones.compartido=0 ORDER BY user_cupones.id DESC LIMIT 1";
	//echo '----------------------'.$sql;
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	foreach ($row as $key => $valor){
		if ($key == 'nombre' || $key == 'hotelName'){
			$arrayDatosOferta[$key] = $valor;
			$arrayDatosOferta[$key.'_san'] = string_sanitize($valor);
		}else{
			$arrayDatosOferta[$key] = $valor;
		}
	}
	liberar($rs);
	return ($arrayDatosOferta);
}

function marcarCuponCompartido($id_cupon){
	$sql = "UPDATE user_cupones SET compartido=1 WHERE id='".$id_cupon."' ";
	//echo '--------------------------'.$sql;
	mysqli_query (conectar(), $sql);
}
?>