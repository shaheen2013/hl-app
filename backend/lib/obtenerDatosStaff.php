<?php

function obtenerDatosHotelStaff($hotels_id)
{	
	$hotels_string = implode(",",$hotels_id);
	$sql = "SELECT hoteles.id, hotelName, brands.id as brand_id FROM hoteles inner join brands ON brands.hotel_id = hoteles.id WHERE hoteles.id IN ($hotels_string)";
	$rows = lecturaArray($sql);
	return $rows;
}

// Fx para obtener los datos del staff para enviarde emails
function obtenerDatosStaffEmail($id_staff)
{
	$id_staff = mysqli_real_escape_string(conectar(), $id_staff);
	
	$sql = "SELECT nombre, email FROM hotel_staff WHERE id=$id_staff";
	$row = lectura($sql);
	return $row;
}

// FX para mirar en session el id_hotel de un staff
function obtenerIdHotelStaff()
{
	if( !empty($_SESSION['staff_logueado']) ){
		return $_SESSION['staff_id_hotel'];
	}else if ( !empty($_SESSION['h_logueado']) ){
		return $_SESSION['h_logueado'];
	}
}

function obtenerIdCadenaStaff()
{
	if( !empty($_SESSION['staff_logueado']) ){
		return $_SESSION['staff_id_cadena'];
	}else if ( !empty($_SESSION['c_logueado']) ){
		return $_SESSION['c_logueado'];
	}
}

// FX para devolver los controladores que puede acceder este staff según su perfil
// El primer controlador es el default ($controladoresStaff[0])
function obtenerControladoresStaff($id)
{
	$controladoresStaff = array();
	$sql = "SELECT controladores.controlador
	FROM hotel_staff
	INNER JOIN hotel_staff_permisos ON hotel_staff_permisos.id_staff_role=hotel_staff.id_role
	INNER JOIN controladores ON controladores.id=hotel_staff_permisos.id_controller
	WHERE hotel_staff.id=$id ORDER BY hotel_staff_permisos.default DESC";
	$rs = mysqli_query (conectar(), $sql) or die(mysqli_error());
	global $urlTree;
	while ($row = mysqli_fetch_assoc($rs))
	{
		$controladoresStaff[]=$urlTree[$row['controlador']];
	}
	liberar ($rs);
	
	return $controladoresStaff;
}

function staff_belongs_to_hotel($staff_id, $hotel_id, $chain_id) 
{
	$con = conectar(1);
	$staff_id = mysqli_real_escape_string($con, $staff_id);
	$hotel_id = mysqli_real_escape_string($con, $hotel_id);
	$chain_id = mysqli_real_escape_string($con, $chain_id);

	if( $chain_id == 0 )
	{
		$where = "hotel_id=$hotel_id";
	}else{
		$where ="hotel_id IN (SELECT id_hotel FROM cadena_hotel WHERE id_cadena=$chain_id) ";
	}
	
	$select_staff_hotel = "SELECT COUNT(id) as n FROM hotel_staff_hotels
	WHERE hotel_staff_id = $staff_id AND $where";

	$belongs_to_hotel = lectura($select_staff_hotel);
	if ($belongs_to_hotel['n'] > 0) {
		return true;
	} else {
		return false;
	}
}

?>