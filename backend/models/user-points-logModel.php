<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

include_once LIB.'permisosUsuario.php';
include_once LIB.'sanitize.php';

function obtenerDatosHotel($id_hotel){
	$sql = "SELECT hotelName AS nombre, id FROM hoteles 
	WHERE hoteles.id='".$id_hotel."' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	return $row;
}

function obtenerDatosUsuario($id_usuario){
	$sql = "SELECT nombre, id FROM users
	WHERE users.id='".$id_usuario."' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	return $row;
}

function obtenerDatosOferta($id_oferta){
	$sql = "SELECT 
	  case when oferta_lang.nombre is null 
      then   oferta_en.nombre 
      else oferta_lang.nombre end AS nombre,
	  adq_ret 
	  FROM hotel_oferta
	  LEFT JOIN hotel_oferta_lang as oferta_en   on hotel_oferta.id = oferta_en.id_oferta   and oferta_en.lang='en' 
      LEFT JOIN hotel_oferta_lang as oferta_lang on hotel_oferta.id = oferta_lang.id_oferta and oferta_lang.lang='". $_SESSION['userNavLang'] . "'
	  WHERE hotel_oferta.id='".$id_oferta."' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	return $row;
}

function obtenerDatosCadena($id_cadena){
	$sql = "SELECT nombre FROM cadena WHERE id='".$id_cadena."' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	return $row;
}

function obtenerPuntosUsuarioReg($id_usuario, $itemsPage, $pagina){
	$sql = "SELECT user_points_reg.id, fecha, id_emisor, puntos, id_regalador, 
	id_oferta, id_referral, id_hotel_action, id_action, id_cadena,
	action_points.nombre_es AS action ";
	$sql2 = " FROM user_points_reg 
	INNER JOIN action_points ON action_points.id=user_points_reg.id_action
	WHERE id_usuario='".$id_usuario."' ";
	$sql3 = "ORDER BY fecha DESC";
	$inicio = $itemsPage*$pagina-$itemsPage;
	$sql3 .= " LIMIT ".$inicio.",".$itemsPage;
	//echo $sql.$sql2.$sql3;
	$rs = mysqli_query (conectar(), $sql.$sql2.$sql3);
	$i=0;
	while ($row = mysqli_fetch_assoc($rs)){
		foreach ($row as $key=>$valor){
			if ($key=='fecha'){
				$arrayPuntosUsuario[$i][$key] = girarFechaHora($valor);
			}else if($key=='id_emisor'){
				if($valor=='hl'){
					$arrayPuntosUsuario[$i][$key]='-';
					$arrayPuntosUsuario[$i]['emisor'] = 'Hotelinking';
					$arrayPuntosUsuario[$i]['emisor_san']='hotelinking';
				}else{
					$arrayPuntosUsuario[$i][$key]=$valor;
					$datosHotel = obtenerDatosHotel($valor);
					$arrayPuntosUsuario[$i]['emisor']= $datosHotel['nombre'];
					$arrayPuntosUsuario[$i]['emisor_san']=string_sanitize($datosHotel['nombre']);
					
				}			
			}else if($key=='id_regalador' || $key=='id_referral'){
				$clave = substr($key, 3);
				if($valor==0){
					$arrayPuntosUsuario[$i]['id_'.$clave]= '-';
					$arrayPuntosUsuario[$i][$clave] = '-';
					$arrayPuntosUsuario[$i][$clave.'_san']= '-';
					$arrayPuntosUsuario[$i][$clave.'_url']= '-';
				}else{
					$arrayPuntosUsuario[$i]['id_'.$clave]=$valor;
					$datosUsuario = obtenerDatosUsuario($valor);
					if($datosUsuario['nombre']==''){$datosUsuario['nombre']='guest';};
					$arrayPuntosUsuario[$i][$clave] = $datosUsuario['nombre'];
					$arrayPuntosUsuario[$i][$clave.'_san']=string_sanitize($datosUsuario['nombre']);
					$arrayPuntosUsuario[$i][$clave.'_url']= obtenerUrlGUIDUsario($datosUsuario['id']);
				}
			}else if($key=='id_oferta'){
				if($valor==0){
					$arrayPuntosUsuario[$i][$key]=$valor;
					$arrayPuntosUsuario[$i]['oferta']='-';
					$arrayPuntosUsuario[$i]['oferta_san']='-';
					$arrayPuntosUsuario[$i]['adq_ret']='-';
				}else{
					$arrayPuntosUsuario[$i][$key]=$valor;
					$datosOferta = obtenerDatosOferta($valor);
					$arrayPuntosUsuario[$i]['oferta']=$datosOferta['nombre'];
					$arrayPuntosUsuario[$i]['oferta_san']=string_sanitize($datosOferta['nombre']);
					$arrayPuntosUsuario[$i]['adq_ret']=$datosOferta['adq_ret'];
				}
			}else if($key=='id_hotel_action'){
				if($valor==0){
					$arrayPuntosUsuario[$i][$key]=$valor;
					$arrayPuntosUsuario[$i]['hotel_action_nombre']='-';
					$arrayPuntosUsuario[$i]['hotel_action_nombre_san']='-';
					$arrayPuntosUsuario[$i]['hotel_action_url']='-';
				}else{
					$arrayPuntosUsuario[$i][$key]=$valor;
					$datosHotel = obtenerDatosHotel($valor);
					$arrayPuntosUsuario[$i]['hotel_action_nombre']= $datosHotel['nombre'];
					$arrayPuntosUsuario[$i]['hotel_action_nombre_san']=string_sanitize($datosHotel['nombre']);
					$arrayPuntosUsuario[$i]['hotel_action_url']=obtenerUrlGUIDHotel($datosHotel['id']);
				}
			}else if($key=='id_cadena'){
				$arrayPuntosUsuario[$i][$key]=$valor;
				if($valor!=0 && $arrayPuntosUsuario[$i]['emisor'] ==''){
					//Si la accion es de una cadena y no tiene emisor, el emisor es la cad
					$datosCadena = obtenerDatosCadena($valor);
					$arrayPuntosUsuario[$i]['emisor']= $datosCadena['nombre'];
					$arrayPuntosUsuario[$i]['emisor_san']=string_sanitize($datosCadena['nombre']);
				}
			}else{
				$arrayPuntosUsuario[$i][$key] = $valor;
			}
		}
		// Si el emisor de los puntos no es hotelinking
		if($arrayPuntosUsuario[$i]['emisor']=='Hotelinking'){
			$arrayPuntosUsuario[$i]['url']= '';
		}else{
			if($arrayPuntosUsuario[$i]['id_cadena']!=0){
				$arrayPuntosUsuario[$i]['url']= obtenerUrlGUIDCadena($arrayPuntosUsuario[$i]['id_cadena']);
			}else{
				$arrayPuntosUsuario[$i]['url']= obtenerUrlGUIDHotel($arrayPuntosUsuario[$i]['id_emisor']);
			}
		}
		$i++;
	}
	liberar($rs);
	
	$sql0 = "SELECT COUNT(DISTINCT(user_points_reg.id)) as N ";
	paginacion2($sql0.$sql2, $pagina, $itemsPage);
		
	return ($arrayPuntosUsuario);
}
?>