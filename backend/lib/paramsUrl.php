<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

// Funcion mara añadir/modificar parametros de una URL
// $value: valor por el que se ordena
// $key: tipo de ordenación
// Ej: ord=nombre => $key=ord, $value=nombre
// Ej2: fecha=asc => $key=asc, $value=fecha
function addToURL($value, $key = 'ord') 
{
    $info = parse_url( $_SERVER["REQUEST_URI"] );
	//echo $info['path'].'<br />';
	if ( substr($info['path'], -1) != '/'){
		$info['path'] .='/';
	}
    parse_str( $_SERVER['QUERY_STRING'], $query );
    return /*$info['scheme'] . '://' . $info['host'] .*/ $info['path'] . '?' . http_build_query( $query ?  array_merge( $query, array($key => $value ) ) : array( $key => $value ) );
}

// Convertimos los parametros de la url en un array para poder tratarlo
// ?param1=1&parama2=2 => array('param1'=>'1', 'param2'=>'2')
function convertirParamsUrlArray($params)
{
	$split_parameters = explode('&', $params);
	$split_complete = array();
	for($i = 0; $i < count($split_parameters); $i++) {
        $final_split = explode('=', $split_parameters[$i]);
		if(!empty($final_split[1])){
			$split_complete[$final_split[0]] = $final_split[1];
		}
    }
	return $split_complete;
}

// Fx que quitar un determinado parametro de la url
function quitarParamUrl($param)
{
	$url = $_SERVER["REQUEST_URI"];//Url completa (con parametros)
	$params = $_SERVER['QUERY_STRING'];//Parametros url
	
	$auxParams = convertirParamsUrlArray($params);
	//Quitamos el actual parametro de la URL
	if(isset($auxParams[$param])){
		unset($auxParams[$param]);	
	}
	//Nuevos parametros
	if(!empty($auxParams)){
		$nuevosParametros = http_build_query($auxParams);
	}else{
		$nuevosParametros = '';
	}	
	$urlParseada = parse_url($url);
	
	//Construimos la nueva URL
	$nuevaURL = $urlParseada['path'].'?'.$nuevosParametros;
	/*if(!empty($nuevosParametros)){
		$nuevaURL .= '&';
	}*/
	return $nuevaURL;
}

// FX para Mirar si un param esta en la URL
// Devuelve: true, false
function mirarParamUrl($param)
{
	$urlParseada = convertirParamsUrlArray($_SERVER['QUERY_STRING']);
	if(isset($urlParseada[$param])){
		return true;
	}else{
		return false;
	}
}

// Para cada filtro generamos una URL sin el propio filtro para poder hacer un botón con cada filtro
// 		Ej: web.com/?filtro1=1&filtro2=2 => web.com/?filtro2=2
// 		En el botón pondriamos el result anterior + &filtro1=33
// De esta manera evitamos añadir el mismo parametro 2 veces en la URL
// $arrayWheres: array con los filtros permitidos
// $url: url actual completa
// $query: paramatros de la URL
// Ej parametros a pasar: ($arrayWheres, $_SERVER["REQUEST_URI"], $_SERVER['QUERY_STRING'])
/*function crearUrlsFiltros($arrayWheres, $url, $query)
{
	//URLs para añadir filtros
	$currentUrl = strtok($url,'?');
	$urlFiltros = array();
	foreach($arrayWheres as $filtro)
	{
		echo $filtro;
		$urlFiltros[$filtro] = quitarParamUrl($currentUrl, $query, $filtro);
	}
	return $urlFiltros;
}*/

// Creamos un array de filtros permitidos a partir de un array o un string con parametros $_GET
// $paramsUrl: contiene todos los parametros $_GET de la URL o un array con los parametros
// $arrayWheres: array con los filtros permitidos
// $array: 1 los parametros pasaso estan en un array, 0 estan en un string ($_GET)
function crearArrayFiltros($paramsUrl, $arrayWheres, $array=0)
{
	if($array==0)
	{
		// Convertimos los parametros de la URL en un array
		$paramsUrl = convertirParamsUrlArray($paramsUrl);
	}
	foreach ($arrayWheres as $key => $value){
		if(isset($paramsUrl[$key])){
			$arrayWheres[$key] = $paramsUrl[$key]; 
		}else{
			$arrayWheres[$key] = '';
		}
	}
	return $arrayWheres;
}

