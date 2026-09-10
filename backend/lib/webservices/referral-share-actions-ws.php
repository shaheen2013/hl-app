<?php
include_once 'librerias.php';// Librerias básicas

// Restringir ips que pueden acceder
include_once RUTA_DIR.LIB.'check_access.php';
checkIpAccess('reshac', $_SERVER['REMOTE_ADDR']);

include_once RUTA_DIR . LIB . 'referral-share-actions.php';//guardarShareUsuario 
include_once RUTA_DIR . LIB . 'facebook.php';
include_once RUTA_DIR . LIB . 'cookies.php';
include_once RUTA_DIR . LIB . 'referrer.php';

//smUId: social media user id
//hId: hotel id
//shId: share id (el social media nos devuelve un id de share)
//idTSh: id tipo de share, pre-stay... (ver tabla tipos_de_share)
if(!empty($_POST['smUId']) && !empty($_POST['hId']) && !empty($_POST['shId']) && !empty($_POST['idTSh']) )
{
	$userIdSM 		= $_POST['smUId'];
	$hotelId 		= $_POST['hId'];
	$shId 			= $_POST['shId'];
	$idTSh 			= $_POST['idTSh'];
	empty($_POST['transaction']) ? $transaction = NULL : $transaction = $_POST['transaction'];

	//Obtenemos el id_usaurio a partir de su id de facebook
	$id_usuario = obtenerIdUsuarioIdFacebook($userIdSM);

	//guardarShareUsuario
	if($id_usuario!='0'){
		//Acciones
		$resultGoal = shareStayGoalActions($id_usuario, $hotelId, 'fb', $shId, $idTSh, $transaction);
	}else{
		$resultGoal = '';
	}

	$result = array(
		'rsG' => $resultGoal
	);

	//echo json_encode($result);
}else{
	echo 'Not direct exec allowed';
	exit();
}

?>
