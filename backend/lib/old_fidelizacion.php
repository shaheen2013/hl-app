<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

function consultarNivelFidelizacion($id, $id_usuario, $tipo){
	if($tipo == 'hot'){
		$sql = "SELECT user_hotels.gasto_total, user_hotels.total_noches,
		hoteles.tipo_fidelizacion, 
		hoteles.fidelizacion_0, hoteles.fidelizacion_1, hoteles.fidelizacion_2,
		hoteles.fidelizacion_3
		FROM user_hotels
		INNER JOIN hoteles ON hoteles.id='".$id."'
		WHERE id_hotel='".$id."' AND  id_usuario='".$id_usuario."' ";
	}else if($tipo == 'cad'){
		$sql = "SELECT SUM(user_hotels.gasto_total) AS gasto_total, 
		SUM(user_hotels.total_noches) AS total_noches, 
		tipo_fidelizacion, fidelizacion_0, fidelizacion_1, fidelizacion_2, 
		fidelizacion_3
		FROM cadena_hotel
		INNER JOIN user_hotels ON user_hotels.id_hotel=cadena_hotel.id_hotel
		INNER JOIN cadena ON cadena.id='".$id."'
		LEFT JOIN cadena_fideliz ON cadena_fideliz.id_cadena=cadena.id
		WHERE cadena_hotel.id_cadena='".$id."' AND user_hotels.id_usuario='".$id_usuario."' ";
	}
	//echo $sql.'<br>';
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	
	if($row['tipo_fidelizacion']=='1'){//Por dinero gastado
		if($row['gasto_total']>$row['fidelizacion_3']){
			$arrayFidelizacion = 4;
		}else if($row['gasto_total']>$row['fidelizacion_2']){
			$arrayFidelizacion = 3;
		}else if($row['gasto_total']>$row['fidelizacion_1']){
			$arrayFidelizacion = 2;
		}else if($row['gasto_total']>$row['fidelizacion_0']){
			$arrayFidelizacion = 1;
		}else{
			$arrayFidelizacion = 0;
		}
	}else if($row['tipo_fidelizacion']=='2'){//Por noches
		if($row['total_noches']>$row['fidelizacion_3']){
			$arrayFidelizacion = 4;
		}else if($row['total_noches']>$row['fidelizacion_2']){
			$arrayFidelizacion = 3;
		}else if($row['total_noches']>$row['fidelizacion_1']){
			$arrayFidelizacion = 2;
		}else if($row['total_noches']>$row['fidelizacion_0']){
			$arrayFidelizacion = 1;
		}else{
			$arrayFidelizacion = 0;
		}
	}else{
		//El hotel/cadena no tiene fidelización
		$arrayFidelizacion='0';
	}
	return $arrayFidelizacion;
}

//$fidelizacion = consultarNivelFidelizacion(57, $_SESSION['u_logueado'], 'hot');
/*echo '<pre>';
print_r($fidelizacion);
echo '</pre>';*/
?>