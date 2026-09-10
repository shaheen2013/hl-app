<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//Contenido solo visible si logueado
include LIB.'logueado.php';
userLanding ();// Si no esta logueado lo manda a la landing

include_once LIB.'sanitize.php';
include_once LIB.'regalarPuntos.php';
include_once LIB.'ordenacion.php';
include_once LIB.'follow.php';
include_once LIB.'fidelizacion.php';
include_once LIB.'obtenerdatosHotel.php';
include_once LIB.'obtenerDatosCadena.php';


if (!empty($_GET['give'])){ // Usuario ha dado puntos a otro
	$give = mysqli_real_escape_string(conectar(), $_GET['give']);
	if($give=='ok'){
		$ok = array (true, '2024');
	}
}

// Array puntos hoteles
$arrayPuntosUsuario = obtenerPuntosUsuario($_SESSION['u_logueado']);
// Array puntos cadena
$arrayPuntosUsuarioCadena = obtenerPuntosUsuarioCadena($_SESSION['u_logueado']);
// Puntos hotelinking (para ofertas de adquisición y propias de Hotelinking)
$puntos_hl = obtenerPuntosHotelinking ($_SESSION['u_logueado']);
// Array para la select de regalar puntos
$arraySelectPuntos = crearSelectPuntos($arrayPuntosUsuario, $arrayPuntosUsuarioCadena);
// Juntamos el array de hoteles y cadenas
if (!empty($arrayPuntosUsuarioCadena) && (!empty($arrayPuntosUsuario))){
	$arrayPuntosUsuario = array_merge($arrayPuntosUsuario,$arrayPuntosUsuarioCadena);
}else if (empty($arrayPuntosUsuarioCadena) && (!empty($arrayPuntosUsuario))){
	$arrayPuntosUsuario = $arrayPuntosUsuario;
}else{
	$arrayPuntosUsuario = $arrayPuntosUsuarioCadena;
}
// Ordenamos el array por puntos
$arrayPuntosUsuario = orderMultiDimensionalArray($arrayPuntosUsuario, 'puntos', 1);

/*echo '<pre>';
print_r($arrayPuntosUsuario);
echo '</pre>';*/
/*echo '<pre>';
print_r($arrayPuntosUsuarioCadena);
echo '</pre>';*/
/*echo '<pre>';
print_r($arraySelectPuntos);
echo '</pre>';*/
?>