<?php

//WS obsoleto, ya no buscamos ciudades en la BD, lo hacemos con maps.googleapis

/*include 'librerias.php';// Librerias básicas
// Restringir ips que pueden acceder
include_once '../ips_acceso.php';

if (ips_acceso_webservice('hpro' , $_SERVER['SERVER_ADDR'])){*/
	/*function obtenerCiudad($pais){
		$sql = "SELECT id, nombre_es AS nombre FROM ciudad WHERE codigo_pais='".$pais."' 
		ORDER BY nombre ASC";
		$rs = mysqli_query (conectar(), $sql);
		$option = '<option>Selecciona una opción</option>';
		$i=0;
		while ($row = mysqli_fetch_assoc($rs)){
			$t=0;
			foreach ($row as $value){
				if ($t==0){
					$option.= '<option value="'.$value.'"';
					if(!empty($_SESSION['hotelCity']) && $_SESSION['hotelCity']== $value){
						$option .=' selected ';
					}
					$option.= '>';
				}else if($t==1){
					$option.= htmlentities($value, ENT_QUOTES, "ISO-8859-1").'</option>';
				}
				$t=1;
			}
			$i++;
		}
		liberar ($rs);
		echo ($option);
	}
	
	if (!empty($_POST['hotelCountry'])){
		$pais = mysqli_real_escape_string(conectar(), $_POST['hotelCountry']);
		obtenerCiudad($pais);
	}
	if (!empty($_POST['hotelCity'])){
		$_SESSION['hotelCity']=mysqli_real_escape_string(conectar(),$_POST['hotelCountry']);
	}*/
/*}
?>*/