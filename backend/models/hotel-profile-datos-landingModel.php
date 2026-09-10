<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

function actualizarFotoLanding($fotoBg)
{
	$sql = "UPDATE hoteles SET fotoBg='".$fotoBg."'	WHERE id='".$_SESSION['h_logueado']."' ";
	//echo $sql;
	mysqli_query (conectar(), $sql);

	//Borrar cache
	deleteCacheByTag('hotel_profile_' . $_SESSION['h_logueado']);
}

function borrarBgAnterior($id_hotel, $img)
{
	$ruta = DIR_IMG_FICHA_HOTEL.$id_hotel.'/fotoBg/';
	if( file_exists($ruta.$img) && !empty($img) )
	{
		unlink ($ruta.$img);
		borrarThumbnail($ruta, $img);
	}
}


function obtenerImgBg($id_hotel)
{//Thumbnail
	$sql = "SELECT fotoBg FROM hoteles WHERE id='".$id_hotel."' ";
	// $rs = mysqli_query (conectar(), $sql);
	// $row = mysqli_fetch_assoc($rs);
	// liberar($rs);
	$row = lectura($sql);
	return $row['fotoBg'];
	// $img = 'med_'.$row['fotoBg'];
	// return $img;
}


function obtenerImgBgFull($id_hotel)
{
	$sql = "SELECT fotoBg FROM hoteles WHERE id='".$id_hotel."' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	$img = $row['fotoBg'];
	return $img;
}
