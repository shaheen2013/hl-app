<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

function hotelCerradoFechaInicioFinBDFX($id_hotel)
{
	$id_hotel = mysqli_real_escape_string(conectar(), $id_hotel);
	$sql = "SELECT jan AS mes1, feb AS mes2 , mar AS mes3, apr AS mes4, may AS mes5, 
	jun AS mes6, jul AS mes7, ago AS mes8, sep AS mes9, oct AS mes10, nov AS mes11, 
	dece AS mes12
	FROM hoteles WHERE id='".$id_hotel."' ";
	$rs = mysqli_query (conectar(), $sql) or die(mysqli_error());
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
}


function comprobarPublicableBDFX()
{
	$sql = "SELECT estrellas, rating, max_rango, 
	jan, feb, mar, apr, may, jun, jul, ago, sep, oct, nov, dece
	FROM hoteles WHERE id='".$_SESSION['h_logueado']."' ";
	$rs = mysqli_query (conectar(), $sql) or die(mysqli_error());
	$row = mysqli_fetch_assoc($rs);
	liberar ($rs);
	return $row;
}


function obtenerLang($id_lang)
{
	$id_lang = mysqli_real_escape_string(conectar(), $id_lang);
	$sql = "SELECT id, lang, img, country
	FROM lang WHERE lang.lang='".$id_lang."' ";
	$rs = mysqli_query (conectar(), $sql) or die(mysqli_error());
	$row = mysqli_fetch_assoc($rs);
	liberar ($rs);
	return $row;
}



?>
