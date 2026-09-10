<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

function insertPuntosRegistro ($id_usuario,$id_emisor,$puntos,$id_action,$id_regalador=0, $id_hotel_action=0, $id_cadena=0, $id_referral=0){ 
	//Fecha 
	$fecha = dateTimeHoy();
	if ($id_cadena==0){
		if (hotelDeCadena($id_emisor)){
			$id_cadena = hotelIdCadena($id_emisor);
		}else{
			$id_cadena = 0;
		}
	}
	$sql = "INSERT INTO user_points_reg (id_usuario, id_emisor, puntos, fecha, 
	id_action, id_regalador, id_hotel_action, id_cadena, id_referral)
	 VALUES 
	('".$id_usuario."', '".$id_emisor."', '".$puntos."', '".$fecha."', '".$id_action."', 
	'".$id_regalador."', '".$id_hotel_action."', '".$id_cadena."', '".$id_referral."')";
	mysqli_query (conectar(), $sql);
	//echo $sql;
}

//Inserta puntos de HOTEL (o Cadena) al usuario ya existente
function insertPuntosUsuario ($id_usuario, $id_emisor, $puntos, $id_cadena=0){
	if ($id_cadena ==0){
		$id_cadena = hotelIdCadena($id_emisor);	
	}
	//mirar si ya tiene puntos de este hotel/cadena
	if ($id_cadena !=0){
		$sql = "SELECT id FROM user_points_cadena 
		WHERE id_usuario='".$id_usuario."' AND id_cadena='".$id_cadena."'";
	}else{
		$sql = "SELECT id FROM user_points 
		WHERE id_usuario='".$id_usuario."' AND id_emisor='".$id_emisor."'";
	}
	//echo $sql;
	$rs = mysqli_query (conectar(), $sql);
	$n_resultados = mysqli_num_rows($rs);
	liberar($rs);
	if ($n_resultados == 1 ){// Ya tiene puntos de este hotel/cadena
		//sumar puntos
		if (hotelDeCadena($id_emisor) || $id_cadena !=0){
			$sql2 = "UPDATE user_points_cadena SET puntos=puntos+'".$puntos."' 
			WHERE id_usuario = '".$id_usuario."' AND id_cadena = '".$id_cadena."' ";
		}else{
			$sql2 = "UPDATE user_points SET puntos = puntos+'".$puntos."' 
			WHERE id_usuario = '".$id_usuario."' AND id_emisor = '".$id_emisor."' ";
		}
	}else{
		//insertar nueva entrada
		if (hotelDeCadena($id_emisor) || $id_cadena !=0){
			$sql2 = "INSERT INTO user_points_cadena(id_usuario, id_cadena, puntos) 
			VALUES ('".$id_usuario."', '".$id_cadena."', '".$puntos."')";

			$sql3 = 'INSERT INTO user_cadena_follow (id_cadena, id_usuario, follow) 
			VALUES ("'.$id_cadena.'","'.$id_usuario.'",0)';
			mysqli_query (conectar(), $sql3);
		}else{
			$sql2 = "INSERT INTO user_points(id_usuario, id_emisor, puntos) 
			VALUES ('".$id_usuario."', '".$id_emisor."', '".$puntos."')";
		}
	}
	//echo $sql2;
	mysqli_query (conectar(), $sql2);
}

//agregar puntos de HOTEL o Cadena, por id de hotel
function agregarPuntos($id_usuario, $id_emisor, $puntos, $id_action){
	if ($puntos >= 1){
		//Insert user-points-registro
		insertPuntosRegistro($id_usuario,$id_emisor,$puntos,$id_action);
		//Update user-points (tipo puntos-hotel)
		insertPuntosUsuario($id_usuario, $id_emisor, $puntos);
	}
}

// Agregar puntos Hotelinking
// $id_usuario : usuario al que regalamos puntos
// $puntos : puntos
// $id_action : tipo accion (1, 2, 3, 4...)
// $id_hotel_action : id_hotel que ejecuta la acción
// $id_regalador : id_regalador
// $id_referral : id_referral
function agregarPuntosHl ($id_usuario, $puntos, $id_action, $id_hotel_action, $id_regalador=0, $id_referral=0){
	$sql = "UPDATE user_points_hl SET puntos=puntos+'".$puntos."' 
	WHERE id_usuario='".$id_usuario."' ";
	mysqli_query (conectar(), $sql);
	insertPuntosRegistro($id_usuario, 'hl', $puntos, $id_action, $id_regalador, $id_hotel_action, '0', $id_referral);
}

// Usuario regala puntos de hotel/cadena a otro usuario (SOLO DE HOTEL o CADENA)
function regalarPuntos ($id_regalador, $id_regalado, $puntos, $id_hot_cad){
	$part = explode('-', $id_hot_cad);
	if ($part[0]=='c'){
		$id_cadena = $part[1];
		$id_hotel = 0;
	}else if ($part[0]=='h'){
		$id_hotel = $part[1];
		$id_cadena = 0;
	}
	// Agregar puntos receptor
	insertPuntosUsuario ($id_regalado,$id_hotel,$puntos,$id_cadena);
	// Quitar puntos emisor
	quitarPuntosRegalo($id_regalador, $puntos,$id_hotel, $id_cadena);
	// Registrar quita de puntos en REG emisor
	insertPuntosRegistro ($id_regalador,$id_hotel,$puntos,9,$id_regalado, 0, $id_cadena);
	// Registrar suma de puntos en REG receptor
	insertPuntosRegistro ($id_regalado,$id_hotel,$puntos,11,$id_regalador, 0, $id_cadena);
}

function quitarPuntosRegalo($id_usuario, $puntos, $id_hotel=0, $id_cadena=0){
	$fecha = dateTimeHoy();
	// Quitar puntos emisor
	if ($id_cadena!=0){
		$sql = "UPDATE user_points_cadena SET puntos=puntos-'".$puntos."' 
		WHERE id_usuario='".$id_usuario."' AND id_cadena='".$id_cadena."' ";
	}else{
		$sql = "UPDATE user_points SET puntos=puntos-'".$puntos."' 
		WHERE id_usuario='".$id_usuario."' AND id_emisor='".$id_hotel."' ";
	}
	mysqli_query (conectar(), $sql);
}

// Obtener action points
function obtenerPuntos($action){
	$sql = "SELECT points FROM action_points WHERE action='".$action."' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	return $row['points'];
}

// Si el usuario adquiere una oferta se registra la quita de puntos
function quitarPuntosReg($id_usuario,$puntos,$id_action,$id_oferta,$emisor){
	$fecha = dateTimeHoy();
	$sql = "INSERT INTO user_points_reg 
	(id_usuario, id_emisor, puntos, fecha, id_action, id_oferta) 
	VALUES 
	('".$id_usuario."', '".$emisor."', '".$puntos."', '".$fecha."', '".$id_action."', 
	'".$id_oferta."')";
	mysqli_query (conectar(), $sql);
}
?>