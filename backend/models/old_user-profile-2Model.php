<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//Viene del post de la misma pagina
function insertarDatosDB2($minEstrellas, $maxEstrellas, $minRango, $maxRango){
	
	//Insert o Update
	$sql = "UPDATE users SET ";
	//estrellas
	$sql .= "minEstrellas = '".$minEstrellas."', maxEstrellas = '".$maxEstrellas."', ";
	//rango precios
	$sql .= "rango_inf = '".$minRango."', rango_sup = '".$maxRango."'";
	//where
	$sql .= "WHERE id='".$_SESSION['u_logueado']."' ";
	
	mysqli_query (conectar(), $sql);
}

function borrarTiposHabUsuario($id_usuario){
	$sql = "DELETE FROM user_tipos_hab WHERE id_usuario='".$id_usuario."' ";
	mysqli_query (conectar(), $sql);
}
//Tipos de habitaciones a instertar
function insertarTiposHabUsuario($id_usuario, $arrayTiposHab){
	borrarTiposHabUsuario($id_usuario);
	$nTiposHab = count($arrayTiposHab);
	$i=0;
	while($i<$nTiposHab){
		$sql2 = "INSERT INTO user_tipos_hab (id_usuario, id_tipo_hab) VALUES ('".$id_usuario."', '".$arrayTiposHab[$i]."' )";
		mysqli_query (conectar(), $sql2);
		$i++;
	}
}

function borrarTiposHotelUsuario($id_usuario){
	$sql = "DELETE FROM user_tipos_hotel WHERE id_usuario='".$id_usuario."' ";
	mysqli_query (conectar(), $sql);
}
//Tipos de hotel 
function insertarTiposHotelUsuario($id_usuario, $arrayTiposHotel){
	borrarTiposHotelUsuario($id_usuario);
	$nyTiposHotel = count($arrayTiposHotel);
	$i=0;
	while($i<$nyTiposHotel){
		$sql2 = "INSERT INTO user_tipos_hotel (id_usuario, id_tipo_hotel) VALUES ('".$id_usuario."', '".$arrayTiposHotel[$i]."' )";
		mysqli_query (conectar(), $sql2);
		$i++;
	}
}

function borrarDecoracionesUsuario($id_usuario){
	$sql = "DELETE FROM user_decoraciones WHERE id_usuario='".$id_usuario."' ";
	mysqli_query (conectar(), $sql);
}
//Decoraciones seleccionadas
function insertarDecoracionesUsuario($id_usuario, $arrayDecoraciones){
	borrarDecoracionesUsuario($id_usuario);
	$nDecoraciones = count($arrayDecoraciones);
	$i=0;
	while($i<$nDecoraciones){
		$sql2 = "INSERT INTO user_decoraciones (id_usuario, id_decoracion) VALUES ('".$id_usuario."', '".$arrayDecoraciones[$i]."' )";
		mysqli_query (conectar(), $sql2);
		$i++;
	}
}

function eliminarExtrasUsuario ($id_usuario){
	$sql = "DELETE FROM user_extras WHERE id_usuario='".$id_usuario."' ";
	mysqli_query (conectar(), $sql);
}
//Tipos de extras en habitaciones a instertar
function insertarExtrasUsuario($id_usuario, $arrayExtras){
	eliminarExtrasUsuario ($id_usuario);
	$nTiposHab = count($arrayExtras);
	$i=0;
	while($i<$nTiposHab){
		$sql2 = "INSERT INTO user_extras (id_usuario, id_extra) VALUES ('".$id_usuario."', '".$arrayExtras[$i]."' )";
		mysqli_query (conectar(), $sql2);
		$i++;
	}
}

function borrarServiciosUsuario($id_usuario){
	$sql = "DELETE FROM user_servicios WHERE id_usuario='".$id_usuario."' ";
	mysqli_query (conectar(), $sql);
}
//Tipos de servicios del hotel a instertar
function insertarServiciosUsuario($id_usuario, $arrayServicios){
	borrarServiciosUsuario($id_usuario);
	$nTiposHab = count($arrayServicios);
	$i=0;
	while($i<$nTiposHab){
		$sql2 = "INSERT INTO user_servicios (id_usuario, id_servicio) VALUES ('".$id_usuario."', '".$arrayServicios[$i]."' )";
		mysqli_query (conectar(), $sql2);
		$i++;
	}
}

