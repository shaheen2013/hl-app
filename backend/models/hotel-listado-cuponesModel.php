<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

include_once LIB.'sanitize.php';
include_once LIB.'fecha.php';

function obtenerListadoCupones($id_hotel, $order, $sort, $itemsPage=1, $pagina=1, $busqueda=0)
{
	$id_hotel = mysqli_real_escape_string(conectar(), $id_hotel);
	$order = mysqli_real_escape_string(conectar(), $order);
	$sort = mysqli_real_escape_string(conectar(), $sort);
	$itemsPage = mysqli_real_escape_string(conectar(), $itemsPage);
	$pagina = mysqli_real_escape_string(conectar(), $pagina);
	$busqueda = mysqli_real_escape_string(conectar(), $busqueda);

	$arrayCupones = array();
	$sql = "SELECT user_cupones.id, user_cupones.voucher, user_cupones.fecha, user_cupones.canjeado, 
	user_cupones.fecha_canj,
	users.id AS id_usuario, users.nombre, users.email,
	IFNULL(users.img, 0) AS img, 
	hotel_oferta.id AS id_oferta, hotel_oferta.puntos,
	 case when oferta_lang.nombre is null 
        then   oferta_en.nombre 
        else oferta_lang.nombre end AS nombre_oferta,
	CASE WHEN oferta_referral_token.transaction !='' THEN oferta_referral_token.transaction
	WHEN  used_promocode.be_transaction !='' THEN used_promocode.be_transaction
	ELSE ''	END AS transaction  ";
	//Para obtener el tipo de share que consiguió el cupón
	$sql .= " ,tipos_share.tipo_".$_SESSION['userLang']." AS share_type ";
	// Nombre canjeador (staff, staff_inactivo, hotel, cadena)
	//$sql .= ", obtenercanjeador(canjeado, user_cupones.id_canjeador, user_cupones.tipo_canjeador) AS redeemed_by ";
	$sql2 = "FROM user_cupones 
	INNER JOIN users ON user_cupones.id_usuario=users.id
	INNER JOIN hotel_oferta ON hotel_oferta.id=user_cupones.id_oferta
	";
	//Para obtener el tipo de share que consiguió el cupón
	$sql2 .= "
	LEFT JOIN oferta_referral_token ON oferta_referral_token.id_cupon=user_cupones.id
	LEFT JOIN used_promocode ON used_promocode.id_cupon=user_cupones.id	
	LEFT JOIN tipos_share ON tipos_share.id=oferta_referral_token.id_tipo_share 
		OR tipos_share.id=used_promocode.id_tipo_share
    LEFT JOIN hotel_oferta_lang as oferta_en   on hotel_oferta.id = oferta_en.id_oferta   and oferta_en.lang='en' 
    LEFT JOIN hotel_oferta_lang as oferta_lang on hotel_oferta.id = oferta_lang.id_oferta and oferta_lang.lang='". $_SESSION['userNavLang'] . "'";

	if( !empty($_SESSION['c_logueado']) )
	{
		$id_cadena = hotelIdCadena($id_hotel);
		$sql2 .= "WHERE (hotel_oferta.id_hotel='".$id_hotel."' OR hotel_oferta.id_cadena='".$id_cadena."') ";
	}else{
		//Es un hotel o un staff
		//Solo mostramos cupones conseguidos por un share en su hotel
		$sql2 .= "WHERE (oferta_referral_token.id_hotel='".$id_hotel."' OR 
		used_promocode.id_hotel='".$id_hotel."') ";
	}

	//Filtro: mostramos solo los de pre-stay y stay
	$sql2 .= " AND (oferta_referral_token.id_tipo_share=2 OR oferta_referral_token.id_tipo_share=3 OR 
	used_promocode.id_tipo_share=2 OR used_promocode.id_tipo_share=3) ";


	if($busqueda!='0')
	{
		$sql2 .=" AND (MATCH (user_cupones.voucher) AGAINST ('%".$busqueda."%') 
		OR MATCH (users.nombre) AGAINST ('%".$busqueda."%')) ";
	}
	$sql3 = "ORDER BY ".$order." ".$sort;
	$inicio = $itemsPage*$pagina-$itemsPage;
	$sql3 .= " LIMIT ".$inicio.",".$itemsPage;
	//$sql .= "WHERE fecha>='".$fechaHoy."' ORDER BY fecha DESC";
	//echo $sql.$sql2.$sql3;
	$rs = mysqli_query (conectar(), $sql.$sql2.$sql3) or die(mysqli_error());
	$i=0;
	while ($row = mysqli_fetch_assoc($rs)){
		foreach ($row as $key=>$valor){
			if($key=='fecha' || $key=='fecha_canj'){ // Quitamos la hora en la "fecha"
				$arrayCupones[$i][$key] = girarFecha(substr($valor, 0, 10));
			}else if ($key=='nombre' || $key=='nombre_oferta'){
				$arrayCupones[$i][$key] = $valor;
				$arrayCupones[$i][$key.'_san'] = string_sanitize($valor);
			}else{
				$arrayCupones[$i][$key] = $valor;
			}
			
		}
		$arrayCupones[$i]['urlGuid'] = obtenerUrlGUIDUsario($row['id_usuario']);
		$i++;
	}
	liberar($rs);

	$sql0 = "SELECT COUNT(DISTINCT(user_cupones.id)) as N ";
	paginacion2($sql0.$sql2, $pagina, $itemsPage);

	return ($arrayCupones);
}
?>