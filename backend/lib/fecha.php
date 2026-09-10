<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

// Devuelve fecha y hora de hoy
function dateTimeHoy(){
	$fechaHoy = date("Y-m-d H:i:s");
	return ($fechaHoy);
}

// Devuelve fecha de hoy
function dateHoy(){
	$fechaHoy = date("Y-m-d");
	return ($fechaHoy);
}

// Convierte fecha del tipo YYY-MM-DD --> DD-MM-YYYY o DD-MM-YYYY --> YYY-MM-DD
function girarFecha ($fecha, $caracter='-'){
	$explodeFecha = explode($caracter, $fecha);
	$fechaGirada = ''; // Fecha default
	if( !empty($explodeFecha[1]) && !empty($explodeFecha[2]) )
	{
		$fechaGirada = $explodeFecha[2].'-'.$explodeFecha[1].'-'.$explodeFecha[0];
	}
	return $fechaGirada;
}

// Convierte fecha del tipo YYY-MM-DD hh:mm:ss --> DD-MM-YYYY hh:mm:ss
function girarFechaHora ($fecha){
	$fechaHora = explode(' ', $fecha);
	$fechaGirada = girarFecha ($fechaHora[0]);
	$fechaHoraGirada = $fechaGirada.' '.array_get($fechaHora,'1', '');
	return $fechaHoraGirada;
}

// FX para comparar 2 unix time
// $from_time: unix time inicial
// $to_time: unix time final
// $unit: unidad de tiempo 'min', 'hour', 'day'
function compararUnixTime($from_time, $to_time, $unit)
{
	$result['unit'] = $unit;
	$result['amount'] = '';
	
	if($unit=='min'){
		$result['amount'] = floor(($to_time - $from_time) / 60);		
	}else if($unit=='hour'){
		$result['amount'] = floor(($to_time - $from_time) / 3600);
	}else if($unit=='day'){
		$result['amount'] = floor(($to_time - $from_time) / 86400);
	}else{
		//error
		$result['amount']='error';
	}
	return $result;
}

// 06-20-2016 -> 2016-06-20
function girarMesDiaAnyo($fecha){
	$fechaParts = explode('-', $fecha);
	return $fechaParts[2].'-'.$fechaParts[0].'-'.$fechaParts[1];
}
?>