<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

include_once LIB.'sanitize.php';

function obtenerUsuariosCheckin($id_hotel, $order, $sort, $itemsPage, $pagina, $busqueda=0){
	if (hotelDeCadena($id_hotel)){
		$id_cadena = hotelIdCadena($id_hotel);
	}else{
		$id_cadena = '0';
	}
	$arrayUsuarios = array();
	$sql = "SELECT user_checkin.id, user_checkin.chkin_date,
	users.nombre, users.id AS id_usuario, users.email, img, ";
	//Fx redeemedOffers
	$sql .= " redeemedOffers(users.id, '".$id_hotel."', '".$id_cadena."', user_checkin.chkin_date) 
	AS redeemedOffers ";
	$sql .= "FROM user_checkin 
	INNER JOIN users ON users.id=user_checkin.id_usuario
	WHERE chkout_date='0000-00-00' AND id_hotel='".$id_hotel."' ";
	$sql2 = ' ';
	if($busqueda!='0'){
		$sql2.= " AND (users.nombre LIKE '%".$busqueda."%' 
		OR users.id_tarjeta LIKE '%".$busqueda."%' 
		OR users.email LIKE '%".$busqueda."%')";
	}
	$sql2.= " ORDER BY ".$order." ". $sort;
	$inicio = $itemsPage*$pagina-$itemsPage;
	$sql2 .= " LIMIT ".$inicio.",".$itemsPage;
	//echo $sql.$sql2;
	$rs = mysqli_query (conectar(), $sql.$sql2);
	$i=0;
	while ($row = mysqli_fetch_assoc($rs)){
		foreach ($row as $key=>$valor){
			if($key=='chkin_date'){
				$arrayUsuarios[$i][$key] = girarFecha($valor);
			}else if($key=='nombre'){
				if ($valor==''){
					// Si el nombre del usuario esta vacio mostramos el email
					$arrayUsuarios[$i][$key]=$row['email'];
					$arrayUsuarios[$i]['nombre_san'] = string_sanitize($row['email']);
				}else{
					$arrayUsuarios[$i][$key] = $valor;
					$arrayUsuarios[$i]['nombre_san'] = string_sanitize($valor);
				}
			}else{
				$arrayUsuarios[$i][$key] = $valor;
			}
		}
		$arrayUsuarios[$i]['urlGuid'] = obtenerUrlGUIDUsario($row['id_usuario']);
		$i++;
	}
	liberar ($rs);
	return $arrayUsuarios;
}
?>