<?php

//Hotelinking mandatory libraries and methods
include_once 'librerias.php';
// Restringir ips que pueden acceder
include_once RUTA_DIR . LIB . 'check_access.php';
!defined('INDEXCONTROLVAL') ? define("INDEXCONTROLVAL", "1") : '';
//checkIpAccess('hl_app', $_SERVER['REMOTE_ADDR']);
include_once RUTA_DIR . LIB . 'obtenerdatosHotel.php';
include_once RUTA_DIR . LIB . 'portal_pro.php';

global $log;
global $logPortal;

function sanitizeRoomNumber($room)
{
    return preg_replace('/\s+/', "", strtolower($room));

}
function redirectToNormalPortal($roomNumber, $firstName, $lastName)
{
    global $logPortal;

    $_SESSION['showNormalPortal'] = true;
    $_SESSION['roomNumber'] = $roomNumber;
    $_SESSION['first_name_prefill'] = $firstName;
    $_SESSION['last_name_prefill'] = $lastName;

    $logPortal->info("Show normal portal", [
        "firstName"     => $firstName,
        "lastName"      => $lastName,
        "roomNumber"    => $roomNumber
    ]);

    return ['validated' => 'showNormalPortal', "radius_ticket_validated" => !empty($_SESSION['radiusTicket'])];
}

function checkRoomNumberInHotelikingPlatform($brandId, $roomNumber, $firstName, $lastName)
{
    global $logPortal;

    $brandAccessCodes = getBrandAccessCodes($brandId);

    $validRoomNumbers = array_map(function ($roomNumber) {
        return sanitizeRoomNumber($roomNumber);
    }, $brandAccessCodes);

    $sanitizedUserRoomNumber = sanitizeRoomNumber($roomNumber);


    if (in_array($sanitizedUserRoomNumber, $validRoomNumbers) || empty($validRoomNumbers)) {
        if (empty($validRoomNumbers)) {
            $logPortal->warning("Empty room number configured, let the user pass", ["brandId" => $brandId, "roomNumber" => $roomNumber]);
        }

        $resp = redirectToNormalPortal($roomNumber, $firstName, $lastName);

    } else {
        include_once RUTA_DIR . LIB . 'hotelinking_integrations.php';

        $roomMappings = getRoomMapping($brandId) ?? [];
        $validRoomMapping = array_first($roomMappings, function ($key, $roomMapping) use ($sanitizedUserRoomNumber) {
            $sanitizedRoomNumberMapping = sanitizeRoomNumber(array_get($roomMapping, 'room_number'));
            $sanitizedRoomCodeMapping = sanitizeRoomNumber(array_get($roomMapping, 'room_code'));

            return $sanitizedRoomNumberMapping === $sanitizedUserRoomNumber || $sanitizedRoomCodeMapping === $sanitizedUserRoomNumber;
        });

        if ($validRoomMapping) {
            $resp = redirectToNormalPortal(data_get($validRoomMapping, 'room_code'), $firstName, $lastName);
        } else {
            $logPortal->warning("Invalid room number on Portal Pro when compare with configured room list", [
                "brandId" => $brandId,
                "roomNumber" => $sanitizedUserRoomNumber,
                "configuredRoomNumbers" => implode(",", $validRoomNumbers),
                "roomMapping" => $roomMappings,
            ]);
    
            $resp = ['validated' => 'invalidRoomNumber', "radius_ticket_validated" => !empty($_SESSION['radiusTicket'])];
        }
    }

    return $resp;
}

function checkRadiusTicket($brandId, $ticket)
{
    global $logPortal;

    // Import lib needed to make calls
    include_once RUTA_DIR . LIB . 'hotelinking_noc.php';

    $validatedTicket = validateRadiusTicket($brandId, $ticket);

    // Check if is validated
    $isRadiusTicketValidated = array_get($validatedTicket, 'validated', false);
    if ($isRadiusTicketValidated) {
        // In case is validated, the username is formed by {brand_id}_{radius_ticket}
        $_SESSION['radiusTicket'] = [
            'username' => "{$brandId}_{$ticket}",
            'password' => $ticket,
        ];

        // Log success validation
        $logPortal->info('PortalProValidation', [
            'message' => 'User validated with radius ticket',
            'radius_ticket' => $ticket
        ]);
    }

    return $isRadiusTicketValidated;
}

$action = array_get($_POST, 'action');
$brand_id = array_get($_POST, 'brand_id');
// Verify that brand_id from POST is a numeric value to avoid SQL INJECTION
$brand_id = is_numeric($brand_id) ? $brand_id : null;
$max_validations = array_get($_POST, 'max_validations');
$room_number = array_get($_POST, 'room_number');
$selected_user_pms_data = array_get($_POST, 'pms_data');

