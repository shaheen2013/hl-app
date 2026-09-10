<?php 
//Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

include_once LIB.'fecha.php';
include_once LIB.'sanitize.php';
include_once LIB.'rmdir.php';

//estados de las ofertas:
// 0 borrador
// 1 publicada
// 2 pausada no editable (anteriormente publicada)
// 3 pausada editable (no tiene canjeadas pendientes)
// 4 Deactivated no editable (ofertas Ref.)
// 5 Activa  (ofertas Ref.) sale en la landing (prevalece la de cadena)
// 6 Deactivated editable (ofertas Ref.)

// $filtro: adq, ret
function obtenerOfertas($id_hotel, $order, $sort, $itemsPage, $pagina, $filtro)
{
    $con = conectar(1);
	$id_hotel = mysqli_real_escape_string($con, $id_hotel);
	$order = mysqli_real_escape_string($con, $order);
	$sort = mysqli_real_escape_string($con, $sort);
	$pagina = mysqli_real_escape_string($con, $pagina);
	$filtro = mysqli_real_escape_string($con, $filtro);

	$array = array();

	if($filtro == 'ref-chain' && !empty($_SESSION['c_logueado'])){
		$filtro = 'ref';
		$tipo = 'cadena';
		$id_logueado = $_SESSION['c_logueado'];
	}else{
		$tipo = 'hotel';
		$id_logueado = $id_hotel;
	}

	$sql = "SELECT DISTINCT 
				hotel_oferta.id,
				DATE(hotel_oferta.fecha_creacion) AS fecha_creacion,
				hotel_oferta.id,
				hotel_oferta.inicio,
				hotel_oferta.adquiridas,
				hotel_oferta.canjeadas,
				hotel_oferta.estado,
				hotel_oferta_lang.nombre,
				hotel_oferta_lang.lang,
				case when oferta_lang.nombre is null 
                then   oferta_en.nombre 
                else oferta_lang.nombre end AS nombre,
				categoria_oferta.categoria_".$_SESSION['userNavLang']." AS categoria,
				tipos_oferta.tipo_adq_ret_".$_SESSION['userNavLang']." AS tipo ";
	if ($filtro == 'adq' || $filtro == 'ret' ){
		$sql .= ", hotel_oferta.fin, hotel_oferta.cupo, hotel_oferta.puntos ";
	}else if($filtro == 'ref'){
		//status oferta Landing
		$sql .= ", (SELECT COUNT(id_oferta) 
		FROM ".$tipo."_oferta_referral
    	WHERE ".$tipo."_oferta_referral.id_".$tipo."=".$id_logueado." 
		AND id_oferta=hotel_oferta.id ) AS landing ";
	}
	$sql2 = " FROM hotel_oferta 
	LEFT JOIN hotel_oferta_lang ON hotel_oferta_lang.id_oferta = hotel_oferta.id
	LEFT JOIN hotel_oferta_lang as oferta_en   on hotel_oferta.id = oferta_en.id_oferta   and oferta_en.lang='en' 
    LEFT JOIN hotel_oferta_lang as oferta_lang on hotel_oferta.id = oferta_lang.id_oferta and oferta_lang.lang='".$_SESSION['userNavLang']."'
	LEFT JOIN categoria_oferta ON categoria_oferta.id_categoria_oferta=hotel_oferta.id_categoria 
	LEFT JOIN tipos_oferta ON tipos_oferta.id_tipo_oferta=hotel_oferta.id_tipo_oferta ";
	// if($filtro == 'ref'){
	// 	//Tabla de la oferta activada para la landing
	// 	$sql2 .= " LEFT JOIN ".$tipo."_oferta_referral
	// 	ON ".$tipo."_oferta_referral.id_".$tipo."=hotel_oferta.id_".$tipo;
	// }
	// $sql2 .= " WHERE adq_ret='".$filtro."' ";
	if(isset($_SESSION['c_logueado']) && $tipo=='cadena'){
		$sql2 .= " WHERE hotel_oferta.id_cadena='".$_SESSION['c_logueado']."' ";
	}else{
		$sql2 .= " WHERE hotel_oferta.id_hotel='$id_hotel'";
	}
	$sql3 = " ORDER BY ".$order." ". $sort;
	$inicio = $itemsPage*$pagina-$itemsPage;
	$sql3 .= " LIMIT ".$inicio.",".$itemsPage;
	$sql4 = " GROUP BY hotel_oferta.id";
	//echo $sql.$sql2.$sql3;

	$row = lecturaArray($sql.$sql2.$sql4.$sql3,$con);

	$i=0;
	foreach($row as $dato){
		foreach ($dato as $key=>$valor){
			if(!empty($key) && ($key == 'inicio' || $key == 'fin' || $key=='fecha_creacion') ){
				$array[$i][$key] = girarFecha($valor);
			}else if ($key=='nombre'){
				$array[$i][$key] = $valor;
				$array[$i]['nombre_san'] = string_sanitize($valor);
			}else{
				$array[$i][$key] = $valor;
				if($key=='cupo'){
					$cupo=$valor;
				}else if($key=='adquiridas'){
					$adquiridas=$valor;
				}
			}
		}
		$i++;
	}

	$sql0 = "SELECT COUNT(DISTINCT hotel_oferta.id) as N ";
	paginacion2($sql0.$sql2, $pagina, $itemsPage);

	return ($array);
}

