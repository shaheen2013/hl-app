<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//Contenido solo visible si logueado
include LIB.'logueado.php';
userLanding ();// Si no esta logueado lo manda a la landing

include LIB.'invitaciones.php';
include LIB.'crearNuevoUsuario.php';
include LIB.'referrer.php';

//post de esta pagina
if (!empty($_POST['save-profile-3']) || !empty($_POST['save-profile-3-next']) ){
	//Paises del mundo
	if (isset ($_POST['countrylist'])){
		borrarCountrylist();
		$i=0;
		$nWorldPlaces = count ($_POST['countrylist']);
		while ($i < $nWorldPlaces){
			InsertCountrylist($_POST['countrylist'][$i]);
			$i++;
		}
	}
	//Invitacion email
	if ($_POST['userEmail']!=""){
		$emailInvitado = mysqli_real_escape_string(conectar(), $_POST['userEmail']);
		$id_usuario = crearNuevoUsuario($emailInvitado, '');
		crearInvitacionUsuario ($emailInvitado,'us',$_SESSION['u_logueado']);
		//Vinculamos al usaurio con el referrer
		vincularReferrer($_SESSION['u_logueado'], $id_usuario, '', '');
	}
	$ok = array (true, '2007');
}

//Devuelve array de todos los paises para rellenar el select
$arrayPaises = obtenerPaises();
$arrayPaisesUsuario = obtenerPaisesUsuario();
?>