<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//contenido solo disponible si logueado
include LIB.'logueado.php';
hotelStaffLanding ();// Si no esta logueado lo manda a la landing

include_once LIB . 'borrarSession.php';

// Desactivar alert superior para  invitar clientes de la Home del hotelero
if (!empty($_GET['alert']) && $_GET['alert'] == '1'){
	quitarAlert($_SESSION['h_logueado']);
}

// Filtramos los errores por los que se pueden mostrar en esta pantalla
// Errores permitodos en esta pantalla: 4052
if(!empty($_GET['error']) && $_GET['error']=='4052' ){
	$ok = array(false, $_GET['error']);
}

borrarSessionInvitarUsuarios();
?>