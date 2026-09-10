<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}
	
	//Obtener datos hotel
	function obtenerDatosHotel($id_hotel){
		$id_hotel = mysqli_real_escape_string(conectar(), $id_hotel);
		$sql ="SELECT hoteles.id, hotelName, street, city AS pais, website, 
		emailReserva, telefonoReservas, estrellas, n_habitaciones,
		tipo_hotel, decoracion, descripcion, condiciones, rating, min_rango, max_rango,
		IFNULL(logo, 0) AS logo
		FROM hoteles 
		WHERE hoteles.id='".$id_hotel."' ";
		$rs = mysqli_query (conectar(), $sql);
		$row = mysqli_fetch_assoc($rs);
		$row['hotelName_san']=string_sanitize($row['hotelName']);
		liberar ($rs);
		return ($row);
	}

	//Obtener deals hotel
	function obtenerDealsHotel($id_hotel){
		$id_hotel = mysqli_real_escape_string(conectar(), $id_hotel);
		//QUERY
		$sql = "SELECT n_referrals, referral_goal.id_oferta, 
				case when oferta_lang.nombre is null 
                then  oferta_en.nombre 
                else oferta_lang.nombre end as nombre, 
				img
				FROM referral_goal
				INNER JOIN hotel_oferta ON hotel_oferta.id=referral_goal.id_oferta
				LEFT JOIN hotel_oferta_lang as oferta_en on hotel_oferta.id = oferta_en.id_oferta  and oferta_en.lang='en' 
                LEFT JOIN hotel_oferta_lang as oferta_lang on hotel_oferta.id = oferta_lang.id_oferta and oferta_lang.lang='".$_SESSION['userNavLang']. "'
				WHERE referral_goal.id_hotel='$id_hotel' ORDER BY n_referrals ASC";

		//Prepare SQL and create array to hold results		
		$data = array();
		$result = mysqli_query (conectar(), $sql);

		//Create array of elements
		while ($list = mysqli_fetch_assoc($result)) {
			array_push($data, $list);
		}

		//Liberate connection and return data
		liberar ($result);
		return ($data);
	}

 ?>