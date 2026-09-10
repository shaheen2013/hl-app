<?php //Miramos si esta definida la variable de control de index.php
use GuzzleHttp\Psr7\Query;

if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//Contenido solo visible si logueado
include LIB . 'logueado.php';
include LIB . 'pager.php';
hotelStaffLanding();// Si no esta logueado lo manda a la landing

// For front purposes
$currentPage = 'staff-management';
$currentSubPage = 'staff-management';

require_once __DIR__ . '/../src/Services/Connections/ApiGatewayConnection.php';
include_once LIB.'ordenacion.php';
include_once LIB.'obtenerDatosStaff.php';	


$hotel_id = array_get($_SESSION, 'h_logueado');
$gateway = new ApiGatewayConnection();

$requestUri = parse_url($_SERVER['REQUEST_URI']);
$queryString = Query::parse($requestUri['query'] ?? '');
$pathUrl = rtrim($requestUri['path'], '/') . '/';

$currentPageUrl = $pathUrl . (!empty($queryString) ? '?' . Query::build($queryString) : '');

//If logged as chain, allow to delete staff of the hotels
$id_cadena = array_get($_SESSION, 'c_logueado', '0');
$brandId = $_SESSION['loggedParentBrandID'] ? $_SESSION['loggedParentBrandID'] : $_SESSION['loggedBrandID'];
$brandType = $_SESSION['loggedParentBrandID'] ? "chain" : "hotel";

function checkStaffEmailStatuses($emails) {
    global $log;
    $verificationStatuses = [];

    $config = AWS_SUITE;
    $aws = new \Aws\Sdk($config);
    $cognitoClient = $aws->createCognitoIdentityProvider();

    foreach ($emails as $email) {
        try {
            $user = $cognitoClient->adminGetUser([
                'UserPoolId' => $config['user_pool_id'],
                'Username' => $email,
            ]);
            $status = $user['UserStatus'] === 'CONFIRMED' ? 1 : 0;
            $verificationStatuses[$email] = $status;

        } catch (\Exception $e) {
            $log->error("Error verifying Cognito status for $email", [$e]);
            $verificationStatuses[$email] = 0;
        }
    }
    return $verificationStatuses;
}


if(!empty($_GET['error'])){
    $ok = array (false, $_GET['error']);
}

if( !empty($_GET['del']) && $hotel_id)
{
	// Borrar staff
	$id_staff = $_GET['del'];
	$staffEmail = $_GET['staffEmail'];
	
	try {
		$response = $gateway->sendRequest(
			["brand_type" => $brandType], 
			HOTELINKING_ENDPOINT . 'brands/' . $brandId . '/staffs/' . $id_staff, 
			'DELETE'
		);

		$config = AWS_SUITE;
		$aws = new \Aws\Sdk($config);
		$cognitoClient = $aws->createCognitoIdentityProvider();

		$cognitoClient->adminDeleteUser([
			'UserPoolId' => $config['user_pool_id'],
			'Username' => $staffEmail
		]);

		$ok =  array (true, '2016');
	} catch (Exception $e) {
		global $log;
        $log->error("Error deleting staff", [$e]);
		$ok =  array (false, '4002');
	}
}

if( !empty($_GET['send']) )
{
	// Reenviamos la invitación al staff. Solo para staffs no verificados

	$id_staff = $_GET['send'];
	
	// Generamos un nuevo pass (el que hay esta en SHA1)
	include_once LIB . 'generarPass.php';
	$pass = generaPass();
	$datosStaff = obtenerDatosInvitacionStaff($id_staff, $_SESSION['h_logueado'], $id_cadena);

	if($datosStaff['activo'] == '0')
	{
		include_once LIB . 'invitaciones.php';
		//Cambiar pass por en generado nuevamente
		cambiarPassInvStaff($id_staff, $pass);
		include_once LIB . 'obtenerDatosStaff.php';
		$datosHotel = obtenerDatosHotelStaff(array($_SESSION['h_logueado']));
		// Enviamos la invitación nueva
		crearInvitacionStaff($datosStaff['email'], $datosStaff['nombre'], $pass, $datosHotel, $id_staff, $datosStaff['token']);
		//Feedback
		$ok = array(true, '2035');
	}else{
		// La cuenta de Staff ya esta activa, al activar la cuenta se borra la invitación
	}
}

// Campo ORDER BY
$pant='stffm'; // Pantalla
if(!empty($_GET['ord']))
{
	$order = $_GET['ord'];
	$_SESSION['ord'.$pant] = $_GET['ord'];
	$sort = toggle();
}else if(!empty($_SESSION['ord'.$pant])){
	$order = $_SESSION['ord'.$pant];
	$sort = $_SESSION['ascdesc'];
}else{
	// Orden por defecto
	$order = 'id';
	$sort = 'ASC';
}

$page = array_get(	$_GET, 'pag', 1);
$itemsPage = array_get($_GET, 'per_page', 10);
$staffParams = [
	"brand_type" => $brandType,
	"page" => $page,
	"per_page" =>  $itemsPage,
	"sort_field" => $order,
	"sort_order" => $sort
];



try {
	$staffs = json_decode($gateway->sendRequest($staffParams, HOTELINKING_ENDPOINT . 'brands/' . $brandId . '/staffs', 'GET'), true);
	
    $emails = array_map(function ($staff) {
        return $staff['email'];
    }, $staffs['data']);
    $verificationStatuses = checkStaffEmailStatuses($emails);
   
	foreach ($staffs['data'] as &$staff) {
		$staff['verified'] = $verificationStatuses[$staff['email']] ?? 0;
    }
	unset($staff);
}

catch (Exception $e) {
	global $log;
	$log->error("Error retrieving staff", [$e]);
	$staffs = null;
}
$totalStaffs = $staffs['meta']['total'];
$numberPages = ceil($totalStaffs / $itemsPage);
$paginate = getPager($numberPages, $page)

?>
