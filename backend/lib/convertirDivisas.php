<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//http://www.xe.com/es/currencyconverter/convert/?Amount=45&From=EUR&To=USD

//Cuidado redondeos

function file_get_contents_utf8($fn) {
     $content = file_get_contents($fn);
      return mb_convert_encoding($content, 'UTF-8',
          mb_detect_encoding($content, 'UTF-8, ISO-8859-1', true));
}

function convertirDivisas($amount, $from, $to){
	if ($amount >0){
		$urlDivisas = "http://www.google.com/finance/converter?a=".$amount."&from=".$from."&to=".$to;
		$get = file_get_contents($urlDivisas);
		$get = explode("<span class=bld>",$get);
		$get = explode("</span>",$get[1]); 
		unset ($get[1]);
		$get = explode(" ",$get[0]);
		//unset ($get[1]);
		return $get[0];
	}
}

//Solo las divisas en un array
function obtenerDivisas(){
	$selectDivisas = obtenerSelectDivisas();
	$get = str_replace('<option value=', '', $selectDivisas);
	$get = array_keys($get);	
	return $get;
}

//Nos devuelve true si una divisa es válida
function divisaOk($coin){
	$divisas = obtenerDivisas();
	if(in_array($coin, $divisas)){
		return true;
	}else{
		return false;
	}
}

//Obtiene el select de divisas de Google
function obtenerSelectDivisas(){
	$urlDivisas = "http://www.google.com/finance/converter";
	$get = file_get_contents_utf8($urlDivisas);
	
	$get = explode('</div>',$get);
	unset ($get[2]);
	unset ($get[3]);
	unset ($get[4]);
	unset ($get[5]);
	unset ($get[6]);
	unset ($get[0]);
	
	$get = explode('<div class=',$get[1]);
	unset ($get[0]);
	
	//$get = preg_replace("<div class=\"sfe-break-top\">", ' ', $get[1]);
	$get = str_replace('sfe-break-top>', '', $get[1]);
	$get = str_replace('</div>', '', $get);
	//$get = explode('<option value=',$get);
	$get = strip_tags($get, '<option>');
	$get = explode('</option>',$get);
	$get[0] = substr($get[0], 1);
	
	foreach($get as $indice => $valor){
		$contenido = strip_tags($get[$indice]);
		$key = substr($valor, 17, 3);
		$monedas[$key]=$contenido;
	}
	unset ($monedas[0]);
	
	//$get = str_replace('<option value=', '', $get);
	//$get = explode(chr(10),  $get);
	
	/*$n = count($get);

	unset ($get[$n-1]);
	unset ($get[$n-2]);
	unset ($get[0]);
	unset ($get[1]);
	unset ($get[2]);
	
	foreach($get as $valor){
		$gets = explode('(', $valor);
		$idMoneda = substr ($gets[1], 0, -1);
		$moneda = substr ($gets[0], 0, -1);
		$monedas[$idMoneda] = $moneda;
	}*/
	
	return $monedas;
}

//$conversion = convertirDivisas('15', 'USD', 'EUR', 'de');
$selectDivisas = obtenerSelectDivisas();

/*echo '<pre>';
print_r($selectDivisas);
echo '</pre>';*/
?>