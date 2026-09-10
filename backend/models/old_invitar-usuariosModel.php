<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

function quitarAlert($id){
	$sql = 'UPDATE hoteles SET alert = 1 WHERE id="'.$id.'"';
	mysqli_query (conectar(), $sql);
}

// Hay que mirar si el hotelero ya tiene usuarios invitados. 
// si los tiene, no hace falta mostrarle el paso de onboarding.
function saltarInviteUsers($id){
	$sql = "SELECT usuarios FROM hotel_total_users WHERE id_hotel = $id";
	$rs = mysqli_query (conectar(), $sql);
	$n = mysqli_num_rows($rs);
	liberar($rs);
	if($n > 0){
		//Updateamos el paso en caso de que no lo tenga updateado
		$sql2 = "UPDATE onboarding SET invite_send = 1 WHERE id_hotel = $id";
		mysqli_query (conectar(), $sql2);
		return true;
	}
	return false;
}


?>