<?php
// Enable CORS 
header("Access-Control-Allow-Origin: *");

include_once 'librerias.php';// Librerias básicas

//include_once RUTA_DIR.LIB.'check_access.php';
//checkIpAccess('appro', gethostbyaddr($_SERVER['REMOTE_ADDR']));

//Variable de control para saber si ha pasado por index.php
define("INDEXCONTROLVAL", "1");

include_once RUTA_DIR.LIB.'cuponAcciones.php';
include_once RUTA_DIR.LIB.'referrer.php';
include_once RUTA_DIR.LIB.'obtenerDatosCadena.php';

// Get all POSTs
$category = (!empty($_POST['category']) ? $_POST['category'] : false);
$userId = (!empty($_POST['userId']) ? $_POST['userId'] : false);
$hotelId = (!empty($_POST['hotelId']) ? $_POST['hotelId'] : false);
$promoCode = (!empty($_POST['promoCode']) ? $_POST['promoCode'] : false);
$validate = (!empty($_POST['validate']) ? $_POST['validate'] : false);
$env = (!empty($_POST['env']) ? $_POST['env'] : false);
$currency = (!empty($_POST['currency']) ? $_POST['currency'] : false);
$amount = (!empty($_POST['amount']) ? $_POST['amount'] : false);
$hotelName = $_POST['hotelName'];
//Check for minimun data
if(!$category || (!$userId && !$hotelName) || !$promoCode){
	//Return KO
	returnKO();
}
//Ger real userId and hotelId
if($category == 'c'){
	//Si la categoría es 'c' el userId es de cadena
	$userId = obtenerIdCadenaGUID($userId);
	//Si la cadena no existe salir
	checkExists($userId);
	$hotelId = obtenerIdHotelGUID($hotelId);
	//Si el hotel no existe salir
	checkExists($hotelId);
	//Evalua si el hotel pertenece a la cadena
	$evalUserId = hotelIdCadena($hotelId);
	if($userId !== $evalUserId){
		//Si no, salir
		returnKO();
	}
}else if($category == 'h'){
	//Si la categoría es 'h' el userId es de hotel
	//Ojo por que aquí se cambia el userId por hotelId
	//Para el resto del webservice, aunque se sigue evaluando si userId existe
	//ESPECIAL PARA AFFILIRED : Si hotelName existe, hay que buscar por hotel name
	if($hotelName != 'undefined'){
		$hotelName = urldecode ($hotelName);
		$hotelName = toASCII($hotelName);
		$hotelName = strtolower($hotelName);
		$hotelName = str_replace("&amp;", "and", $hotelName);
		$hotelData = obtenerIdHotelName($hotelName);
		$hotelId = $hotelData['id_hotel'];
		$guid = $hotelData['guid'];
		
	}else{
		$hotelId = obtenerIdHotelGUID($userId);
	}
	//Si el hotel no existe salir
	checkExists($hotelId);
}else{
	//Return KO
	returnKO();
}

//Leer promo code
if(($userId || $hotelName) && $promoCode && !$validate && !$amount){

	$log->info('Reading promo code', ['userId' => $userId, 'hotelName' => $hotelName, 'promo code' => $promoCode]);

	//Devolvemos el promocode
	obtenerDatosPromoCode($promoCode, $hotelId);
	exit;
}

//Validar Promocode
if($validate && $validate === "1" && $amount && $currency && ($userId || $hotelName)){

	$log->info('Validating promo code', ['validate' => $validate, 'amount' => $amount, 'currency' => $currency, 'userId' => $userId, 'hotelName' => $hotelName]);

	$result = canjearPromoCode($promoCode, $hotelId, $hotelId, 'hotel');
	$resultado = json_encode($result);
	echo $resultado;
	if ($result['code']=='200' && $amount!='' && $result['cuponId'] !='')
	{
		//Si se ha canjeado el cupon guardamos el booking value
		guardarBookingValue($hotelId, $result['cuponId'], $amount, $currency);
	}
	exit;
}else{
	//Return KO
	returnKO();
}

/////////////////////////////////////
//////FUNCTIONS//////////////////////
/////////////////////////////////////

function checkExists($id){
	if($id === null){
		//Return KO
		returnKO();
	}
};

function returnKO(){
	$response = array(
		"error" => "Invalid data sent"
		);
	echo json_encode($response);
	exit;
}

function toASCII( $str )
{
	return strtr(mb_convert_encoding($str, 'ISO-8859-1', 'UTF-8'),
	mb_convert_encoding(
			'ŠŒŽšœžŸ¥µÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýÿ', 'ISO-8859-1', 'UTF-8'),
		'SOZsozYYuAAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaaceeeeiiiionoooooouuuuyy');
}
?>