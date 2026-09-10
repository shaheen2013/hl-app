<?php //Miramos si esta definida la variable de control de index.php

use App\Controllers\Users\UnsubscribeController;

if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}

//Contenido solo visible si logueado
include LIB . 'logueado.php';
hotelStaffLanding(); // Si no esta logueado lo manda a la landing

include LANG . array_get($_SESSION, 'userLang') . '/clients-profile.php';

include_once LIB . 'obtenerdatosHotel.php';

/*main*/
global $log;
$userId = (int)$url['dir2'];
$hotelId = array_get($_SESSION, 'h_logueado', array_get($_SESSION, 'staff_id_hotel'));
$chainId = array_get($_SESSION, 'c_logueado');
$staffId = $_SESSION['staff_logueado'] ?? null;
$brandId = $_SESSION['loggedBrandID'];
$gateway = new ApiGatewayConnection();

$guidHotel = obtenerGUIDHotel(array_get($_SESSION, 'h_logueado', array_get($_SESSION, 'staff_id_hotel')));
$hotelName = array_get($_SESSION, 'hotelName');
$userInHotel = false;
$isUserUnsubscribe = false;

/*Get the generic info from our DB*/
try {
    $clientProfile = json_decode($gateway->sendRequest([], HOTELINKING_ENDPOINT . 'brands/' . $brandId . '/clients/' . $userId, 'GET'))->data;
    $firstConnection = getFirstConnection($clientProfile);
    $lastConnection = getLastConnection($clientProfile);
    $visits = getVisits($clientProfile);
    $userInHotel = in_array($brandId , array_flatten(array_pluck($clientProfile, 'brand_id')));
    $isUserUnsubscribe = data_get($clientProfile, '0.unsubscribed');
    $clientProfileDisplay = !empty($clientProfile);

    if (array_get($_GET, 'unsubscribe')) {
        if (!$isUserUnsubscribe) {
            include_once LIB . 'apiGateway.php';
            $brandId = array_get($_SESSION, 'hotel.brand_id');
            try {
                // Send Event to Api to unsubscribe
                UnsubscribeController::unsubscribeUser($brandId, $userId, true);
                // Feedback message ok to front
                $ok = [true, '2038'];
                $isUserUnsubscribe = true;
            } catch (Exception $e) {
                $ok = [false, '4098'];
            }
        } else {
            header('Location: /' . $url['dir1'] . '/' . $url['dir2']);
        }
    }
} catch (Exception $e) {
    $log->error("Error getting Satisfaction List", [$e]);
    $ok =  array (false, '4067');
    $clientProfileDisplay = false;
}

$currentSubPage = 'clients-profiles';


function getConnections($data) {
    return array_flatten(array_pluck(array_flatten(array_pluck($data, 'visits')), 'connections'));
}

function getFirstConnection($data) {
    $connections = getConnections($data);

    usort($connections, function($a, $b) {
        return strtotime($a->created_at) - strtotime($b->created_at);
    });

    return array_get($connections, '0');
}

function getLastConnection($data)
{
    $connections = getConnections($data);
    
    usort($connections, function($a, $b) {
        return strtotime($a->created_at) - strtotime($b->created_at);
    });

    return array_first(array_reverse($connections),function($key, $value) {
        return $value->access_code != "Bypass";
    });
}

function getVisits($data)
{
    $visits = array_flatten(array_pluck($data, 'visits'));

    $visits = array_map(function ($visit) use ($data) {
        $userInfo = array_first($data, function ($key, $value) use ($visit) {
            return $visit->user_brand_id === $value->user_brand_id;
        });

        $visit->brandName = data_get($userInfo, 'brand_name');

        return $visit;
    }, $visits);

    usort($visits, function($a, $b) {
        return strtotime($a->check_in) - strtotime($b->check_in);
    });

    return $visits;
}

function groupConnections($connections)
{
    return array_reduce($connections, function ($carry, $item) {
        $key = implode(':', [data_get($item, 'mac_address'), data_get($item, 'source')]);
        if (!isset($carry[$key])) {
            $carry[$key] = [
                'mac_address' => data_get($item, 'mac_address'),
                'source' => data_get($item, 'source'),
                'device_family' => data_get($item, 'device_family'),
                "operating_system" => data_get($item, 'operating_system'),
                "operating_system_version" => data_get($item, 'operating_system_version'),
                "created_at" => data_get($item, 'created_at'),
                "access_code" => data_get($item, 'access_code'),
                'count' => 0,
            ];
        }
        $carry[$key]['count']++;
        return $carry;
    }, []);
}

function getDeviceInfo($connection)
{
    
    $deviceFamily = data_get($connection, 'device_family') != "Other" ? data_get($connection, 'device_family') : "" ;
    $operatingSystem = data_get($connection, 'operating_system');
    $macAddress = data_get($connection, 'mac_address') && strpos(data_get($connection, 'mac_address'), 'NO_MAC_') !== 0  
        ? "::" . data_get($connection, 'mac_address') 
        : "";

    return [
        "type" => ($deviceFamily == "iPhone" || $operatingSystem == "Android") ? "fa-mobile" : "fa-laptop",
        "info"  => $operatingSystem . " " . data_get($connection, "operating_system_version") . " " . $deviceFamily . $macAddress
    ]; 
}
