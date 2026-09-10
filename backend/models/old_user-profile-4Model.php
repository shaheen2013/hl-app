<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

function obtenerTiposOferta(){
	$sql = "SELECT id_categoria_oferta, categoria_".$_SESSION['userLang']." AS nombre FROM categoria_oferta";
	$rs = mysqli_query (conectar(), $sql);
	$i=0;
	while ($row = mysqli_fetch_assoc($rs)) {
		$arrayTiposOferta[$i]['id']=$row['id_categoria_oferta'];
		$arrayTiposOferta[$i]['nombre']=$row['nombre'];
		$i++;
	}
	liberar ($rs);
	return ($arrayTiposOferta);	
}

//Insertar ofertas
function InsertTipoOferta ($oferta){
	//SELECT si ya existe!!!
	$sql = "INSERT INTO user_categoria_oferta (id_usuario, id_tipo_oferta) VALUES ('".$_SESSION['u_logueado']."', '".$oferta."') ";
	mysqli_query (conectar(), $sql);
}

//Insertar notificaciones de ofertas
function InsertOfertaNotificaciones ($notif_hotelinking, $notif_especiales, $compart_hoteles){
	$sql = "UPDATE users SET notif_hotelinking='".$notif_hotelinking."', notif_especiales='".$notif_especiales."', compart_hoteles='".$compart_hoteles."' WHERE id='".$_SESSION['u_logueado']."' ";
	mysqli_query (conectar(), $sql);
}

//Borramos los tipos de oferta
function borrarTiposOferta(){
	$sql = "DELETE FROM user_categoria_oferta WHERE id_usuario='".$_SESSION['u_logueado']."' ";
	mysqli_query (conectar(), $sql);
}

//Borramos las notificaciones de oferta
function borrarNotificaciones(){
	$sql = "DELETE FROM user_categoria_oferta WHERE id_usuario='".$_SESSION['u_logueado']."' ";
	mysqli_query (conectar(), $sql);
}

//Obtenemos los tipos de oferta del usuario (Option select)
function obtenerOfferPreferencesUsuario(){
	$arrayOfertasUsuario = array();
	$sql = "SELECT user_categoria_oferta.id_tipo_oferta, categoria_".$_SESSION['userLang']." AS nombre 
	FROM user_categoria_oferta 
	INNER JOIN categoria_oferta 
		ON categoria_oferta.id_categoria_oferta=user_categoria_oferta.id_tipo_oferta
	WHERE id_usuario='".$_SESSION['u_logueado']."' ";
	$rs = mysqli_query (conectar(), $sql);
	$i=0;
	while ($row = mysqli_fetch_assoc($rs)){
		$arrayOfertasUsuario[$i]['nombre']=$row['nombre'];
		$arrayOfertasUsuario[$i]['id_oferta']=$row['id_tipo_oferta'];
		$i++;
	}
	liberar($rs);
	return ($arrayOfertasUsuario);
}

//Obtenemos los notificaciones del usuario (checkbox)
function obtenerNotificaciones (){
	$sql = "SELECT notif_hotelinking, notif_especiales, compart_hoteles FROM users WHERE id='".$_SESSION['u_logueado']."' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	
	$arrayPreferenciasProfile4['notif_hotelinking'] = $row['notif_hotelinking'];
	$arrayPreferenciasProfile4['notif_especiales'] = $row['notif_especiales'];
	$arrayPreferenciasProfile4['compart_hoteles'] = $row['compart_hoteles'];
	
	liberar ($rs);
	return ($arrayPreferenciasProfile4);	
}
?>