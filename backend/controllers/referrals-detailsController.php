<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//Contenido solo visible si logueado
include LIB.'logueado.php';
hotelStaffLanding ();// Si no esta logueado lo manda a la landing

include_once LIB.'paginacion2.php';
include_once LIB.'ordenacion.php';
include_once LIB.'obtenerdatosHotel.php';
include_once LIB.'obtenerDatosUsuario.php';


// Campo ORDER BY
$pant='rfer'; // Pantalla
if(!empty($_GET['ord'])){
	$order = mysqli_real_escape_string(conectar(), $_GET['ord']);
	$_SESSION['ord'.$pant] = $_GET['ord'];
	$sort = toggle();
}else if(!empty($_SESSION['ord'.$pant])){
	$order = $_SESSION['ord'.$pant];
	$sort = $_SESSION['ascdesc'];
}else{
	// Orden por defecto
	$order = 'id';
	$sort = 'DESC';
}

$id_usuario = $url['dir2'];
$urlActual = strtok($_SERVER['REQUEST_URI'],'?'); //Url actual sin parametros
if( substr($urlActual, -1)!='/'){//Si la URL no tiene / al final, se lo ponemos
	$urlActual .='/';
}

$datosUsuario = obtenerDatosUsuarioReferral($id_usuario);

$itemsPage = 10;
$arrayUsuarios=obtenerDetalleUsuario($_SESSION['h_logueado'], $order, $sort, $itemsPage, $pagina, $id_usuario);

$monedaHotel = obtenerMonedaHotel($_SESSION['h_logueado']);

/*echo '<pre>';
print_r($datosUsuario);
echo '</pre>';*/
?>