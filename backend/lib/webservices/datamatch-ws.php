<?php
include 'librerias.php'; // Librerias básicas

// Restringir ips que pueden acceder
include_once RUTA_DIR . LIB . 'check_access.php';
checkIpAccess('ch-pos', $_SERVER['REMOTE_ADDR']);

include RUTA_DIR . LIB . 'hotelinking_integrations.php';
include RUTA_DIR . LIB . 'obtenerDatosUsuario.php';
include RUTA_DIR . LIB . 'obtenerdatosHotel.php';

include '../../lang/' . (isset($_SESSION['userLang']) ? $_SESSION['userLang'] : 'en') . '/datamatch-users.php';

global $log;

$brand_id = array_get($_POST, 'brand_id', null);
$action = array_get($_POST, 'action', null);
$datamatch_id = array_get($_POST, 'datamatch_id', null);
$draw = array_get($_POST, 'draw_count', null);
$n_per_page = array_get($_POST, 'n_per_page', null);
$page = array_get($_POST, 'page', null) + 1;
$search_text = array_get($_POST, 'searchText', null);

$integration_brand_id = array_get($_POST, 'integration_brand_id');

if( !is_null($brand_id) && !is_null($draw)){ // Call become from dashboard 
    $order_list = array_get($_POST, 'order', null);
    $columns = array_get($_POST, 'columns', null);

    $datamatchArray = [];
    if (!is_null($draw)) { // Case need to refresh the datatable
        
        if($order_list){
            $column_name = array_get($columns, $order_list[0]['column']);
            $order_list[0]['column'] = $column_name['data'];
        }

        if ($action == 'list_datamatch') {
            // Fetch response from integrations
            $datamatchs_data = listDatamatchForBrand($brand_id, $n_per_page, $page, $order_list);

            // Fetch datamatchs
            $datamatchs = array_get($datamatchs_data, 'data');

            // Fetch pagination data
            $pagination_data = $datamatchs_data;
            unset($pagination_data['data']);

            // Format dates
            $defaultFormat = 'd/m/Y';

            if ($datamatchs) {
                foreach ($datamatchs as $key => $datamatch) {
                    $arr_datamatch = $datamatch;

                    // Dates
                    $arr_datamatch['date_from'] = formatDates(array_get($datamatch, 'date_from', null), $defaultFormat);
                    $arr_datamatch['date_to'] = formatDates(array_get($datamatch, 'date_to', null), $defaultFormat);

                    // Insert in array
                    $datamatchArray[$key] = $arr_datamatch;
                }
            } else {
                $datamatchArray = [];
                $pagination_data = [];
            }
        } elseif ($action == 'list_matched_users') {

            // Call integrations to fetch matched users
            $list_matched_response = getDatamatchPmsUsers($brand_id, $datamatch_id, $n_per_page, $page, $search_text, $order_list);

            // Fetch datamatchs
            $integrationsUsersPmsData = array_get($list_matched_response, 'data');

            // Fetch pagination data
            $pagination_data = $list_matched_response;
            unset($pagination_data['data']);

            // Fetch users info
            $datamatchArray = [];
            $userIdList = [];

            // Dates formats
            $birthdayFormat = 'd/m/Y';
            $defaultFormat = 'd/m/Y';

            // Data to not send to front
            // $toNotSend = ['id', 'user_id', 'hotel_id', 'datamatch_id'];
            $toNotSend = ['id', 'hotel_id', 'datamatch_id'];

            // Get list of users ids
            $userIdList = array_pluck($integrationsUsersPmsData, 'user_id');

            // Get list of hotel ids
            $hotelIdList = array_pluck($integrationsUsersPmsData, 'hotel_id');

            if (!empty($userIdList)) {
                // Fetch user data from db
                $usersData = getDatamatchUserBasicData($userIdList, $brand_id);
                sort($usersData);

                // Fetch user data from db
                $hotelsData = getHotelDataByListIds($hotelIdList);
                sort($hotelsData);

                // Merge data with integrations data
                foreach ($usersData as $userData) {
                    $userAppId = array_get($userData, 'user_id', null);
                    if ($userAppId) {
                        foreach ($integrationsUsersPmsData as $userPmsData) {
                            $userIntegrationId = array_get($userPmsData, 'user_id', null);
                            if ($userAppId == $userIntegrationId) {
                                // Hotel
                                $hotelName = '';
                                $dm_hotel_id = array_get($userPmsData, 'hotel_id');
                                foreach ($hotelsData as $key => $value) {
                                    $hotelId = array_get($value, 'id');
                                    if ($hotelId == $dm_hotel_id) {
                                        $hotelName = array_get($value, 'name');
                                    }
                                }

                                // Genders
                                $sexo = array_get($userData, 'sexo', null);
                                $gender = array_get($userPmsData, 'gender', null);

                                //Subscribed
                                $subscribed = array_get($userData, 'subscribed', null);
                                $subscribedKey = $subscribed === null ? null : ($subscribed ? 'yes' : 'no');

                                // Dates
                                $fecha_nacimiento = formatDates(array_get($userData, 'fecha_nacimiento', null), $birthdayFormat);
                                $birthday = formatDates(array_get($userPmsData, 'birthday', null), $birthdayFormat);
                                $checkin = formatDates(array_get($userPmsData, 'check_in', null), $defaultFormat);
                                $checkout = formatDates(array_get($userPmsData, 'check_out', null), $defaultFormat);
                                $res_date = formatDates(array_get($userPmsData, 'res_date', null), $defaultFormat);

                                // Merging arrays
                                $array_datamatch = array_merge($userData, $userPmsData);
                                $array_datamatch['fecha_nacimiento'] = $fecha_nacimiento;
                                $array_datamatch['sexo'] = $sexo ? array_get($DataMatchUsersLangs, $sexo) : $sexo;

                                $array_datamatch['gender'] = $gender ? array_get($DataMatchUsersLangs, $gender) : $gender;
                                $array_datamatch['birthday'] = $birthday;
                                $array_datamatch['check_in'] = $checkin;
                                $array_datamatch['check_out'] = $checkout;
                                $array_datamatch['res_date'] = $res_date;

                                $array_datamatch['hotel_name'] = $hotelName;

                                $array_datamatch['subscribed'] = $subscribedKey ? array_get($DataMatchUsersLangs, $subscribedKey, $subscribedKey) : null;

                                // Remove value from array to not pass to front
                                foreach ($toNotSend as $valueToRemove) {
                                    unset($array_datamatch[$valueToRemove]);
                                }

                                array_push($datamatchArray, $array_datamatch);
                            }
                        }
                    }
                }
            }
        } else {
            $datamatchArray = [];
            $pagination_data = [];
        }

        $result = [];
        $result['draw'] = $draw + 1;
        $result['recordsTotal'] = array_get($pagination_data, 'total');
        $result['recordsFiltered'] = array_get($pagination_data, 'total');
        $result['data'] = $datamatchArray;

        echo json_encode($result);
    } else {
        if ($action == 'export_datamatch') {
            // $result = exportDatamatchForBrand($brand_id, $datamatch_id);

            $result = $datamatch_id;
            echo $result;
        } else if ($action == 'import_datamatch') {
            $result = importDatamatchForBrand($brand_id, $datamatch_id);
            echo $result;
        } else{
            echo false;
        }
    }

} else if (!is_null($brand_id)) { // Call become from private
    if($action == 'datamatch_config'){
        $result = getDatamatchOptions($brand_id);

        echo json_encode($result);
    }else if($action == 'list_integrations_config'){
        $result = listIntegrationOptions($brand_id);

        echo json_encode($result);
    }else if($action == 'get_integrations_config'){
        $result = getIntegrationOptions($brand_id, $integration_brand_id);

        echo json_encode($result);
    } else if ($action == 'create_datamatch') {
        $brand_csv = array_get($_POST, 'brand_csv');
        $date_from = array_get($_POST, 'date_from');
        $date_to = array_get($_POST, 'date_to');

        header('Content-Type: application/json');

        if ($brand_csv && $date_from && $date_to) {
            $result = createDatamatchForBrand($brand_id, $date_from, $date_to, $brand_csv);
            echo json_encode($result);
        } else {
            echo false;
        }
    }else{
        $log->error('datamatch-ws', ['message' => 'No action passed or not mapped', 'post' => $_POST]);
        echo json_encode([]);
    }
}else{
    $log->error('datamatch-ws', ['message' => 'No brand_id passed', 'post' => $_POST]);
    echo json_encode([]);
}

// Function to format date using a format defined in parameters
function formatDates($date, $format)
{
    if (!$date || !$format) {
        return null;
    }
    $date = new DateTime($date);
    return $date->format($format);
}
