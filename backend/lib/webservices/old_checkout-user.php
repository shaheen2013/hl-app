<?php
include 'librerias.php';// Librerias básicas
// Restringir ips que pueden acceder
include_once RUTA_DIR.LIB.'check_access.php';
checkIpAccess('ch-pos', $_SERVER['REMOTE_ADDR']);

include_once RUTA_DIR.LIB.'convertirDivisas.php';

//if (ips_acceso_webservice('hcdo' , $_SERVER['SERVER_ADDR'])){
	
	function calcularDolarPuntos($dolares){
		$sql = "SELECT equivalencia FROM dolar_puntos";
		$rs = mysqli_query (conectar(), $sql);
		$row = mysqli_fetch_assoc($rs);
		liberar($rs);
		$puntos = $dolares * $row['equivalencia'];
		return $puntos;
	}

	if (!empty($_POST['currency']) && !empty($_POST['amount'])){
		$currency = mysqli_real_escape_string(conectar(), $_POST['currency']);
		$amount = mysqli_real_escape_string(conectar(), $_POST['amount']);
		
		if ($currency == 'USD'){
			$puntos = calcularDolarPuntos($amount);
			$puntos = round($puntos); 
		}else{
			$usd = convertirDivisas($amount, $currency, 'USD');
			$usd = round($usd);
			$puntos = calcularDolarPuntos($usd);
		}
		echo $puntos;
	}
//}
?>