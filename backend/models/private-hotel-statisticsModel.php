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
	$id = mysqli_real_escape_string(conectar(), $id);
	$sql = "SELECT id_hotel,pre_iframe_opens,pre_login,pre_second_click,pre_share,pre_declined_user_friends,pre_declined_public_profile,pre_declined_email,pre_declined_publish_actions,pre_canceled,pre_reintents,hoteles.hotelName FROM hotel_statistics
	INNER JOIN hoteles on hoteles.id = hotel_statistics.id_hotel
	WHERE id_hotel = ".$id;
	$rs = mysqli_query (conectar(1), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	return $row;
}