<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//contenido solo disponible si logueado
include LIB.'logueado.php';
hotelStaffLanding ();// Si no esta logueado lo manda a la landing

if (!empty($_POST['hotelConfirmButton'])){
	//Notificaciones
	isset($_POST['notificaciones'])? $notificaciones = $_POST['notificaciones'] : $notificaciones = 0;
	$friends = $_POST['friends'];
	$diasEnvioReview = $_POST['diasEnvioReview'];

	guardarNotificaciones($_SESSION['h_logueado'], $notificaciones, $friends, $diasEnvioReview);
	//Feedback
	$ok = array (true, '2007');
}	

$notificaciones = obenerNotificaciones($_SESSION['h_logueado']);

/*echo '<pre>';
print_r($fidelicacionHotel);
echo '</pre>';*/
?>