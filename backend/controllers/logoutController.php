<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

session_destroy();
if (isset($_COOKIE['PHPSESSID'])) {
    unset($_COOKIE['PHPSESSID']);
	setcookie('PHPSESSID', '', time() - 3600, '/', '', true, true);

}
/*if(!empty($_SESSION['h_logueado'])){
	//Si es hotelero lo mandamos al BASE_PATH
	//$redireccionLogout = '';
	//Redireccionamos
	//header ('location: /'. $redireccionLogout .'');
}else{
	//Si es otro, lo mandamos a la tienda
	//$redireccionLogout = $urlTree['tienda'];
	//$redireccionLogout = '#';
}*/
?>