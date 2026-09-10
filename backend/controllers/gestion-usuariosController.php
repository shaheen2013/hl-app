<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}
//Contenido solo visible si logueado
include LIB.'logueado.php';
hotelStaffLanding ();// Si no esta logueado lo manda a la landing

//include_once LIB.'paginacion.php';
include_once LIB.'paginacion2.php';
include_once LIB.'ordenacion.php';
include_once LIB.'agregarPuntos.php';
include_once LIB.'enviarEmail.php';
include_once LIB.'obtenerDatosUsuario.php';

// For front purposes
$currentPage = 'guests-database';
$currentSubPage = 'guests-database';

// Campo ORDER BY
$pant='ges-us'; // Pantalla
if(!empty($_GET['ord'])){
	$order = mysqli_real_escape_string(conectar(), $_GET['ord']);
	$_SESSION['ord'.$pant] = $_GET['ord'];
	$sort = toggle();
}else if(!empty($_SESSION['ord'.$pant])){
	$order = $_SESSION['ord'.$pant];
	$sort = $_SESSION['ascdesc'];
}else{
	// Orden por defecto
	$order = 'users.id';
	$sort = 'DESC';
}

if (!empty($_GET['give'])){ // Hotel ha dado puntos a usuario
	$give = mysqli_real_escape_string(conectar(), $_GET['give']);
	if($give=='ok'){
		$ok = array (true, '2024');
	}
}

if(!empty($_POST['givePoints'])){
	$puntos = mysqli_real_escape_string(conectar(), $_POST['givePoints']);
	$id_usuario = mysqli_real_escape_string(conectar(), $_POST['userId']);
	// Agregar puntos a usuario
	agregarPuntos($id_usuario, $_SESSION['h_logueado'], $puntos, 12);
	$datosUsuario = obtenerDatosUsuario($id_usuario);
	$datosHotel = obtenerDatosHotel($_SESSION['h_logueado']);
	// Mandar email al usuario
	include_once LANG.$_SESSION['userLang'].'/email/gestion-usuarios.php';
	include_once LIB.'plantillasMails/gestion-usuarios.php';
	mandarEmailMandrillPlantilla($datosUsuario['email'], $datosUsuario['nombre'], $asunto, $cuerpo, 'standard-template');
	header('Location: /'.$urlTree['gestion-usuarios'].'/?give=ok');
}

// Los parametros $_GET['filter'] y $_GET['us'] vienen de la pantalla 'referrals'
if (!empty($_GET['filter']) && !empty($_GET['us'])){
	$filter = mysqli_real_escape_string(conectar(), $_GET['filter']);
	$id_usuario = mysqli_real_escape_string(conectar(), $_GET['us']);

	$arrayUsuarios=obtenerUsuarios($_SESSION['h_logueado'], $id_usuario, $filter ,$order, $sort, $itemsPage, $pagina);
	
}else{
	$page = array_get($_GET, 'pag', 1);
	$itemsPage = array_get($_GET, 'per_page', 10);	
	
	$arrayUsuarios = obtenerUsuarios($_SESSION['h_logueado'],'','',$order, $sort, $itemsPage, $pagina);
}

/*echo '<pre>';
print_r($arrayUsuarios);
echo '</pre>';*/
?>