<?php
// Librería que contiene los métodos necesario para un usuario conectado (logged) 

// Badge de encuestas pendientes 
/*function obtenerEncuestasBar($id_usuario){
	$sql = "SELECT COUNT(id) AS encuestas 
	FROM user_encuestas 
	WHERE id_usuario='".$id_usuario."' AND done=0 AND user_encuestas.id_checkout!=0 ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	return $row['encuestas'];
}*/

// Badge de wishlist
/*function obtenerTotalWishlistBar($id_usuario){
	$sql = "SELECT COUNT(id) AS wishlist FROM user_wishlist 
	WHERE id_usuario='".$id_usuario."' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	return $row['wishlist'];
}*/

function obtenerTotalVouchersBar($id_usuario){
	$sql = "SELECT COUNT(id) AS vouchers FROM user_cupones 
	WHERE id_usuario='".$id_usuario."' AND canjeado=0";
	$row = lectura($sql);
	return $row['vouchers'];
}

/*function usuarioCheckinBar($id_usuario){
	$usuarioCheckinBar = array(	);
	$sql = "SELECT hoteles.id, hoteles.hotelName AS nombre, guid
	FROM user_checkin
	INNER JOIN hoteles ON hoteles.id=user_checkin.id_hotel
	LEFT JOIN hoteles_img_logo ON hoteles_img_logo.id_hotel=hoteles.id
	INNER JOIN hotel_guid ON hotel_guid.id_hotel=hoteles.id
	WHERE chkout_date='0000-00-00' AND id_usuario='".$id_usuario."' 
	ORDER BY chkin_date DESC LIMIT 1";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	if(!empty($row)){
		foreach ($row as $key=>$valor){
			if($key == 'nombre'){
				$usuarioCheckinBar[$key]=$valor;
				$usuarioCheckinBar[$key.'_san']=string_sanitize($valor);
			}else{
				$usuarioCheckinBar[$key]=$valor;
			}
		}
	}
	if(!empty($usuarioCheckinBar))
	{
		global $urlTree;
		$usuarioCheckinBar['urlGUIDHotel'] = $urlTree['hotel'].'/'.$usuarioCheckinBar['nombre_san'].'/'.$usuarioCheckinBar['guid'];
	}
	return $usuarioCheckinBar;	
}*/
?>