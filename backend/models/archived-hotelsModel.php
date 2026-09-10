<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

include_once LIB.'loguearHotel.php';
include_once LIB.'generarToken.php';

include_once 'chain-managementModel.php';

$id_hotel = $_SESSION['h_logueado'];
$id_hotel = $_SESSION['c_logueado'];

obtenerTotalOfertasHotel($id_hotel);
obtenerRatingHotel($id_hotel);

function  getArchivedHotels($id_cadena)
{
	$arrayHoteles = array();
	$sql = "SELECT DISTINCT hoteles.id, hotelName, IFNULL(logo,0) AS logo, hoteles.id,
	hoteles.rating, hoteles.estrellas, city, brands.id AS brand_id
	FROM cadena_hotel
	INNER JOIN hoteles ON hoteles.id=cadena_hotel.id_hotel
	INNER JOIN brands ON hoteles.id = brands.hotel_id
	WHERE id_cadena='".$id_cadena."'
	AND hoteles.activated=0
	ORDER BY id DESC";
	$row = lecturaArray($sql);

	$i=0;
	foreach($row as $hotel){
		foreach ($hotel as $key=>$valor){
			$arrayHoteles[$i][$key]=$valor;
		}
		$arrayHoteles[$i]['nofertas']=obtenerTotalOfertasHotel($hotel['id']);
		$arrayHoteles[$i]['rating']=obtenerRatingHotel($hotel['id']);
		$i++;	
	}

	return $arrayHoteles;
}

function chainChangeHotel($id_hotel, $changeOrActivate)
{
	//get info hotel
	$sql = "SELECT hoteles.id
	FROM hoteles 
	INNER JOIN cadena_hotel ON cadena_hotel.id_hotel=hoteles.id
	WHERE hoteles.id='".$id_hotel."' 
		AND cadena_hotel.id_cadena='".$_SESSION['c_logueado']."' ";
	$rs = mysqli_query (conectar(), $sql);
	$n_resultados=mysqli_num_rows($rs);
	if($n_resultados==1){
		$row = mysqli_fetch_assoc($rs);
		liberar($rs);
		if ($changeOrActivate === 1){
			loguearHotel($row['id'], 1);
		}else{
			loguearCadena($_SESSION['c_logueado']);
		}
		return true;
	}else{
		return false; // The hotel doesnt't belong to the chain
	}
}
?>