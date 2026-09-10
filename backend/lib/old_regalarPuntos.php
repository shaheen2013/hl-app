<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

// Regalar puntos de hotel/cadena (NUNCA de Hotelinking) de usuario a usuario
include_once LIB.'agregarPuntos.php';
include_once LIB.'crearNuevoUsuario.php';
include_once LIB.'invitaciones.php';
include_once LIB.'referrer.php';
include_once LIB.'ordenacion.php';
include_once LIB.'obtenerDatosUsuario.php';

function obtenerPuntosHotel($id_usuario){
	$arrayPuntosUsuario = array();
	$sql = "SELECT hoteles.id, puntos, hotelName AS nombre
	FROM user_points
	INNER JOIN hoteles ON user_points.id_emisor=hoteles.id
	WHERE user_points.id_usuario='".$id_usuario."'
	ORDER BY puntos DESC ";
	$rs = mysqli_query (conectar(), $sql);
	$i=0;
	while ($row = mysqli_fetch_assoc($rs)){
		foreach ($row as $key=>$valor){
			$arrayPuntosUsuario[$i][$key] = $valor;
		}
		$i++;
	}
	liberar($rs);
	return ($arrayPuntosUsuario);
}

function obtenerPuntosCadena($id_usuario){
	$arrayPuntosUsuario = array();
	$sql = "SELECT puntos, cadena.nombre, cadena.id
	FROM user_points_cadena
	LEFT JOIN cadena ON cadena.id=user_points_cadena.id_cadena
	WHERE user_points_cadena.id_usuario='".$id_usuario."'
	ORDER BY puntos DESC ";
	$rs = mysqli_query (conectar(), $sql);
	$i=0;
	while ($row = mysqli_fetch_assoc($rs)){
		foreach ($row as $key=>$valor){
			$arrayPuntosUsuario[$i][$key] = $valor;
		}
		$i++;
	}
	liberar($rs);
	return ($arrayPuntosUsuario);
}

function crearSelectPuntos($arrayPuntosHotel, $arrayPuntosUsuarioCadena){
	$arraySelectPuntos = array();
	$i=0;
	foreach($arrayPuntosHotel as $puntosHotel){
		$arraySelectPuntos[$i]['id']= 'h-'.$puntosHotel['id'];
		$arraySelectPuntos[$i]['nombre']= $puntosHotel['nombre'];
		$arraySelectPuntos[$i]['puntos']= $puntosHotel['puntos'];
		$i++;
	}
	foreach($arrayPuntosUsuarioCadena as $puntosCadena){
		$arraySelectPuntos[$i]['id']= 'c-'.$puntosCadena['id'];
		$arraySelectPuntos[$i]['nombre']= $puntosCadena['nombre'];
		$arraySelectPuntos[$i]['puntos']= $puntosCadena['puntos'];
		$i++;
	}
	$arraySelectPuntos = orderMultiDimensionalArray ($arraySelectPuntos, 'puntos', 1);
	return $arraySelectPuntos;
}

function mirarPuntosMaximos($id_usuario, $id_hot_cad){
	$hotelCadena = explode('-', $id_hot_cad); // contiene c/h + id (c-9, h-11)
	if ($hotelCadena[0]=='c'){
		$sql = "SELECT puntos FROM user_points_cadena
		WHERE id_cadena='".$hotelCadena[1]."' AND id_usuario='".$id_usuario."' ";
	}else if ($hotelCadena[0]=='h'){
		$sql = "SELECT puntos FROM user_points
		WHERE id_emisor='".$hotelCadena[1]."' AND id_usuario='".$id_usuario."' ";
	}
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar ($rs);
	return $row['puntos'];
}

// Devuelve el ID del usuario si la cuenta no esta creada
function obtenerUsuarioEmail($email){
	$sql = "SELECT id, DATE(created) AS created  FROM users WHERE email='".$email."' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar ($rs);
	return $row;
}

function obtenerDatosRegalador($id){
	$sql = "SELECT id, nombre FROM users WHERE id='".$id."' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar ($rs);
	return $row;
}

