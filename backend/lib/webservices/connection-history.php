<?php
//Variable de control para saber si ha pasado por index.php
define("INDEXCONTROLVAL", "1");
include 'librerias.php'; // Librerias básicas
// Restringir ips que pueden acceder
//include_once RUTA_DIR . LIB . 'check_access.php';
//checkIpAccess('ch-pos', $_SERVER['REMOTE_ADDR']);
//include_once RUTA_DIR . LIB . 'cookies.php';
//used for unify redirect
include_once RUTA_DIR . LIB . 'obtenerdatosHotel.php';
include_once RUTA_DIR . LIB . 'hotelinking_emails.php';

require_once '../utils.php';
include_once RUTA_DIR . LIB . 'apiGateway.php';
// use UAParser\Parser;

$cookieName = 'hltc_connectionId';


// function that upserts a row in the connection history table
function upsert_connection_history($user_id, $hotel_id, $room_id, $mac_address, $headers)
{
    // global $stay_time;

    $hotelInfo = getHotelData($hotel_id);
    $stay_time = array_get($hotelInfo, 'stay_time', 7);

    $where = " WHERE DATE(`last_login`) + INTERVAL '$stay_time' DAY > CURDATE() AND id_hotel = '$hotel_id' AND id_user = '$user_id' AND mac_address = '$mac_address'";

    //read-replica
    $con = conectar(1);

    //select connection_history if already exists for this user/hotel
    $row = lecturaArray("SELECT id FROM connection_history $where order by last_login desc", $con);
    if (sizeof($row) == 0) {
        //if it doesn't exist then make an insert
        return insert_connection_history($user_id, $hotel_id, $room_id, $mac_address, $headers);
    } else {
        $rowId = $row[0]['id'];
        //if it does exist make an update
        $updateQuery = "UPDATE connection_history
                        SET
                            last_login = NOW(),
                            times_login = times_login + 1,
                            id_room = IF(id_room IS NULL OR id_room = '', '$room_id', id_room)
                        WHERE id = $rowId";
        $id_connection = escritura($updateQuery);
        return $id_connection;
    }
}

function insertOrUpdateUsersVisitsToChain($user_id, $hotel_id)
{
    //    global $log;

    if (empty($user_id) || empty($hotel_id)) {
        //        $log->error('no $user_id or $hotel_id in insertOrUpdateUsersVisitsToChain');
        return;
    }

    //get chain stay time
    $hotelInfo = getHotelData($hotel_id);
    $chain_stay_time = array_get($hotelInfo, 'chain_stay_time', 7);
    if ($chain_stay_time == null) {
        $chain_stay_time = 7;
    };


    $selectQuery = "SELECT cadena_hotel.id_cadena as chain_id, users_visits.chain_id as exist_on_table,
        CASE WHEN DATE(users_visits.last_login) + INTERVAL $chain_stay_time DAY < CURDATE() THEN true ELSE false END AS repeating_user
        FROM  cadena_hotel
        LEFT JOIN  users_visits ON cadena_hotel.id_cadena = users_visits.chain_id and users_visits.user_id=$user_id
        WHERE cadena_hotel.id_hotel=$hotel_id";

    //    $log->debug("------------------------------------".$selectQuery."-----------------------------------------");
    $row = lectura($selectQuery);
    $existOnTable = array_get($row, 'exist_on_table');
    $repeating = array_get($row, 'repeating_user');
    $chain_id = array_get($row, 'chain_id');
    //    $log->debug("------------------------------------".$chain_id."-----------------------------------------");
    //condition to increment
    //the chain exist allready and the user is repeating or is a new chain and we will insert it in our database
    if (($existOnTable && $repeating) || ($chain_id && !$existOnTable)) {
        $insertQuery = "INSERT INTO users_visits (`chain_id`, `user_id`, `num_visits`)
              VALUES ($chain_id, $user_id , 1)
              ON DUPLICATE KEY UPDATE num_visits = num_visits + 1, recurrent = 1";
        //        $log->debug("------------------------------------".$insertQuery."-----------------------------------------");
        escritura($insertQuery);
    }
    return $repeating;
}

function evaluateIfIsNewConnection($user_id, $hotel_id)
{
    $hotelInfo = getHotelData($hotel_id);
    $stay_time = array_get($hotelInfo, 'stay_time', 7);

    $selectQuery = "SELECT  hotel_id,
CASE WHEN DATE(users_visits.last_login) + INTERVAL '$stay_time' DAY < CURDATE()
 THEN true ELSE false END AS repeating_user,
 num_visits
FROM  users_visits
Where
 users_visits.hotel_id=$hotel_id and user_id = $user_id";
    $row = lectura($selectQuery);
    return $row;
}

