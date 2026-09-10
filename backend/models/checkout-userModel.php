<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//include_once LIB.'fecha.php';
include_once LIB.'sanitize.php';

function obtenerDatosUsuario($id_usuario, $id_hotel){
	$sql = "SELECT users.id AS id_usuario, nombre, id_tarjeta, users.email, img
	FROM users
	INNER JOIN user_hotels ON user_hotels.id_usuario=users.id
	WHERE user_hotels.id_usuario='".$id_usuario."' 
	AND user_hotels.id_hotel='".$id_hotel."' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	if ($row['nombre']==''){
		$row['nombre']=$row['email'];
	}
	$row['nombre_san'] = string_sanitize($row['nombre']);
	$row['urlGuid'] = obtenerUrlGUIDUsario($row['id_usuario']);
	liberar ($rs);
	return $row;
}

function obtenerCuponesUsuario($id_usuario, $id_hotel, $order=0, $sort=0, $itemsPage=1, $pagina=1){
	$arrayCupones = array();
	$sql = "SELECT DISTINCT user_cupones.id, user_cupones.voucher , user_cupones.id_usuario, 
	user_cupones.id_oferta, user_cupones.fecha_canj, user_cupones.canjeado,
	case when oferta_lang.nombre is null 
    then   oferta_en.nombre 
    else oferta_lang.nombre end AS nombre_oferta, 
	hotel_oferta.puntos, hotel_oferta.adq_ret ";
	$sql2 = " FROM user_cupones
	INNER JOIN hotel_oferta ON hotel_oferta.id=user_cupones.id_oferta
	INNER JOIN users ON users.id=user_cupones.id_usuario
	INNER JOIN user_checkin ON user_checkin.id_usuario=users.id
	LEFT JOIN hotel_oferta_lang as oferta_en   on hotel_oferta.id = oferta_en.id_oferta   and oferta_en.lang='en' 
    LEFT JOIN hotel_oferta_lang as oferta_lang on hotel_oferta.id = oferta_lang.id_oferta and oferta_lang.lang='". $_SESSION['userNavLang'] . "'
	WHERE user_cupones.id_usuario='".$id_usuario."' AND chkout_date='0000-00-00' ";
	if (hotelDeCadena($id_hotel)){//Si es hotel de cadena tb mostramos ofertas de cadena
		$id_cadena = hotelIdCadena($id_hotel);
		$sql2 .= "AND (hotel_oferta.id_hotel='".$id_hotel."' or  hotel_oferta.id_cadena='".$id_cadena."')";
	}else{
		$sql2 .= "AND hotel_oferta.id_hotel='".$id_hotel."' ";
	}
	$sql2 .= "AND fecha_canj BETWEEN chkin_date AND NOW() AND canjeado=1";
	$sql3 = " ORDER BY ".$order." ". $sort;
	$inicio = $itemsPage*$pagina-$itemsPage;
	$sql3 .= " LIMIT ".$inicio.",".$itemsPage;
	//echo $sql.$sql2.$sql3;
	$rs = mysqli_query (conectar(), $sql.$sql2.$sql3);
	$i=0;
	while ($row = mysqli_fetch_assoc($rs)){
		foreach ($row as $key=>$valor){
			if ($key=='fecha_canj'){
				$arrayCupones[$i][$key] = girarFechaHora($valor);
			}else if($key=='nombre_oferta'){
				$arrayCupones[$i][$key] = $valor;
				$arrayCupones[$i]['nombre_oferta_san'] = string_sanitize($valor);
			}else{
				$arrayCupones[$i][$key] = $valor;
			}
		}
		$i++;
	}
	liberar ($rs);
	
	$sql0 = "SELECT COUNT(DISTINCT(user_cupones.id)) as N ";
	paginacion2($sql0.$sql2, $pagina, $itemsPage);
	
	return $arrayCupones;
}
?>