function obtenerDatosHotelRP($id_hotel){
	$sql = "SELECT id, nombreHotel AS name FROM hoteles WHERE id='".$id_hotel."' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar ($rs);
	return $row;
}

function obtenerDatosCadenaRP($id_cadena){
	$sql = "SELECT id, nombre AS name FROM cadena WHERE id='".$id_cadena."' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar ($rs);
	return $row;
}

if(!empty($_POST['userEmail'])){ // Regalar puntos de hotel/cadena a otro usuario
	$id_hot_cad = mysqli_real_escape_string(conectar(), $_POST['hotelRewards']);
	$puntos = mysqli_real_escape_string(conectar(), $_POST['rewardPoints']);
	$email = mysqli_real_escape_string(conectar(), $_POST['userEmail']);
	// id usuario regalado (0 si no existe)
	$id = mysqli_real_escape_string(conectar(), $_POST['id']);

	// Mirar puntos maximos
	$puntosUsuario = mirarPuntosMaximos($_SESSION['u_logueado'], $id_hot_cad);
	if ($puntosUsuario >=  $puntos){
		//Mirar si los puntos son de hotel o cadena
		$hotelCadena = explode('-', $id_hot_cad);
		if ($hotelCadena[0]=='c'){// cadena
			$id_cadena = $hotelCadena[1];
			$id_hotel = 0;
			// Obtener datos de cadena
			$datosHotelCadena = obtenerDatosCadenaRP($id_cadena);
		}else if ($hotelCadena[0]=='h'){//hotel
			$id_hotel = $hotelCadena[1];
			$id_cadena = 0;
			// Obtener datos de hotel
			$datosHotelCadena = obtenerDatosHotelRP($id_hotel);
		}
		
		// Mirar si la cuenta del usuario no esta operativa ()
		$usuario = obtenerUsuarioEmail($email);
		$id_usuario = $usuario['id'];
		if ($id_usuario==''){
			//echo '1<br>';
			// Crear usuario
			$id_usuario = crearNuevoUsuario($email, $id_hotel);
			// Invitación + regalo de puntos -------------------------<<<<
			include_once LANG.$_SESSION['userLang'].'/email/regalarPuntos2.php';
			include_once LIB.'plantillasMails/regalarPuntos2.php';
			crearInvitacionUsuario ($email, 'us', $_SESSION['u_logueado'],$puntos,0, $txtExtra, 1);
			// Cuando haga login se vinculara como referrer
		}else if($usuario['created']=='0000-00-00'){
			//echo '2<br>';
			//Usuario creado pero que todavia no ha aceptado la invitación
			// Invitación nueva + regalo de puntos -------------------------<<<<
			include_once LANG.$_SESSION['userLang'].'/email/regalarPuntos2.php';
			include_once LIB.'plantillasMails/regalarPuntos2.php';
			crearInvitacionUsuario ($email, 'us', $_SESSION['u_logueado'],$puntos,0, $txtExtra, 1);
		}else{
			//echo '3<br>';
			$arrayDatosEmail = obtenerDatosUsuarioMail($id_usuario);
			$regalador = obtenerDatosRegalador($_SESSION['u_logueado']);
			// Mandar email de regalo de puntos ---------------------<<<<
			include_once LANG.$_SESSION['userLang'].'/email/regalarPuntos1.php';
			include_once LIB.'plantillasMails/regalarPuntos1.php';
			mandarEmailMandrillPlantilla($arrayDatosEmail['email'], $arrayDatosEmail['nombre'], $asunto, $cuerpo, 'standard-template');
		}
		// Regalar puntos (u_log -> id_us)(quitar & sumar puntos)
		regalarPuntos($_SESSION['u_logueado'], $id_usuario, $puntos, $id_hot_cad);
		
		//header('Location: '.$urlTree['user-points'].'/?give=ok');
	}else{
		// no tiene puntos suficientes
		$ok = array (false, '4020');
	}
}
?>