function obtenerUserProfile2 ($id_usuario){
	$sql = "SELECT 
	minEstrellas, maxEstrellas, rango_inf, rango_sup
	FROM users WHERE id='".$_SESSION['u_logueado']."' ";
	$rs = mysqli_query (conectar(), $sql);
	$arrayUserProfile2 = mysqli_fetch_assoc($rs);
	liberar ($rs);
	return ($arrayUserProfile2);
}

function obtenerDatosUsuarioExtras($id_usuario){
	$arrayHotelExtras = array();
	$sql = "SELECT user_extras.id_extra, extra_".$_SESSION['userLang']." AS extra 	
	FROM user_extras 
	INNER JOIN extras_hotel ON extras_hotel.id_extra=user_extras.id_extra
	WHERE id_usuario='".$id_usuario."' ";
	$rs = mysqli_query (conectar(), $sql);
	$i=0;
	while ($row = mysqli_fetch_assoc($rs)){
		/*$sql2 = "SELECT extra_es FROM extras_hotel WHERE id_extra='".$row['id_extra']."' ";
		$rs2 = mysqli_query (conectar(), $sql2);
		$row2 = mysqli_fetch_assoc($rs2);
		liberar ($rs2);*/
		$arrayHotelExtras[$i]['id_extra']=$row['id_extra'];
		$arrayHotelExtras[$i]['extra']=$row['extra'];
		$i++;
	}
	liberar ($rs);
	return ($arrayHotelExtras);
}

function obtenerDatosUsuarioTiposHab($id_usuario){
	$arrayHotelTiposHab = array();
	$sql = "SELECT id_tipo_hab	FROM user_tipos_hab WHERE id_usuario='".$id_usuario."' ";
	$rs = mysqli_query (conectar(), $sql);
	$i=0;
	while ($row = mysqli_fetch_assoc($rs)){
		$sql2 = "SELECT tipo_hab_es FROM tipos_hab_hotel WHERE id_tipo_hab='".$row['id_tipo_hab']."' ";
		$rs2 = mysqli_query (conectar(), $sql2);
		$row2 = mysqli_fetch_assoc($rs2);
		liberar ($rs2);
		$arrayHotelTiposHab[$i]['id_tipo_hab']=$row['id_tipo_hab'];
		$arrayHotelTiposHab[$i]['tipo_hab']=$row2['tipo_hab_es'];
		$i++;
	}
	liberar ($rs);
	return ($arrayHotelTiposHab);
}

function obtenerDatosUsuarioServicios($id_usuario){
	$arrayUsuarioServicios = array();
	$sql = "SELECT id_servicio	FROM user_servicios WHERE id_usuario='".$id_usuario."' ";
	$rs = mysqli_query (conectar(), $sql);
	$i=0;
	while ($row = mysqli_fetch_assoc($rs)){
		$sql2 = "SELECT servicio_es FROM servicios_hotel WHERE id_servicio='".$row['id_servicio']."' ";
		$rs2 = mysqli_query (conectar(), $sql2);
		$row2 = mysqli_fetch_assoc($rs2);
		liberar ($rs2);
		$arrayUsuarioServicios[$i]['id_servicio']=$row['id_servicio'];
		$arrayUsuarioServicios[$i]['servicio']=$row2['servicio_es'];
		$i++;
	}
	liberar ($rs);
	return ($arrayUsuarioServicios);
}

function obtenerDatosUsuarioTiposHotel($id_usuario){
	$arrayUsuarioTiposHotel = array();
	$sql = "SELECT id_tipo_hotel FROM user_tipos_hotel WHERE id_usuario='".$id_usuario."' ";
	$rs = mysqli_query (conectar(), $sql);
	$i=0;
	while ($row = mysqli_fetch_assoc($rs)){
		$sql2 = "SELECT tipo_es FROM tipos_hotel WHERE id_tipo_hotel='".$row['id_tipo_hotel']."' ";
		$rs2 = mysqli_query (conectar(), $sql2);
		$row2 = mysqli_fetch_assoc($rs2);
		liberar ($rs2);
		$arrayUsuarioTiposHotel[$i]['id_tipo_hotel']=$row['id_tipo_hotel'];
		$arrayUsuarioTiposHotel[$i]['tipo']=$row2['tipo_es'];
		$i++;
	}
	liberar ($rs);
	return ($arrayUsuarioTiposHotel);
}

