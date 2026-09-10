<?php
include 'librerias.php';// Librerias básicas

// Restringir ips que pueden acceder
include_once RUTA_DIR.LIB.'check_access.php';
checkIpAccess('hoprosm', $_SERVER['REMOTE_ADDR']);

include_once RUTA_DIR.MODEL.'idiomasModel.php';
include_once RUTA_DIR.LIB.'webservices/msgFeedback.php';
include_once RUTA_DIR.LIB.'obtenerdatosHotel.php';

if ( isset($_POST['changeLang']) )//-- Cambio de lang
{
	$result = obtenerSMTextShareIdioma($_SESSION['h_logueado'], $_POST['changeLang']);
	$langSelected = obtenerLang($_POST['changeLang']);
	$result['lang'] = '<img src="'.BASE_PATH . DIR_IMG . 'flags/' . $langSelected['img'] .'" alt="'.$langSelected['lang'].' flag" class="pl flag-icon"> <strong>'.$langSelected['country'].'</strong>'; 
	echo json_encode($result);
}

if(!empty($_POST['checkShareTexts']))
{
	$shareTextsHotel = obtenerTodosSMTextShare($_SESSION['h_logueado']);
	$i=0;
	foreach($shareTextsHotel as $lang)
	{
		if(empty($lang['pre']) || empty($lang['stay']) || empty($lang['post']))
		{
			$result[$i]['lang']=$lang['lang'];
			$result[$i]['code']='404';
		}else{
			$result[$i]['lang']=$lang['lang'];
			$result[$i]['code']='200';
		}
		$i++;
	}
	echo json_encode($result);
}

// get all social media share text by hotel 
function obtenerTodosSMTextShare($id_hotel){
    $id_hotel = mysqli_real_escape_string(conectar(1), $id_hotel);

    $sql = "SELECT pre, stay, post, lang FROM hotel_share_text WHERE id_hotel=$id_hotel ";
    $rs = mysqli_query (conectar(), $sql) or die(mysqli_error(conectar(1)));
    while ($row = mysqli_fetch_assoc($rs)){
		$result[]=$row;
	}
    liberar($rs);
    return $result;
}