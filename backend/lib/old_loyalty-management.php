<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//Funciones comunes de loyalty-management del Model y el Webservice

//Devuelve los rango de fidelización que tiene el hotel (de 0 a 200 -> nivel 1...)
function obtenerFidelizacionHotel($id, $tipoLogueado){
	$row = '';
	$sql = "SELECT tipo_fidelizacion, 
	fidelizacion_0 AS fid0, fidelizacion_1 AS fid1, fidelizacion_2 AS fid2, fidelizacion_3 AS fid3, 
	n_fideliz, activado FROM ";
	if($tipoLogueado=='c'){
		$sql .= " cadena_fideliz WHERE id_cadena";
	}else if($tipoLogueado=='h'){
		$sql .= " hotel_fideliz WHERE id_hotel";
	}
	$sql .= "=".$id;
	//echo $sql;
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	return $row;
}

function obtenerNFidelizacionHotel($id, $tipoLogueado){
	$row = '';
	$sql = "SELECT n_fideliz
	FROM ";
	if($tipoLogueado=='c'){
		$sql .= " cadena_fideliz WHERE id_cadena";
	}else if($tipoLogueado=='h'){
		$sql .= " hotel_fideliz WHERE id_hotel";
	}
	$sql .= "=".$id;
	//echo $sql;
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	return $row['n_fideliz'];
}

//Guarda el nivel de fidelización del hotel o cadena
function guardarUpToNivel ($id, $tipoLogueado, $upTo, $level){
	$sql = "INSERT INTO ";
	if($tipoLogueado=='c'){
		$sql .= " cadena_fideliz (id_cadena, ";
	}else if($tipoLogueado=='h'){
		$sql .= " hotel_fideliz (id_hotel,  ";
	}
	$sql .= " fidelizacion_".$level.") VALUES ('".$id."', '".$upTo."')";
	$sql .= " ON DUPLICATE KEY UPDATE ";
	$sql .= " fidelizacion_".$level."='".$upTo."' ";
	//echo $sql;
	mysqli_query (conectar(), $sql);
}

//Borra todos los beneficios del hotel o cadena de un level
function borrarBeneficiosHotel($id, $tipoLogueado, $level){
	$sql = "DELETE FROM ";
	if($tipoLogueado=='c'){
		$sql .= " cadena_benef_fideliz WHERE id_cadena";
	}else if($tipoLogueado=='h'){
		$sql .= " hotel_benef_fideliz WHERE id_hotel";
	}
	$sql .= "='".$id."' AND level=".$level;
	//echo $sql;
	mysqli_query (conectar(), $sql);
}

//Devuelve los beneficios que tiene el hotel/cadena guardados anteriormente
function obtenerBeneficiosHotel($id, $tipoLogueado, $level){
	$arrayBeneficios = obtenerTiposBeneficiosFidelizacion();
	//Idioma por el que esta ordenado
	if(isset($_SESSION['userLang'])){
		$idioma = $_SESSION['userLang'];
	}else{
		$idioma = 'en';
	}
	//Array con todos los beneficios de BD
	foreach ($arrayBeneficios as $key=>$valor){
		$array[$key]=0;
	}
	$sql = "SELECT benef_fideliz.id 
	FROM benef_fideliz ";
	if($tipoLogueado=='c'){
		$sql .= "LEFT JOIN cadena_benef_fideliz ON cadena_benef_fideliz.id_benef=benef_fideliz.id
		WHERE cadena_benef_fideliz.id_cadena=".$id;
	}else if($tipoLogueado=='h'){
		$sql .= "LEFT JOIN hotel_benef_fideliz ON hotel_benef_fideliz.id_benef=benef_fideliz.id
		WHERE hotel_benef_fideliz.id_hotel=".$id;
	}
	$sql .= " AND level=".$level." ORDER BY beneficio_".$idioma." ASC";
	//echo $sql;
	$rs = mysqli_query (conectar(), $sql);
	while ($row = mysqli_fetch_assoc($rs)){
		$array[$row['id']]=1;//Marcamos con 1 los beneficios que tiene
	}
	liberar ($rs);
	return $array;//Devuelve array de beneficios con 1 y 0 dependiendo de lo que el hotel tiene seleccionado
}

