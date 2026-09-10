<?php
// Basic libraries
include_once 'librerias.php';

// Disable indexcontrolval to be accesible as a webservice
define("INDEXCONTROLVAL", "1");
// Users feed for app/database/home
// it retrieves users and satisfactions from DDBB
// It´s called from ajax request inside app/database/home main.js every X minutes

if (empty($_POST['getUsersFeed'])) {
    $log->debug('no user feed retrieved');
    exit();
}


getNewUsersFeed($_POST['hotel_id'], $_POST['from'], $_POST['usersNum']);
function getNewUsersFeed($hotel_id, $from, $usersNum)
{
    global $log;
    $con = conectar(1);
    $from = mysqli_real_escape_string($con, $from);
    $userNum = mysqli_real_escape_string($con, $usersNum);
    $sql = "SELECT users.id, users.nombre, users.location, users.fecha_nacimiento, users.sexo, users_visits.last_login, user_facebook.facebook_img FROM users_visits
            LEFT JOIN users ON users_visits.user_id = users.id
            LEFT JOIN user_facebook ON users_visits.user_id = user_facebook.id_usuario";
    if ($userNum != 'null') {
        $sql .= " where users_visits . hotel_id = $hotel_id ORDER BY users_visits.last_login DESC LIMIT " . $usersNum;
    } else {
        $sql .= " where users_visits . hotel_id = $hotel_id and users_visits . last_login > '$from'";
    }
    $results = lecturaArray($sql, $con);
    echo json_encode($results);
}
