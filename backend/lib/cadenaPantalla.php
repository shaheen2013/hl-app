<?php
// Pantalla solo accesible para cadena

if( empty($_SESSION['c_logueado']) ){
	header('Location: /'.$_SESSION['defaultPage']);
}
?>