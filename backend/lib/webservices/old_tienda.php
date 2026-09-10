<?php 
include 'librerias.php';// Librerias básicas
//include_once LIB.'fecha.php';

// Añade un elemento al wishlist 
/*function anadirWishlist($id_oferta){
	$id_usuario = $_SESSION['u_logueado'];
	//Mirar si ya esta en wishlist
	$sql = "SELECT id FROM user_wishlist 
	WHERE id_usuario='".$id_usuario."' AND id_oferta='".$id_oferta."' ";
	$rs = mysqli_query (conectar(), $sql);
	$n_resultados=mysqli_num_rows($rs);
	liberar($rs);
	if ($n_resultados!=0){
		$fecha = dateTimeHoy();
		$sql2 = "INSERT INTO ser_wishlist 
		(id_oferta, id_usuario, fecha) 
		VALUES 
		('".$id_Oferta."', '".$id_usuario."', '".$fecha."')";
		mysqli_query (conectar(), $sql2);
	}else{
		//oferta ya en wishlist
	}
}

// Borra un elemento de wishlist 
function borrarWishlist($id_oferta){
	$sql = "DELETE FROM user_wishlist WHERE id='".$id_oferta."' ";
	mysqli_query (conectar(), $sql);
}

// Acciones ----------------------------------------------------------
if(!empty($_POST['wishlist'])){ // Añadir a wishlit
	anadirWishlist($_POST['wishlist']);
}

if(!empty($_POST['noWishlist'])){ // Borrar de wishlit
	borrarWishlist($_POST['wishlist']);
}*/
/*echo 'hola';
echo json_encode($_POST);*/
?>