function obtenerDatosUsuarioDecoraciones($id_usuario){
	$arrayUsuarioDecoracion = array();
	$sql = "SELECT id_decoracion FROM user_decoraciones WHERE id_usuario='".$id_usuario."' ";
	$rs = mysqli_query (conectar(), $sql);
	$i=0;
	while ($row = mysqli_fetch_assoc($rs)){
		$sql2 = "SELECT decoracion_es FROM tipos_hotel_decoracion WHERE id_decoracion='".$row['id_decoracion']."' ";
		$rs2 = mysqli_query (conectar(), $sql2);
		$row2 = mysqli_fetch_assoc($rs2);
		liberar ($rs2);
		$arrayUsuarioDecoracion[$i]['id_decoracion']=$row['id_decoracion'];
		$arrayUsuarioDecoracion[$i]['decoracion']=$row2['decoracion_es'];
		$i++;
	}
	liberar ($rs);
	return ($arrayUsuarioDecoracion);
}

function obtenerTiposHotel(){
	$sql = "SELECT * FROM tipos_hotel";
	$rs = mysqli_query (conectar(), $sql);
	$i=0;
	while ($row = mysqli_fetch_assoc($rs)) {
		$arrayTiposHotel[$i]['id_tipo_hotel']=$row['id_tipo_hotel'];
		$arrayTiposHotel[$i]['tipo']=htmlentities($row['tipo_'.$_SESSION['userLang']], ENT_QUOTES, "ISO-8859-1");
		$i++;
	}
	liberar ($rs);
	return ($arrayTiposHotel);
}

function obtenerTiposDecoracion(){
	$sql = "SELECT * FROM tipos_hotel_decoracion";
	$rs = mysqli_query (conectar(), $sql);
	$i=0;
	while ($row = mysqli_fetch_assoc($rs)) {
		$arrayTiposDecoracion[$i]['id_decoracion']=$row['id_decoracion'];
		$arrayTiposDecoracion[$i]['decoracion']=htmlentities($row['decoracion_'.$_SESSION['userLang']], ENT_QUOTES, "ISO-8859-1");
		$i++;
	}
	liberar ($rs);
	return ($arrayTiposDecoracion);
}

function obtenerExtrasHotel(){
	$sql = "SELECT * FROM extras_hotel";
	$rs = mysqli_query (conectar(), $sql);
	$i=0;
	while ($row = mysqli_fetch_assoc($rs)) {
		$arrayExtrasHotel[$i]['id_extra']=$row['id_extra'];
		$arrayExtrasHotel[$i]['extra']=htmlentities($row['extra_'.$_SESSION['userLang']], ENT_QUOTES, "ISO-8859-1");
		$i++;
	}
	liberar ($rs);
	return ($arrayExtrasHotel);
}

function obtenerTiposHabHotel(){
	$arrayTiposHabHotel = array();
	$sql = "SELECT * FROM tipos_hab_hotel";
	$rs = mysqli_query (conectar(), $sql);
	$i=0;
	while ($row = mysqli_fetch_assoc($rs)) {
		$arrayTiposHabHotel[$i]['id_tipo_hab']=$row['id_tipo_hab'];
		$arrayTiposHabHotel[$i]['tipo_hab']=htmlentities($row['tipo_hab_'.$_SESSION['userLang']], ENT_QUOTES, "ISO-8859-1");
		$i++;
	}
	liberar ($rs);
	return ($arrayTiposHabHotel);
}

function obtenerServiciosHotel(){
	$arrayServiciosHotel = array();
	$sql = "SELECT * FROM servicios_hotel";
	$rs = mysqli_query (conectar(), $sql);
	$i=0;
	while ($row = mysqli_fetch_assoc($rs)) {
		$arrayServiciosHotel[$i]['id_servicio']=$row['id_servicio'];
		$arrayServiciosHotel[$i]['servicio']=htmlentities($row['servicio_'.$_SESSION['userLang']], ENT_QUOTES, "ISO-8859-1");
		$i++;
	}
	liberar ($rs);
	return ($arrayServiciosHotel);
}
?>