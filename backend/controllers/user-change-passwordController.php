<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//Contenido solo visible si logueado
include LIB.'logueado.php';
userLanding ();// Si no esta logueado lo manda a la landing

if(!empty($_POST['newPass']) && !empty($_POST['rNewPass'])){
	if(isset($_POST['oldPass'])){
		$oldPass = mysqli_real_escape_string(conectar(), $_POST['oldPass']);
	}else{
		$oldPass='';
	}
	$newPass = mysqli_real_escape_string(conectar(), $_POST['newPass']);
	$rNewPass = mysqli_real_escape_string(conectar(), $_POST['rNewPass']);
	//Mirar si el password antiguo es correcto
	if(oldPassOK($_SESSION['u_logueado'], $oldPass)){
		//Mirar que los 2 nuevos passwords coinciden
		if($newPass == $rNewPass){
			if( (strlen($newPass)<6) || (strlen($newPass)>12) ){
				//Longitud de pass incorrecto
				$ok = array (false, '4031');
			}else{
				//Todo OK, cambiamos el pass
				cambiarPassUsuario($_SESSION['u_logueado'], $newPass);
				$ok = array (true, '2007');
			}
		}else{
			$ok = array(false, '4034');
		}
	}else{
		$ok = array(false, '4013');
	}
}

//Miramos si tiene pass anterior para mostrar input 'actual password'
$tienePassAnterior = tienePassAnterior($_SESSION['u_logueado']);
?>