<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

include_once RUTA_DIR . LIB . 'cache.php';

function insertarDatosDB2 ($estrellas, $minRango, $maxRango, $numHabitaciones, $hotelType, $hotelDecoration, $hotelDescription, $hotelConditions, $jan, $feb, $mar, $apr, $may, $jun, $jul, $ago, $sep, $oct, $nov, $dece){

	$sql = "UPDATE hoteles SET estrellas='".$estrellas."', min_rango='".$minRango."', max_rango='".$maxRango."',n_habitaciones ='".$numHabitaciones."'

	, tipo_hotel='".$hotelType."', decoracion='".$hotelDecoration."',

	descripcion='".$hotelDescription."', condiciones='".$hotelConditions."',
	jan='".$jan."', feb='".$feb."', mar='".$mar."', apr='".$apr."', may='".$may."', jun='".$jun."', jul='".$jul."', ago='".$ago."', sep='".$sep."', oct='".$oct."', nov='".$nov."' , dece='".$dece."'
	WHERE id='".$_SESSION['h_logueado']."' ";
	// echo '---------------------'.$sql;
	mysqli_query (conectar(), $sql);

	//remove hotel profile tag in cache
	deleteCacheByTag('hotel_profile_' . $_SESSION['h_logueado']);	
}

function borrarTiposHabHotel($id_hotel){
	$sql = "DELETE FROM hotel_tipos_hab WHERE id_hotel='".$id_hotel."' ";
	mysqli_query (conectar(), $sql);
}

//Tipos de habitaciones a instertar
function insertarTiposHabHotel($id_hotel, $arrayTiposHab){
	borrarTiposHabHotel($id_hotel);
	$nTiposHab = count($arrayTiposHab);
	$i=0;
	while($i<$nTiposHab){
		$sql2 = "INSERT INTO hotel_tipos_hab (id_hotel, id_tipo_hab) VALUES ('".$id_hotel."', '".$arrayTiposHab[$i]."' )";
		mysqli_query (conectar(), $sql2);
		$i++;
	}
}

function borrarExtrasHotel($id_hotel){
	$sql = "DELETE FROM hotel_extras WHERE id_hotel='".$id_hotel."' ";
	mysqli_query (conectar(), $sql);
}

//Tipos de extras en habitaciones a instertar
function insertarExtrasHotel($id_hotel, $arrayExtras){
	borrarExtrasHotel($id_hotel);
	$nTiposHab = count($arrayExtras);
	$i=0;
	while($i<$nTiposHab){
		$sql2 = "INSERT INTO hotel_extras (id_hotel, id_extra) VALUES ('".$id_hotel."', '".$arrayExtras[$i]."' )";
		mysqli_query (conectar(), $sql2);
		$i++;
	}
}

function borrarServiciosHotel($id_hotel){
	$sql = "DELETE FROM hotel_servicios WHERE id_hotel='".$id_hotel."' ";
	mysqli_query (conectar(), $sql);
}

//Tipos de servicios del hotel a instertar
function insertarServiciosHotel($id_hotel, $arrayServicios){
	borrarServiciosHotel($id_hotel);
	$nTiposHab = count($arrayServicios);
	$i=0;
	while($i<$nTiposHab){
		$sql2 = "INSERT INTO hotel_servicios (id_hotel, id_servicio) VALUES ('".$id_hotel."', '".$arrayServicios[$i]."' )";
		mysqli_query (conectar(), $sql2);
		$i++;
	}
}

function obtenerDatosHotelProfile2 ($id_hotel){
	$arrayHotelProfile2 = array();
	$sql = "SELECT id, hotelName AS nombre,
	 estrellas, min_rango, max_rango, n_habitaciones, tipo_hotel, decoracion,
	 descripcion, condiciones AS conditions, notif
	FROM hoteles WHERE id='".$id_hotel."' ";
	$rs = mysqli_query (conectar(), $sql);
	$arrayHotelProfile2 = mysqli_fetch_assoc($rs);
	liberar ($rs);
	$arrayHotelProfile2['nombre_san']=string_sanitize($arrayHotelProfile2['nombre']);
	return ($arrayHotelProfile2);
}

function obtenerDatosHotelExtras($id_hotel){
	$arrayHotelExtras = array(array ('id_extra'=>''));
	$sql = "SELECT id_extra	FROM hotel_extras WHERE id_hotel='".$id_hotel."' ";
	$rs = mysqli_query (conectar(), $sql);
	$n_resultados = mysqli_num_rows($rs);
	$i=0;
	/*if ($n_resultados==0){
		$arrayHotelExtras[$i]['id_extra']='';
	}else{*/
		while ($row = mysqli_fetch_assoc($rs)){
			$sql2 = "SELECT extra_es FROM extras_hotel WHERE id_extra='".$row['id_extra']."' ";
			$rs2 = mysqli_query (conectar(), $sql2);
			$row2 = mysqli_fetch_assoc($rs2);
			liberar ($rs2);
			$arrayHotelExtras[$i]['id_extra']=$row['id_extra'];
			$arrayHotelExtras[$i]['extra']=$row2['extra_es'];
			$i++;
		}
	//}
	liberar ($rs);
	return ($arrayHotelExtras);
}

