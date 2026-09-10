<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

include_once LIB.'paramsUrl.php';

function obtenerUsuarios($busqueda, $order=0, $sort=0, $itemsPage=1, $pagina=1, $action='', $id_usuario=''){
	
	$busqueda = mysqli_real_escape_string(conectar(1), $busqueda);
	$action = mysqli_real_escape_string(conectar(1), $action);
	$id_usuario = mysqli_real_escape_string(conectar(1), $id_usuario);
	$arrayUsuarios = array();

	$id_hotel = array_get($_SESSION,'h_logueado', array_get($_SESSION,'staff_id_hotel'));

	$sql = "SELECT 
				SQL_CALC_FOUND_ROWS *,
				users.id,
				users.nombre as name, 
				users.email, 
				users.user_card as id_card, 
				CASE WHEN user_facebook.id IS NULL THEN 0 ELSE user_facebook.facebook_img END AS img,
				CASE WHEN user_facebook.id IS NULL THEN users.location ELSE user_facebook.locale END AS locale, 
				CASE WHEN user_facebook.id IS NULL THEN users.fecha_nacimiento ELSE user_facebook.birthday END AS birthday, 
				CASE WHEN user_facebook.id IS NULL THEN users.sexo ELSE user_facebook.gender END AS gender, 
				CASE WHEN user_facebook.id IS NULL THEN NULL ELSE user_facebook.locationName END AS location, 
				users.lang as language,
				user_facebook.amigos as fb_friends,
				users.created, 
				CASE WHEN connection_history.first_login IS NULL THEN users.created ELSE connection_history.first_login END AS first_checkin, 
				CASE WHEN connection_history.last_login IS NULL THEN users.created ELSE connection_history.last_login END AS last_checkin,
				connection_history.id_room,
				user_hotels.user_hotel_id as pms_id

				FROM user_hotels
				LEFT JOIN users ON users.id = user_hotels.id_usuario 
				LEFT JOIN user_facebook ON user_facebook.id_usuario = user_hotels.id_usuario
				LEFT JOIN connection_history ON connection_history.id_user = users.id AND connection_history.id_hotel= user_hotels.id_hotel
				WHERE user_hotels.id_hotel='$id_hotel'";

	if (!empty($busqueda)){
		$sql .= " AND ( users.nombre LIKE '%".$busqueda."%' OR users.email LIKE '%".$busqueda."%' )";
	}
	
	$sql .= "GROUP BY users.id, users.user_card";
	$sql2 = '';
	if(campoOrdValido($order, $sql)){//Miramos si $order es un campo válido para ordenar
		$sql2 = " ORDER BY ".$order." ". $sort;
	}
	$inicio = $itemsPage*$pagina-$itemsPage;
	$sql2 .= " LIMIT ".$inicio.",".$itemsPage;
	$con = conectar();

	$row = lecturaArray($sql.$sql2, $con, false);

	$total = lectura("SELECT FOUND_ROWS()", $con)['FOUND_ROWS()'];
	
	$i=0;
	foreach($row as $usuario){
		foreach ($usuario as $key=>$valor){
			// if ($key == 'email'){
			// 	$arrayEmail = explode('@', $usuario['email']);
			// 	$email = $arrayEmail[0];
			// 	$arrayUsuarios[$i][$key]=$email;
			// 	$arrayUsuarios[$i]['completeEmail']=$usuario['email'];
			// }else{
				$arrayUsuarios[$i][$key]=$valor;
			// }
		}
		$i++;
	}
	
	// $sql0 = "SELECT COUNT(tracking_cookies.id) AS N ";
	// paginacion2($sql0.$sql2, $pagina, $itemsPage);
	//paginacion3($sql.$sql2, $pagina, $itemsPage);
	
	// return $arrayUsuarios;
	return array(
		'total' => $total,
		'clients' => $arrayUsuarios
	);
}
?>