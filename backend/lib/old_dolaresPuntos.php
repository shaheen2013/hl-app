<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

function calcularDolarPuntos($dolares){
	$sql = "SELECT equivalencia FROM dolar_puntos";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	$puntos = $dolares * $row['equivalencia'];
	return $puntos;
}
?>