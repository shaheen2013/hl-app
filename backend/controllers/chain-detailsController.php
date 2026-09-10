<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//contenido solo disponible si logueado
include LIB.'logueado.php';
hotelStaffLanding ();// Si no esta logueado lo manda a la landing
include LIB.'isIndependent.php';
include LIB.'cadenaPantalla.php';
include LIB . 'obtenerdatosHotel.php';
include_once LIB.'subirArchivos.php';
include_once LIB.'sanitize.php';
include_once LIB.'generarUrlCorrecta.php';

// API CONNECTION
include_once APP . 'Services/Connections/ApiGatewayConnection.php';

// obtener $id_cadena
if (empty($_SESSION['c_logueado'])){
    $id_cadena = obtenerIdCadena($_SESSION['h_logueado']);
    // Borrar email y pass del hotel
    borrarDatosHotel($_SESSION['h_logueado']);
} else {
    $id_cadena = $_SESSION['c_logueado'];
}

// For front purposes
$currentPage = 'chain-management';
$currentSubPage = 'add-chain-info';
$arrayCountryLangs = getCountryLangs();
// datos cadena
if (!empty($_POST['save-chain-details']))
{
    global $log;
    $nombre = $_POST['chainName'];
    $descripcion = $_POST['chainDescription'];
    $email_contacto = $_POST['ChainContactEmail'];
	$tel_contacto = $_POST['ChainContactNumber'];
    $chain_color = $_POST['chainColor'];

    // updating account information

    $payload = [
		'account' => [
			'account_name' => $nombre,
			'description' => $descripcion,
			'contact_email' => $email_contacto,
			'contact_tel' => $tel_contacto,
            'stay_time' => $_POST['stay_time'],
            'loyalty_min_visits' => $_POST['loyalty_min_visits'],
            'background_color' => $chain_color
		]
	];

    $gateway = new ApiGatewayConnection();
    $endPoint = HOTELINKING_ENDPOINT . "accounts/" . $_SESSION['hotel']['parent_brand_id'] . "/info";

    try {
        $gateway->sendRequest($payload, $endPoint, 'PUT');
        //Delete from cache
        deleteCacheByTags(array('cadena_profile_' . $id_cadena, 'cadena_email_' . $id_cadena));

    } catch (Exception $e) {
        $log->error('Error updating account profile', ['message' => $e->getMessage(), 'error'=> $e]);
        return [];
    }
	
	actualizarWebsitesReserva(intval($_SESSION['chain']['brand_id']), $_POST['chainWebsite']); 

    $countryIds=array_column ($arrayCountryLangs, 'id');
    $chainCountryIds = array_values (array_intersect($countryIds,array_keys ( $_POST)));
//    If the hotel has configuration for more countries we save them too
    if(sizeof($chainCountryIds) > 0){
        foreach($chainCountryIds as $chainCountryId){
            updateChainCountryLangUrl($id_cadena,$chainCountryId, $_POST[$chainCountryId]);
        }
    }
	
	$ok = array(true, '2007');
}
elseif(!empty ($_POST['hotelAddUrlLang'])){
    insertChainCountryLangUrl($id_cadena, $_POST['country']);
    $ok = array (true, '2007');
}
elseif(!empty ($_POST['deleteLang'])){
    updateChainCountryLangUrl($id_cadena, $_POST['country_lang_id'],'',0);
    $ok = array (true, '2007');
}

if (!empty($_SESSION['c_logueado']) && !empty($_SESSION['hotel']['parent_brand_id'])) {

	$arrayDatosCadena = obtenerDatosChainDetails($_SESSION['c_logueado']);

	$brandLanguages = getAllLanguagesFromBrand($_SESSION['hotel']['parent_brand_id']);
	$languages = getAllLanguages();

	$_SESSION['logo_cadena'] = $arrayDatosCadena['logo'];

} else {
	$arrayDatosCadena = array('nombre'=>'', 'descripcion'=>'','logo'=>'','email'=>'','numero'=>'', 'background_color'=>'');
}

$arrayChainCountryLangs=getChainCountryLangUrl($id_cadena);
/*echo '<pre>';
print_r($arrayDatosCadena);
echo '</pre>';*/
?>