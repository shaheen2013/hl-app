<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//Contenido solo visible si logueado
include LIB.'logueado.php';
userLanding ();// Si no esta logueado lo manda a la landing

function crearTarjetaUsuario($id_usuario, $id_tarjeta, $nombre, $rutaTarjeta){
	$originalImage = "public/img/loyalty-card-base.png";
	$im = imagecreatefrompng($originalImage);
	imagesavealpha($im, true); // important to keep the png's transparency
	// $black = imagecolorallocate($im, 0, 0, 0);
	$white = imagecolorallocate($im, 255, 255, 255);
	$width = 1176; // the width of the image
	$height = 800; // the height of the image
	$font = 'fonts/OpenSans-Light.ttf';
	$fontBold = 'fonts/OpenSans-Bold.ttf';
	$outputImage = "" . $rutaTarjeta."/tarjeta".$id_usuario.".png";
	
	// imagettftext: image , size, angle, x, y, color, font, text
	imagettftext($im, 50, 0, 80, 480, $white, $fontBold, $id_tarjeta);
	imagettftext($im, 50, 0, 80, 560, $white, $font, $nombre);
	imagepng($im, $outputImage, 0);
	imagedestroy($im);
	return $outputImage;
}

$arrayDatosTarjeta = obtenerDatosTarjeta($_SESSION['u_logueado']);
//creamos un token para la url de la tarjeta para que el usuario no pueda leer otras tarjetas 
//de otros usuarios (hash de id_usuario+$0br4$$4d4)
$hashRuta = sha1($_SESSION['u_logueado'].'$0br4$$4d4');

$rutaTarjeta = DIR_IMG_FICHA_USUARIO.$hashRuta;

if (!is_dir($rutaTarjeta)) {
	mkdir ($rutaTarjeta, 0777);
}
//if(!file_exists($rutaTarjeta."/tarjeta".$_SESSION['u_logueado'].".png")){
$imagen = crearTarjetaUsuario($_SESSION['u_logueado'], $arrayDatosTarjeta['id_tarjeta'], $arrayDatosTarjeta['nombre'], $rutaTarjeta);
/*}else{
	//tarjeta ya creada anteriormente -> no la creamos de nuevo
	$imagen = DIR_IMG_FICHA_USUARIO.$_SESSION['u_logueado']."/tarjeta".$_SESSION['u_logueado'].".png";
}*/
?>