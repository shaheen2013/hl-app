<?php //Miramos si esta definida la variable de control de index.php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}

$hotel = array_get($_GET, 'id', NULL);
$user_id = array_get($_GET, 'uid', NULL);
//if the query param id is not set or it is not a number of a hotel then 404
if ( (empty($hotel) || (int)$hotel == 0) || empty($user_id) ) {
    header('Location: /' . $urlTree['404']);
} else {
    include_once MODEL . 'satisfaction-surveyModel.php';
    $user = getUserNameAndLang($user_id);
    //Obtener nombre del usuario
    $user_name = $user['nombre'];

    // Obtain the user gender
    $user_gender = array_get($user,'sexo','male');

    //obtener el idioma del usuario
    $_SESSION['userLang'] = $user ['lang'];
    $user_name = !empty($user_name) ? $user_name .', ' : ' ' ;
    $datosHotel = obtenerDatosHotelSatisfactionThanks($hotel);// Datos hotel necesarios para esta pantalla
}
