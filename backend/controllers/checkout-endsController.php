<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//Contenido solo visible si logueado
include LIB.'logueado.php';
hotelStaffLanding ();// Si no esta logueado lo manda a la landing

include_once LIB.'staff_id_hotel.php';


$id_hotel = obtenerIdHotel();

if (!empty($_POST['save-survey']) && !empty($_SESSION['checkout'])){
	$rate = mysqli_real_escape_string(conectar(), $_POST['rating']);
	$hotelUserComment = mysqli_real_escape_string(conectar(), $_POST['hotelUserComment']);
	$id_usuario = mysqli_real_escape_string(conectar(), $_POST['userId']);
	if ($id_usuario == $_SESSION['checkout']){
		guardarRating($id_usuario, $rate, $hotelUserComment, $id_hotel, $_SESSION['id_checkout']);
	}
	unset ($_SESSION['checkout']);
	unset ($_SESSION['id_checkout']);
}
?>