<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

function guardarPassStaff($pass, $id_staff)
{
	$pass = mysqli_real_escape_string(conectar(), $pass);
	
	$sql = "UPDATE hotel_staff SET password='".$pass."' WHERE id='".$id_staff."'; ";
	mysqli_query (conectar(), $sql) or die(mysqli_error());
}

function guardarNombreStaff($nombre, $id_staff)
{
	$nombre = mysqli_real_escape_string(conectar(), $nombre);
	
	$sql = "UPDATE hotel_staff SET nombre='".$nombre."' WHERE id='".$id_staff."' ";
	mysqli_query (conectar(), $sql) or die(mysqli_error());
}

function obtenerdatosStaff ($id_staff)
{
	$sql = "SELECT nombre, password FROM hotel_staff WHERE id='".$id_staff."' ";
	$rs = mysqli_query (conectar(), $sql) or die(mysqli_error());
	$row = mysqli_fetch_assoc($rs);
	liberar ($rs);
	return $row;
}
?>