<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//Fx para guardar las ofertas de pre-stay, stay y post-stay
//$tipo: 'prestay', 'stay' o 'poststay'. Debe coincidir con la tabla de la base de datos (hotel_oferta_".$tipo.")
//En el caso de stay, id_oferta no es un id, es la url a guardar, OJO
function guardarOfertaStay($id_oferta, $id_hotel, $tipo)
{
	$id_oferta = sqlEscape($id_oferta);
	$sql = "INSERT INTO hotel_oferta_".$tipo." (id_hotel, id_oferta) VALUES ('".$id_hotel."', '".$id_oferta."') 
	 ON DUPLICATE KEY UPDATE id_oferta='".$id_oferta."'";
	escritura($sql);
	//Borrar cache
	deleteCacheByTag('hotel_goals_' . $id_hotel);
}
?>