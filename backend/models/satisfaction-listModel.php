<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

function getSendingDaysReview($id_hotel){

	$sql = "SELECT diasEnvio FROM hotel_review where id_hotel = $id_hotel";
	
	$row = lectura($sql);

	return $row['diasEnvio'];

}

?>