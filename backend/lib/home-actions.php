<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

// Librerias básicas
include_once '../app/debug.php';

//Incluir el config
require '../app/config.php';
require '../app/request.php';
include '../models/_indexModel.php';

// Desactivar Tour inicial de la Home del hotelero
if ($_POST['action'] == 'modalClose'){
	
	//Update tour
	$sql = 'UPDATE hoteles SET modal = 1 WHERE id="'.$_POST['userId'].'"';
	mysqli_query (conectar(), $sql);
	return $sql;
}

// Desactivar alert superior para  crear ofertas de la Home del hotelero
?>