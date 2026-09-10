<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

function guardarNotificaciones($id_hotel, $notif, $friends, $diasEnvioReview)
{
	$con 				= conectar();
	$friends 			= mysqli_real_escape_string($con, $friends);
	$notificaciones 	= mysqli_real_escape_string($con, $notif);
	$diasEnvioReview 	= mysqli_real_escape_string($con, $diasEnvioReview);

	$sql = "UPDATE hoteles SET notif='".$notif."', alert_friends='".$friends."' , diasEnvioReview='".$diasEnvioReview."'
	WHERE id='".$id_hotel."' ";
	escritura($sql, $con);
}

function obenerNotificaciones($id_hotel)
{
	$sql = "SELECT notif, alert_friends, diasEnvioReview FROM hoteles WHERE id='".$id_hotel."' ";
	$arrayNotif = lectura($sql);
	return $arrayNotif;
}
?>