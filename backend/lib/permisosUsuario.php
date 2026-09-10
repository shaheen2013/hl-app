<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//Inicialmente los usuario solo pueden acceder a las pantallas que son de referral tool, el resto redirigen a 404
if(!empty($_SESSION['refTool']) && $_SESSION['refTool']){
	header('Location: /'.$urlTree['404']);
}
?>