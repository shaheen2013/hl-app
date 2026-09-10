<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

// FX para separar los campos de cada linea
// Separadores admitidos: "tabulador horizontal" y "punto y coma"
// Devuelve error 4046 si el archivo está mal formado
function separarCampos($linea)
{
	$result[0] = array();
	$result[1] = '200';//Msg error por defecto (No hay errores)
	
	// Explode por tabulador horizontal
	$result[0] = explode(chr(9), $linea);
	if (empty($result[0][1]))
	{
		// Si no hay tabuladores -> explode punto y coma
		$result[0] = explode(chr(59), $linea);
	}
	$result[0]=array_map('trim',$result[0]);//Limpiamos espacios en blanco
	if (empty($result[0]))
	{
		//Archivo mal formado, no tiene los separadores correctos
		//Creamos un array vacio para evitar notices
		$result[0]=array('0'=>'','1'=>'','2'=>'','3'=>'','4'=>'','5'=>'');
		//$result[0][0]=array('0'=>'','1'=>'','2'=>'','3'=>'','4'=>'','5'=>'');
		$result[1] = '4046';
	}
	return $result;
}

?>