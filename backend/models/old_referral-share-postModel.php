<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

include_once RUTA_DIR.LIB.'fecha.php';

function verificarIPCheckout($ip, $segundos){
	$fechaHora = dateTimeHoy();
	
	//Mirar último checkout IP
	$sql = "SELECT date FROM last_checkout_ip WHERE ip='".$ip."' ORDER BY date DESC LIMIT 1";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	
	//Registrar último checkout IP
	$sql2 = "INSERT INTO last_checkout_ip (ip, date) VALUES ('".$ip."', '".$fechaHora."') 
	ON DUPLICATE KEY UPDATE date='".$fechaHora."' ";
	mysqli_query (conectar(), $sql2);
	
	$DTUltimoCheckoutIp = strtotime($row['date']);//Fecha última vez
	$DTfechaHora = strtotime($fechaHora);
	$segundosDiferencia = $DTfechaHora - $DTUltimoCheckoutIp;
	if($segundosDiferencia > $segundos){
		return true;
	}else{
		return false;
	}
}
?>