//Devuelve los tipos de fidelización (beneficio 1, beneficio 2...)
function obtenerTiposBeneficiosFidelizacion(){
	$array = '';
	$sql = "SELECT id, beneficio_".$_SESSION['userLang']." AS beneficio 
	FROM benef_fideliz ORDER BY beneficio ASC";
	$rs = mysqli_query (conectar(), $sql);
	while ($row = mysqli_fetch_assoc($rs)){
		$array[$row['id']]=$row['beneficio'];
	}
	liberar ($rs);
	return $array;
}

//Funcion para activar/desactivar loyalty
function changeFidelizStatus($id, $tipoLogueado, $estado){
	if($tipoLogueado=='c'){
		$tipo = "cadena";
	}else if($tipoLogueado=='h'){
		$tipo = "hotel";
	}
	$sql = "UPDATE ".$tipo."_fideliz SET activado=".$estado." 
	WHERE id_".$tipo."='".$id."' ";
	//echo $sql;
	mysqli_query (conectar(), $sql);
}

// Verifica si se puede activar el Loyalty
function mirarSiProcesoOk($id, $tipoLogueado){
	$loyalty = false;
	$fidelizHotel = obtenerFidelizacionHotel($id, $tipoLogueado);
	if($fidelizHotel['n_fideliz']==0){
		$loyalty = true;
	}else{
		$niveles = $fidelizHotel['n_fideliz']-1;
		for ($i=0; $i <= $niveles; $i++) {
			if($fidelizHotel['fid'.$i]!=0){
				//echo '<br>'.$i.': '.$fidelizHotel['fid'.$i],'=0?';
				$t=$i+1;
				if($i==$niveles || $fidelizHotel['fid'.$i]<$fidelizHotel['fid'.$t]){//Si no es el último
					//echo ' Ok';
					$loyalty = true;
				}else{
					//echo ' Posterior mas bajo que el actual';
					$loyalty = false;
					break;
				}
			}else{
				//echo '<br>hay cero';
				$loyalty = false;
				break;
			}
		}	
	}
	return $loyalty;
}

//Obtenemos el rango de fidelización anterior
function obtenerFidelizAntPost($id, $tipoLogueado, $level, $nivelesFideliz){
	$arrayAntPost = array ('ant' => '0', 'post' => '0');
	if($level !=0){
		$levelAnterior = $level-1;
		$sql = "SELECT fidelizacion_".$levelAnterior." AS fidAnt
		FROM ";
		if($tipoLogueado=='c'){
			$sql .= " cadena_fideliz WHERE id_cadena";
		}else if($tipoLogueado=='h'){
			$sql .= " hotel_fideliz WHERE id_hotel";
		}
		$sql .= "=".$id;
		//echo '<br>'.$sql;
		$rs = mysqli_query (conectar(), $sql);
		$row = mysqli_fetch_assoc($rs);
		$arrayAntPost['ant'] = $row['fidAnt'];
	}
	
	if($level != $nivelesFideliz-1){
		$levelPosterior = $level+1;
		$sql = "SELECT fidelizacion_".$levelPosterior." AS fidPost
		FROM ";
		if($tipoLogueado=='c'){
			$sql .= " cadena_fideliz WHERE id_cadena";
		}else if($tipoLogueado=='h'){
			$sql .= " hotel_fideliz WHERE id_hotel";
		}
		$sql .= "=".$id;
		//echo '<br>'.$sql;
		$rs = mysqli_query (conectar(), $sql);
		$row = mysqli_fetch_assoc($rs);
		$arrayAntPost['post'] = $row['fidPost'];
	}
	return $arrayAntPost;
}
?>