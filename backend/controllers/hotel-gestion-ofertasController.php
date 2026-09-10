<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//Contenido solo visible si logueado
include LIB.'logueado.php';
hotelStaffLanding ();// Si no esta logueado lo manda a la landing

// For front purposes
$currentPage = 'offer-management';
$currentSubPage = 'offer-management';

include_once LIB.'paginacion2.php';
include_once LIB.'ordenacion.php';
include_once LIB.'borrarSession.php';

include_once APP . 'Services/Connections/ApiGatewayConnection.php';

$gateway = new ApiGatewayConnection();

//Parametros $_GET-----------------------------
// dir3(tipo) : adq, ret, ref (adquisicion, retencion, referral)
// ord : campos de la bd
// id : id de la oferta
// est(estado) : pub (publicar), del (eliminar), pau (pause), ply (reanudar)
// act(activar)+id_oferta (solo las de referral)
// desact(desactivar)+id_oferta (solo las de referral)
//---------------------------------------------

borrarSessionCrearOferta($_SESSION['h_logueado']);

// Campo ORDER BY
$pant='hot-ges-of'; // Pantalla
if(!empty($_GET['ord'])){
	$order = $_GET['ord'];
	$_SESSION['ord'.$pant] = $order;
	$sort = toggle();
}else if(!empty($_SESSION['ord'.$pant])){
	$order = $_SESSION['ord'.$pant];
	$sort = $_SESSION['ascdesc'];
}else{
	// Orden por defecto
	$order = 'fecha_creacion';
	$sort = 'DESC';
}

// Cambiar estado de la oferta
if(!empty($_GET['est']) && (!empty($_GET['id']))){
	$id_oferta = $_GET['id'];
    if ($_GET['est'] == 'del'){
		try {
			$brand_id = $_SESSION['hotel']['brand_id'];
			$gateway->sendRequest([], HOTELINKING_ENDPOINT . 'brand/' . $brand_id . '/reward-offer/'. $id_oferta, 'DELETE');
			$ok = array (true, '4097'); //OK
		} catch (\Exception $e) { 
			$ok = array(false, '4096'); //ERROR
		}
	}else if ($_GET['est'] == 'pau'){
		pausarOferta($_SESSION['h_logueado'], $id_oferta);
	}else if ($_GET['est'] == 'ply'){
		reanudarOferta($_SESSION['h_logueado'], $id_oferta);
	}
}


//TODO: Mover a API 

//Activar oferta referral para que se mueste en la landing
if(!empty($_GET['act'])){
	$id = mysqli_real_escape_string(conectar(), $_GET['act']);
	$error = activarOfertaReferral($id);
	if ($error == 1){
		$ok = array (true, '2027');//OK
	}else if($error == 2){
		//Oferta ya activada
	}else{
		//Oferta no activable (draft) o que no es suya
		//$ok = array (false, '4042');
	}
}
//Desactivar oferta referral para que se mueste en la landing
if(!empty($_GET['desact'])){
	$id = mysqli_real_escape_string(conectar(), $_GET['desact']);
	$error = desactivarOfertaReferral($id);
	if ($error == 1){
		$ok = array (true, '2028');//OK
	}else{
		$ok = array (false, '4042');
	}
}

$filtro = mysqli_real_escape_string(conectar() , $url['dir2']);//adq, ret, ref

$tipo='';
$itemsPage = 10;
// Mostramos las ofertas segun el tipo (Retencion / Adquisicion / Referrer)
if(!empty($filtro)) { 
	$arrayOfertas = obtenerOfertas($_SESSION['h_logueado'], $order, $sort, $itemsPage, $pagina, $filtro);
	if($filtro=='ret'){
		$tipo = 1; //'For loyal guests';
	}else if($filtro=='adq'){
		$tipo = 2; //'For new guests';
	}else if($filtro=='ref' || $filtro=='ref-chain'){
		$tipo = 3; //'For referrer guests';
	}
}else{
	$arrayOfertas = obtenerOfertas($_SESSION['h_logueado'], $order, $sort, $itemsPage, $pagina, 'adq');
	$tipo = 2; //'For new guests';
}

$arrayOfertas = array_map(function ($element) {
	$element['fecha_creacion'] = get_gmdate_from_hotel(array_get($element, 'fecha_creacion', '0000-00-00 00:00:00'), $_SESSION['h_logueado'], 'd-m-Y');

	return $element;
}, $arrayOfertas);

?>