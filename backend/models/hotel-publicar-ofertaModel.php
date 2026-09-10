<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//Onboarding
function updateOfertaStep($element, $id){
	$sql = "UPDATE onboarding SET $element = 1 WHERE id_hotel = '".$id."'";
	mysqli_query (conectar(), $sql);
}
?>