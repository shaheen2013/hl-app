<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//Contenido solo visible si logueado
include LIB.'logueado.php';
userLanding ();// Si no esta logueado lo manda a la landing

if (!empty($_POST['save-user-profile-4'])){
	
	//Select ofertas	
	if (isset ($_POST['offerPreferencesH'])){
		borrarTiposOferta();
		$i=0;
		$nOfferPreferences = count ($_POST['offerPreferencesH']);
		while ($i < $nOfferPreferences){
			InsertTipoOferta($_POST['offerPreferencesH'][$i]);
			$i++;
		}
	}else{
		borrarTiposOferta();
	}

	if(isset($_POST['fromHotelinking'])){
		$fromHotelinking=1;
		//si ya ha intentado enviarse el 'additional share email' permitimos un nuevo envio
		if(isset($_SESSION['additional'])){
			unset($_SESSION['additional']);
		}
	}else{
		$fromHotelinking=0;
	}
	if(isset($_POST['fromSpecialOffers'])){
		$fromSpecialOffers=1;
	}else{
		$fromSpecialOffers=0;
	}
	if(isset($_POST['share'])){
		$share=1;
	}else{
		$share=0;
	}
	InsertOfertaNotificaciones($fromHotelinking, $fromSpecialOffers, $share);
	$ok = array (true, '2007');
}

//Devuelve array de todos los tipos de oferta para rellenar el select
$arrayTiposOferta = obtenerTiposOferta();
//Devuelve arrays con las preferencias de este usuario
$arrayOfertasUsuario = obtenerOfferPreferencesUsuario(); // Option select
$arrayNotificaciones = obtenerNotificaciones(); // checkboxes

/*echo '<pre>';
print_r($arrayTiposOferta);
echo '</pre>';
*/
/*echo '<pre>';
print_r($arrayOfertasUsuario);
echo '</pre>';*/
/*
echo '<pre>';
print_r($arrayNotificaciones);
echo '</pre>';*/
?>

