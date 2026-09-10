<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//Contenido solo visible si logueado
include LIB.'logueado.php';
userLanding ();// Si no esta logueado lo manda a la landing

include_once LIB.'wishlist.php';
include_once LIB.'fecha.php';

// Borrar de wishlist
if (!empty($_GET['del'])){
	$borrar = mysqli_real_escape_string(conectar(), $_GET['del']);
	borrarWishlist($borrar);
	$ok =  array (true, '2009');
}
$arrayWishlist = obtenerArrayWishlist($_SESSION['u_logueado']);

/*echo '<pre>';
print_r($arrayWishlist);
echo '</pre>';*/
?>