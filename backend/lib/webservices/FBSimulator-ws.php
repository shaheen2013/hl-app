<?php
// Simulación de interacción con Facebook
include_once 'librerias.php';// Librerias básicas

// FX para generar usuarios simulados de FB
if( !empty($_POST['getUser']) )
{
	$microtime = round(microtime(true) * 1000);
	
	$FBData = array();  
	$FBData["name"] = "AWS_TU_".$microtime;
	$FBData["email"] = $_SESSION['fbemail'] = "aws_test_".$microtime."@hotelinking.joopbox.com";
	$FBData["id"] = $_SESSION['userIdSM'] = $microtime;
	$FBData["friends"]["summary"]["total_count"] = 256;
	$FBData["fbAccessToken"] = $microtime;
	
	// Borramos las variables de session que utilizamos en el modo normal
	//if( isset($_SESSION['userIdSM']) ){	unset($_SESSION['userIdSM']);}
	if( isset($_SESSION['id_usuario']) ){ unset($_SESSION['id_usuario']);}
	//if( isset($_SESSION['fbemail']) ){ unset($_SESSION['fbemail']);}
	if( isset($_SESSION['additional']) ){ unset($_SESSION['additional']);}
	
	echo  json_encode($FBData);
}
?>