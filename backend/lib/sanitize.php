<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

function string_sanitize($string){
	// espacios por -
	$string = str_replace(' ', '-', $string);
	
	$originales = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
    $modificadas = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyrr';
	$string = mb_convert_encoding($string, 'ISO-8859-1', 'UTF-8');
    $string = strtr($string, mb_convert_encoding($originales, 'ISO-8859-1', 'UTF-8'), $modificadas);
	
	// minusculas
	$string = strtolower($string);
	
	$strip = array("~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "_", "=", 
	"+", "[", "{", "]", "}", "\\", "|", ",", ";", ":", "\"", "'", "&#8216;", "&#8217;", 
	"&#8220;", "&#8221;", "&#8211;", "&#8212;", "â€”", "â€“", '·',",", "<", ".", ">", "/", "?");
    foreach ($strip as $valor){
		$string = str_replace($valor,'',$string);
	}
	$string = filter_var($string, FILTER_SANITIZE_URL);
	return ($string);	
}

// sanitiza un archivo con extension
//function sanitizeNombreImg($Img){
function archivoExtension($Img){
	$extension = pathinfo($Img, PATHINFO_EXTENSION);
	$nombre = string_sanitize(pathinfo($Img, PATHINFO_FILENAME));
	$nombreSan=$nombre.'.'.$extension;
	return $nombreSan;
}
?>
