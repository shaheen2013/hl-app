<?php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//Set a test cookie in local or production environments
function createCookie($env, $storeArray, $hotelId, $referrerId, $cadenaId, $uID = false){

	$uniqueId = $uID;

	//if existed before update the cookie else create a new one
	if(!$uID){

		//Store cookie in DDBB and get the unique ID
		$storeCookie = storeCookieReference($storeArray, $hotelId, $referrerId,$cadenaId);
		//Guarda el UID de la cookie
		$storeArray['hluid'] = $storeCookie;

		$uniqueId = $storeCookie;

	}else {

		//Cookie has unique ID so not new, update timestamp
		updateCookieReference($uID);

	}

	//Make string
	$storeInCookie = json_encode($storeArray, true);
	//setcookie(name,value,time,path,domain,secure);
	//Create cookie depending environment
	if($env === 'test'){
		setcookie('hltc_'.$hotelId.'_'.$cadenaId.'',$storeInCookie,time()+31556926,'/','.hotelinking.local', true, true);
	}else{
		setcookie('hltc_'.$hotelId.'_'.$cadenaId.'',$storeInCookie,time()+31556926,'/','.hotelinking.com', true, true);
	}
	//Return Cookie Unique ID
	return $uniqueId;
}

//Create cookie information
function createCookieArray($referrer, $hotel, $token, $uID = false, $cadena){
	//Create array
	$storeArray = array(
		'hlr' 	=> 	$referrer,
		'hlh' 	=> 	$hotel,
		'hlt' 	=> 	$token,
        'hlcid' =>  $cadena
	);
	//If UID exists add it
	if($uID){
		$storeArray['hluid'] = $uID;
	}

	return $storeArray;
}

//Check if cookie exists
function checkCookie($name){
	if(!isset($_COOKIE[$name])){
		return false;
	}
	return true;
}

//Open cookie
function openCookie($name){
	$cookie = $_COOKIE[$name];
	$cookie = stripslashes($cookie);
	$originalCookie = json_decode($cookie, true);
	return $originalCookie;
}

//Create a new cookie
function storeCookieReference($reference, $hotelId, $referrerId, $cadenaId){
	$con 		=     	conectar();
	$referrer 	= 		mysqli_real_escape_string($con, $referrerId);
	$hotel 		=    	mysqli_real_escape_string($con, $hotelId);
	$token 		=    	mysqli_real_escape_string($con, $reference['hlt']);
	$date 		=     	date("Y-m-d H:i:s");
  $cadena 	=   	mysqli_real_escape_string($con, $cadenaId);
	$userAgent 	= 		$_SERVER['HTTP_USER_AGENT'];

	$sql = "INSERT INTO tracking_cookies (cookie_id, referrer_id, hotel_id, created_at, cadena_id, user_agent)
			VALUES ('$token', '$referrer', '$hotel', '$date',";
	empty($cadenaId)? $sql .= " NULL " : $sql .= " '$cadena'";
	$sql .= " , '$userAgent') ON DUPLICATE KEY UPDATE last_modified=(NOW())";
	return escritura($sql, $con);
}

//Update cookie reference
function updateCookieReference($id){
	$sql = "UPDATE tracking_cookies SET last_modified = CURRENT_TIMESTAMP, created_times =  created_times + 1 WHERE id = $id";
	escritura($sql);
}

//Update cookie referral
function updateCookieReferral($referral, $id){
    if($id && $referral) {
        $sql = "UPDATE tracking_cookies SET referral_id = $referral WHERE id = $id";
        escritura($sql);
    }
}

// function updateCookieByToken($token, $referral_id){
// 		if($token && $referral_id){
// 			$sql = "UPDATE tracking_cookies SET referral_id = $referral_id WHERE cookie_id = $token";
// 		}
// 		return escritura($sql);
// }

//Get cookie by Id
/*function getCookieById($id){
	$id = sqlEscape($id);
	$sql = "SELECT id FROM tracking_cookies WHERE id = '$id' LIMIT 1";
	$row = lectura($sql);
	return $row['id'];
}*/

//Get all info from cookie
function getAllCookieById($id)
{
	$con = conectar(1);
	$id = mysqli_real_escape_string($con, $id);
	$sql = "SELECT * FROM tracking_cookies WHERE id = '$id' LIMIT 1";
	$row = lectura($sql, $con);
	return $row;
}

// FX para buscar si ya existe una cookie con ese referrer, referral y hotel
function getCookieReferrerHotel($referrer_id, $referral_id, $id_hotel)
{
	$con = conectar(1);
	$referrer_id = mysqli_real_escape_string($con, $referrer_id);
	$referral_id = mysqli_real_escape_string($con, $referral_id);
	$id_hotel = mysqli_real_escape_string($con, $id_hotel);

	$sql = "SELECT id FROM tracking_cookies
	WHERE referrer_id=$referrer_id AND referral_id=$referral_id AND hotel_id=$id_hotel ORDER BY id DESC LIMIT 1";
	$row = lectura($sql, $con);
	return $row['id'];
}

/* FX para borrar cookies
// Para borrar una cookie son necesarios los siguientes datos:
//		- referrer_id
//		- hotel_id
//		- cookie_id
// Además, no puede borrar cookies que tengan referral_id
*/
function deleteCookieById($id, $referrer_id, $hotel_id, $cookie_id)
{
	$con 			= conectar();
	$id 			= mysqli_real_escape_string($con, $id);
	$referrer_id 	= mysqli_real_escape_string($con, $referrer_id);
	$hotel_id 		= mysqli_real_escape_string($con, $hotel_id);
	$cookie_id 		= mysqli_real_escape_string($con, $cookie_id);
	$sql = "DELETE FROM tracking_cookies
	WHERE id = $id AND referrer_id = $referrer_id AND hotel_id = $hotel_id AND cookie_id = '".$cookie_id."'
	AND referral_id IS NULL ";
	escritura($sql, $con);
}

//Get cookie referrer by cookie id
function getCookieReferrer($id)
{
	$con = conectar();
	$id  = mysqli_real_escape_string($con, $id);
	$sql = "SELECT referrer_id FROM tracking_cookies WHERE id = '$id' LIMIT 1";
	$row = lectura($sql, $con);
	return $row['referrer_id'];
}
