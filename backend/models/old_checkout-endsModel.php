<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

include_once LIB.'fecha.php';

function guardarRating($id_usuario, $rate, $hotelUserComment, $id_hotel, $id_checkout){
	// Miramos si el usuario esta vinculado con el hotel
	$sql = "SELECT id FROM user_hotels 
	WHERE id_usuario='".$id_usuario."' AND id_hotel='".$id_hotel."' ";
	$rs = mysqli_query (conectar(), $sql);
	$n_resultados=mysqli_num_rows($rs);
	liberar($rs);
	if ($n_resultados!=0){// El usuario esta vinculado con el hotel
		$fecha = dateHoy();
		$sql = "INSERT INTO hotel_user_rate 
		(id_hotel, id_usuario, rate, coment, fecha, id_checkout) 
		VALUES 
		('".$id_hotel."', '".$id_usuario."', '".$rate."', '".$hotelUserComment."', '".$fecha."', '".$id_checkout."')"; 
		mysqli_query (conectar(), $sql);
	}
}
?>