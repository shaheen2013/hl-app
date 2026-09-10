<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

function insertarHotel($id, $website, $phone, $categoria){
	//Actualizamos la invitación
	$sql = ("UPDATE invitaciones_hotel_pendientes SET website = '".$website."', telefono = '".$phone."', categoria_hotel='".$categoria."' WHERE id = ".$id."");
	$conexion = conectar();
	$query = mysqli_query ($conexion, $sql);

	//Si el update se ha hecho correctamente, completar la invitación
	if ($query){
		$conexion2 = conectar();
		//echo '--OK invitación creada <br>';
		$sql2 = ("UPDATE invitaciones_hotel_pendientes SET invite_completed = '1' WHERE id =".$id."");
		$query2 = mysqli_query ($conexion2, $sql2);
		return true;
	}else{
		//echo "--error al crear la invitación";
		return false;
	}
}

//Recoge el email del hotel en función del ID
function getHotelEmail($id){
	$sql = ("SELECT email FROM invitaciones_hotel_pendientes WHERE id = ".$id."");
	$conexion = conectar();
	$query = mysqli_query ($conexion, $sql);
	$results = mysqli_fetch_array($query,MYSQLI_ASSOC);
	$email = $results['email'];
	liberar($query);
	return $email;
};

//Genera un código de referido y lo guarda 
function testCode($code, $id){
	$sql = ("SELECT ref_code FROM invitaciones_hotel_pendientes WHERE ref_code = '$code'");
	$rs = mysqli_query(conectar(), $sql);
	$rows = mysqli_num_rows($rs);
	liberar($rs);
	//Checkea si el código de referencia existe
	if($rows == 0){
			$sql2 = ("UPDATE invitaciones_hotel_pendientes SET ref_code = '$code' WHERE id ='$id'");
			$query2 = mysqli_query (conectar(), $sql2);	
			return true;
		}else{
			return false;
	}
}

// añade un referido y devuelve el email y el referido para transaccional
function updateReferrals($referral){
		$sql2 = ("UPDATE invitaciones_hotel_pendientes SET referrals = referrals + 1 WHERE ref_code = '".$referral."'");
		$query2 = mysqli_query (conectar(), $sql2);
		$sql = ("SELECT email, referrals, id FROM invitaciones_hotel_pendientes WHERE ref_code = '".$referral."'");
		$query = mysqli_query (conectar(), $sql);
		$results = mysqli_fetch_array($query,MYSQLI_ASSOC);
		liberar($query);
		return $results;
}

// Averigua si el usuario no quiere recibir notificaciones
function getUnsubState($id){
	$sql = ("SELECT unsub FROM invitaciones_hotel_pendientes WHERE id = '$id'");
	$query = mysqli_query(conectar(), $sql);
	$results = mysqli_fetch_array($query,MYSQLI_ASSOC);
	return $results['unsub'];
}
?>