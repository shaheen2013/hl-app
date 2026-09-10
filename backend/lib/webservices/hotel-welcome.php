<?php
//WS
include 'librerias.php';// Librerias básicas

// Restringir ips que pueden acceder
include_once RUTA_DIR.LIB.'check_access.php';
checkIpAccess('sys', $_SERVER['REMOTE_ADDR']);

if(!empty($_POST['pass'])){
	$pass = mysqli_real_escape_string(conectar() , $_POST['pass']);
	if (!preg_match("/^.*(?=.{6,18})(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/", $pass))
	{	
		// Longitud de password incorrecto
		echo false;
	}else{
		// Longitud de password correcto (6-18)
		echo true;
	}
}
?>