<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

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