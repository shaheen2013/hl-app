<?php 
// Enable CORS 
header("Access-Control-Allow-Origin: *");

include_once 'librerias.php';// Librerias básicas
//include_once RUTA_DIR.LIB.'check_access.php';
//checkIpAccess('iframeWS', gethostbyaddr($_SERVER['REMOTE_ADDR']));

//Variable de control para saber si ha pasado por index.php
define("INDEXCONTROLVAL", "1");
//Recibe llamadas desde el API V1.1 y en función del check que hay en el profile del hotel
//abre el iframe en la pantalla de checkout del hotel.
//La conexión a la BBDD se tiene que hacer a la RR.
//Si el check está activado devuelve su estado al API para que lance el google tag manager que
//abre el iframe.
include_once RUTA_DIR.LIB.'obtenerdatosHotel.php';

$guid = null;
$hotelName = null;


// $hotel_guid = array_get($_POST, 'userId', null);
// $token = array_get($_POST, 'token', null);
// $hotel_name = array_get($_POST, 'hotelName', null);
// $env = array_get($POST, 'env', null);

//existe un post con la variable de entorno
if(isset($_POST['env'])){
	if(isset($_POST['token']) || isset($_POST['userId']) ||  isset($_POST['hotelName'])){

		$logOp->info('IframeWebservice', $_POST);
		
		if(isset($_POST['token'])){
      $token = $_POST['token'];
			$guid = getGuidFromToken($token);
      $logOp->info('guid for token ' . $token. 'is : ' .$guid);
		} 

		//es de pre estancia
		if((isset($_POST['userId']) ||  isset($_POST['hotelName'])) && !isset($_POST['token'])){
			
			//Si hay userId tenemos el GUID del hotel
			if(isset($_POST['userId']) && isNotNullEmptyOrUndefined($_POST['userId'])){
				$guid = $_POST['userId'];
				$logOp->info('Hotel guid for iframe, is ' . $guid);
			};

			//Si hay hotelName tenemos el nombre del hotel (Para Affilired)
			if(isset($_POST['hotelName']) && isNotNullEmptyOrUndefined($_POST['hotelName'])){
				$hotelName = $_POST['hotelName'];
				$logOp->info('HotelName for iframe, is ' . $hotelName);
			};

		}


		//Comprueba que hay GUID y con el GUID busca un hotel
		if(!empty($guid)){

			$hotel_id = obtenerIdHotelGUID($guid);

			$logOp->info('Hotel id for iframe, is ' . $hotel_id);


		}else{

			//Tenemos el nombre de Affilired por lo que tenemos que mapearlo
			$hotelName = urldecode ($hotelName);
			$hotelName = toASCII($hotelName);
			$hotelName = strtolower($hotelName);
			$hotelName = str_replace("&amp;", "and", $hotelName);
			$hotelData = obtenerIdHotelName($hotelName);
			$hotel_id = $hotelData['id_hotel'];
			$guid = $hotelData['guid'];

		}

		//Si no devuelve ningún hotel KO
		if(!$hotel_id){
			$logOp->error('IframeWebservice error: could not retreive hotel_id from post', $_POST);

			returnKO();
			exit();
		}

		//Check el iframe en función del tipo
		if(isset($_POST['token'])){
			//Revisa el check landing
			$showIframe = checkShowIframe($hotel_id, true);
		}else{
			//Revisa el check pre estancia
			$showIframe = checkShowIframe($hotel_id, false);
		}

    //Devuelve el estado al API
    showIframe($showIframe, $guid);
    exit;
		
	}else{
		$logOp->error('IframeWebservice error: no token or userId or hotelName in $_POST', $_POST);
		returnKO();
		exit;
	}
}else{
	$logOp->error('IframeWebservice error: no environment in $_POST', $_POST);
	returnKO();
	exit;
}


///////////
//helpers//
///////////

function isNotNullEmptyOrUndefined($str){
	return !empty($str) && ($str !== 'undefined') && ($str !== 'null');
}

function returnKO(){
	$response = array(
		"code" => "404",
		"error" => "Invalid data sent"
		);
	echo json_encode($response);
	return;
}

function showIframe($showIframe = 'enabled', $guid){
	$response = array(
		"code" => "200",
		"showIframe" => $showIframe,
		"guid" => $guid
	);
	echo json_encode($response);
	return;
}

function toASCII( $str )
{
    return strtr(mb_convert_encoding($str, 'ISO-8859-1', 'UTF-8'), 
	mb_convert_encoding(
        'ŠŒŽšœžŸ¥µÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýÿ', 'ISO-8859-1', 'UTF-8'),
        'SOZsozYYuAAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaaceeeeiiiionoooooouuuuyy');
}
