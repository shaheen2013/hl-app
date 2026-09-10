<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

function obtenerOfferMethod(){
	$sql = "SELECT id_method, lang_index FROM offer_method ";
	$rs = mysqli_query (conectar(), $sql);
	while ($row = mysqli_fetch_assoc($rs)){
		$offerMethods[$row['id_method']]=$row['lang_index'];
	}
	liberar ($rs);
	return $offerMethods;
}
?>