function eliminarOferta($id_hotel, $id_oferta)
{
	$id_hotel = mysqli_real_escape_string(conectar(), $id_hotel);
	$id_oferta = mysqli_real_escape_string(conectar(), $id_oferta);

	//Miramos si la oferta es de hotel o de cadena
	$hotelCadena = mirarHotelCadena($id_oferta);
	if ($hotelCadena['tipo']=='cadena' && $hotelCadena['id']==$_SESSION['c_logueado']){
		$id = $_SESSION['c_logueado'];
	}else if($hotelCadena['tipo']=='hotel' && $hotelCadena['id']==$_SESSION['h_logueado']){
		$id = $_SESSION['h_logueado'];
	}else{
		$id=0;
	}

	$sql2 = "SELECT COUNT(id) AS n, adq_ret, adquiridas FROM hotel_oferta WHERE id='".$id_oferta."' 
	AND (estado='0' OR estado='3' OR estado='6') ";
	$sql2 .= " AND id_".$hotelCadena['tipo']."='".$id."'";
	$rs2 = mysqli_query (conectar(), $sql2);
	if(!empty($rs2) )
	{
		$row2 = mysqli_fetch_assoc($rs2);
		liberar($rs2);
		if($row2['n']=='1' && $row2['adquiridas']=='0'){

			if (file_exists(RUTA_DIR.DIR_IMG_OFERTAS.$id_oferta.'/')){
				rm_dir(RUTA_DIR.DIR_IMG_OFERTAS.$id_oferta.'/');
			}

			// Borramos oferta del hotel si esta en borrador (0) o pausada editable/borrable (3) o no asignada borrable(6)
			$sql = "DELETE hotel_oferta FROM hotel_oferta ";
			//Si borramos una oferta de referral debemos borrar sus goals, si tiene
			$sql .= "LEFT JOIN referral_goal ON referral_goal.id_oferta=".$id_oferta;
			$sql .= " WHERE hotel_oferta.id='".$id_oferta."' AND (estado='0' OR estado='3' OR estado='6');";
			$r = mysqli_query(conectar(), $sql);
			
			return $r;	
		}
	}
	//Borrar cache
	$arrayTags = array('oferta_' . $id_oferta, 'hotel_goals_'.$id_hotel);
	deleteCacheByTags($arrayTags);
}

function pausarOferta($id_hotel, $id_oferta)
{
	$id_hotel = mysqli_real_escape_string(conectar(), $id_hotel);
	$id_oferta = mysqli_real_escape_string(conectar(), $id_oferta);

	//Miramos si la oferta es de hotel o de cadena
	$hotelCadena = mirarHotelCadena($id_oferta);
	if ($hotelCadena['tipo']=='cadena' && $hotelCadena['id']==$_SESSION['c_logueado']){
		$id = $_SESSION['c_logueado'];
	}else if($hotelCadena['tipo']=='hotel' && $hotelCadena['id']==$_SESSION['h_logueado']){
		$id = $_SESSION['h_logueado'];
	}

	$sql = "SELECT canjeadas, adquiridas FROM hotel_oferta WHERE id='".$id_oferta."' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);

	$sql2 = "UPDATE hotel_oferta SET estado='";
	if ($row['adquiridas']==0 || $row['adquiridas']==$row['canjeadas']){
		$sql2 .='3'; // estado 3 pausada editable
	}else{
		$sql2 .='2'; // estado 2 pausada no editable
	}
	$sql2 .="' WHERE id='".$id_oferta."' AND estado='1' ";
	$sql2 .=" AND id_".$hotelCadena['tipo']."='".$id."'  ";
	//echo $sql2;
	mysqli_query (conectar(), $sql2);
	$n = mysqli_affected_rows(conectar());
	// --> MSG feedback
}

function reanudarOferta($id_hotel, $id_oferta)
{
	$id_hotel = mysqli_real_escape_string(conectar(), $id_hotel);
	$id_oferta = mysqli_real_escape_string(conectar(), $id_oferta);

	//Miramos si la oferta es de hotel o de cadena
	$hotelCadena = mirarHotelCadena($id_oferta);
	if ($hotelCadena['tipo']=='cadena' && $hotelCadena['id']==$_SESSION['c_logueado']){
		$id = $_SESSION['c_logueado'];
	}else if($hotelCadena['tipo']=='hotel' && $hotelCadena['id']==$_SESSION['h_logueado']){
		$id = $_SESSION['h_logueado'];
	}
	$sql = "UPDATE hotel_oferta SET estado='1' WHERE id='".$id_oferta."' 
	AND (estado='2' OR estado='3')"; //estado=2/3 -> pausada
	$sql .= " AND id_".$hotelCadena['tipo']."='".$id."' ";
	//echo $sql;
	mysqli_query (conectar(), $sql);
	// --> MSG feedback
}

