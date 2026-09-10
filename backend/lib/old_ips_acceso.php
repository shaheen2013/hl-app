<?php
// Filtrado de ips para webservices
// Tabla BD ips_webservice
// Nombre del webservice: $ws
// IP: $ip del server ($_SERVER['REMOTE_ADDR']) que nos hace la petición
// 		La ip también puede ser el address del server: gethostbyaddr($_SERVER['REMOTE_ADDR'])
// Access = 1 -> OK, else -> NO OK
function ips_acceso_webservice ($ws ,$ip){
	/*$sql = "SELECT access FROM ips_webservice WHERE ip = '".$ip."' AND ws='".$ws."' ";
	//echo $sql;
	$rs = mysqli_query (conectar(1), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	if ($row['access'] == '1'){
		return true;
	}else{
		return false;
	}*/
	return true;
}
?>