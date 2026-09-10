<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

include_once LIB.'fecha.php';

function registrarVisita($ip, $tipo, $id, $http_referer){
	$sql = "SELECT id 
	FROM ".$tipo."_page_ips
	WHERE id_".$tipo."='".$id."' AND ip='".$ip."' ";
	//echo $sql;
	$rs = mysqli_query (conectar(), $sql);
	$n_results = mysqli_num_rows($rs);
	liberar($rs);
	if ($n_results ==0){// guardar ip visita 
		$fecha = dateTimeHoy();
		$sql2 = "INSERT INTO ".$tipo."_page_ips 
		(ip, id_".$tipo.", fecha, http_referer) VALUES 
		('".$ip."', '".$id."', '".$fecha."', '".$http_referer."')";
		//echo $sql2;
		mysqli_query (conectar(), $sql2);
	}
}
?>