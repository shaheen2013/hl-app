<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//Contenido solo visible si logueado
include LIB . 'logueado.php';
hotelStaffLanding (); // if not logged send to landing

$config = AWS_SUITE;
$aws = new \Aws\Sdk($config);
$cognitoClient = $aws->createCognitoIdentityProvider();

// For front purposes
$currentPage = 'staff-management';
$currentSubPage = 'staff-management';

$new_staff_role = array_get($_GET, 'staffRole');
$staff_id = array_get($_GET, 'id');
$staffLang = array_get($_GET, 'staffLang');
$mfaRequired = array_get($_GET, 'mfaRequired');
$previousRole = array_get($_GET, 'previousRole');
$staffEmail = array_get($_GET, 'staffEmail');
$hotel_id = array_get($_SESSION, 'h_logueado');
$chain_logged = array_get($_SESSION, 'c_logueado', 0);
$new_staff_hotels = $chain_logged ? array_get($_GET, 'staff_hotel') : [$hotel_id]; 
global $log;

if ($new_staff_hotels != null && $new_staff_role != null) {
    include_once LIB.'obtenerDatosStaff.php';
    if(staff_belongs_to_hotel($staff_id, $hotel_id, $chain_logged))
	{
        update_staff($new_staff_hotels, $new_staff_role, $staff_id);

        try {
            $cognitoClient->adminUpdateUserAttributes([
                'UserAttributes' => [
                    [
                        'Name' => 'custom:lang',
                        'Value' => $staffLang,
                    ],
                    [
                        'Name' => 'custom:mfa_required',
                        'Value' => $mfaRequired,
                    ]
                    ],
                'UserPoolId' => $config['user_pool_id'],
                'Username' => $staffEmail
            ]);

            if ($mfaRequired === "false") {
                $cognitoClient->adminSetUserMFAPreference([
                    "UserPoolId" => $config['user_pool_id'],
                    "Username"   => $staffEmail,
                    "SMSMfaSettings" =>  [
                        'Enabled' => false,
                        'PreferredMfa' => false
                    ],
                    "SoftwareTokenMfaSettings" => [
                        'Enabled' => false,
                        'PreferredMfa' => false
                    ]
                ]);
            }
            

        } catch (\Exception $e) {
            $log->error("Error updating User Attributes", [$e]);
            header('Location: /'.$urlTree['staff-management'].'/?error=4112');
            exit();
        }

        if ($previousRole !== $new_staff_role) {
            $groups = [
                "1" => "ACCOUNT_ADMINS",
                "2" => "BRAND_ADMINS",
                "3" => "STAFF"
             ];

            $previousGroup = $groups[$previousRole];
            $newGroup = $groups[$new_staff_role];

            try {
                $cognitoClient->adminRemoveUserFromGroup([
                    'GroupName' => $previousGroup,
                    'UserPoolId' => $config['user_pool_id'],
                    'Username' => $staffEmail
                ]);
    
                $cognitoClient->adminAddUserToGroup([
                    'GroupName' => $newGroup,
                    'UserPoolId' => $config['user_pool_id'],
                    'Username' => $staffEmail
                ]);

            } catch (\Exception $e) {
                $log->error("Error updating User Role", [$e]);
                header('Location: /'.$urlTree['staff-management'].'/?error=4112');
                exit();
            }
        }

        $ok =  array (true, '2037');
    } else {
        //The staff does not belongs to this hotel/chain, not allow to delete
		$ok =  array (false, '4002');
    }
}

if (!empty($chain_logged))
{
    //Get all the hotels from chain to put in the select)
	include_once LIB . 'obtenerDatosCadena.php';
	$arrayHotelesCadena = obtenerHotelesCadenaBasico($_SESSION['c_logueado']);
}

if ($staff_id != null) {
    $actual_staff_role = get_actual_staff_role($staff_id);
    $staff_hotels = get_actual_staff_hotels($staff_id);

    try {
        $cognitoUser = $cognitoClient->adminGetUser([
            'Username' => $actual_staff_role['email'],
            'UserPoolId' => $config['user_pool_id'],
        ]);

        $userMfaRequired = getUserAttribute($cognitoUser, 'custom:mfa_required');
        $userLang = getUserAttribute($cognitoUser, 'custom:lang');
    } catch (\Exception $e) {
        $log->error("Error getting Cognito User on edit staff page", [$e]);
        header('Location: /'.$urlTree['staff-management'].'/?error=4111');
        exit();
    }

}

if (array_get($_SESSION,'userLang') == 'es') {
    $lang = 'es';
} else {
    $lang = 'en';
}

$roles = get_roles($lang);

function getUserAttribute($userInfo, $attributeToRetrieve)
{
    $attribute = array_first($userInfo['UserAttributes'], function ($key, $attribute) use ($attributeToRetrieve) {
        return array_get($attribute, 'Name') === $attributeToRetrieve;
    });

    return data_get($attribute, 'Value');

}
?>