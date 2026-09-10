<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

include_once LIB.'loguearHotel.php';
include_once LIB.'generarToken.php';

// crear cadena vacia para vincular con el hotel
function crearCadena($id_hotel, $permisos){
	// obtenemos email y pass del hotel y se lo pasamos a la cadena
	$sql2 = "SELECT email, password FROM hoteles WHERE id='".$id_hotel."' ";
	$rs2 = mysqli_query (conectar(), $sql2);
	$row2 = mysqli_fetch_assoc($rs2);
	liberar($rs2);
	$sql = "INSERT INTO cadena (nombre, email, password) 
	VALUES ('-', '".$row2['email']."', '".$row2['password']."')";
	$link = conectar();
	mysqli_query ($link, $sql);
	$id_cadena = mysqli_insert_id($link);
	// Borrar pass del hotel (dejamos el email igual que la cadena, se puede cambiar en el hotel profile)
	$sql3 = "UPDATE hoteles SET password='' WHERE id='".$id_hotel."' ";
	mysqli_query (conectar(), $sql3);

	$sql4 = "UPDATE hotel_oferta SET id_cadena = '{$id_cadena}', id_hotel = 0 WHERE id_hotel = '{$id_hotel}'";
	mysqli_query (conectar(), $sql4);
	
	$sql5 = "UPDATE user_satisfaction SET id_cadena = '{$id_cadena}' WHERE id_hotel = '{$id_hotel}'";
	mysqli_query (conectar(), $sql5);

	//Generar GUID de cadena
	do{// Si el GUID ya existe lo volvemos a generar
		$guid = guidv4();
	} while ( guidRepetido($guid, 'cadena_guid') );
	$sql7 = "INSERT INTO cadena_guid (id_cadena, guid) VALUES ('".$id_cadena."', '".$guid."')";
	mysqli_query (conectar(), $sql7);

	$sql8 = "INSERT INTO permisos_cadenas (id_cadena, LY, RF, MK) 
	VALUES ('".$id_cadena."', '".$permisos['LY']."', '".$permisos['RF']."', '".$permisos['MK']."') ";
	mysqli_query (conectar(), $sql8);

	return ($id_cadena);
}

function vincularCadenaHotel($id_cadena, $id_hotel)
{
	//Create connection
	$con = conectar();

	$sql2 = "SELECT id FROM cadena_hotel 
			WHERE id_cadena='".$id_cadena."' AND id_hotel='".$id_hotel."' ";

	$rs2 = lecturaArray($sql2, $con, FALSE);
	$n_resultados = count($rs2);
	if ($n_resultados==0)
	{

		$sql = "INSERT INTO cadena_hotel (id_cadena, id_hotel) 
				VALUES ('".$id_cadena."', '".$id_hotel."')";
		try {

			escritura($sql, $con);

		 } catch (Exception $err){
			global $log;

			$log->error('Error when inserting hotel in the chain in the table cadena_hotel', [
				'Error' => $err,
				'id_chain' => $id_cadena,
				'id_hotel' => $id_hotel,
				'SQL' => $sql,
				'SESION' => $_SESSION,
				]);

			return FALSE;
		 }
	}

	return TRUE;
}

function puntosHotelACadena($id_cadena, $id_hotel){
	// Copiar los puntos de hotel a la cadena
	$sql = "SELECT id_usuario, puntos FROM user_points WHERE id_emisor='".$id_hotel."' ";
	//echo '<br />------------------------'.$sql;
	$rs = mysqli_query (conectar(), $sql);
	while ($row = mysqli_fetch_assoc($rs)){
		$sql2 = "INSERT INTO user_points_cadena (id_usuario, id_cadena, puntos) 
		VALUES ('".$row['id_usuario']."', '".$id_cadena."', '".$row['puntos']."')";
		//echo '<br />------------------------'.$sql2;
		mysqli_query (conectar(), $sql2);
	}
	liberar($rs);
	// Borrar puntos hotel
	$sql3 = "DELETE FROM user_points WHERE id_emisor='".$id_hotel."' ";
	//echo '<br />------------------------'.$sql3;
	mysqli_query (conectar(), $sql3);

	// Actualizar user_points_reg (WHERE id_hotel -> id_cadena (Diff columnas))
	/*$sql4 = "UPDATE user_points_reg SET id_cadena='".$id_cadena."'
	WHERE id_emisor='".$id_hotel."' ";
	echo '<br />------------------------'.$sql4;
	mysqli_query (conectar(), $sql4);*/
}

//FX para copiar datos de cadena a hotel
function datosCadenaHotel($id_hotel)
{
	//Si el hotel es de cadena, le insertamos datos de cadena
	if(hotelDeCadena($id_hotel)){
		$id_cadena = hotelIdCadena($id_hotel);

		$sql8 = "SELECT booking_engine FROM cadena WHERE id='".$id_cadena."' ";
		$rs = mysqli_query (conectar(), $sql8);
		$row = mysqli_fetch_assoc($rs);
		liberar($rs);
		if(!empty($row['booking_engine'])){
			$sql9 = "UPDATE hoteles SET booking_engine='".$row['booking_engine']."' WHERE id='".$id_hotel."' ";
			mysqli_query (conectar(), $sql9);
		}
	}
}

function obtenerTotalOfertasHotel($id_hotel)
{
	$sql = "SELECT COUNT(id) AS n FROM hotel_oferta WHERE id_hotel=$id_hotel ";
	$row = lectura($sql);
	return ($row['n']);
}

function obtenerRatingHotel($id_hotel)
{
	//Get from cache
    $cacheName = 'obtenerRatingHotel_' . $id_hotel;
	$cache = getFromCache($cacheName);

    if(!$cache)
    {
    	$sql = "SELECT ROUND(((SUM(puntuacion)/COUNT(id))),1) AS rating FROM user_satisfaction WHERE id_hotel=$id_hotel AND done=1 LIMIT 500";
		$row = lectura($sql);

		if($row)
            setToCache($cacheName, $row, 86400);//Expira en 24h

    }else{
        $row = $cache->get();
    }

	$rating = (empty($row['rating'])? 'N/A' : $row['rating']);
	return $rating;
}

function  obtenerHotelesCadena($id_cadena)
{
	$arrayHoteles = array();
	$sql = "SELECT DISTINCT hoteles.id, hotelName, IFNULL(logo,0) AS logo, hoteles.id,
	hoteles.rating, hoteles.estrellas, city
	FROM cadena_hotel
	INNER JOIN hoteles ON hoteles.id=cadena_hotel.id_hotel
	WHERE id_cadena='".$id_cadena."'
	AND hoteles.activated=1
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

function cadenaCambioHotel($id_hotel)
{
	//obtener datos hotel
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
		loguearHotel($row['id'], 1);
		return true;
	}else{
		return false; // El hotel no pertenece a la cadena
	}
}

function guardarNombreLogo($hotel_id, $logoName){
    $logoUrl = SECURE_BASE_PATH . DIR_IMG_FICHA_HOTEL . $hotel_id . '/logo/med_' . $logoName;
    $sql = "UPDATE hoteles SET logo='$logoUrl' WHERE id='$hotel_id' ";
//	$sql2 = "INSERT INTO hoteles (logo) VALUES ('$logoUrl') WHERE id='$hotel_id'";
	escritura($sql);
}

function obtenerEmail($id, $tipo){
	$sql = "SELECT email FROM ";
	if($tipo == 'cad'){
		$sql .= " cadena ";
	}else if($tipo == 'hot'){
		$sql .= " hoteles ";
	}
	$sql .= " WHERE id='".$id."' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	return $row['email'];
}
?>
