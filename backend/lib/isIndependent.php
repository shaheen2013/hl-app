<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

if (empty($_SESSION['isIndependent']) || $_SESSION['isIndependent']==0 ){
	header('Location: /'.$urlTree['hotel-home']);
}
?>