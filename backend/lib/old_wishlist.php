<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

include_once 'fecha.php';

function borrarDeWishlist ($id_oferta, $id_usuario){
	$sql = "DELETE FROM user_wishlist 
	WHERE id_oferta='".$id_oferta."' AND id_usuario='".$id_usuario."' ";
	mysqli_query (conectar(), $sql);
}

function ofertaWishlist($id_oferta, $id_usuario){
	//Mirar si ya esta en wishlist
	$sql = "SELECT id FROM user_wishlist 
	WHERE id_usuario='".$id_usuario."'  AND id_oferta='".$id_oferta."' ";
	$rs = mysqli_query (conectar(), $sql);
	$n_resultados=mysqli_num_rows($rs);
	liberar($rs);
	if ($n_resultados==0){
		$fecha = dateTimeHoy();
		$sql2 = "INSERT INTO user_wishlist 
		(id_oferta, id_usuario, fecha) 
		VALUES 
		('".$id_oferta."', '".$id_usuario."', '".$fecha."')";
		mysqli_query (conectar(), $sql2);
		// Oferta instertada en wishlist
	}else{
		//oferta ya en wishlist
	}
}

// Acciones ----------------------------------------------------------
// Añadir a wishlist
if(!empty($_GET['wlst']) && !empty($_SESSION['u_logueado'])){
	$id_oferta = mysqli_real_escape_string(conectar(), $_GET['wlst']);
	ofertaWishlist($id_oferta, $_SESSION['u_logueado']);
	$ok = array (true, '2010');
}else if(!empty($_GET['wlst']) && empty($_SESSION['u_logueado'])){
	// Usuario no logueado
	$ok = array (false, '4015');
}

// Borrar de wishlist
if(!empty($_GET['unwlst']) && !empty($_SESSION['u_logueado'])){
	$id_oferta = mysqli_real_escape_string(conectar(), $_GET['unwlst']);
	borrarDeWishlist($id_oferta, $_SESSION['u_logueado']);
	$ok = array (true, '2009');
}else if(!empty($_GET['wlst']) && empty($_SESSION['u_logueado'])){
	// Usuario no logueado
	$ok = array (false, '4015');
}
?>