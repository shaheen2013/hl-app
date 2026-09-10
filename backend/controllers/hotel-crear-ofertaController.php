<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//Contenido solo visible si logueado
include LIB.'logueado.php';
hotelStaffLanding ();// Si no esta logueado lo manda a la landing

include_once LIB.'obtenerdatosHotel.php';

// --> webservices/hotel-crear-oferta.php

//Obtenemos los permisos que tiene el hotel (LY, RF, MK)
$permisosHotel = obtenerPermisosHotel($_SESSION['loggedBrandID']);
//array de tipos oferta adq/ret/ref
$arrayOfferMethod = obtenerOfferMethod();
/*echo '<pre>';
print_r($onboarding);
echo '</pre>';*/

if($permisosHotel['LY']==0 && $permisosHotel['RF']==1 && $permisosHotel['MK']==0){
	//Si solo tiene RF mostar offer method solo RF
	unset ($arrayOfferMethod['adq']);
	unset ($arrayOfferMethod['ret']);
}

unset($_SESSION['publicada']);//La oferta no ha sido publicada (para info de onboarding)
?>