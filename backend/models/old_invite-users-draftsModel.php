<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

function obtenerListas($id_hotel, $order, $sort, $itemsPage, $pagina){
	
	$arrayListas = array ();
	
	$sql = "SELECT COUNT(hotel_list_users.id) AS n, hotel_list.id, hotel_list.nombre, fecha ";
	$sql2 = " FROM hotel_list 
	LEFT JOIN hotel_list_users ON hotel_list_users.id_list=hotel_list.id
	WHERE id_hotel='".$id_hotel."' 
	AND lista_acabada='1' ";
	$sql3 = " GROUP BY hotel_list.id ";
	$sql3 .= " ORDER BY ".$order." ". $sort;
	$inicio = $itemsPage*$pagina-$itemsPage;
	$sql3 .= " LIMIT ".$inicio.",".$itemsPage;
	//echo $sql.$sql2.$sql3;
	$rs = mysqli_query (conectar(), $sql.$sql2.$sql3);
	$i=0;
	while($row = mysqli_fetch_assoc($rs)){
		foreach ($row as $key=>$valor){
			if ($key == 'fecha'){
				$arrayListas[$i][$key] = girarFecha($valor);
			}else{
				$arrayListas[$i][$key] = $valor;
			}
		}
		$i++;
	}
	liberar ($rs);
	
	$sql0 = "SELECT COUNT(DISTINCT(hotel_list.id)) as N ";
	paginacion2($sql0.$sql2, $pagina, $itemsPage);
	
	return $arrayListas;
}

function borrarLista($id_list, $id_hotel){
	$sql = "SELECT id FROM hotel_list WHERE id='".$id_list."' 
	AND id_hotel='".$id_hotel."' ";
	$rs = mysqli_query (conectar(), $sql);
	$n_results = mysqli_num_rows($rs);
	liberar($rs);
	if ($n_results != 0 ){// si la lista es del hotel la borramos
		$sql2 = "DELETE FROM hotel_list WHERE id='".$id_list."' 
		AND id_hotel='".$id_hotel."' ";
		mysqli_query (conectar(), $sql2);
		$sql3 = "DELETE FROM hotel_list_users WHERE id_list='".$id_list."' ";
		mysqli_query (conectar(), $sql3);
		return true;
	}else{
		return false;
	}
}
?>