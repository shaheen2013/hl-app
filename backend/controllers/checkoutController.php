<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//Contenido solo visible si logueado
include LIB.'logueado.php';
hotelStaffLanding ();// Si no esta logueado lo manda a la landing

include_once LIB.'paginacion2.php';
include_once LIB.'staff_id_hotel.php';
include_once LIB.'ordenacion.php';
include_once LIB.'obtenerDatosUsuario.php';

$id_hotel = obtenerIdHotel();

// Campo ORDER BY
if(!empty($_GET['ord'])){
	$order = mysqli_real_escape_string(conectar(), $_GET['ord']);
	$_SESSION['ord'] = $_GET['ord'];
	$sort = toggle();
}else if(!empty($_SESSION['ord'])){
	$order = $_SESSION['ord'];
	$sort = $_SESSION['ascdesc'];
}else{
	// Orden por defecto
	$order = 'chkin_date';
	$sort = 'DESC';
}
$itemsPage = 10;
if(!empty($_GET['search'])){
	$busqueda = mysqli_real_escape_string(conectar() , $_GET['search']);
	$listadoUsuarios = obtenerUsuariosCheckin($id_hotel, $order, $sort, $itemsPage, $pagina, $busqueda);
}else{
	// Mostrar usuarios que estan en check-in
	$listadoUsuarios = obtenerUsuariosCheckin($id_hotel, $order, $sort, $itemsPage, $pagina);
}

unset ($_SESSION['checkout']);

/*echo '<pre>';
print_r($listadoUsuarios);
echo '</pre>';*/
?>