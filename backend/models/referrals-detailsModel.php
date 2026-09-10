<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

include_once LIB.'paramsUrl.php';

function obtenerDetalleUsuario($id_hotel, $order=0, $sort=0, $itemsPage=1, $pagina=1, $id_usuario){
	$arrayUsuarios = array();
	$sql = "SELECT DISTINCT ";
	/*$sql = " users.id, users.nombre AS name, users.email,  IFNULL(users.img, 0) AS img, 
	tw_followers, fb_friends ,  ";*/
	//Total spent
	$sql .= " user_cupones.id, 
	ROUND((IFNULL(
	CASE WHEN booking_value.id_checkout=0 THEN conversion_current_coin ELSE 0 END,0)+
	IFNULL(money,0)),2) AS total_spent, booking_value.id AS idBV, ";
	
	//$sql .= " ROUND(money, 2) AS total_spent_1, ";//Dinero gastado en checkout
	//$sql .= " ROUND(conversion_current_coin, 2) AS total_spent_2, ";//Dinero gastado bookin_val no contabilizado
	
	$sql .= "user_cupones.voucher AS promo_code, 
	hotel_oferta.id AS offer_id,
	 case when oferta_lang.nombre is null 
     then   oferta_en.nombre 
     else oferta_lang.nombre end AS offer_name ";
	
	$sql2 = " FROM referrer_users
	LEFT JOIN user_money_hotel ON user_money_hotel.id_hotel=".$id_hotel." 
		AND user_money_hotel.id_usuario=".$id_usuario."
	LEFT JOIN booking_value ON booking_value.id_hotel=".$id_hotel." 
		AND booking_value.id_usuario=".$id_usuario."
	LEFT JOIN user_cupones ON user_cupones.id=booking_value.id_cupon 
		AND user_cupones.id_usuario=".$id_usuario."
	LEFT JOIN hotel_oferta ON hotel_oferta.id=user_cupones.id_oferta
	LEFT JOIN hotel_oferta_lang as oferta_en   on hotel_oferta.id = oferta_en.id_oferta   and oferta_en.lang='en' 
    LEFT JOIN hotel_oferta_lang as oferta_lang on hotel_oferta.id = oferta_lang.id_oferta and oferta_lang.lang='". $_SESSION['userNavLang'] . "'";
	
	//Quitamos los booking values que han hecho ckeckout
	$sql2 .= "WHERE ";
	//$sql2 .= " AND users.id=".$id_usuario." ";
	//Detalles filtrados por id_usuario
	$sql2 .= " referrer_users.invitado='".$id_usuario."' ";
	$sql3 = '';
	if(campoOrdValido($order, $sql)){//Miramos si $order es un campo válido para ordenar
		$sql3 = " ORDER BY ".$order." ". $sort;
	}
	$inicio = $itemsPage*$pagina-$itemsPage;
	$sql3 .= " LIMIT ".$inicio.",".$itemsPage;
	//echo '<!--'.$sql.$sql2.$sql3.'-->';
	$rs = mysqli_query (conectar(), $sql.$sql2.$sql3);
	$i=0;
	while ($row = mysqli_fetch_assoc($rs)){
		if($row['id']){
			foreach ($row as $key=>$valor){
				if ($key == 'email'){
					$arrayEmail = explode('@', $row['email']);
					$email = $arrayEmail[0];
					$arrayUsuarios[$i][$key]=$email;
				}else if ($key == 'name' || $key == 'referrer' || $key == 'offer_name'){
					$arrayUsuarios[$i][$key] = $valor;
					$arrayUsuarios[$i][$key.'_san'] = string_sanitize($valor);
				}else if($key == 'total_spent'){
					$arrayUsuarios[$i][$key] = number_format($valor, 2, ',', '.');
				}else{
					$arrayUsuarios[$i][$key]=$valor;
				}
			}
		}
		$i++;
	}
	liberar($rs);
	
	$sql0 = "SELECT COUNT(user_cupones.id) AS N ";
	paginacion2($sql0.$sql2, $pagina, $itemsPage);
	//paginacion3($sql.$sql2, $pagina, $itemsPage);
	
	return $arrayUsuarios;
}

function obtenerDatosUsuarioReferral($id_usuario){
	$sql = "SELECT id, email, nombre, img FROM users WHERE id='".$id_usuario."' ";
	$rs = mysqli_query (conectar(), $sql);
	while ($row = mysqli_fetch_assoc($rs)){
		foreach ($row as $key=>$valor){
			if ($key == 'nombre'){
				$arrayUsuarios[$key]=$valor;
				$arrayUsuarios[$key.'_san']=string_sanitize($valor);
			}else{
				$arrayUsuarios[$key]=$valor;
			}
		}
	}
	$arrayUsuarios['urlGuid'] = obtenerUrlGUIDUsario($id_usuario);
	liberar($rs);
	return $arrayUsuarios;
}
?>