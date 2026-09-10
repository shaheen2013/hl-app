<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

function obtenerDatosUsuario($id_usuario){
	$sql = "SELECT users.nombre, img
	FROM users
	WHERE users.id='".$id_usuario."' ";
	$rs = mysqli_query (conectar(), $sql);
	$n_resultados = mysqli_num_rows($rs);
	if ($n_resultados!=0){
		$row = mysqli_fetch_assoc($rs);
	}else{
		$row='';
	}
	liberar ($rs);
	return ($row);
}

function obtenerPreferenciasHotelUsuario($id_usuario){
	$sql = "SELECT minEstrellas, maxEstrellas, rango_inf, rango_sup
	FROM users WHERE id='".$id_usuario."' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar ($rs);
	return $row;
}

function obtenerTiposHotelUsuario($id_usuario){
	$array = array();
	$sql = "SELECT tipo_".$_SESSION['userLang']." AS tipoHotel
	FROM user_tipos_hotel
	INNER JOIN tipos_hotel ON tipos_hotel.id_tipo_hotel = user_tipos_hotel.id_tipo_hotel
	WHERE user_tipos_hotel.id_usuario='".$id_usuario."' ";
	$rs = mysqli_query (conectar(), $sql);
	while ($row = mysqli_fetch_assoc($rs)){
		$array[]=$row['tipoHotel'];
	}
	liberar ($rs);
	return $array;
}

function tiposHotel(){
	$array = array();
	$sql = "SELECT tipo_".$_SESSION['userLang']." FROM tipos_hotel ";
	$rs = mysqli_query (conectar(), $sql);
	while ($row = mysqli_fetch_assoc($rs)){
		$array[]=$row['tipo_'.$_SESSION['userLang'].''];
	}
	liberar ($rs);
	return $array;
}

function obtenerTiposDecoracionUsuario($id_usuario){
	$array = array();
	$sql = "SELECT decoracion_".$_SESSION['userLang']." AS decoracion
	FROM user_decoraciones
	INNER JOIN tipos_hotel_decoracion ON tipos_hotel_decoracion.id_decoracion=user_decoraciones.id_decoracion
	WHERE user_decoraciones.id_usuario='".$id_usuario."' ";
	$rs = mysqli_query (conectar(), $sql);
	while ($row = mysqli_fetch_assoc($rs)){
		$array[]=$row['decoracion'];
	}
	liberar ($rs);
	return $array;
}

function tiposDecoracion(){
	$array = array();
	$sql = "SELECT decoracion_".$_SESSION['userLang']." FROM tipos_hotel_decoracion ";
	$rs = mysqli_query (conectar(), $sql);
	while ($row = mysqli_fetch_assoc($rs)){
		$array[]=$row['decoracion_'.$_SESSION['userLang'].''];
	}
	liberar ($rs);
	return $array;
}

function obtenerTiposHabUsuario($id_usuario){
	$array = array();
	$sql = "SELECT tipo_hab_".$_SESSION['userLang']." AS tipo_hab
	FROM user_tipos_hab
	INNER JOIN tipos_hab_hotel ON tipos_hab_hotel.id_tipo_hab=user_tipos_hab.id_tipo_hab
	WHERE user_tipos_hab.id_usuario='".$id_usuario."' ";
	$rs = mysqli_query (conectar(), $sql);
	while ($row = mysqli_fetch_assoc($rs)){
		$array[]=$row['tipo_hab'];
	}
	liberar ($rs);
	return $array;
}

function tiposHabitacion(){
	$array = array();
	$sql = "SELECT tipo_hab_".$_SESSION['userLang']." FROM tipos_hab_hotel ";
	$rs = mysqli_query (conectar(), $sql);
	while ($row = mysqli_fetch_assoc($rs)){
		$array[]=$row['tipo_hab_'.$_SESSION['userLang'].''];
	}
	liberar ($rs);
	return $array;
}

function obtenerExtrasHabUsuario($id_usuario){
	$arrayHotelExtras = array();
	$sql = "SELECT extra_".$_SESSION['userLang']." AS extra
	FROM user_extras
	INNER JOIN extras_hotel ON extras_hotel.id_extra=user_extras.id_extra
	WHERE id_usuario='".$id_usuario."' ";
	$rs = mysqli_query (conectar(), $sql);
	while ($row = mysqli_fetch_assoc($rs)){
		$arrayHotelExtras[]=$row['extra'];
	}
	liberar ($rs);
	return $arrayHotelExtras;
}

function tiposExtras(){
	$array = array();
	$sql = "SELECT extra_".$_SESSION['userLang']." FROM extras_hotel";
	$rs = mysqli_query (conectar(), $sql);
	while ($row = mysqli_fetch_assoc($rs)){
		$array[]=$row['extra_'.$_SESSION['userLang'].''];
	}
	liberar ($rs);
	return $array;
}

function obtenerServiciosHotelUsuario($id_usuario){
	$array = array();
	$sql = "SELECT servicio_".$_SESSION['userLang']." AS servicio
	FROM user_servicios
	INNER JOIN servicios_hotel ON servicios_hotel.id_servicio=user_servicios.id_servicio
	WHERE id_usuario='".$id_usuario."' ";
	$rs = mysqli_query (conectar(), $sql);
	while ($row = mysqli_fetch_assoc($rs)){
		$array[]=$row['servicio'];
	}
	liberar ($rs);
	return $array;
}

