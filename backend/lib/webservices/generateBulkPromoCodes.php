<?php 
//webservice para generar promocodes válidos en dev
include_once 'librerias.php';// Librerias básicas
include_once RUTA_DIR.LIB.'referrer.php';

//Generate codes method

$num = $_GET['num'];

function generatePromoCodes($num){
	$promocodes = array();
	for ($i=0; $i < $num; $i++) {
		$promocode = asignarOfertaReferral(5,41,1);
		echo $promocode.'<br>';
	}
}

//generatePromoCodes($num);
?>