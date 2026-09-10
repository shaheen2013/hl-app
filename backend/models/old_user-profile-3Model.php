<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

include_once LIB.'permisosUsuario.php';	

function InsertCountrylist ($countrylist){
	$sql2 = "INSERT INTO user_paises (id_usuario, id_pais) VALUES ('".$_SESSION['u_logueado']."', '".$countrylist."') ";
	mysqli_query (conectar(), $sql2);
}

function borrarCountrylist(){
	$sql = "DELETE FROM user_paises WHERE id_usuario='".$_SESSION['u_logueado']."' ";
	mysqli_query (conectar(), $sql);
}

//Funcion que devuelve un array con todos los paises de la tabla "paises"
function obtenerPaises(){
	$sql = "SELECT * FROM paises";
	$rs = mysqli_query (conectar(), $sql);
	$i=0;
	while ($row = mysqli_fetch_assoc($rs)) {
		$arrayPaises[$i]['id']=$row['id_pais'];
		$arrayPaises[$i]['pais']=$row['pais'];
		$i++;
	}
	liberar ($rs);
	return ($arrayPaises);
}

function obtenerPaisesUsuario(){
	$arrayPaisesUsuario = array();
	$sql = "SELECT id_pais FROM user_paises WHERE id_usuario='".$_SESSION['u_logueado']."' ";
	$rs = mysqli_query (conectar(), $sql);
	$i=0;
	while ($row = mysqli_fetch_assoc($rs)){
		$sql2 = "SELECT pais FROM paises WHERE id_pais='".$row['id_pais']."' ";
		$rs2 = mysqli_query (conectar(), $sql2);
		$row2 = mysqli_fetch_assoc($rs2);
		liberar($rs2);
		$arrayPaisesUsuario[$i]['pais']=$row2['pais'];
		$arrayPaisesUsuario[$i]['id_pais']=$row['id_pais'];
		$i++;
	}
	liberar($rs);
	
	return ($arrayPaisesUsuario);
}
?>