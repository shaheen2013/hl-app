<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

// Follow hotel (o todos los hoteles de la cadena) viene de user-points
function followHotelCadena($id, $tipo, $id_usuario){
	if($tipo=='hot'){
		followHotel($id, $id_usuario);
	}else if($tipo=='cad'){
		followCadena($id, $id_usuario);
	}
}

function unfollowHotelCadena($id, $tipo, $id_usuario){
	if($tipo=='hot'){
		unFollowHotel($id, $id_usuario);
	}else if($tipo=='cad'){
		unFollowCadena($id, $id_usuario);
	}
}

// Follow de hotel, viene de ficha hotel
function followHotel($id_hotel, $id_usuario){
	$sql2 = "SELECT follow FROM user_hotels WHERE id_usuario='".$id_usuario."' 
	AND id_hotel='".$id_hotel."' ";
	$rs2 = mysqli_query (conectar(), $sql2);
	$row2 = mysqli_fetch_assoc($rs2);
	liberar($rs2);
	if ($row2['follow']==''){
		$sql3 = "INSERT INTO user_hotels (id_usuario, id_hotel, follow) 
		VALUES ('".$id_usuario."', '".$id_hotel."', '1')";
		mysqli_query (conectar(), $sql3);
	}else{
		$sql3 = "UPDATE user_hotels SET follow=1 WHERE id_usuario='".$id_usuario."'
		 AND id_hotel='".$id_hotel."' ";
		mysqli_query (conectar(), $sql3);
	}
}

function followCadena($id_cadena, $id_usuario){
	// Follow de todos los hoteles de la cadena
	$sql = "SELECT id_hotel FROM cadena_hotel WHERE id_cadena='".$id_cadena."' ";
	$rs = mysqli_query (conectar(), $sql);
	while($row = mysqli_fetch_assoc($rs)){
		followHotel($row['id_hotel'], $id_usuario);
	}
	liberar($rs);
	// Follow cadena
	$sql4 = "SELECT COUNT(id) AS n FROM user_cadena_follow 
	WHERE id_cadena='".$id_cadena."' AND id_usuario='".$id_usuario."' ";
	$rs4 = mysqli_query (conectar(), $sql4);
	$row4 = mysqli_fetch_assoc($rs4);
	liberar($rs4);
	if($row4['n']!=0){
		$sql5 = "UPDATE user_cadena_follow SET follow=1
		WHERE id_cadena='".$id_cadena."' AND id_usuario='".$id_usuario."' ";
	}else{
		$sql5 = "INSERT INTO user_cadena_follow (id_cadena, id_usuario, follow)
		VALUES ('".$id_cadena."', '".$id_usuario."', '1') ";
	}
	mysqli_query (conectar(), $sql5);
}

function unFollowHotel($id_hotel, $id_usuario){
	$sql2 = "SELECT id FROM user_hotels WHERE id_usuario='".$id_usuario."' 
	AND id_hotel='".$id_hotel."' ";
	$rs2 = mysqli_query (conectar(), $sql2);
	$n_resultados = mysqli_num_rows($rs2);
	if ($n_resultados==0){
		/*$sql3 = "INSERT INTO user_hotels (id_usuario, id_hotel, follow) 
		VALUES ('".$id_usuario."', '".$id_hotel."', '0')";
		mysqli_query (conectar(), $sql3);*/
	}else{
		$sql3 = "UPDATE user_hotels SET follow=0 WHERE id_usuario='".$id_usuario."'
		 AND id_hotel='".$id_hotel."' ";
		mysqli_query (conectar(), $sql3);
	}
	liberar($rs2);
}

function unFollowCadena($id_cadena, $id_usuario){
	$sql = "UPDATE user_cadena_follow SET follow=0
	WHERE id_cadena='".$id_cadena."' AND id_usuario='".$id_usuario."' ";
	mysqli_query (conectar(), $sql);
	// Unfollow de todos los hoteles de la cadena
	$sql2 = "SELECT id_hotel FROM cadena_hotel WHERE id_cadena='".$id_cadena."' ";
	$rs2 = mysqli_query (conectar(), $sql2);
	while($row2 = mysqli_fetch_assoc($rs2)){
		unFollowHotel($row2['id_hotel'], $id_usuario);
	}
}

// Follow this hotel
/*if(!empty($_GET['fol']) && !empty($_SESSION['u_logueado'])){
	$id_hotel = mysqli_real_escape_string(conectar(), $_GET['fol']);
	followHotel($id_hotel, $_SESSION['u_logueado']);
	$ok = array (true, '2013');
}else if(!empty($_GET['fol']) && empty($_SESSION['u_logueado'])){
	// Usuario no logueado
	$ok = array (false, '4015');
}
// Unfollow this hotel
if(!empty($_GET['unfol']) && !empty($_SESSION['u_logueado'])){
	$id_hotel = mysqli_real_escape_string(conectar(), $_GET['unfol']);
	unFollowHotel($id_hotel, $_SESSION['u_logueado']);
	$ok = array (true, '2014');
}else if(!empty($_GET['fol']) && empty($_SESSION['u_logueado'])){
	// Usuario no logueado
	$ok = array (false, '4015');
}*/

if (!empty($_GET['fol']) && !empty($_GET['tipo']) && !empty($_SESSION['u_logueado'])){ // id del hotel
	$fol = mysqli_real_escape_string(conectar(), $_GET['fol']);
	$tipo = mysqli_real_escape_string(conectar(), $_GET['tipo']);
	followHotelCadena($fol, $tipo, $_SESSION['u_logueado']);
	$ok = array (true, '2013');
}else if(!empty($_GET['fol']) && empty($_SESSION['u_logueado'])){
	// Usuario no logueado
	$ok = array (false, '4015');
}

if (!empty($_GET['unfol']) && !empty($_GET['tipo']) && !empty($_SESSION['u_logueado'])){ // id del hotel
	$unfol = mysqli_real_escape_string(conectar(), $_GET['unfol']);
	$tipo = mysqli_real_escape_string(conectar(), $_GET['tipo']);
	unfollowHotelCadena($unfol, $tipo, $_SESSION['u_logueado']);
	$ok = array (true, '2014');
}else if(!empty($_GET['fol']) && empty($_SESSION['u_logueado'])){
	// Usuario no logueado
	$ok = array (false, '4015');
}
?>