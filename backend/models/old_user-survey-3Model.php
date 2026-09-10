<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

include_once LIB.'permisosUsuario.php';

include_once LIB.'sanitize.php';
include_once LIB.'fecha.php';

// Miramos si ya esta compartida esta encuesta
function encuestaCompartida($id_encuesta){
	$sql = "SELECT COUNT(id) AS n FROM user_shares WHERE id_encuesta='".$id_encuesta."' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	if($row['n']>='1'){
		return true;
	}else{
		return false;
	}
}
// Miramos si esta encuesta es de este usuario
function encuestaDeUsuario($id_usuario, $id_encuesta){
	$sql = "SELECT COUNT(id) AS n 
	FROM user_encuestas WHERE id='".$id_encuesta."' AND id_usuario='".$id_usuario."' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	if($row['n']=='1'){
		return true;
	}else{
		return false;
	}
}

/*function obtenerDatosEncuesta($id_encuesta){
	$sql = "SELECT hoteles.id, hotelName 
	FROM user_encuestas
	INNER JOIN hoteles ON hoteles.id=user_encuestas.id_hotel
	WHERE user_encuestas.id='".$id_encuesta."' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	
	//san
	
	return $row;
}*/
?>