function obtenerDatosHotelTiposHab($id_hotel){
	$arrayHotelTiposHab = array(array ('id_tipo_hab'=>''));
	$sql = "SELECT id_tipo_hab	FROM hotel_tipos_hab WHERE id_hotel='".$id_hotel."' ";
	$rs = mysqli_query (conectar(), $sql);
	$n_resultados = mysqli_num_rows($rs);
	$i=0;
	/*if ($n_resultados==0){
		$arrayHotelTiposHab[$i]['id_tipo_hab']='';
	}else{*/
		while ($row = mysqli_fetch_assoc($rs)){
			$sql2 = "SELECT tipo_hab_es FROM tipos_hab_hotel WHERE id_tipo_hab='".$row['id_tipo_hab']."' ";
			$rs2 = mysqli_query (conectar(), $sql2);
			$row2 = mysqli_fetch_assoc($rs2);
			liberar ($rs2);
			$arrayHotelTiposHab[$i]['id_tipo_hab']=$row['id_tipo_hab'];
			$arrayHotelTiposHab[$i]['tipo_hab']=$row2['tipo_hab_es'];
			$i++;
		}
	//}
	liberar ($rs);
	return ($arrayHotelTiposHab);
}

function obtenerDatosHotelServicios($id_hotel){
	$arrayHotelServicios = array(array ('id_servicio'=>''));
	$sql = "SELECT id_servicio	FROM hotel_servicios WHERE id_hotel='".$id_hotel."' ";
	$rs = mysqli_query (conectar(), $sql);
	$n_resultados = mysqli_num_rows($rs);
	$i=0;
	/*if ($n_resultados==0){
		$arrayHotelServicios[$i]['id_servicio']='';
	}else{*/
		while ($row = mysqli_fetch_assoc($rs)){
			$sql2 = "SELECT servicio_es FROM servicios_hotel WHERE id_servicio='".$row['id_servicio']."' ";
			$rs2 = mysqli_query (conectar(), $sql2);
			$row2 = mysqli_fetch_assoc($rs2);
			liberar ($rs2);
			$arrayHotelServicios[$i]['id_servicio']=$row['id_servicio'];
			$arrayHotelServicios[$i]['servicio']=$row2['servicio_es'];
			$i++;
		}
	//}
	liberar ($rs);
	return ($arrayHotelServicios);
}

function obtenerDatosHotelTemporada($id_hotel){
	$sql = "SELECT jan, feb , mar, apr, may, jun, jul, ago, sep, oct, nov, dece
	FROM hoteles WHERE id='".$id_hotel."' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	return ($row);
}

function obtenerTiposHotel(){
	$arrayTiposHotel = array();
	$sql = "SELECT id_tipo_hotel, tipo_".$_SESSION['userLang']." AS tipo
	FROM tipos_hotel ORDER BY tipo ASC";
	$rs = mysqli_query (conectar(), $sql);
	$i=0;
	while ($row = mysqli_fetch_assoc($rs)) {
		$arrayTiposHotel[$i]['id_tipo_hotel']=$row['id_tipo_hotel'];
		$arrayTiposHotel[$i]['tipo']=htmlentities($row['tipo'], ENT_QUOTES, "ISO-8859-1");
		$i++;
	}
	liberar ($rs);
	return ($arrayTiposHotel);
}

function obtenerTiposDecoracion(){
	$arrayTiposDecoracion = array();
	$sql = "SELECT id_decoracion, decoracion_".$_SESSION['userLang']." AS decoracion
	FROM tipos_hotel_decoracion ORDER BY decoracion ASC";
	$rs = mysqli_query (conectar(), $sql);
	$i=0;
	while ($row = mysqli_fetch_assoc($rs)) {
		$arrayTiposDecoracion[$i]['id_decoracion']=$row['id_decoracion'];
		$arrayTiposDecoracion[$i]['decoracion']=htmlentities($row['decoracion'], ENT_QUOTES, "ISO-8859-1");
		$i++;
	}
	liberar ($rs);
	return ($arrayTiposDecoracion);
}

function obtenerExtrasHotel(){// Room features
	$arrayExtrasHotel = array();
	$sql = "SELECT id_extra, extra_".$_SESSION['userLang']." AS extra
	FROM extras_hotel WHERE id_extra!='oth' ORDER BY extra ASC";
	$rs = mysqli_query (conectar(), $sql);
	$i=0;
	while ($row = mysqli_fetch_assoc($rs)) {
		$arrayExtrasHotel[$i]['id_extra']=$row['id_extra'];
		$arrayExtrasHotel[$i]['extra']=htmlentities($row['extra'],ENT_QUOTES,"ISO-8859-1");
		$i++;
	}
	liberar ($rs);
	$sql2= "SELECT id_extra, extra_".$_SESSION['userLang']." AS extra
	FROM extras_hotel WHERE id_extra='oth' ";
	$rs2 = mysqli_query (conectar(), $sql2);
	$row2 = mysqli_fetch_assoc($rs2);
	liberar($rs2);
	$arrayExtrasHotel[]=$row2;
	return ($arrayExtrasHotel);
}

