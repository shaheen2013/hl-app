<?php
	include 'librerias.php';// Librerias básicas
	
	// Restringir ips que pueden acceder
	include_once RUTA_DIR.LIB.'check_access.php';
	checkIpAccess('onboa', $_SERVER['REMOTE_ADDR']);
	
	//Funcion para actualizar los pasos
	function updateStep($step, $id){
		$sql = ("UPDATE onboarding SET $step = '1' WHERE id_hotel =".$id."");
		$query = mysqli_query (conectar(), $sql);
	}

	//Actualizar pasos
	if(!empty($_POST['step'])){
		$step = updateStep($_POST['step'], $_POST['id']);
	}
 ?>