function insertOrUpdateUsersVisits($user_id, $hotel_id)
{
    //    global $log;

    if (empty($user_id) || empty($hotel_id)) {
        //        $log->error('no $user_id or $hotel_id in insertOrUpdateUsersVisits');
        return;
    }
    //recuperar last_login o si existe
    $existOnTable = evaluateIfIsNewConnection($user_id, $hotel_id);
    $repeating = array_get($existOnTable, 'repeating_user');
    //condition to increment
    //the chain exist already and the user is repeating or is a new chain and we will insert it in our database
    if (($existOnTable && $repeating) || ($hotel_id && !$existOnTable)) {
        $insertQuery = "INSERT INTO users_visits (`hotel_id`, `user_id`, `num_visits`)
              VALUES ($hotel_id, $user_id , 1)
              ON DUPLICATE KEY UPDATE num_visits = num_visits + 1, recurrent = 1";
        //        $log->debug("------------------------------------".$insertQuery."-----------------------------------------");
        escritura($insertQuery);
    }
    return $repeating;
}

function recover_loyalty_email($hotel_id)
{
    $insertQuery = "SELECT loyalty_emails, loyalty_alerts FROM hoteles where id = $hotel_id";
    $hotel = lectura($insertQuery);
    return $hotel;
}

function insert_connection_history($user_id, $hotel_id, $room_id, $mac_address, $headers)
{
    //    global $log;
    $con = conectar(0);
    $device_brand = mysqli_real_escape_string($con, array_get($headers, 'device_brand', ''));
    $device_family = mysqli_real_escape_string($con, array_get($headers, 'device_family', ''));
    $browser = mysqli_real_escape_string($con, array_get($headers, 'browser', ''));
    $browser_version = mysqli_real_escape_string($con, array_get($headers, 'browser_version', ''));

    $device_os = mysqli_real_escape_string($con, array_get($headers, 'os', ''));
    $device_os_version = mysqli_real_escape_string($con, array_get($headers, 'os_version', ''));
    $device_model = mysqli_real_escape_string($con, array_get($headers, 'device_model', ''));
    $browser_lang = mysqli_real_escape_string($con, array_get($headers, 'browser_lang', ''));

    $insertQuery = "
            INSERT INTO connection_history (id_user, id_hotel, id_room, mac_address, browser, browser_version, operating_system, operating_system_version, device_family, device_brand, device_model, browser_lang)
            VALUES ('$user_id', '$hotel_id', '$room_id', '$mac_address',
                  '{$browser}', '{$browser_version}', '{$device_os}', '{$device_os_version}',
                  '{$device_family}', '{$device_brand}', '{$device_model}', '{$browser_lang}') ";
    $id_connection = escritura($insertQuery);
    global $log;
    //    $log->info('connection history created', ['hotel_id' => $hotel_id, 'user_id' => $user_id]);
    return $id_connection;
}

function parseHeaders()
{
    $headers = array();
    $ua = $_SERVER['HTTP_USER_AGENT'];
    $parser = Parser::create();
    $result = $parser->parse($ua);
    //Curate header
    $headers['browser'] = $result->ua->family;
    $headers['browser_version'] = $result->ua->toVersion();
    $headers['os'] = $result->os->family;
    $headers['os_version'] = $result->os->toVersion();
    $headers['device_family'] = $result->device->family;
    $headers['device_brand'] = $result->device->brand;
    $headers['device_model'] = $result->device->model;
    $headers['browser_lang'] = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
    return $headers;
}

