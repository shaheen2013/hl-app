<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

include_once LIB.'paramsUrl.php';

function obtenerUsuarios($id_hotel, $busqueda, $order=0, $sort=0, $itemsPage=1, $pagina=1, $action='', $id_usuario=''){

	$busqueda = mysqli_real_escape_string(conectar(1), $busqueda);
	$action = mysqli_real_escape_string(conectar(1), $action);
	$id_usuario = mysqli_real_escape_string(conectar(1), $id_usuario);

	$arrayUsuarios = array();
	$sql = "SELECT users.id, IFNULL(users.nombre, 'anonymous') AS name, users.email,  
	IFNULL(users.img, 0) AS img, tw_followers, fb_friends, amount AS total_spent, 
	DATE(created_at) AS created_at, DATE(redeemed_at) AS redeemed_at, transaction_num AS transaction, 
	destination_hotel_id, hoteles.hotelName,";
	//Invitador
	$sql .= "(SELECT nombre FROM users WHERE id=tracking_cookies.referrer_id) AS referrer, ";

	//Status
	$sql .= " CASE 
	WHEN tracking_cookies.transaction_num ='' AND tracking_cookies.amount=0.00 THEN 'Pending to book'
	ELSE 'Booked'
	END AS status ";

	$sql2 = " FROM tracking_cookies 
	LEFT JOIN users ON users.id=tracking_cookies.referral_id 
	LEFT JOIN hoteles ON hoteles.id=tracking_cookies.destination_hotel_id	
	WHERE tracking_cookies.hotel_id=".$id_hotel." ";
	if ($busqueda !=''){
		$sql2 .= " AND ( users.nombre LIKE '%".$busqueda."%' 
		OR users.email LIKE '%".$busqueda."%' )";
	}
	if ($action=='gst'){
		$sql2 .= " AND tracking_cookies.referrer_id='".$id_usuario."' 
		AND (tracking_cookies.transaction_num!='' OR tracking_cookies.amount !='')";
	}else if ($action=='rfr'){
		$sql2 .= " AND tracking_cookies.referrer_id='".$id_usuario."' 
		AND tracking_cookies.transaction_num='' AND tracking_cookies.amount=0.00";
	}else if ($action=='trfr'){
		// No se contabilizab los anonymous
		$sql2 .= " AND tracking_cookies.referrer_id=".$id_usuario." AND tracking_cookies.referral_id!='' ";
	}
	//$sql2 .= "GROUP BY users.id";
	$sql3 = '';
	if(campoOrdValido($order, $sql)){//Miramos si $order es un campo válido para ordenar
		$sql3 = " ORDER BY ".$order." ". $sort;
	}
	$inicio = $itemsPage*$pagina-$itemsPage;
	$sql3 .= " LIMIT ".$inicio.",".$itemsPage;
	$row = lecturaArray($sql.$sql2.$sql3, '', true, 1);
	$i=0;
	foreach($row as $usuario){
		foreach ($usuario as $key=>$valor){
			if ($key == 'email'){
				$arrayEmail = explode('@', $usuario['email']);
				$email = $arrayEmail[0];
				$arrayUsuarios[$i][$key]=$email;
				$arrayUsuarios[$i]['completeEmail']=$usuario['email'];
			}else if($key == 'total_spent'){
				$arrayUsuarios[$i][$key] = number_format($valor, 2, ',', '.');
			}else{
				$arrayUsuarios[$i][$key]=$valor;
			}
		}
		$i++;
	}

    $sql0 = "SELECT COUNT(tracking_cookies.id) AS N ";
    $arrayUsuarios['total'] = paginacion2($sql0.$sql2, $pagina, $itemsPage);
	//paginacion3($sql.$sql2, $pagina, $itemsPage);

	return $arrayUsuarios;
}
?>
