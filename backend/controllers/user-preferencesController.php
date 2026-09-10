<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//Contenido solo visible si logueado
include LIB.'logueado.php';
userLanding ();// Si no esta logueado lo manda a la landing

include_once LIB.'obtenerDatosUsuario.php';

//obtener id_hotel a partir del GUID 
$id_usuario = obtenerIdUsuarioGUID($url['dir3']);

if(!empty($_SESSION['h_logueado']) || !empty($_SESSION['staff_id_hotel'])){

	//todos
	$tiposHotel = tiposHotel();
	$tiposDecoracion = tiposDecoracion();
	$tiposHabitacion = tiposHabitacion();
	$tiposExtras = tiposExtras();
	$serviciosHotel = serviciosHotel();
	$categoriasOfertas = categoriasOfertas();

	//Datos del usuario
	$datosUsuario = obtenerDatosUsuario($id_usuario);

	//Preferencias de usuario (user-profile-2)
	$preferenciasHotelUsuario = obtenerPreferenciasHotelUsuario($id_usuario);
	$tiposHotelUsuario = obtenerTiposHotelUsuario($id_usuario);//Tipos de hotel
	$tiposDecoracionUsuario = obtenerTiposDecoracionUsuario($id_usuario);//Decoraciones hotel
	$tiposHabUsuario = obtenerTiposHabUsuario($id_usuario);//Tipos habitaciones
	$extrasHabUsuario = obtenerExtrasHabUsuario($id_usuario);
	$serviciosHotelUsuario = obtenerServiciosHotelUsuario($id_usuario);

	//Ofertas compradas
	$tiposOfertasCompradas = obtenerTiposOfertasCompradasUsuario($id_usuario);
	$tiposOfertasWishlisted = obtenerTiposOfertasWUsuario($id_usuario);

	//datos de las categorias consumidas
	$ofertasCompradas = array();
	foreach ($categoriasOfertas as $value) {
		if(array_key_exists($value, $tiposOfertasCompradas)){
			$ofertasCompradas[] = [''.$value.'' => $tiposOfertasCompradas[$value]];
		}else{
			$ofertasCompradas[]=  [''.$value.'' =>0];
		}
	}
	$pop = array_pop($ofertasCompradas);

	//datos de las categorias wishlisted
	$ofertasWishlisted = array();
	foreach ($categoriasOfertas as $value) {
		if(array_key_exists($value, $tiposOfertasWishlisted)){
			$ofertasWishlisted[] = [''.$value.'' => $tiposOfertasWishlisted[$value]];
		}else{
			$ofertasWishlisted[]=  [''.$value.'' =>0];
		}
	}
	$pop = array_pop($ofertasWishlisted);
	
	//---- Datos que tiene el hotel en su profile ---------------------
	//tipo hotel
	$hotelTipo = obtenerHotelTipo($_SESSION['h_logueado']);
	//tipo decoración
	$hotelDecoracion = obtenerHotelDecoracion($_SESSION['h_logueado']);
	//tipos habitación
	$hotelTiposHab = obtenerHotelTiposHab($_SESSION['h_logueado']);
	//extras
	$hotelExtras = obtenerHotelExtras($_SESSION['h_logueado']);
	//servicios
	$hotelServicios = obtenerHotelServicios($_SESSION['h_logueado']);
}
?>