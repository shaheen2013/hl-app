<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

function propietarioCupon($id_usuario, $id_cupon)
{
	$id_usuario = mysqli_real_escape_string(conectar(), $id_usuario);
	$id_cupon = mysqli_real_escape_string(conectar(), $id_cupon);
	
	$sql = "SELECT id FROM user_cupones 
	WHERE id='".$id_cupon."' AND id_usuario='".$id_usuario."' ";
	$rs = mysqli_query (conectar(), $sql);
	$n_resultados = mysqli_num_rows($rs);
	liberar ($rs);
	if($n_resultados == '1'){
		return true;
	}else{
		return false;
	}
}

function cuponCanjeado($id_cupon)
{
	$id_cupon = mysqli_real_escape_string(conectar(), $id_cupon);
	
	$sql = "SELECT canjeado FROM user_cupones WHERE id='".$id_cupon."' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar ($rs);
	if ($row['canjeado']==1){
		return true;
	}else{
		return false;
	}
}

function regalarCupon($id_cupon, $id_usuario)
{
	$id_usuario = mysqli_real_escape_string(conectar(), $id_usuario);
	$id_cupon = mysqli_real_escape_string(conectar(), $id_cupon);
	
	$sql = "UPDATE user_cupones SET id_usuario='".$id_usuario."' WHERE id='".$id_cupon."' ";
	mysqli_query (conectar(), $sql);
}

// Devuelve el ID del usuario si la cuenta no esta creada
function obtenerIdUsuarioEmail($email)
{
	$email = mysqli_real_escape_string(conectar(), $email);
	
	$sql = "SELECT id FROM users WHERE email='".$email."' AND created!='0000-00-00 00:00:00'";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar ($rs);
	return $row['id'];
}

function obtenerIdHotelDeOferta($id_oferta)
{
	$id_oferta = mysqli_real_escape_string(conectar(), $id_oferta);
	
	$sql = "SELECT id_hotel FROM hotel_oferta WHERE id='".$id_oferta."' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar ($rs);
	return $row['id_hotel'];
}

function obtenerIdHotelDeCupon($id_cupon)
{
	$id_cupon = mysqli_real_escape_string(conectar(), $id_cupon);
	
	$sql = "SELECT id_hotel FROM user_cupones
	LEFT JOIN hotel_oferta ON hotel_oferta.id=user_cupones.id_oferta
	WHERE user_cupones.id='".$id_cupon."' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar ($rs);
	return $row['id_hotel'];
}

// Devuelve los datos del cupon
// Si la oferta es de cadena el nombre del hotel es NULL, debemos devolver el de cadena
function obtenerDatosCuponRC($id_cupon)
{
	$id_cupon = mysqli_real_escape_string(conectar(), $id_cupon);
	
	$sql = "SELECT voucher, ";
	//Idioma oferta
	$sql .= "case when oferta_lang.nombre is null 
        then   oferta_en.nombre 
        else oferta_lang.nombre end AS  nombre_oferta, hotel_oferta.img, hotel_oferta.id AS nombre_oferta, ";
	$sql .=  " hotel_oferta.id AS id_oferta, hoteles.hotelName, hotel_oferta.puntos, 
	cadena.nombre AS nombre_cadena
	FROM user_cupones 
	INNER JOIN hotel_oferta ON hotel_oferta.id=user_cupones.id_oferta
	LEFT JOIN hoteles ON hoteles.id=hotel_oferta.id_hotel
	LEFT JOIN cadena ON cadena.id=hotel_oferta.id_cadena
	LEFT JOIN hotel_oferta_lang as oferta_en   on hotel_oferta.id = oferta_en.id_oferta   and oferta_en.lang='en' 
    LEFT JOIN hotel_oferta_lang as oferta_lang on hotel_oferta.id = oferta_lang.id_oferta and oferta_lang.lang='". $_SESSION['userNavLang'] . "'
	WHERE user_cupones.id='".$id_cupon."' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar ($rs);
	// Si la oferta es de cadena no tiene nombre de hotel
	if (empty($row['hotelName']))
	{
		$row['hotelName']=$row['nombre_cadena'];
	}
	return $row;
}

// Obtiene los datos del regalador para el email
function obtenerDatosRegalador($id_usuario)
{
	$id_usuario = mysqli_real_escape_string(conectar(), $id_usuario);
	
	$sql = "SELECT nombre FROM users WHERE id='".$id_usuario."' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar ($rs);
	return $row;
}
?>