function obtenerTiposHabHotel(){
	$arrayTiposHabHotel = array();
	$sql = "SELECT id_tipo_hab, tipo_hab_".$_SESSION['userLang']." AS tipo_hab
	FROM tipos_hab_hotel WHERE id_tipo_hab!='oth' ORDER BY tipo_hab ASC";
	$rs = mysqli_query (conectar(), $sql);
	$i=0;
	while ($row = mysqli_fetch_assoc($rs)) {
		$arrayTiposHabHotel[$i]['id_tipo_hab']=$row['id_tipo_hab'];
		$arrayTiposHabHotel[$i]['tipo_hab']=htmlentities($row['tipo_hab'],ENT_QUOTES,"ISO-8859-1");
		$i++;
	}
	liberar ($rs);
	$sql2= "SELECT id_tipo_hab, tipo_hab_".$_SESSION['userLang']." AS tipo_hab
	FROM tipos_hab_hotel WHERE id_tipo_hab='oth' ";
	$rs2 = mysqli_query (conectar(), $sql2);
	$row2 = mysqli_fetch_assoc($rs2);
	liberar($rs2);
	$arrayTiposHabHotel[]=$row2;
	return ($arrayTiposHabHotel);
}

function obtenerServiciosHotel(){
	$arrayServiciosHotel = array();
	$sql = "SELECT id_servicio, servicio_".$_SESSION['userLang']." AS servicio
	FROM servicios_hotel WHERE id_servicio!='oth' ORDER BY servicio ASC";
	$rs = mysqli_query (conectar(), $sql);
	$i=0;
	while ($row = mysqli_fetch_assoc($rs)) {
		$arrayServiciosHotel[$i]['id_servicio']=$row['id_servicio'];
		$arrayServiciosHotel[$i]['servicio']=htmlentities($row['servicio'], ENT_QUOTES, "ISO-8859-1");
		$i++;
	}
	liberar ($rs);
	$sql2= "SELECT id_servicio, servicio_".$_SESSION['userLang']." AS servicio
	FROM servicios_hotel WHERE id_servicio='oth' ";
	$rs2 = mysqli_query (conectar(), $sql2);
	$row2 = mysqli_fetch_assoc($rs2);
	liberar($rs2);
	$arrayServiciosHotel[]=$row2;
	return ($arrayServiciosHotel);
}

function obtenerFotosHotel($id_hotel){
	$arrayFotos = array();
	$sql = "SELECT id, img, pri FROM hoteles_img WHERE id_hotel='".$id_hotel."' ";
	$rs = mysqli_query (conectar(), $sql);
	$i=0;
	while ($row = mysqli_fetch_assoc($rs)) {
		foreach ($row as $key=>$valor){
			if($key == 'img'){
				$arrayFotos[$i][$key] = 'small_'.$valor;
			}else{
				$arrayFotos[$i][$key] =$valor;
			}
		}
		$i++;
	}
	liberar ($rs);
	return ($arrayFotos);
}

// Guardamos el nombre de la IMG en la BD con el Id de Hotel
function guardarNombreImg($id, $nombreImg){
	// Miramos si ya existe esta imagen
	$sql2="SELECT img FROM hoteles_img WHERE id_hotel='".$id."' AND img='".$nombreImg."' ";
	$rs2 = mysqli_query (conectar(), $sql2);
	$n_resultados = mysqli_num_rows($rs2);
	if ($n_resultados==0){
		$sql="INSERT INTO hoteles_img (id_hotel, img) VALUES ('".$id."', '".$nombreImg."')";
		mysqli_query (conectar(), $sql);
	}
	liberar($rs2);
}

function obtenerNombreFoto($id_hotel, $id_foto){
	$sql="SELECT img FROM hoteles_img WHERE id='".$id_foto."' AND id_hotel='".$id_hotel."'";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	return $row['img'];
}

function borrarFotoBD($id_hotel, $id_foto){
	$sql = "DELETE FROM hoteles_img WHERE id='".$id_foto."' AND id_hotel='".$id_hotel."' ";
	mysqli_query (conectar(), $sql);
}

function priorizarFoto($id_hotel, $id_foto){
	$sql = "UPDATE hoteles_img SET pri=0 WHERE id_hotel='".$id_hotel."' ";
	mysqli_query (conectar(), $sql);
	$sql2 = "UPDATE hoteles_img SET pri=1 WHERE id_hotel='".$id_hotel."'
	AND id='".$id_foto."'";
	mysqli_query (conectar(), $sql2);
}

//onboarding
function hotelProfile2Onboarding($id_hotel){
	//update para el onboarding
		$sql2 = ("UPDATE onboarding SET hotel_profile = '1' WHERE id_hotel =".$id_hotel."");
		$query2 = mysqli_query (conectar(), $sql2);
}
?>