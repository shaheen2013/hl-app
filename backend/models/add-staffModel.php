<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

include_once LIB . 'invitaciones.php';

// FX para mirar si un email de staff ya existe como staff (staff_inactivo), hotel o cadena
function mirarSiEmailExisteStaff($email)
{
	$email = mysqli_real_escape_string(conectar(), $email);
	
	$sql = "SELECT
	(SELECT COUNT(id) FROM hotel_staff WHERE email='$email' AND deleted=0) AS staff, 
	(SELECT COUNT(id) FROM hotel_staff_inactivo WHERE email='$email') AS staff_inact, 
	(SELECT COUNT(id) FROM hoteles WHERE email='$email') AS hotel, 
	(SELECT COUNT(id) FROM cadena WHERE email='$email') AS cadena";
	$rs = mysqli_query (conectar(), $sql) or die(mysqli_error());
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	return $row;
}

// FX Para crear un staff y enviarle la invitación
// Crea el usuario en la tabla hotel_staff
// Crea la "invitación" en la tabla verificar_email, a la espera de que verifique el email
function invitarStaff($email, $name, $lang, $mfaRequired, $role_id, $hotels_id, $pass)
{
	borrarVerifAnteriores($email, 'stf');
	$userAttributes = [
		[
			'Name' => 'email',
			'Value' => $email,
		],
		[
			'Name' => 'email_verified',
			'Value' => 'true',
		],
		[
			'Name' => 'name',
			'Value' => $name,
		],
		[
			'Name' => 'custom:lang',
			'Value' => $lang,
		],
		[
			'Name' => 'custom:mfa_required',
			'Value' => $mfaRequired,
		]
	];

	if (!empty($_SESSION["loggedParentBrandID"])) {
		array_push($userAttributes, [
			'Name' => 'custom:account_id',
			'Value' => $_SESSION["loggedParentBrandID"],
		]);
	}
	
	if ($hotels_id) {
		include_once LIB . 'obtenerDatosStaff.php';
		$datosHotel = obtenerDatosHotelStaff($hotels_id);
		$brandIds = array_map(function($brandData)  {
			return array_get($brandData, 'brand_id');
		}, $datosHotel);
		array_push($userAttributes, [
			'Name' => 'custom:brand_id',
			'Value' => implode(",", $brandIds)
		]);
	} else {
		$hotels_id = array($_SESSION['h_logueado']);

		// Add brand id on custom attributes if is independent hotel
		if (!array_get($_SESSION, 'loggedParentBrandID')) {
			array_push($userAttributes, [
				'Name' => 'custom:brand_id',
				'Value' => array_get($_SESSION, 'loggedBrandID')
			]);
		}
	}
	//Fecha creación staff
	include_once LIB . 'fecha.php';
	$created_at = dateTimeHoy();
	
	//Insert user on hotel_staff (BD)
	$sql3 = "INSERT INTO hotel_staff 
	(email, nombre, id_role, activo, password, fecha_creado) 
	VALUES 
	('$email', '$name', $role_id, 1, '".sha1($pass)."', '$created_at')";
	$staff_id = escritura($sql3);
	
	//Insert hotels who can access user on hotel_staff_hotels
	$hotel_staff_hotels_values = array_map(function($hotel_id) use ($staff_id) {
        return " ($hotel_id,$staff_id)";
    }, $hotels_id);

	$sql4 = "INSERT INTO hotel_staff_hotels (hotel_id, hotel_staff_id) VALUES ".join(', ', $hotel_staff_hotels_values);
	escritura($sql4);

	// Registramos el staff para email a verificar
	try {
		$config = AWS_SUITE;
		$aws = new \Aws\Sdk($config);
		$cognitoClient = $aws->createCognitoIdentityProvider();
		$cognitoClient->adminCreateUser([
			'DesiredDeliveryMediums' => ["EMAIL"],
			'UserAttributes' => $userAttributes,
			'UserPoolId' => $config['user_pool_id'],
			'Username' => $email
		]);

		$groups = [
			"1" => "ACCOUNT_ADMINS",
			"2" => "BRAND_ADMINS",
			"3" => "STAFF"
 		];
		$group = $groups[$role_id];
		$cognitoClient->adminAddUserToGroup([
			'GroupName' => $group,
			'Username' => $email,
			'UserPoolId' =>  $config['user_pool_id'],
		]);

		return true;
	} catch (\Exception $e) {
		global $log;
		$log->error("Error Syncronizing Cognito User", ["email" => $email, "exception" => $e]);
		return false;
	}
}

function obtenerRoles()
{
	$arrayRoles = array();
	$sql = "SELECT id, role_".$_SESSION['userLang']." AS role FROM hotel_staff_roles ORDER BY role ASC";
	$rs = mysqli_query (conectar(), $sql) or die(mysqli_error());
	$i=0;
	while ($row = mysqli_fetch_assoc($rs)) {
		$arrayRoles[$i]['id_role']=$row['id'];
		$arrayRoles[$i]['role']=$row['role'];
		$i++;
	}
	liberar ($rs);
	return ($arrayRoles);
}
?>