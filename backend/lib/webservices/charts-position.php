<?php
include_once 'librerias.php';// Librerias básicas
include_once RUTA_DIR.LIB.'check_access.php';
checkIpAccess('ch-pos', $_SERVER['REMOTE_ADDR']);

$pag = mysqli_real_escape_string(conectar(), $_POST['pag']);
if ($pag== $urlTree['hotel-home']){
	$elementos = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13 ,14);
}else if($pag==$urlTree['campaigns-dashboard']){
	$elementos = array(0, 1, 2, 3, 4, 5, 6, 7, 8);
}else if($pag=='reputation-dashboard'){
	$elementos = array(0, 1, 2, 3, 4, 5);
}else if($pag==$urlTree['referrals-dashboard']){
	$elementos = array(0, 1, 2, 3, 4, 5);
}else if($pag==$urlTree['leads-dashboard']){
	$elementos = array(0, 1);
}
$t=count($elementos); // Total charts

if (!empty($_POST['pag']) && !empty($_POST['sortOrder'])){
	$sortOrder = mysqli_real_escape_string(conectar(), $_POST['sortOrder']);
	//echo $pag.' - '.$sortOrder;
	$arrayOrden = explode(',', $sortOrder);
	// Creamos el SQL
	$sql = "UPDATE hotel_charts_position SET ";
	$i=0;
	while($i < $t){
		$sql .= "`".$pag."-".$i."`=".$arrayOrden[$i].", ";
		$i++;
	}
	$sql = substr($sql, 0, -2); // Quitamos la última coma
	$sql .= " WHERE id_hotel=".$_SESSION['h_logueado'];
	mysqli_query (conectar(), $sql);
	//echo $sql;

}else if(!empty($_POST['pag']) ){
	$sql = "SELECT ";
	$i=0;
	while ($i < $t){
		$sql .= "`".$pag."-".$elementos[$i]."` AS '".$i."', ";
		$i++;
	}
	$sql = substr($sql, 0, -2); // Quitamos la última coma
	$sql .= " FROM hotel_charts_position WHERE id_hotel='".$_SESSION['h_logueado']."' ";
	$rs = mysqli_query (conectar(), $sql);
	$result = mysqli_fetch_array($rs);
	$result = json_encode($result);
	liberar($rs);
	echo $result;
}
?>