function serviciosHotel(){
	$array = array();
	$sql = "SELECT servicio_".$_SESSION['userLang']." FROM servicios_hotel";
	$rs = mysqli_query (conectar(), $sql);
	while ($row = mysqli_fetch_assoc($rs)){
		$array[]=$row['servicio_'.$_SESSION['userLang'].''];
	}
	liberar ($rs);
	return $array;
}

function obtenerTiposOfertasCompradasUsuario($id_usuario){
	$array = array();
	$sql = "SELECT categoria_".$_SESSION['userLang']." AS categoria, COUNT(id_categoria_oferta) AS n
	FROM user_cupones
	INNER JOIN hotel_oferta ON hotel_oferta.id=user_cupones.id_oferta
	INNER JOIN categoria_oferta ON categoria_oferta.id_categoria_oferta=hotel_oferta.id_categoria
	WHERE id_usuario='".$id_usuario."' GROUP BY categoria ORDER BY n DESC";
	$rs = mysqli_query (conectar(), $sql);
	while ($row = mysqli_fetch_assoc($rs)){
		$array[$row['categoria']]=$row['n'];
	}
	liberar ($rs);
	return $array;
}

function categoriasOfertas(){
	$array = array();
	$sql = "SELECT categoria_".$_SESSION['userLang']." FROM categoria_oferta";
	$rs = mysqli_query (conectar(), $sql);
	while ($row = mysqli_fetch_assoc($rs)){
		$array[]=$row['categoria_'.$_SESSION['userLang'].''];
	}
	liberar ($rs);
	return $array;
}

function obtenerTiposOfertasWUsuario($id_usuario){
    $array = array();
    $sql = "SELECT categoria_".$_SESSION['userLang']." AS categoria, COUNT(id_categoria_oferta) AS n
    FROM user_wishlist
    INNER JOIN hotel_oferta ON hotel_oferta.id=user_wishlist.id_oferta
    INNER JOIN categoria_oferta ON categoria_oferta.id_categoria_oferta=hotel_oferta.id_categoria
    WHERE id_usuario='".$id_usuario."' GROUP BY categoria ORDER BY n DESC";
    $rs = mysqli_query (conectar(), $sql);
	while ($row = mysqli_fetch_assoc($rs)){
		$array[$row['categoria']]=$row['n'];
	}
	liberar ($rs);
    return $array;
}

//Datos de hotel logueado
function obtenerHotelTipo($id_hotel){
	$sql = "SELECT tipo_".$_SESSION['userLang']." AS tipo
	FROM hoteles
	INNER JOIN tipos_hotel ON tipos_hotel.id_tipo_hotel=hoteles.tipo_hotel
	WHERE hoteles.id='".$id_hotel."' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	return $row['tipo'];
}

function obtenerHotelDecoracion($id_hotel){
	$sql = "SELECT decoracion_".$_SESSION['userLang']." AS decoracion
	FROM hoteles
	INNER JOIN tipos_hotel_decoracion ON tipos_hotel_decoracion.id_decoracion=hoteles.decoracion
	WHERE hoteles.id='".$id_hotel."' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	return $row['decoracion'];
}

function obtenerHotelTiposHab($id_hotel){
	$array = array();
	$sql = "SELECT tipo_hab_".$_SESSION['userLang']." AS tipo_hab
	FROM hotel_tipos_hab
	INNER JOIN tipos_hab_hotel ON tipos_hab_hotel.id_tipo_hab=hotel_tipos_hab.id_tipo_hab
	WHERE hotel_tipos_hab.id_hotel='".$id_hotel."' ";
	$rs = mysqli_query (conectar(), $sql);
	while ($row = mysqli_fetch_assoc($rs)){
		$array[]=$row['tipo_hab'];
	}
	liberar($rs);
	return $array;
}
function obtenerHotelExtras($id_hotel){
	$array = array();
	$sql = "SELECT extra_".$_SESSION['userLang']." AS extra
	FROM hotel_extras
	INNER JOIN extras_hotel ON extras_hotel.id_extra=hotel_extras.id_extra
	WHERE hotel_extras.id_hotel='".$id_hotel."' ";
	$rs = mysqli_query (conectar(), $sql);
	while ($row = mysqli_fetch_assoc($rs)){
		$array[]=$row['extra'];
	}
	liberar($rs);
	return $array;
}

function obtenerHotelServicios($id_hotel){
	$array = array();
	$sql = "SELECT servicio_".$_SESSION['userLang']." AS servicio
	FROM hotel_servicios
	INNER JOIN servicios_hotel ON servicios_hotel.id_servicio=hotel_servicios.id_servicio
	WHERE hotel_servicios.id_hotel='".$id_hotel."' ";
	$rs = mysqli_query (conectar(), $sql);
	while ($row = mysqli_fetch_assoc($rs)){
		$array[]=$row['servicio'];
	}
	liberar($rs);
	return $array;
}
?>