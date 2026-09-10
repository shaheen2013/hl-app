<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

function obtenerStaff($hotel_id, $chain_id, $order, $sort, $itemsPage=1, $pagina=1)
{
	$order = mysqli_real_escape_string(conectar(1), $order);
	$inicio = $itemsPage*$pagina-$itemsPage;
	
	
	$sql = "SELECT hotel_staff.id, hotel_staff_hotels.hotel_id, hotel_staff.nombre, hotel_staff.email, GROUP_CONCAT(hoteles.hotelName) as hotelName,
	hotel_staff.activo AS verified, hotel_staff_roles.role_es AS rol ";
	$sql2 = "FROM hotel_staff 
	INNER JOIN hotel_staff_roles ON hotel_staff_roles.id=hotel_staff.id_role 
	INNER JOIN hotel_staff_hotels ON hotel_staff_hotels.hotel_staff_id=hotel_staff.id
	INNER JOIN hoteles ON hoteles.id=hotel_staff_hotels.hotel_id
	WHERE ";
	$sql2 .= queryHotelCadenaStaffManagement($hotel_id, $chain_id);
	$order_by = " ORDER BY ".$order." ".$sort;
	$group_by = " GROUP BY hotel_staff.id";
	$limit = " LIMIT ".$inicio.",".$itemsPage;

	$arrayStaff = array();
	$arrayStaff = lecturaArray($sql.$sql2.$group_by.$order_by);

	// Paginación
	$sql0 = "SELECT COUNT(DISTINCT(hotel_staff.id)) as N ";
	paginacion2($sql0.$sql2, $pagina, $itemsPage);
	
	return $arrayStaff;
}

// FX para generar el WHERE de varias querys de esta pantalla
function queryHotelCadenaStaffManagement($hotel_id, $chain_id)
{
	if( $chain_id == 0 )
	{
		//Logged as hotel
		$sql = "hotel_id=$hotel_id";
	}else{
		//Logged as chain
		$sql ="hotel_id IN (SELECT id_hotel FROM cadena_hotel WHERE id_cadena=$chain_id) ";
	}
	return $sql;
}

// FX para obtener los datos de una invitación de staff.
// Solo para staffs no activados. Los staffs activos no tienen invitaciones pendientes.
// Filtramos solo por staff del hotel / cadena
function obtenerDatosInvitacionStaff($staff_id, $hotel_id, $chain_id)
{	
	$sql = "SELECT verificar_email.email, verificar_email.token, hotel_staff.activo, hotel_staff.nombre
	FROM hotel_staff
	INNER JOIN verificar_email ON verificar_email.id_tipo=hotel_staff.id
	INNER JOIN hotel_staff_hotels ON hotel_staff_hotels.hotel_staff_id=hotel_staff.id
	WHERE verificar_email.id_tipo='$staff_id' AND tipo='stf' AND ";
	$sql .= queryHotelCadenaStaffManagement($hotel_id, $chain_id);	
	$sql .= " ORDER BY fecha DESC LIMIT 1";
	$row = lectura($sql);
	return $row;
}

// FX para cambiar el pass de la invitación del Staff
// Al reenviar la invitación nuevamente, debemos resetear el Pass
function cambiarPassInvStaff($staff_id, $pass)
{
	$passSha1 = sha1($pass);
	$sql = "UPDATE hotel_staff SET password='$passSha1' WHERE id=$staff_id";
	escritura($sql);
}
?>