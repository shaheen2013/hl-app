<?php
//Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}
//Si está logueado
if (empty($_SESSION['private'])) {
    header('Location: /');
}

include LIB . 'obtenerdatosHotel.php';

//Get Statistics
function getHotelStatistics ($id){
    $id = mysqli_real_escape_string(conectar(1), $id);
    $sql = "SELECT id_hotel,
            landing_iframe_opens,
            landing_fb_clicks,
            landing_mail_clicks,
            landing_fb_success,
            landing_mail_success,
            landing_declined_user_friends,
            landing_declined_public_profile,
            landing_declined_email,
            landing_declined_publish_actions,
            landing_canceled,
            landing_reintents,
            hoteles.hotelName FROM hotel_statistics
	INNER JOIN hoteles on hoteles.id = hotel_statistics.id_hotel
	WHERE id_hotel = ".$id;
    $rs = mysqli_query (conectar(1), $sql);
    $row = mysqli_fetch_assoc($rs);
    liberar($rs);
    return $row;
}