<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

function obtenerRatingHotel($id_hotel){
	$id_hotel= mysqli_real_escape_string(conectar(), $id_hotel);
	$sql = "SELECT IFNULL (rating,0) AS rating FROM hoteles WHERE id='".$id_hotel."' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	return (round($row['rating'],1));
}

function obtenerEquivalenciaEstrellas($id_hotel){
	$id_hotel= mysqli_real_escape_string(conectar(), $id_hotel);
	$sql = "SELECT estrellas FROM hoteles WHERE id='".$id_hotel."' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	$sql2 = "SELECT equiv FROM equiv_estrellas WHERE estrellas='".$row['estrellas']."' ";
	$rs2 = mysqli_query (conectar(), $sql2);
	$row2 = mysqli_fetch_assoc($rs2);
	liberar($rs2);
	return $row2['equiv'];
}

function obtenerPrecioMax($id_hotel){
	$id_hotel= mysqli_real_escape_string(conectar(), $id_hotel);
	$sql = "SELECT max_rango FROM hoteles WHERE id='".$id_hotel."' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	return $row['max_rango'];
}

function costeTemporada($id_hotel, $inicio, $fin){
	$id_hotel= mysqli_real_escape_string(conectar(), $id_hotel);
	$inicio= mysqli_real_escape_string(conectar(), $inicio);
	$fin= mysqli_real_escape_string(conectar(), $fin);
	//si hay meses con valor 0 (hotel cerrado) no los contamos para el coste de temporada
	$sql = "SELECT jan AS mes1, feb AS mes2 , mar AS mes3, apr AS mes4, may AS mes5, 
	jun AS mes6, jul AS mes7, ago AS mes8, sep AS mes9, oct AS mes10, nov AS mes11, 
	dece AS mes12
	FROM hoteles WHERE id='".$id_hotel."' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	$total_meses = 0;
	if ($fin == '00-00-0000'){
		// Calcular media de un año
		$meses = 0; 
		foreach ($row as $key => $value){
			if($value!=0){
				$total_meses += $value;
				$meses++;
			}
		}
		$temporada = round ($total_meses / $meses, 1);
	}else if ($inicio != 0){
		// Calcular dias / meses
		$explodeInicio = explode ('-', $inicio);
		$explodeFin = explode ('-', $fin);
		
		$anoInicio = $explodeInicio[0];
		$anoFin = $explodeFin[0];
		$mesInicio = $explodeInicio[1];
		if ($mesInicio[0]==0){
			$mesInicio = $mesInicio[1];
		}
		$mesFin = $explodeFin[1];
		if ($mesFin[0]==0){
			$mesFin = $mesFin[1];
		}
		$diaInicio = $explodeInicio[2];
		if ($diaInicio[0]==0){
			$diaInicio = $diaInicio[1];
		}
		$diaFin = $explodeFin[2];
		if ($diaFin[0]==0){
			$diaFin = $diaFin[1];
		}
		$totalDias = 0;
		$valorDias = 0;
		while ($anoInicio <= $anoFin){
			if ($anoInicio == $anoFin){ // Mismo año
				while ($mesInicio <= $mesFin){
					if($row['mes'.$mesInicio]!=0){//mes=0->hotel cerrado. Nos saltamos ese mes
						if ($mesInicio == $mesFin){ // Mismo mes
							while ($diaInicio <= $diaFin){
								//echo '-----mm-------'.$diaInicio.' - '.$row['mes'.$mesInicio];
								$valorDias += $row['mes'.$mesInicio];
								$totalDias++;
								$diaInicio++;
							}
						}else{ // Mes distinto
							while ($diaInicio <= 30){
								//echo '------om------'.$diaInicio.' - '.$row['mes'.$mesInicio];
								$valorDias += $row['mes'.$mesInicio];
								$totalDias++;
								$diaInicio++;
							}
							$diaInicio = 1;
						}
					}
					$mesInicio++;
				}
				$mesInicio=1;
			}else{ // Año distinto
				while ($mesInicio <= 12){
					//echo '------oa------'.$diaInicio.' - '.$row['mes'.$mesInicio].'<br/>';
					if($row['mes'.$mesInicio]!=0){//mes=0->hotel cerrado. Nos saltamos ese mes
						if ($mesInicio == $mesFin){
							while ($diaInicio <= $diaFin){
								$valorDias += $row['mes'.$mesInicio];
								$totalDias++;
								$diaInicio++;
							}
						}else{
							while ($diaInicio <= 30){
								$valorDias += $row['mes'.$mesInicio];
								$totalDias++;
								$diaInicio++;
							}
							$diaInicio = 1;
						}
					}
					$mesInicio++;
				}
				//Reseteamos el mes
				$mesInicio = '1';
			}
			$anoInicio++;
		}
		//echo '::::'.$valorDias .'/'. $totalDias;
		if ($totalDias==0){
			$temporada = 0;
		}else{
			$temporada = round ($valorDias / $totalDias, 1);
		}
	}else{
		$temporada = 0;
	}
	//Si la temporada vale 0 significa que el hotel esta siempre cerrado 
	/*if($temporada == 0){
		$temporada = 10;
	}*/
	return $temporada;
}

// Para ofertas de adquisicion
function calcularPuntosAdq($id_hotel, $noches, $inicio, $fin, $offertype, $descuento){
	//echo '--'.$noches.' '.$offertype.' '.$descuento;
	//Tipos de oferta -> Constante C
	if ($offertype=='ngr'){ // Noche gratis
		$constante=1000;
	}else if ($offertype=='upg'){ // Upgrade
		$constante=500;
	}else if ($offertype=='des'){ // Descuento
		if ($descuento==0){
			$constante=1;
		}else{
			$constante=10*$descuento;
		}
	}else{
		$constante=500;
	}
	
	$valorEstrellas = obtenerEquivalenciaEstrellas($id_hotel); // X
	$ratingHotel = obtenerRatingHotel($id_hotel); // Y
	$precioMax = obtenerPrecioMax($id_hotel); // Z
	$penalizacion = $noches * $constante * 0.1; // W 10% cada noche que requiere pasar 
	if ($penalizacion == $constante){
		// Para evitar que ($constante - $penalizacion) = 0.
		$constpen = 1;
	}else if ($constante < $penalizacion){
		$constpen = 1;
	}else{
		$constpen = $constante - $penalizacion;
	}
	$temporada = costeTemporada($id_hotel, $inicio, $fin); 
	//$temporada = 0; // T (alta=15, media=10, baja=5, hotel cerrado=0)
	$suavizar = 10;
	
	$puntos = round ((($valorEstrellas + $ratingHotel + ($precioMax/100) + $temporada) * ($constpen)) / $suavizar, 0);
	//echo ' (('.$valorEstrellas.' + '.$ratingHotel.' + ('.$precioMax.'/100) +'.$temporada. ') *('.$constante.' - '.$penalizacion.'))/ '.$suavizar.' = ';
	//echo $puntos;
	return $puntos;
}

// Para ofertas de retencion
/*function calcularPuntosRet($dolares){
	$sql = "SELECT equivalencia FROM dolar_puntos";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	$puntos = $dolares * $row['equivalencia'];
	return $puntos;
}*/
?>