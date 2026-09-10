<?php
//ob_start();
//Can´t access directly to this file
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}

header('Location: /' . $urlTree['404']);
exit;

include_once LIB . 'isBot.php'; // crea $isFacebook. Si es un bot -> exit

//Include Libraries
//Include affilired library for creating cookies
//Incluye lang para el iframe Dialog
include_once LANG . $_SESSION['userLang'] . "/redirect.php";

include_once LIB . 'affilired.php';
//Obtener datos hotel
include_once LIB . 'obtenerdatosHotel.php';
//Obtener datos del usuario
include_once LIB . 'obtenerDatosUsuario.php';
//cookies
include_once LIB . 'cookies.php';

//Load variables
//From get -->
$referrerGuid = $_GET['r']; //'4b161c3d-5c77-4384-b687-0a42ebfe6ff1';
//echo $referrerGuid . '<br>';
$hotelGuid = $_GET['h']; //'54db25ee-b858-4308-bcc4-ee0acd772825';
//echo $hotelGuid . '<br>';
$token = $_GET['t']; //'thisisaverycomplcatedtoken';
//echo $token . '<br>';
//From get -->
$hotelId = obtenerIdHotelGUID($hotelGuid);
//echo $hotelId . '<br>';;
$referrerId = obtenerIdUsuarioGUID($referrerGuid);
//echo $referrerId . '<br>';

//From affilired library -->
$affiliredId = getAffiliredId($hotelGuid);
//From affilired library -->
$affiliredUrl = 'https://scripts.affilired.com/?adnid=' . $affiliredId . '&adnetwork=hlink&a=4461';
$cookieUniqueId = NULL;
//Check if this hotel and user exists
if (!empty($hotelId) && !empty($referrerId)) {
    //Get Hotel website
    $hotelWebsite = obtenerWebsiteReservaHotel($hotelId);
    $checkToken = obtenerTokenShareUsuario($referrerId, $hotelId);
    //echo $checkToken. '<br>';
    $cadenaId = checkChain($hotelId);
    //Con el token del usuario
    if ($checkToken != '') {
        //echo 'existe token' .'<br>';
        //check if both tokens are equal
        if ($checkToken == $token) {
            //Si el navegador es de Facebook nos saltamos el proceso de la cookie
            if ($isFacebook != 'Facebook') {
                //echo 'token correcto' .'<br>';
                //Create an array with values
                $storeInCookie = createCookieArray($referrerGuid, $hotelGuid, $token, false, $cadenaId);
                //Check if the cookie exists.
                if (!checkCookie('hltc_' . $hotelId . '_' . $cadenaId . '')) {
                    //echo 'No existe cookie, creando nueva'.'<br>';
                    //Create a new cookie and store reference in DDBB
                    $log->info("redirectController cookies : ", [ "hotel_id" => $hotelId , "cadena_id " => $cadenaId , "referrer_id " => $referrerId ]);
                    $createCookie = createCookie(ENV, $storeInCookie, $hotelId, $referrerId, $cadenaId);
                } else {
                    //echo 'existe cookie hltc_'.$hotelId.'_'.$cadenaId .'<br>';
                    //Get old information from the cookie
                    $originalCookie = openCookie('hltc_' . $hotelId . '_' . $cadenaId . '');
                    //echo 'Abriendo la cookie' .'<br>';
                    //echo '<pre>';
                    // print_r($originalCookie);
                    //echo '</pre>';
                    //Get the unique ID
                    $cookieUniqueId = $originalCookie['hluid'];
                    //echo 'Unique ID ' .$cookieUniqueId.'<br>';
                    //Get the original referrer
                    $originalReferrer = $originalCookie['hlr'];
                    //echo 'Original Referrer ' .$cookieUniqueId .'<br>';
                    //If referrer is empty or some has some problem create new cookie with new data
                    if (empty($originalReferrer)) {
                        //echo 'el referrer está vacio, actualizando cookie<br>';
                        //Create a new cookie and store in DDBB
                        $createCookie = createCookie(ENV, $storeInCookie, $hotelId, $referrerId, $cadenaId, $cookieUniqueId);
                        $log->info("redirectController2 cookies : ", [ "hotel_id" => $hotelId , "cadena_id " => $cadenaId , "referrer_id " => $referrerId ] );
                    } else {
                        //echo 'el referrer es correcto' .'<br>';
                        //Create a new array for store in the cookie, mantain original referrer
                        $storeInCookie = createCookieArray($originalReferrer, $hotelGuid, $token, $cookieUniqueId, $cadenaId);
                        //Create new cookie
                        $createCookie = createCookie(ENV, $storeInCookie, $hotelId, $referrerId, $cadenaId, $cookieUniqueId);
                        $log->info("redirectController3 cookies : ", [ "hotel_id" => $hotelId , "cadena_id " => $cadenaId , "referrer_id " => $referrerId ]);
                        //echo 'regenerada la cookie' .'<br>';
                    }
                }
            }
        } else {
            $redirectError = true;
        }
    } else {
        $redirectError = true;
    }
} else {
    $urlError = true;
}

if (empty($urlError)) {

    if (empty($redirectError)) {
        //$websiteRedirect = $hotelWebsite . '?hltoken=' . $token;
        $websiteRedirect = createRedirect($hotelWebsite, 'hltoken', $token);
    } else {
        $websiteRedirect = $hotelWebsite;
    }
}

//ob_end_flush();

//HELPERS//
function checkChain($hotelId)
{
    //check si el hotel es de cadena
    $esDeCadena = hotelDeCadena($hotelId);

    if ($esDeCadena) {
        $idCadena = hotelIdCadena($hotelId);
        return $idCadena;
    }
    return false;
}

//Crea la URL del hotel
//$bookingEngineUrl = createUrl($website, $info['getParam'], $promoResponse['bookingEngineCode'], $promoCode);
function createRedirect($url, $param, $paramValue)
{

    $query = parse_url($url, PHP_URL_QUERY);

    if ($query) {
        $url .= '&' . $param . '=' . $paramValue;
    } else {
        $url .= '?' . $param . '=' . $paramValue;
    }
    return $url;
}