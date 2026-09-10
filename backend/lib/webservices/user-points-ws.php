<?php
include 'librerias.php';// Librerias básicas

include_once RUTA_DIR.LIB.'check_access.php';
checkIpAccess('us-po-giv', $_SERVER['REMOTE_ADDR']);

$busqueda = mysqli_real_escape_string(conectar(), $_POST['email']);
$sql = "SELECT users.id, nombre, img
FROM users 
WHERE email='".$busqueda."' ";
//echo $sql;
$rs = mysqli_query (conectar(), $sql);
$n_resultados = mysqli_num_rows($rs);
include_once RUTA_DIR.LANG.$_SESSION['userLang'].'/give-rewards-friend-modal-ws.php';// Lang
if ($n_resultados==1){
	$row = mysqli_fetch_assoc($rs);
	$msg = $userFound.': <strong> '.$row['nombre'].'</strong><img src="'.$row['img'].'" ><br /><input hidden name="id" value="'.$row['id'].'"> ';
}else{
	$msg = '<input hidden name="id" value="0">
	<h3 class="naranja"><strong>'.$userDoesntExist.'</strong></h3>
					<p>'.$weWillSendInvite.'.</p>';
}
liberar ($rs);
$msg .= '<p>'.$confirmGivePoints.'.</p>
		<input type="submit" class="btn btn-success mt btn-lg" value="'.$button.'">';
echo $msg;
?>
