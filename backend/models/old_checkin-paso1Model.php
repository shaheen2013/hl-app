<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

include_once LIB.'sanitize.php';
//include_once LIB.'fecha.php';

function buscarUsuario($busqueda){
	/*$sql = "SELECT email, id_tarjeta
	FROM users 
	WHERE MATCH (email) AGAINST ('%".$busqueda."%') 
	OR MATCH (id_tarjeta) AGAINST ('%".$busqueda."%') ";*/
	// Ejecutar en el SQL (por cada MATCH):
	// ALTER TABLE tabla ADD FULLTEXT (columna)
	$sql1 = "SELECT users.id, users.nombre, users.img, tw_followers, fb_friends  
	FROM users ";
	$sql = $sql1." WHERE id_tarjeta='".$busqueda."' LIMIT 1";
	//echo $sql;
	$rs = mysqli_query (conectar(), $sql);
	$n_resultados=mysqli_num_rows($rs);
	if ($n_resultados==0){
		$sql = $sql1." WHERE email='".$busqueda."' ";
		$rs = mysqli_query (conectar(), $sql);
		$row = mysqli_fetch_assoc($rs);
	}else{
		$row = mysqli_fetch_assoc($rs);
	}
	if(empty($row['nombre'])){ // Si no tiene nombre le ponemos uno por defecto
		$row['nombre'] = 'user-name';
	}
	if(!empty($row['id']))
	{
		$row['san_name']=string_sanitize($row['nombre']);
		$row['urlGuid']=obtenerUrlGUIDUsario($row['id']);
	}
	liberar($rs);
	return ($row);
}

function checkinUsuario ($id_usuario, $id_hotel, $fechaChIn){
	$fechaHora = dateTimeHoy();
	$sql = "INSERT INTO user_checkin (id_usuario, id_hotel, chkin_date, checkin_fechahora) 
	VALUES ('".$id_usuario."', '".$id_hotel."', '".$fechaChIn."', '".$fechaHora."')";
	mysqli_query (conectar(), $sql);
	//Mirar si es cliente del hotel
	$sql2 = "SELECT id FROM user_hotels 
	WHERE id_usuario='".$id_usuario."' AND id_hotel='".$id_hotel."' ";
	$rs2 = mysqli_query (conectar(), $sql2);
	$n_resultados=mysqli_num_rows($rs2);
	if ($n_resultados==0){ // No es usuario del hotel todavia
		vincularUsuarioHotelero($id_usuario, $id_hotel);
	}
	liberar($rs2);
	//return 'Check-in OK';
}

function comprobarSiUsuarioTieneCupones($id_usuario, $id_hotel){
	$sql = "SELECT user_cupones.id
	FROM user_cupones
	INNER JOIN hotel_oferta ON hotel_oferta.id=user_cupones.id_oferta
	WHERE user_cupones.id_usuario='".$id_usuario."' AND canjeado=0 ";
	if (hotelDeCadena($id_hotel)){//Si es hotel de cadena tb mostramos ofertas de cadena
		$id_cadena = hotelIdCadena($id_hotel);
		$sql .= "AND (hotel_oferta.id_hotel='".$id_hotel."' or  hotel_oferta.id_cadena='".$id_cadena."')";
	}else{
		$sql .= "AND hotel_oferta.id_hotel='".$id_hotel."'  ";
	}
	//echo $sql;
	$rs = mysqli_query (conectar(), $sql);
	$n_resultados=mysqli_num_rows($rs);
	liberar($rs);
	if ($n_resultados==0){
		return 0;
	}else{
		return 1;
	}
}

function usuarioYaCheckin ($id_usuario, $id_hotel){
	$sql = "SELECT id, chkout_date FROM user_checkin 
	WHERE id_usuario='".$id_usuario."' ";
	// Solo miramos si ya esta en checkin en este hotel, 
	// si no ha hecho checkout en otro hotel, permitimos el checkin en este
	$sql .= "AND id_hotel='".$id_hotel."' ";
	$sql .= "ORDER BY id DESC LIMIT 1";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	if ($row['chkout_date']=='0000-00-00'){
		return 1;
	}else{
		return 0;
	}
}

function usuarioYaCheckinEmail($email, $id_hotel){
	$sql = "SELECT COUNT(user_checkin.id) AS yaCheckin FROM user_checkin 
	INNER JOIN users ON users.id=user_checkin.id_usuario
	WHERE chkout_date='0000-00-00' AND id_hotel='".$id_hotel."' 
	AND users.email='".$email."' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	if ($row['yaCheckin']==0){
		return false;
	}else{
		return true;
	}
}

// Mirar si es un usuario 
function usuarioReferido($id_usuario){
	$sql = "SELECT invitador FROM referrer_users WHERE invitado='".$id_usuario."' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	return $row['invitador'];
}

// Mirar si es el primer checkin que realiza
function primerCheckin($id_usuario){
	$sql = "SELECT id FROM user_checkin WHERE id_usuario='".$id_usuario."' ";
	$rs = mysqli_query (conectar(), $sql);
	$n_resultados = mysqli_num_rows($rs);
	liberar($rs);
	if ($n_resultados == 0){
		return true;
	}else{
		return false;
	}
}

// Mirar si ha usado un promocode
function noUsadoPromoCode($id_usuario, $id_hotel){
	$sql = "SELECT COUNT(promo_code) AS n 
	FROM used_promocode 
	WHERE id_hotel='".$id_hotel."' AND id_usuario='".$id_usuario."' AND id_encuesta!=0 ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	if($row['n']=='0'){
		return true;
	}else{
		return false;
	}
}
?>