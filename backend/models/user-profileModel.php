<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//Viene del post
function insertarDatosDB1($userName, $fechaNacimiento, $userSex, $userCity){
	
	$sql = "UPDATE users SET nombre='".$userName."', fecha_nacimiento='".$fechaNacimiento."', sexo='".$userSex."', location='".$userCity."' WHERE id='".$_SESSION['u_logueado']."'";
	mysqli_query (conectar(), $sql);
}

function obtenerDatosUsuarioProfile($id){
	$sql = "SELECT 
	nombre, email, fecha_nacimiento, sexo, pais, provincia, location 
	FROM users WHERE id='".$id."' ";
	$rs = mysqli_query (conectar(), $sql);
	$arrayDatosUsuario = mysqli_fetch_assoc($rs);
	liberar ($rs);
	return ($arrayDatosUsuario);
}
?>