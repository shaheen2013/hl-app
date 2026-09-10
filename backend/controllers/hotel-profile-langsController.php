<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//contenido solo disponible si logueado
include LIB.'logueado.php';
hotelStaffLanding ();// Si no esta logueado lo manda a la landing
// For front purposes
$currentSubPage = "hotel-profile-langs";

include_once LIB.'obtenerdatosHotel.php';
	
if(!empty($_POST['langSelection']))
{
	//Guardar idiomas (varios) hotel
	guardarLangsHotel($_SESSION['h_logueado'], $_POST);
	//Feedback
	$ok = array(true, '2007');
}

if(!empty($_POST['lang']))
{
	//Guardamos idioma (1) del hotel
	$_SESSION['userNavLang'] = $_SESSION['userLang'] =  guardarLangHotel($_SESSION['h_logueado'], $_POST['lang']);
	//Cambiamos el lang de SESSION y recargamos la página nuevamente con el lang nuevo
	header('Location: /'.$urlTree['hotel-profile-langs'].'/?chLang=ok');
}

if(!empty($_GET['chLang']) && $_GET['chLang']=='ok')
{
	//Pagina recargada después de cambio de lang + Feedback
	$ok = array(true, '2007');
}

//Obtenemos los idiomas del hotel
$langsHotel = obtenerIdLangsHotel($_SESSION['h_logueado']);
$langHotel = obtenerLangHotel($_SESSION['h_logueado']);

//Get lang list de contenido
$contentLangList = getLangList('content');

//Get lang list de contenido
$systemLangList = getLangList('system');
?>
