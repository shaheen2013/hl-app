<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

include_once LIB.'obtenerDatosUsuario.php';
include_once LIB.'sanitize.php';

function obtenerUsuarios($id_hotel, $id_usuario, $action, $order=0, $sort=0, $itemsPage=1, $pagina=1){
	$arrayUsuarios = array();	
	$sql = "SELECT DISTINCT user_hotels.id_usuario,
	users.id, users.nombre, users.verificado AS estado, users.email, 
	users.location, 	
	IFNULL (img, 0) AS img, tw_followers, fb_friends,
	IFNULL (referrer_users.invitador, 0) AS invitador_id, ";
	
	//Si ha sido invitado mostramos el nombre del referrer (OK)
	$sql .= "CASE WHEN referrer_users.invitador IS NULL THEN '-'
	ELSE (SELECT nombre FROM users WHERE id=referrer_users.invitador) 
	END AS invitador_nombre, ";
	
	//Status 
	$sql .= "CASE 
	WHEN referrer_users.invitador IS NULL AND COUNT(user_checkin.id)=0 THEN 'Invite sent'
	WHEN referrer_users.invitador IS NULL AND COUNT(user_checkin.id)!=0 THEN 'Guest'
	WHEN referrer_users.invitador IS NOT NULL AND users.verificado=0 THEN 'Invite sent'
	WHEN referrer_users.invitador IS NOT NULL 
		AND NOT EXISTS (SELECT id FROM user_checkin 
	WHERE id_usuario=user_hotels.id_usuario AND id_hotel='".$id_hotel."')
	AND users.verificado=1 THEN 'Pending'
	ELSE 'Guest'
	END AS status, ";
	
	//Total spendings (OK)
	$sql .= "(SELECT IFNULL(gasto_total,0) FROM user_hotels 
		WHERE id_hotel='".$id_hotel."' AND id_usuario=users.id) 
		AS total_spendings, ";
	
	//Total nights (OK)
	$sql .= "(SELECT total_noches FROM user_hotels 
		WHERE id_hotel='".$id_hotel."' AND id_usuario=users.id) 
		AS total_nights, ";
		
	//Puntos
	if(hotelDeCadena($id_hotel)){
		//Hotel de cadena
		$sql .="IFNULL(user_points_cadena.puntos,0) AS puntos";
	}else{
		$sql .="IFNULL(user_points.puntos,0) AS puntos";
	}
	
	$sql2 = " FROM user_hotels
	LEFT JOIN users ON users.id=user_hotels.id_usuario
	LEFT JOIN referrer_users ON referrer_users.invitado=users.id AND referrer_users.id_hotel='".$id_hotel."' 
	LEFT JOIN user_checkin ON user_checkin.id_usuario=users.id AND user_checkin.id_hotel='".$id_hotel."'
	LEFT JOIN user_encuestas ON user_encuestas.id=referrer_users.id_encuesta 
		AND user_encuestas.id_hotel='".$id_hotel."' ";
	
	//Puntos
	if(hotelDeCadena($id_hotel)){
		$id_cadena = hotelIdCadena($id_hotel);
		$sql2 .= " LEFT JOIN cadena_hotel ON cadena_hotel.id_hotel=user_hotels.id_hotel 
		LEFT JOIN user_points_cadena ON user_points_cadena.id_usuario=user_hotels.id_usuario     
		AND user_points_cadena.id_cadena=".$id_cadena."  ";
	}else{
		$sql2 .= " LEFT JOIN user_points ON user_points.id_usuario=user_hotels.id_usuario ";
	}
	//---------------------------------------------------------
	$sql2 .= " WHERE user_hotels.id_hotel = '".$id_hotel."' ";
	if ($action=='gst'){
		$sql2 .= " AND referrer_users.invitador='".$id_usuario."' 
		AND user_checkin.id_hotel='".$id_hotel."' ";
	}else if ($action=='rfr'){
		$sql2 .= " AND referrer_users.invitador='".$id_usuario."' 
		AND users.id NOT IN (SELECT DISTINCT id_usuario FROM user_checkin WHERE id_hotel='".$id_hotel."')";
	}else if ($action=='trfr'){
		$sql2 .= " AND referrer_users.invitador='".$id_usuario."' ";
	}
	if(hotelDeCadena($id_hotel)){//Puntos
		$id_cadena = hotelIdCadena($id_hotel);
		$sql2 .= " AND user_points_cadena.id_cadena='".$id_cadena."' ";
	}else{
		$sql2 .= " AND user_points.id_emisor = '".$id_hotel."' ";
	}
	
	$sql3 = " GROUP BY user_hotels.id_usuario ORDER BY ".$order." ". $sort;
	$inicio = $itemsPage*$pagina-$itemsPage;
	$sql3 .= " LIMIT ".$inicio.",".$itemsPage;
	//-------------------------------------------------------
	//echo $sql.$sql2.$sql3;
	$rs = mysqli_query (conectar(), $sql.$sql2.$sql3);
	$i=0;
	while ($row = mysqli_fetch_assoc($rs)){
		foreach ($row as $key=>$valor){
			if ($key == 'email'){
				$arrayEmail = explode('@', $row['email']);
				$email = $arrayEmail[0];
				$arrayUsuarios[$i][$key]=$email;
			}else if ($key=='nombre' || $key=='invitador_nombre'){
				$arrayUsuarios[$i][$key] = $valor;
				$arrayUsuarios[$i][$key.'_san'] = string_sanitize($valor);
				
			}else{
				$arrayUsuarios[$i][$key]=$valor;
			}
		}
		$arrayUsuarios[$i]['invitador_urlGuid']=obtenerUrlGUIDUsario($row['invitador_id']);
		$arrayUsuarios[$i]['urlGuid']=obtenerUrlGUIDUsario($row['id']);
		$i++;
	}
	liberar ($rs);
	
	$sql0 = "SELECT COUNT(DISTINCT(user_hotels.id)) as N ";
	paginacion2($sql0.$sql2, $pagina, $itemsPage);
	return ($arrayUsuarios);
}

function obtenerDatosUsuario($id_usuario){
	$sql = "SELECT nombre, email FROM users WHERE id='".$id_usuario."' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar ($rs);
	return $row;
}
function obtenerDatosHotel($id_hotel){
	$sql = "SELECT hotelName FROM hoteles WHERE id='".$id_hotel."' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar ($rs);
	return $row;
}
?>