function create_connection_history($user_id, $hotel_id, $room_id, $mac_address, $headers, $user_is_client)
{
    global $log;
    $log->debug("ConnectionHistoryWS", [
        'message' => "creating connection history",
        'user_data' => [
            'user_id' => $user_id,
            'hotel_id' => $hotel_id,
            'room_id' => $room_id,
            'mac_address' => $mac_address,
            'headers' => $headers,
            'user_is_client' => $user_is_client
        ]
    ]);

    $con = conectar();
    $repeating = false;
    $repeating_chain = false;
    $user_id = mysqli_real_escape_string($con, $user_id);
    $room_id = mysqli_real_escape_string($con, $room_id);
    $hotel_id = mysqli_real_escape_string($con, $hotel_id);
    $mac_address = macFormatter($mac_address);

    //    $id_connection = hasConnectionCookie($hotel_id, $user_id);
    //check if previously had a connection
    //    if ($id_connection) {
    //        $log->debug('Updating connection history', array('id' => $id_connection));
    //        return update_connection_history($id_connection, $hotel_id, $user_id, $room_id, $mac_address, $headers);
    //    } else {
    //hack to always insert if id_user is 0 (not verified by facebook) and does not have a mac_address
    if (is_null($mac_address) && $user_id === 0) {
        $log->debug("ConnectionHistoryWS", [
            'message' => "insert_connection_history",
            'user_data' => [
                'user_id' => $user_id,
                'hotel_id' => $hotel_id,
                'room_id' => $room_id,
                'mac_address' => $mac_address,
                'headers' => $headers,
            ]
        ]);
        $id_connection = insert_connection_history($user_id, $hotel_id, $room_id, $mac_address, $headers);
    } else {
        //if hotel is from chain then chain_id else null
        $chain_id = hotelIdCadena($hotel_id);
        if (!empty($chain_id) && $user_is_client) {
            //actualize user_visits
            $repeating_chain = insertOrUpdateUsersVisitsToChain($user_id, $hotel_id);
        }
        //if it creates another row we will insert a new update on users_visits
        if ($user_is_client) {
            $repeating = insertOrUpdateUsersVisits($user_id, $hotel_id);
        }
        $log->debug("ConnectionHistoryWS", [
            'message' => "upsert_connection_history",
            'user_data' => [
                'user_id' => $user_id,
                'hotel_id' => $hotel_id,
                'room_id' => $room_id,
                'mac_address' => $mac_address,
                'headers' => $headers,
            ]
        ]);
        //insert connection row in connection_history table
        $id_connection = upsert_connection_history($user_id, $hotel_id, $room_id, $mac_address, $headers);
    }
    //set the connection history cookie to be able to check for it in future connections
    create_connection_cookie($hotel_id, $user_id, $id_connection);
    return ["id_connection" => $id_connection, "repeating" => $repeating, "repeating_chain" => $repeating_chain];
    //    }
}

//update a exiting connection with id
//this updates the last_login timestamp
function update_connection_history($id_connection, $hotel_id, $user_id, $room_id, $mac_address, $headers)
{
    $con = conectar();
    $updateQuery = "UPDATE connection_history SET last_login = NOW(), times_login = times_login + 1, mac_address='$mac_address' WHERE id = '$id_connection' AND id_hotel = '$hotel_id' AND id_user = '$user_id'";
    return escritura($updateQuery, $con);
}

//when user makes connection for the first time create a connection cookie
//we store it with reference to $hotel_id
function create_connection_cookie($hotel_id, $user_id, $id_connection)
{
    global $cookieName;
    // global $stay_time;
    $name = $cookieName . "_" . $user_id . $hotel_id;

    $hotelInfo = getHotelData($hotel_id);
    $stay_time = array_get($hotelInfo, 'stay_time', 7);

    //time of cookie is (stay_time days * 24h * 60minutes * 60seconds)
    setcookie($name, $id_connection, time() + ($stay_time * 24 * 60 * 60), '/', '.hotelinking.com', true, true);
}


//check if user has a connection cookie -> means it already connected to the wifi beforehand
//return the cookie value => connection id
function hasConnectionCookie($hotel_id, $user_id)
{
    global $cookieName;
    $name = $cookieName . "_" . $user_id . $hotel_id;
    return checkCookie($name) ? openCookie($name) : false;
}

