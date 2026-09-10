<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

function ofertaHotelCadena($id_oferta)
{
	$id_oferta = mysqli_real_escape_string(conectar() , $id_oferta);
	$sql = "SELECT id_hotel, id_cadena
	FROM hotel_oferta WHERE id='".$id_oferta."'";
	$row = lectura($sql);
	$tipo = '';	$id = '';
	if($row['id_cadena']!='0'){
		//Oferta de cadena 
		$tipo = 'cad';
		$id = $row['id_cadena'];
	}else{
		//Oferta de hotel
		$tipo = 'hot';
		$id = $row['id_hotel'];
	}
	$hotelCadena = array('id'=>$id, 'tipo'=>$tipo);
	return $hotelCadena;
}
?>