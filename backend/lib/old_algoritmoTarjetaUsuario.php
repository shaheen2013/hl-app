<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

function generarNumTarjetaUsaurio(){
	$cadena = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
	$longitudCadena=strlen($cadena);
	$userID = '';
	$longitudUserID = 5; // Longitud del id de usuario
	$i=0;
	while ($i<=$longitudUserID-1){
		$pos=rand(0,$longitudCadena-1);
		$caracter = substr($cadena,$pos,1);
		// $userID .= $caracter;
		if (strlen($userID) >= 2){
			$caracterA = substr($userID,-1); // Caracter anterior
			$caracterAA = substr($userID,-2,1); // Caracter anterior al anterior
		}else{
			$caracterA='';
			$caracterAA='';
		}
	
		if (strlen($userID) < 2){
			$userID .= $caracter;
			$i++;
		}else if (($caracterA == $caracterAA) && ($caracter == $caracterA)){
			// Impedimos que se repitan 3 caracteres seguidos
		}else{
			$userID .= $caracter;
			$i++;
		}
	}

	// Comprobamos si existe el ID
	$sql = "SELECT id FROM users WHERE id_tarjeta = '".$userID."' ";
	$rs = mysqli_query (conectar(), $sql);
	$n_resultados=mysqli_num_rows($rs);
	liberar($rs);
	if ($n_resultados==0){
		return $userID; // El ID no existe en la BD
	}else{
		$userID = generarNumTarjetaUsaurio(); // ID repetido, lo volvemos a calcular
		return $userID;
	}
}
?>