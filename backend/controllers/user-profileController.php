<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//Contenido solo visible si logueado
include  LIB .'logueado.php';
userLanding ();// Si no esta logueado lo manda a la landing

include_once LIB.'generarToken.php';
include_once LIB.'vincularRedesSociales.php';

if (!empty($_POST['hotelConfirmButton'])){
	$userName = mysqli_real_escape_string(conectar(), $_POST['userName']);
	//el email no se puede cambiar
	//$userEmail = mysqli_real_escape_string(conectar(), $_POST['userEmail']);
	$birthDay = mysqli_real_escape_string(conectar(), $_POST['birthDay']);
	$birthMonth = mysqli_real_escape_string(conectar(), $_POST['birthMonth']);
	$birthYear = mysqli_real_escape_string(conectar(), $_POST['birthYear']);
	$userSex = mysqli_real_escape_string(conectar(), $_POST['userSex']);
	$userCity = mysqli_real_escape_string(conectar(), $_POST['userCity']);

	$fechaNacimiento = $birthYear.'-'.$birthMonth.'-'.$birthDay;
	
	insertarDatosDB1 ($userName/*,$userEmail*/, $fechaNacimiento, $userSex, $userCity);
	$ok = array (true, '2007');
}

// Array con: nombre, email, location para rellenar form de profile
$arrayDatosUsuario = obtenerDatosUsuarioProfile($_SESSION['u_logueado']);
if ($arrayDatosUsuario['fecha_nacimiento']=='0000-00-00' || $arrayDatosUsuario['fecha_nacimiento']=='')
{
	$arrayFechaNacimiento[0]='';
	$arrayFechaNacimiento[1]='';
	$arrayFechaNacimiento[2]='';
}else{
	$arrayFechaNacimiento = explode ('-', $arrayDatosUsuario['fecha_nacimiento']);
}

$redesSociales = obtenerRedesSociales($_SESSION['u_logueado']);
/*echo '<pre>';
print_r ($redesSociales);
echo '</pre>';*/
?>