function mirarHotelCadena($id_oferta)
{
	$id_oferta = mysqli_real_escape_string(conectar(), $id_oferta);

	//Mirar si la ofeta es de hotel o cadena
	$sql = "SELECT id_hotel, id_cadena, adq_ret, estado, adquiridas 
	FROM hotel_oferta WHERE id='".$id_oferta."'";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	$tipo = '';	$id = '';
	if(!empty($row['id_cadena']) && $row['id_cadena']==$_SESSION['c_logueado']){
		//Oferta de cadena que pertenece al c_logueado
		$tipo = 'cadena';
		$id = $_SESSION['c_logueado'];
	}else if(!empty($row['id_hotel']) && $row['id_hotel']==$_SESSION['h_logueado']){
		//Oferta de hotel que pertenece al h_logueado
		$tipo = 'hotel';
		$id = $_SESSION['h_logueado'];
	}
	$hotelCadena = array('id'=>$id, 'tipo'=>$tipo, 'adq_ret'=>$row['adq_ret'] ?? null, 'estado'=>$row['estado'] ?? null, 'adquiridas'=>$row['adquiridas'] ?? null);
	return $hotelCadena;
}

function obtenerOfertaReferalAnterior($id, $tipo)
{// de hotel o cadena
	$id = mysqli_real_escape_string(conectar(), $id);

	$sql = "SELECT id_oferta FROM ".$tipo."_oferta_referral WHERE id_".$tipo."=".$id;
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	return $row['id_oferta'] ?? null;
}

function activarOfertaReferral($id_oferta)
{
	$id_oferta = mysqli_real_escape_string(conectar(), $id_oferta);

	$hotelCadena = mirarHotelCadena($id_oferta);

	if($hotelCadena['adq_ret']=='ref' && $hotelCadena['tipo'] != '' && $hotelCadena['id'] != ''
	&& ($hotelCadena['estado'] == '4' || $hotelCadena['estado'] == '6' ) ){//Estado 1 -> publicable -> activable
		//Desactivar la oferta de referral anterior
		$id_oferta_anterior = obtenerOfertaReferalAnterior($hotelCadena['id'], $hotelCadena['tipo']);
		desactivarOfertaReferral($id_oferta_anterior);//Para cambiarle el status

		$sql = "INSERT INTO ".$hotelCadena['tipo']."_oferta_referral 
		(id_".$hotelCadena['tipo'].", id_oferta) 
		VALUES ('".$hotelCadena['id']."', '".$id_oferta."')
	 	ON DUPLICATE KEY UPDATE id_oferta=".$id_oferta;
		mysqli_query (conectar(), $sql);
		//Pasamos oferta a Asignada (5)
		$sql2 = "UPDATE hotel_oferta SET estado=5 WHERE id=".$id_oferta;
		mysqli_query (conectar(), $sql2);

		//Borrar cache
		deleteCacheByTag($hotelCadena['tipo'].'_oferta_landing_' . $hotelCadena['id'], 'hotel_oferta_wifi_' . $hotelCadena['id']);

		return 1;
	}else if($hotelCadena['estado'] == '5'){
		//Oferta ya activada
		return 2;
	}else{
		//Esta intentando activar una oferta que no es suya o no es de referral o esta en edición
		return 0;
	}
}

function desactivarOfertaReferral($id_oferta)
{
	$id_oferta = mysqli_real_escape_string(conectar(), $id_oferta ?? '');
	$hotelCadena = mirarHotelCadena($id_oferta);

	if($hotelCadena['adq_ret']=='ref' && $hotelCadena['tipo'] != '' && $hotelCadena['id'] != ''){
		$sql = "DELETE FROM ".$hotelCadena['tipo']."_oferta_referral 
		WHERE id_".$hotelCadena['tipo']." AND id_oferta='".$id_oferta."' ";
		
		mysqli_query (conectar(), $sql);

		if($hotelCadena['adquiridas']!='0' && $hotelCadena['estado']=='5'){
			//Si tiene adquiridas estado = 4
			$sql2 = "UPDATE hotel_oferta SET estado=4 WHERE id=".$id_oferta;
			mysqli_query (conectar(), $sql2);
		}else if($hotelCadena['adquiridas']=='0' && $hotelCadena['estado']=='5'){
			//Si NO tiene adquiridas estado = 6
			$sql2 = "UPDATE hotel_oferta SET estado=6 WHERE id=".$id_oferta;
			mysqli_query (conectar(), $sql2);
		}
		//Borrar cache
		deleteCacheByTag($hotelCadena['tipo'].'_oferta_landing_' . $hotelCadena['id']);

		return 1;
	}else{
		//Esta intentando desactivar una oferta que no es suya o no es de referral
		return 0;
	}
}
?>
