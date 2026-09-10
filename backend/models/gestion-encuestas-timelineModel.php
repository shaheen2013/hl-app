<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

function obtenerEncuestas(/* $oderby, $limit */){
	$sql ="SELECT * FROM user_encuestas WHERE id_hotel = '".$_SESSION['h_logueado']."' ORDER BY fecha DESC LIMIT 0, 50";
	$rs = mysqli_query (conectar(), $sql);
	
	$i=0;
	while ($row = mysqli_fetch_assoc($rs)) {
		$sql2 = "SELECT twitter_img, nombre FROM users WHERE id ='".$row['id_usuario']."'";
		$rs2 = mysqli_query (conectar(), $sql2);
		$row2 = mysqli_fetch_assoc($rs2);
		liberar ($rs2);
		
		//$arrayGestionEncuestas[$i]['id_us']=$row['id_usuario'];
		$arrayGestionEncuestas[$i]['img']=$row2['twitter_img'];
		$arrayGestionEncuestas[$i]['nombre']=$row2['nombre'];
		$arrayGestionEncuestas[$i]['fecha']= substr($row['fecha'],0, 10);
		$arrayGestionEncuestas[$i]['comentario_pos']=$row['positiveComment'];
		$arrayGestionEncuestas[$i]['comentario_neg']=$row['negativeComment'];
		$arrayGestionEncuestas[$i]['puntuacion']=$row['rating'];
		
		$i++;	
	}
	liberar($rs);
	
	return($arrayGestionEncuestas);
}
?>