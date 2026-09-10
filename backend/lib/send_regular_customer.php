<?php //Miramos si esta definida la variable de control de index.php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}

//Main method that gets all needed regular_customer info from hotelinkingDB and set it on hotelinkingEmailsDB
function sendRegularCostumerToEmailPlatform($hotel_id, $user_id, $room_num, $emailsToSend, $chain_id){
    global $log;
    //Check if the guest had visit enough the chain in order to send a notification to our client
    $num_visits_chain = null;
    if($chain_id){
        $num_visits_chain =     getUserNumberVisitToChain($chain_id, $user_id);
        $min_num_visits_chain = getChainLoyaltyMinVisits($hotel_id);
        $log->debug($num_visits_chain );
        $log->debug($min_num_visits_chain );
        if( $num_visits_chain < $min_num_visits_chain){
            $num_visits_chain = null;
        }
    }
    //Check if the guest had visit enough the hotel in order to send a notification to our client
    $num_visits_hotel =     getUserNumberVisitToHotel($hotel_id, $user_id);
    $min_num_visits_hotel = getHotelLoyaltyMinVisits($hotel_id);
    $log->debug($num_visits_hotel);
    $log->debug($min_num_visits_hotel);
    if($num_visits_hotel < $min_num_visits_hotel){
        $num_visits_hotel = null;
    }

    //if the guest had accomplished any of the last verification the
    if(($num_visits_hotel != null) || $num_visits_chain != null){
        $log->debug($min_num_visits_hotel);
        include_once RUTA_DIR . LIB . 'hotelinking_emails.php';
        include_once RUTA_DIR . LIB . 'emails-webservice-helpers.php';

        $user = buildUser($user_id, $hotel_id, $chain_id, $room_num, $num_visits_chain, $num_visits_hotel);
        //$emailsToSend contains few emails delimited by ',' so we will explode them and send a notification to each email
        $emailsToSend =     explode(',', $emailsToSend);
        $log->debug("before insertando emails loyalty", $emailsToSend);
        foreach ($emailsToSend as $emailToSend){
            $log->debug("insertando emails loyalty");
            insertRegularCostumerInEmailPlatform($user, $hotel_id, $user_id, $emailToSend);
        }
    }
}

//Check if user is a regular client
function getUserNumberVisitToChain($chain_id, $user_id)
{
    $con = conectar(1); // 1 significa que lee de la read réplica
    $chain_id = mysqli_real_escape_string($con, $chain_id);
    $user_id = mysqli_real_escape_string($con, $user_id);
    $sql = "SELECT num_visits FROM users_visits where chain_id = $chain_id and user_id =$user_id ";

    return array_get(lectura($sql, $con),'num_visits');
}

function getUserNumberVisitToHotel($hotel_id, $user_id)
{
    $con = conectar(1); // 1 significa que lee de la read réplica
    $hotel_id = mysqli_real_escape_string($con, $hotel_id);
    $user_id = mysqli_real_escape_string($con, $user_id);
    $sql = "SELECT num_visits FROM users_visits WHERE user_id = $user_id AND  hotel_id = $hotel_id  ";

    return array_get(lectura($sql, $con),'num_visits');
}

function getHotelLoyaltyMinVisits($hotel_id){
    $con = conectar(1); // 1 significa que lee de la read réplica
    $hotel_id = mysqli_real_escape_string($con, $hotel_id);

    $sql = "SELECT loyalty_min_visits FROM hoteles WHERE id = $hotel_id  ";

    return array_get(lectura($sql, $con),'loyalty_min_visits');
}
function getChainLoyaltyMinVisits($hotel_id){
    $con = conectar(1); // 1 significa que lee de la read réplica
    $hotel_id = mysqli_real_escape_string($con, $hotel_id);

    $sql = "SELECT loyalty_min_visits FROM cadena WHERE id = (SELECT id_cadena FROM cadena_hotel WHERE id_hotel = $hotel_id)  ";

    return array_get(lectura($sql, $con),'loyalty_min_visits');
}
function insertRegularCostumerInEmailPlatform($user, $id_hotel_email, $id_user_email, $emailsToSend)
{
    $con = conectar(2);
    $id_user_email = mysqli_real_escape_string($con, $id_user_email);
    $id_hotel_email = mysqli_real_escape_string($con, $id_hotel_email);
    $user_history  = mysqli_real_escape_string($con,serialize($user['hotelHistory']));
    $sql = "INSERT INTO regular_customer (user_id, hotel_id, last_connection, send_date, room_num, age, hotels_timeline, num_visits,num_visits_chain, gender, addressee)
            VALUES ($id_user_email, $id_hotel_email, NOW(), NOW(), '".$user['room_num']."' , '".$user['birthday']."', '".$user_history."', '".$user['num_visits_hotel']."', '".$user['num_visits_chain']."', '".$user['genero']."', ' ".$emailsToSend."' )";
    escritura($sql, $con);
}

function buildUser($user_id, $hotel_id, $chainID, $room_num, $num_visits_chain, $num_visits_hotel){
    $user = null;
    $dbUser =  getUserInfo($user_id);
    $hotelHistory =  getHotelHistory($user_id, $hotel_id, $chainID);
    if($dbUser){
        $user = array(
            'id' => $user_id,
            'email' => $dbUser['id'],
            'name' => !empty($dbUser['nombre']) ? $dbUser['nombre'] : 'customer',
            'lang' => !empty($dbUser['lang']) ? $dbUser['lang'] : 'en',
            'birthday' => !empty($dbUser['birthday']) ? $dbUser['birthday'] : NULL,
            'room_num' => $room_num,
            'hotelHistory' => $hotelHistory,
            'num_visits_chain' => $num_visits_chain,
            'num_visits_hotel' =>$num_visits_hotel,
            'genero'=> !empty($dbUser['genero']) ? $dbUser['genero'] : 'customer',
        );
    }
    return $user;
}

function getUserInfo($user_id){
    $con = conectar(1); // 1 significa que lee de la read réplica
    $user_id = mysqli_real_escape_string($con, $user_id);
    $sql = "SELECT id, nombre, email, timestampdiff(year,fecha_nacimiento,NOW()) as birthday, lang, sexo as genero FROM users WHERE id = '$user_id'";
    $result = lectura($sql, $con);
    
    return $result;
}


function getHotelHistory($user_id, $hotel_id, $chainID){
    $con = conectar(1); // 1 significa que lee de la read réplica
    $id_hotel = mysqli_real_escape_string($con, $hotel_id);
    $user_id = mysqli_real_escape_string($con, $user_id);
    $condition = empty($chain_id) ? "= $hotel_id" : "in (Select id_hotel from cadena_hotel where id_cadena = '$chain_id')";

    $sql = "SELECT 
            hoteles.hotelName as HOTEL_NAME,  
            CONCAT(date(connection_history.first_login), ' - ', date(connection_history.last_login)) as VISIT_DATE, 
            times_login AS TIMES_LOGIN 
        FROM connection_history 
        INNER JOIN hoteles ON hoteles.id = connection_history.id_hotel
        WHERE connection_history.id_hotel $condition AND id_user = '$user_id' ORDER BY connection_history.first_login DESC";
    $result = lecturaArray($sql, $con);
    
    return $result;
}

