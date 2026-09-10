<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

// Devuelve el id del hotel tanto si el usuario logueado es un Hotelero como un Staff
function obtenerIdHotel (){
	if (!empty($_SESSION['h_logueado'])){
		return $_SESSION['h_logueado'];
	}else if (!empty($_SESSION['staff_id_hotel'])){
		return $_SESSION['staff_id_hotel'];
	}	
}


?>