// POST to webservice from wifi redirect page with room number
if ($_POST) {
    global $log;
    $log->debug("ConnectionHistoryWS", ['post' => $_POST, 'session' => $_SESSION]);

    //    $log->info('connection history');
    $user_id = array_get($_POST, 'user_id', null);
    $brandID = (int) array_get($_POST, 'brand_id', null);
    $hotel_id = array_get($_POST, 'hotel_id', null);
    $room_id = array_get($_POST, 'room_id', null);
    $mac = array_get($_POST, 'mac', null);
    $connection_source = array_get($_POST, 'source', null);
    $headers = array_get($_POST, 'headers', null);
    $brandProducts = array_get($_POST, 'brandProducts', null);
    $user = array_get($_POST, 'user', null);
    $user_is_client = array_get($_POST, 'customer', null);
    $user_birthday = array_get($user, 'birthday');
    $trigger_loyalty = false;
    $trigger_loyalty_chain = false;
    $send = array();
    $newConnection = false;


    //create a connection_history
    try {
        $connectionInfo = evaluateIfIsNewConnection($user_id, $hotel_id);
        $newConnection = array_get($connectionInfo, "repeating_user", false) || array_get($connectionInfo, "num_visits", 0) == 0;
        if ($newConnection) {
            $user['id'] = (int) $user_id;
            $brandVisitedPayload = [
                "brand" => [
                    "id" => $brandID
                ],
                "roomID" => $room_id,
                "user" => $user,
                "numVisits" => (int) array_get($connectionInfo, "num_visits", 0)
            ];

            emitEvent('Brands', 'brand_visited', $brandVisitedPayload,  ["brandID" => $brandID], '2.0.0');
        }
        $connection_resume = create_connection_history($user_id, $hotel_id, $room_id, $mac, $headers, $user_is_client);
        $connection_id = array_get($connection_resume, 'id_connection');
        $trigger_loyalty = array_get($connection_resume, 'repeating');
        $trigger_loyalty_chain = array_get($connection_resume, 'repeating_chain');

        //check if is a new connection before to insert this connection history
        if ($newConnection) {
            // Create birthday Alarm
            include_once RUTA_DIR . LIB . 'send_birthday_alarm.php';
            global $log;
            $log->debug("Despues del if", [array_get(getBirthdayProductConfigAlarm($brandID), 'birthday_alarm')]);
            if (array_get(getBirthdayProductConfigAlarm($brandID), 'birthday_alarm') && $user_birthday != null) {
                $birthday = date('Ymd', strtotime($user_birthday));
                $toDay = date('Y', strtotime($user_birthday)) . date('md');
                $daysToBirthday = (strtotime($birthday) - strtotime($toDay)) / (60 * 60 * 24);
                $birthdayNotificationFields = getBirthdayAlarmMails($hotel_id);
                $emailsToSend = array_get($birthdayNotificationFields, 'birthdayAlertEmails', null);
                $notificationDaysRange = array_get($birthdayNotificationFields, 'birthday_alarm_days_range', 3);
                if ($emailsToSend != null) {
                    if ($daysToBirthday < $notificationDaysRange && $daysToBirthday > -$notificationDaysRange) {
                        $emailsToSend = explode(',', $emailsToSend);
                        foreach ($emailsToSend as $email) {
                            sendBirthdayAlarmToEmailPlatform($user['id'], $hotel_id, $room_id, array_get($user, 'gender'), $email);
                        }
                    }
                }
            }
        }
    } catch (Exception $e) {
        //        $log->warning('connection-history', array('message' => $e->getMessage(), '$_POST' => $_POST));
    }

    if (getBrandProductActive($brandProducts, 'loyalty')) {
        include_once RUTA_DIR . LIB . 'loyalty.php';

        //if hotel is from chain then chain_id else null
        $chain_id = hotelIdCadena($hotel_id);

        //        $log->debug('Checking if user is chain loyalty for chain ' . $chain_id);

        $userLoyalty = isUserLoyalty($user_id, $chain_id, $hotel_id);

        if ($userLoyalty && ($trigger_loyalty_chain || $trigger_loyalty)) {
            $log->debug('User is loyal');

            $curl = new Curl\Curl();
            $user['num_visits'] = $userLoyalty;
            $curl->post(SECURE_BASE_PATH . LIB . 'webservices/chain-loyalty-email-ws.php/', array(
                'hotel_id' => $hotel_id,
                'user' => $user,
                'brand_id' => $brandID
            ));

            if ($curl->error) {
                $log->error('Error sending data to chain-loyalty-ws', array('error' => $curl->error_message));
            }
        } else {
            $log->debug('User is not chain loyalty');
        }


        //send email to hotel if user is loyalty
        $userHotelLoyalty = isUserHotelLoyalty($user_id, $hotel_id);
        //$productosHotel = obtenerProductosHotel($hotel_id);//obtener productos hotel (review, satisfacciÃ³n...)
        $emailsToSend = recover_loyalty_email($hotel_id);
        $chain_id = hotelIdCadena($hotel_id);
        if ($emailsToSend['loyalty_alerts'] == 1 && ($userLoyalty || $userHotelLoyalty) && ($trigger_loyalty)) {
            $arrayParametros = array(
                'id_hotel' => $hotel_id,
                'id_user' => $user_id,
                'room_id' => $room_id,
                'emails' => $emailsToSend['loyalty_emails'],
                'chain_id' => $chain_id
            );
            $urlWebservice = SECURE_BASE_PATH . LIB . 'webservices/hotel-loyalty-email-ws.php/';

            $curl = new Curl\Curl();

            //            $log->addDebug('sending data to hotel-loyalty-ws', $arrayParametros);
            $curl->post($urlWebservice, $arrayParametros);

            if ($curl->error) {
                //                $log->addError('Error sending data to hotel-loyalty-ws', array('error' => $curl->error));
            }
        }
    }


    //all is good return success to ajax call
    $send['done'] = 'connection_history';
    $send['id'] = $connection_id;

    echo json_encode($send);
    exit;
} else {
    global $log;
    $log->error("connection-history", ['message' => "Post with no data", 'POST' => $_POST]);
}
