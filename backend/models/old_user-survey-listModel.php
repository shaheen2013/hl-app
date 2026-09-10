<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

include_once LIB.'permisosUsuario.php';

include_once LIB.'sanitize.php';

function obtenerEncuestas($id_usuario, $order, $sort, $itemsPage, $pagina){
	$sql = "SELECT user_encuestas.id, user_encuestas.done, user_encuestas.rating,
	user_checkin.chkin_date, user_checkin.chkout_date, user_checkin.puntos,
	hoteles.hotelName, 
	hoteles.logo, hoteles.id AS id_hotel, COUNT(user_shares.id) AS nShares ";
	$sql2 = " FROM user_encuestas
	INNER JOIN user_checkin ON user_checkin.id=user_encuestas.id_checkout
	INNER JOIN hoteles ON hoteles.id=user_encuestas.id_hotel
	LEFT JOIN user_shares ON user_shares.id_encuesta=user_encuestas.id
	WHERE user_encuestas.id_usuario='".$id_usuario."' ";
	$sql3 = " GROUP BY user_encuestas.id ";
	$sql3 .= " ORDER BY ".$order." ".$sort;
	$inicio = $itemsPage*$pagina-$itemsPage;
	$sql3 .= " LIMIT ".$inicio.",".$itemsPage;
	//echo $sql.$sql2.$sql3;
	$rs = mysqli_query (conectar(), $sql.$sql2.$sql3);
	$i=0;
	$arrayEncuestas = array();
	while ($row = mysqli_fetch_assoc($rs)){
		foreach ($row as $key=>$valor){
			if($key=='chkin_date' || $key=='chkout_date'){
				$arrayEncuestas[$i][$key] = girarFecha($valor);
			}else if($key=='hotelName'){
				$arrayEncuestas[$i][$key] = $valor;
				$arrayEncuestas[$i]['nombre_san'] = string_sanitize($valor);
			}else{
				$arrayEncuestas[$i][$key] = $valor;
			}
		}
		$arrayEncuestas[$i]['urlGuid']=obtenerUrlGUIDHotel($row['id_hotel']);
		$i++;
	}
	liberar($rs);
	
	$sql0 = "SELECT COUNT(DISTINCT(user_encuestas.id)) as N ";
	paginacion2($sql0.$sql2, $pagina, $itemsPage);
	
	return ($arrayEncuestas);
}
?>