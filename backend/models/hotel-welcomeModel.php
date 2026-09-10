<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

function comprobarHoteleroInsertado($email){
	//--------------------------------------------------------------
	// Recibe el mail y lo comprobamos en BD de hoteleros
	//--------------------------------------------------------------
	$sql = "SELECT * FROM hoteles WHERE email = '".$email."'";
	$row = mysqli_query (conectar(), $sql);
	$n_resultados = mysqli_num_rows($row);

	if ($n_resultados == 1 ){
		// Hotel ya insertado
		return(true);
	}else{
		// Hotel no insertado
		return(false);
	}
	liberar($row);
}

function obtenerDatosHotelToken($token){
	$sql = "SELECT IFNULL(email, 0) AS email, quota, hotelName
	FROM invitaciones_hotel WHERE token='".$token."' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	
	return $row;
}

function tokenCorrecto($token){
	$sql = "SELECT COUNT(id) AS n FROM invitaciones_hotel
	WHERE token='".$token."' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	if($row['n']=='0'){
		return false;
	}else{
		return true;
	}
}
?>