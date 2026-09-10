<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//contenido solo disponible si logueado
include LIB.'logueado.php';
hotelStaffLanding ();// Si no esta logueado lo manda a la landing
include LIB.'isIndependent.php';

include_once LIB.'obtenerdatosHotel.php';

// For front purposes
$currentPage = 'chain-management';
$currentSubPage = 'archived-hotels';

if(!empty($_GET['msg'])){
	if($_GET['msg']=='2017'){
		$ok = array (true, $_GET['msg']);
	} else if ($_GET['msg']=='4065'){
		$ok = array (false, $_GET['msg']);
	}
}

if(!empty($_SESSION['c_logueado'])){
	$arrayHoteles = getArchivedHotels($_SESSION['c_logueado']);
}

if(!empty($_GET['change']) && $_GET['change']!=$_SESSION['h_logueado']){
	$id_hotel = $_GET['change'];

	changeHotel($id_hotel, $id_hotel, 1,$urlTree);
}

if(!empty($_GET['activate'])){
	$id_hotel = $_GET['activate'];

	changeHotel($id_hotel, $_SESSION['h_logueado'], 0, $urlTree);
}

function changeHotel($id_hotel, $id_hotel_to_change, $loggin_as_hotel,$urlTree){
	$id_hotel = $id_hotel;
	
	// Borramos datos de session
	include_once LIB.'borrarSession.php';
	borrarSessionProfileLanding($id_hotel);
	borrarSessionCrearOferta($id_hotel);

	if (chainChangeHotel($id_hotel_to_change, $loggin_as_hotel)){
		header('Location: /'.$urlTree['archived-hotels'].'/');

	}else{
		$ok = array(false, '4002');
	}
}

?>