$user_data = [];
$user_data['first_name'] = trim(array_get($_POST, 'first_name', ''));
$user_data['last_name'] = trim(array_get($_POST, 'last_name', ''));
$user_data['birthday'] = array_get($_POST, 'birthday');
$user_data['document_id'] = trim(array_get($_POST, 'document_id', ''));
$access_code = array_get($_POST, 'access_code');
$radiusTicket = array_get($_POST, 'radius_ticket');
$radiusTicketToHosted = array_get($_POST, 'radius_ticket_to_hosted');
$ssidType = array_get($_POST, 'ssid_type');

$logPortal->debug("PortalPro uservalidate: ", [
    '$_POST' => $_POST
]);

$portalProConfig = getPortalProConfiguration($brand_id);
$portalProRestrictive = $portalProConfig['restrictive'];

if ($action == 'validate-room') {
    $_SESSION['showingEprivacy'] = true;

    // Validate first & directly premium code
    if (!empty($access_code)) {
        $resp = ['validated' => false, "radius_ticket_validated" => !empty($_SESSION['radiusTicket'])];

        if ($portalProConfig["premium_code"]) {
            $codes = array_map('strtolower', getBrandAccessCodes($brand_id, "premium"));
            if (in_array(strtolower($access_code), $codes)) {
                $_SESSION['customer'] = true;
                $_SESSION['premium_code_active'] = true;

                $logPortal->info('PortalProValidation', [
                    'message' => 'User validated with premium code',
                    'premium_code' => $access_code,
                    'brand_id' => $brand_id,
                    'customer' => array_get($_SESSION, 'customer')
                ]);
                
                echo (json_encode(['validated' => true, 'premium_code' => true]));
                return;
            }
        }

        if ($portalProConfig["access_code"]) {
            $codes = array_map('strtolower', getBrandAccessCodes($brand_id, "guest"));
            if (in_array(strtolower($access_code), $codes)) {
                //set user as not a customer (not hotel client)
                $_SESSION['customer'] = false;
                $_SESSION['hotel_access_codes'] = $access_code;

                $logPortal->info('PortalProValidation', [
                    'message' => 'User validated with access code',
                    'access_code' => $access_code,
                    'customer' => $_SESSION['customer']
                ]);
                
                echo (json_encode(['validated' => true, 'access_code' => true]));
                return;
            }
        }

        // Validate radius ticket calling Noc
        if (($portalProConfig["radius_ticket"] || $portalProConfig["premium_ticket"]) && $_SESSION['portalPro']['isRadiusTicketValidation'] !== false && (!$radiusTicketToHosted || $ssidType === "premium")) {
            $isRadiusTicketValidated = checkRadiusTicket($brand_id, $access_code);

            if ($isRadiusTicketValidated) {
                // Set other session variables
                $_SESSION['customer'] = true;

                // Using ticket as roomNumber
                $_SESSION['roomNumber'] = $access_code;
            }

            // Return the validation result
            echo (json_encode([
                'validated' => $isRadiusTicketValidated,
                'premium_ticket' => $portalProConfig["premium_ticket"],
            ]));
            return;
        }

        // When access_code in portalPro has not been validated, exits access_code validation block
        echo (json_encode(['validated' => false]));
        return;
    }

    if($radiusTicket && empty($_SESSION['radiusTicket'])) {
        $isRadiusTicketValidated = checkRadiusTicket($brand_id, $radiusTicket);

        if (!$isRadiusTicketValidated) {
            echo (json_encode(['validated' => false]));
            return;
        }
    }

    // Validate pmsUser calling Integrations
    if (!array_get($_SESSION, 'pms_user')) {
        // Import lib needed to make calls
        include_once RUTA_DIR . LIB . 'hotelinking_integrations.php';

        // Validate required fields based on configuration
        $requiredFieldsValid = true;
        $trimmedRoomNumber = trim($room_number ?? '');
        
        if ($portalProConfig['first_name'] && empty($user_data['first_name'])) {
            $requiredFieldsValid = false;
        }
        if ($portalProConfig['last_name'] && empty($user_data['last_name'])) {
            $requiredFieldsValid = false;
        }
        if (empty($trimmedRoomNumber)) {
            $requiredFieldsValid = false;
        }
        if ($portalProConfig['document_id'] && empty($user_data['document_id'])) {
            $requiredFieldsValid = false;
        }

        if ($brand_id && $requiredFieldsValid) {
            // When the maximum number of validations has reached, we prevent further requests to integrations.
            if (array_get($_SESSION, 'portal_pro_validations', 0) >= $max_validations) {
                $resp = !$portalProRestrictive
                    ? checkRoomNumberInHotelikingPlatform($brand_id, $room_number, array_get($_POST, 'first_name'), array_get($_POST, 'last_name'))
                    : ['validated' => false, "radius_ticket_validated" => !empty($_SESSION['radiusTicket'])];

                echo (json_encode($resp));
                return;
            }

            $response = validateUser($brand_id, $trimmedRoomNumber, $user_data, $portalProConfig);
            $logPortal->debug("VALIDATE USER RESPONSE", ["response" => $response]);
            $resp = ['validated' => false, "radius_ticket_validated" => !empty($_SESSION['radiusTicket'])];
            if(array_has($response, 'error')) {
                $_SESSION['portal_pro_validations'] = array_get($_SESSION, 'portal_pro_validations', 0) + 1;

                // In case of restrictive, we return the invalidation
                if ($portalProRestrictive) {
                    // In case of a user that is invalidating many times, we log for statistics investigation
                    if ($_SESSION['portal_pro_validations'] > $max_validations) {

                        $logPortal->info("PortalProValidation", [
                            'message' => "User not validated attempting many times on restrictive portal",
                            'action' => $action,
                            'reason' => $response['error']['message'],
                            'user_data' => $user_data,
                            'room_number' => $room_number,
                            'max_validations' => $max_validations,
                            'validations' => $_SESSION['portal_pro_validations'],
                        ]);
                    }
                    echo (json_encode($resp));
                    return;
                }
                
                // Validate the user verifing the max number of validations configured for this brand
                if (array_get($_SESSION, 'portal_pro_validations', 0) >= $max_validations) {
                    $resp = checkRoomNumberInHotelikingPlatform($brand_id, $room_number, array_get($_POST, 'first_name'), array_get($_POST, 'last_name'));

                    // Log not validated
                    if (array_get($_SESSION, 'portal_pro_validations', 0) == $max_validations) {
                        $logPortal->info("PortalProValidation", [
                            'message' => "User not validated",
                            'action' => $action,
                            'reason' => $response['error']['message'],
                            'user_data' => $user_data,
                            'room_number' => $room_number,
                            'max_validations' => $max_validations,
                            'validations' => $_SESSION['portal_pro_validations'],
                        ]);
                    }
                }
                echo (json_encode($resp));
                return;
            } 

            if(empty($response)) {
                // Log error
                $logPortal->error("PortalProValidation", [
                    'message' => "Empty response from integrations",
                    'user_data' => $user_data,
                    'room_number' => $room_number,
                ]);

                // if portal pro is restrictive dont let the user be validated
                if ($portalProRestrictive) {
                    $resp = ['validated' => false, "radius_ticket_validated" => !empty($_SESSION['radiusTicket'])];
                } else {
                    $resp = checkRoomNumberInHotelikingPlatform($brand_id, $room_number, array_get($_POST, 'first_name'), array_get($_POST, 'last_name'));
                }
                echo (json_encode($resp));
                return;
            }

            $pms_data = array_get($response, 'pms_data');

            if ($pms_data) {
                // Set roomNumber in session
                $_SESSION['roomNumber'] = $room_number;
                if (count($pms_data) == 1) {
                    // Set pms_user in session
                    $pms_data = array_get($pms_data, 0);
                    $_SESSION['pms_user'] = $pms_data;
                    $_SESSION['user_hotel_id'] = $pms_data['pms_id'] ?? null;
                    $_SESSION['card_id'] = $pms_data['document_id'] ?? null;
                    $_SESSION['check_out'] = $pms_data['check_out'] ?? null;
                    $_SESSION['customer'] = true;

                    $reservation = $pms_data;
                    unset($reservation['res_comments']);
                    unset($reservation['res_extras']);


                    // Put on session the portal_pro_user validated
                    $_SESSION['portal_pro_user'] = [
                        'pms_id' => array_get($pms_data, 'pms_id'),
                        'res_id' => array_get($pms_data, 'res_id'),
                        'name' => array_get($pms_data, 'first_name') . (strlen(array_get($pms_data, 'last_name')) > 0 ? ' ' : '') . array_get($pms_data, 'last_name'),
                        'first_name' => array_get($pms_data, 'first_name'),
                        'last_name' => array_get($pms_data, 'last_name'),
                        'gender' => array_get($pms_data, 'gender'),
                        'birthday' => array_get($pms_data, 'birthday'),
                        'room_number' => array_get($pms_data, 'res_room_number'),
                        'check_in' => array_get($pms_data, 'check_in'),
                        'check_out' => array_get($pms_data, 'check_out'),
                        'email' => array_get($pms_data, 'email'),
                        'pms_reservation' =>  $reservation
                    ];

                    // Log validated
                    $logPortal->info("PortalProValidation", [
                        'message' => "User validated",
                        'action' => $action,
                        'pms_user_was_in_session' => false,
                        'pms_id' => array_get($pms_data, 'pms_id'),
                        'first_name' => array_get($pms_data, 'first_name'),
                        'last_name' => array_get($pms_data, 'last_name'),
                        'document_id' => array_get($pms_data, 'document_id'),
                        'room_number' => array_get($pms_data, 'res_room_number'),
                        'portal_pro_user' => $_SESSION['portal_pro_user'],
                        'pms_reservation' =>  $reservation
                    ]);

                    echo (json_encode($response));
                    return;
                } else {
                    $resp = ['validated' => 'select', 'pms_data' => $pms_data, "radius_ticket_validated" => !empty($_SESSION['radiusTicket'])];
                    echo (json_encode($resp));
                    return;
                }
            } else {
                // Log error
                $logPortal->error("PortalProValidation", [
                    'message' => "Result with empty pms_user in validation response",
                    'user_data' => $user_data,
                    'room_number' => $room_number,
                    'result' => $response,
                ]);
                echo (json_encode($resp));
                return;
            }
        } else {
            $logPortal->error("Call to integrations uservalidate without all required data", [
                'brand_id' => $brand_id,
                'room_number' => $room_number,
                'trimmed_room_number' => $trimmedRoomNumber,
                'required_fields_valid' => $requiredFieldsValid,
                'user_data' => $user_data,
                'portal_pro_config' => $portalProConfig,
                'POST' => $_POST
            ]);
            $resp = ['validated' => false, "radius_ticket_validated" => !empty($_SESSION['radiusTicket'])];
            echo (json_encode($resp));
            return;
        }
    } else {
        $pms_data[0] = array_get($_SESSION, 'pms_user');

        $resp = ['validated' => true, 'pms_data' => $pms_data, "radius_ticket_validated" => !empty($_SESSION['radiusTicket'])];

        // Log validated
        $logPortal->info("PortalProValidation", [
            'message' => "Pms User in Session",
            'action' => $action,
            'pms_user_was_in_session' => true,
            'pms_id' => array_get($pms_data, 'pms_id'),
            'first_name' => array_get($pms_data, 'first_name'),
            'last_name' => array_get($pms_data, 'last_name'),
            'document_id' => array_get($pms_data, 'document_id'),
            'room_number' => array_get($pms_data, 'res_room_number')
        ]);

        echo (json_encode($resp));
        return;
    }
} elseif ($action == 'select-user') {
    $_SESSION['pms_user'] = $selected_user_pms_data;
    $_SESSION['user_hotel_id'] = $selected_user_pms_data['pms_id'] ?? null;
    $_SESSION['card_id'] = $selected_user_pms_data['document_id'] ?? null;
    $_SESSION['check_out'] = $selected_user_pms_data['check_out'] ?? null;
    $_SESSION['customer'] = true;

    $reservation = $selected_user_pms_data;
    unset($reservation['res_comments']);
    unset($reservation['res_extras']);


    // Put on session the portal_pro_user validated
    $_SESSION['portal_pro_user'] = [
        'pms_id' => array_get($selected_user_pms_data, 'pms_id'),
        'res_id' => array_get($selected_user_pms_data, 'res_id'),
        'name' => array_get($selected_user_pms_data, 'first_name') . (strlen(array_get($selected_user_pms_data, 'last_name')) > 0 ? ' ' : '') . array_get($selected_user_pms_data, 'last_name'),
        'first_name' => array_get($selected_user_pms_data, 'first_name'),
        'last_name' => array_get($selected_user_pms_data, 'last_name'),
        'gender' => array_get($selected_user_pms_data, 'gender'),
        'birthday' => array_get($selected_user_pms_data, 'birthday'),
        'room_number' => array_get($selected_user_pms_data, 'res_room_number'),
        'check_in' => array_get($selected_user_pms_data, 'check_in'),
        'check_out' => array_get($selected_user_pms_data, 'check_out'),
        'pms_reservation' =>  $reservation
    ];

    // Log validated
    $logPortal->info("PortalProValidation", [
        'message' => "User validated",
        'action' => $action,
        'pms_user_was_in_session' => false,
        'pms_id' => array_get($selected_user_pms_data, 'pms_id'),
        'first_name' => array_get($selected_user_pms_data, 'first_name'),
        'last_name' => array_get($selected_user_pms_data, 'last_name'),
        'document_id' => array_get($selected_user_pms_data, 'document_id'),
        'room_number' => array_get($selected_user_pms_data, 'res_room_number'),
        'portal_pro_user' =>  $_SESSION['portal_pro_user'],
        'pms_reservation' => $reservation
    ]);

    $resp = ['validated' => true, 'pms_data' => $selected_user_pms_data, "radius_ticket_validated" => !empty($_SESSION['radiusTicket'])];
    echo (json_encode($resp));
    return;
} else {
    $logPortal->error("Call to integrations uservalidate without action", [$_POST]);
    $resp = ['validated' => false, "radius_ticket_validated" => !empty($_SESSION['radiusTicket'])];
    echo (json_encode($resp));
    return;
}
return;
