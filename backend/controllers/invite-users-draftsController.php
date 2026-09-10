<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//Contenido solo visible si logueado
include LIB.'logueado.php';
hotelStaffLanding ();// Si no esta logueado lo manda a la landing

include LIB.'paginacion2.php';
include LIB.'ordenacion.php';
include LIB.'fecha.php';

// Campo ORDER BY
$pant='chk-ou-us'; // Pantalla
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

if(!empty($_GET['del'])){
	$id_list = mysqli_real_escape_string(conectar(), $_GET['del']);
	if(borrarLista($id_list, $_SESSION['h_logueado'])){
		$ok = array (true, '2020');
	}else{
		//$ok = array (false, '4002');
	}	
}

// Viene de invitar-usuarios-2 al guardar la lista
if(!empty($_GET['save'])){
	$save = mysqli_real_escape_string(conectar(), $_GET['save']);
	if ($save=='ok'){
		$ok = array(true, '2019');
	}
}

$itemsPage = 10;
$arrayListas = obtenerListas($_SESSION['h_logueado'], $order, $sort, $itemsPage, $pagina);

/*echo '<pre>';
print_r($arrayListas);
echo '</pre>';*/
?>