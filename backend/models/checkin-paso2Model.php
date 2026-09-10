<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

include_once LIB.'fecha.php';
include_once LIB.'sanitize.php';

function obtenerCuponesUsuario($id_usuario, $id_hotel, $order, $sort, $itemsPage=1, $pagina=1, $search=0)
{
	$id_usuario = mysqli_real_escape_string(conectar(), $id_usuario);
	$order = mysqli_real_escape_string(conectar(), $order);
	
	$arrayCupones = array();
	$sql = "SELECT user_cupones.id, user_cupones.voucher , user_cupones.id_usuario, 
	user_cupones.id_oferta, user_cupones.fecha, 
	case when oferta_lang.nombre is null 
    then  oferta_en.nombre 
    else oferta_lang.nombre end as nombre_oferta, 
	hotel_oferta.fin,
	users.id AS id_usuario, users.nombre
	FROM user_cupones
	INNER JOIN hotel_oferta ON hotel_oferta.id=user_cupones.id_oferta
	LEFT JOIN hotel_oferta_lang as oferta_en on hotel_oferta.id = oferta_en.id_oferta  and oferta_en.lang='en' 
    LEFT JOIN hotel_oferta_lang as oferta_lang on hotel_oferta.id = oferta_lang.id_oferta and oferta_lang.lang='".$_SESSION['userNavLang'] . "'  
	INNER JOIN users ON users.id=user_cupones.id_usuario
	WHERE user_cupones.id_usuario='".$id_usuario."' ";
	if (hotelDeCadena($id_hotel))
	{//Si es hotel de cadena tb mostramos ofertas de cadena
		$id_cadena = hotelIdCadena($id_hotel);
		$sql .= " AND (hotel_oferta.id_hotel='".$id_hotel."' or  hotel_oferta.id_cadena='".$id_cadena."')";
	}else{
		$sql .= " AND hotel_oferta.id_hotel='".$id_hotel."' ";
	}
	if($search!='0')
	{
		$search = mysqli_real_escape_string(conectar(), $search);
		$sql .=" AND (MATCH (user_cupones.voucher) AGAINST ('%".$search."%') ) ";
	}
	$sql2 = " AND canjeado=0 ORDER BY ".$order." ".$sort;
	$inicio = $itemsPage*$pagina-$itemsPage;
	$sql2 .= " LIMIT ".$inicio.",".$itemsPage;
	//echo '-----------------'.$sql.$sql2;
	$rs = mysqli_query (conectar(), $sql.$sql2);
	$i=0;
	while ($row = mysqli_fetch_assoc($rs)){
		foreach ($row as $key=>$valor){
			if ($key=='fecha'){
				$arrayCupones[$i][$key] = girarFecha(substr($valor, 0, 10));
			}else if($key=='fin'){
				$arrayCupones[$i][$key] = girarFecha($valor);
			}else if($key=='nombre' || $key=='nombre_oferta'){
				$arrayCupones[$i][$key] = $valor;
				$arrayCupones[$i][$key.'_san'] = string_sanitize($valor);
			}else{
				$arrayCupones[$i][$key] = $valor;
			}
		}
		$arrayCupones[$i]['urlGuid']=obtenerUrlGUIDUsario($row['id_usuario']);
		$i++;
	}
	liberar($rs);
	return ($arrayCupones);
}

/*function buscarCupon($busqueda, $id_hotel, $order, $sort){
	$sql = "SELECT user_cupones.id, voucher, fecha, canjeado, 
	users.nombre,
	hotel_oferta.fin, hotel_oferta.nombre AS nombre_oferta
	FROM user_cupones 
	INNER JOIN users ON user_cupones.id_usuario=users.id
	INNER JOIN hotel_oferta ON hotel_oferta.id=user_cupones.id_oferta ";
	$sql .="WHERE (user_cupones.voucher) LIKE ('%".$busqueda."%') "; 
	//$sql .="OR MATCH (users.nombre) AGAINST ('%".$busqueda."%') ";
	$sql .="AND hotel_oferta.id_hotel='".$id_hotel."' ";
	$sql .="ORDER BY ".$order." ".$sort;
	// Ejecutar en el SQL (por cada MATCH):
	// ALTER TABLE tabla ADD FULLTEXT index_name(columna)
	// echo '------------------'.$sql;
	$rs = mysqli_query (conectar(), $sql);
	//$row = mysqli_fetch_assoc($rs);
	$i=0;
	while ($row = mysqli_fetch_assoc($rs)){
		foreach ($row as $key=>$valor){
			if ($key=='fecha'){
				$arrayCuponesBuscados[$i][$key] = girarFecha(substr($valor, 0, 10));
			}else if($key=='fin'){
				$arrayCuponesBuscados[$i][$key] = girarFecha($valor);
			}else{
				$arrayCuponesBuscados[$i][$key] = $valor;
			}
		}
		$i++;
	}
	liberar($rs);
	return ($arrayCuponesBuscados);
}*/
?>