/*function obtenerFiltros($currentUrl, $params, $arrayNombres){
	foreach ($params as $key=>$valor){
		if(array_key_exists($key, $arrayNombres)){
			$urlFiltrada = quitarParamUrl($currentUrl, $params, $key);
			$arrayFiltros[$arrayNombres[$key]]='<a href="'.$urlFiltrada.'">'.$key.'</a>';
		}
	}
	return 	$arrayFiltros;
}*/

function explodeCampo($cadena, $explode){
	$i=0;
	foreach ($cadena as $campoAS){
		$campos = explode ( $explode , $campoAS);
		if(!empty($campos[1])){
			$filtro[$i]=$campos[1];
		}else{
			$filtro[$i]=$campos[0];
		}
		$i++;
	}
	return $filtro;
}

//Campos validos para campo ORD de tabla = a parametros del SELECT
//Campo: campo $_GET de la url (ord=campo)
//Campos: cadena con los campos de la SELECT validos. 
//Debemos quitar tablas (tabla.campo), alias (campo1 AS campo), ...
function campoOrdValido($campo, $cadenaCampos){
	//echo $campo.' '.$cadenaCampos;
	$cadenaCampos = str_replace('SELECT', '', $cadenaCampos);
	$cadenaCampos = str_replace('DISTINCT', '', $cadenaCampos);
	$cadenaCampos = str_replace("\n", "", $cadenaCampos);//Quitamos intros
	$cadenaCampos = str_replace("\r", "", $cadenaCampos);
	$cadenaCampos = trim(preg_replace('/\t+/', '', $cadenaCampos));//Quitamos tabuladores
	
	//Quitamos todo lo que este entre parentesis
	$filtro1 = preg_replace("/\(([^()]|(?R))*\)/", "", $cadenaCampos) . "\n";
	//explode por ,
	$filtro2 = explode ( ',' , $filtro1);
	//campo1 AS campo (Explode por AS)
	$filtro3 = explodeCampo($filtro2, ' AS ');
	//tabla.campo (Explode por .)
	$filtro4 = explodeCampo($filtro3, '.');
	//explode por espacio
	$filtro5 = str_replace(" ", '', $filtro4);//Quitamos espacios
	
	//Limpiamos array
	$array = array_map('trim', $filtro5);
	/*echo '<pre>';
	print_r($array);//Array final con los campos ORD válidos
	echo '</pre>';*/
	
	if( in_array ($campo, $array) ){//Miramos si el campo existe en el array filtrado final
		//echo $campo.' ORD correcto';
		return true;
	}else{
		//echo $campo.' ORD incorrecto';
		return false;
	}
}

//FX para quitar parametros de la URL. Nos pasan $_SERVER['REQUEST_URI']
//$bar: 1->url con barra/ al final, url sin barra
function urlSinParams ($requestUri, $bar=1 ){
	$urlActual = strtok($requestUri,'?'); //Url actual sin parametros
	if($bar==1){
		if( substr($urlActual, -1)!='/'){//Si la URL no tiene / al final, se lo ponemos
			$urlActual .='/';
		}
	}
	return $urlActual;
}

// Para saber si hay que poner & o ? para parametros url
// Devuelve los parametros (si lo hay)+ &/?
/*function ampInt(){
	if ($_SERVER['QUERY_STRING']!=''){
		return $_SERVER['REQUEST_URI'].'&';
	}else{
		return $_SERVER['REQUEST_URI'].'?';
	}
}*/

//setURL(‘http://mydomain.com?gender=male’, ‘site’, ‘back’);
/*function setURL($key, $value) {
	$url = $_SERVER['REQUEST_URI'].'/'.$_SERVER['QUERY_STRING'];
	
    $separator = (parse_url($url, PHP_URL_QUERY) == NULL) ? '?' : '&';

    $query = $key."=".$value;
    $url .= $separator . $query;
    
	return $url;
	//var_dump($url); exit;
}*/
?>