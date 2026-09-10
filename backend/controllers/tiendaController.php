<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

include_once LIB.'paginacion2.php';
include_once LIB.'wishlist.php';
include_once LIB.'obtenerdatosHotel.php';
include_once LIB.'acqOffer.php';
include_once LIB.'fecha.php';

$arrayCategorias = obtenerCategorias();// Categorias checkin
if(!empty($_SESSION['u_logueado'])){
	$arrayWhislist = obtenerWhislist($_SESSION['u_logueado']);
}

$arrayWheres = array ( // contiene todos los WHERES para la búsqueda SQL
		'cty' => '', //ciudad
		'place_name' => '',//place de lugar de google
		'place_type' => '',
		'place_country' => '',
		'place_adm_area' => '',
		'latNE' => '',
		'lngNE' => '',
		'latSW' => '',
		'lngSW' => '',
		'hot' => '', //id hotel (num.)
		'hcn' => '', //nombre hotel o cadena (text)
		'cad' => '', //id cadena (num.)
		'from' => '', //fecha_desde
		'till' => '', //fecha_hasta (0000-00-00 -> sin fecha hasta)
		'pt1' => '', //puntos_desde (NO PUEDE SER 0)
		'pt2' => '', //puntos_hasta (NO PUEDE SER 0)
		'es1' => '', //estrellas desde (NO PUEDE SER 0)
		'es2' => '', //estrellas hasta (NO PUEDE SER 0)
		'rat1' => '', //rating desde (NO PUEDE SER 0)
		'rat2' => '', //rating hasta (NO PUEDE SER 0) (rat1+rat2)
		'adre' => '',//Adquisición, retención (adq / ret)
		'req' => '', //requerimientos (noches, sin condiciones)
		'cat'=> '' //Categoria
		//'pref' // pref=1 : preferencias del usuario activada
		);
	
// Añadimos categorias al array de wheres
/*$nCat = count ($arrayCategorias);
$n=0;
while($n < $nCat){
	foreach ($arrayCategorias[$n] as $key => $value){
		if ($key == 'id'){
			$arrayWheres[$value]= '';
			$arrayMinCategorias[] = $value;
		}
	}
	$n++;
}*/

// Cargamos en el array de Wheres los valores pasados por GET
foreach ($arrayWheres as $key => $value){
	if(isset($_GET[$key])){
		$arrayWheres[$key] = mysqli_real_escape_string(conectar(), $_GET[$key]); 
	}else{
		$arrayWheres[$key] = '';
	}
}
// Preferencias usuario ----------------------------------------------------
if (!empty($_GET['pref']) && $_GET['pref']==1){
	$arrayPreferenciasUsuario = obtenerPreferenciasUsuario (); // Estrellas, rango precios
	$arrayDecoracionesUsuario = obtenerDecoracionesUsuario(); // Decoraciones
	$arrayExtrasUsuario = obtenerExtrasUsuario(); // Extras
	$arrayServiciosUsuario = obtenerServiciosUsuario(); // Servicios
	$arrayTiposHotelUsuario = obtenerTiposHotelUsuario(); // Tipos hotel
	$arrayTiposHabUsuario = obtenerTiposHabUsuario(); // Tipos habitacion
	
	// Añadimos preferencias usuario al array de wheres
	foreach ($arrayPreferenciasUsuario as $key => $value){
		$arrayWheres[$key]= $value;
	}
	// Añadimos decoraciones usuario al array de wheres
	foreach ($arrayDecoracionesUsuario as $key => $value){
		$arrayWheres[$value]= '1';
	}
	// Añadimos extras usuario al array de wheres
	if(!empty($arrayExtrasUsuario)){
		foreach ($arrayExtrasUsuario as $key => $value){
			$arrayPrefUsuarioExtras[$value]= '1';
		}
	}else{
		$arrayPrefUsuarioExtras = array();
	}
	// Añadimos servicios usuario al array de wheres
	if(!empty($arrayExtrasUsuario)){
		foreach ($arrayServiciosUsuario as $key => $value){
			$arrayPrefUsuarioServicios[$value]= '1';
		}
	}else{
		$arrayPrefUsuarioServicios = array();
	}
	// Añadimos TiposHab usuario al array de wheres
	foreach ($arrayTiposHabUsuario as $key => $value){
		$arrayPrefUsuarioTiposHab[$value]= '1';
	}
	$_SESSION['pref_usuario']=1;
}else{
	$_SESSION['pref_usuario']=0;
	$arrayPrefUsuarioExtras = array ();
	$arrayPrefUsuarioServicios = array ();
	$arrayPrefUsuarioTiposHab = array ();
}

// Array como el de wheres pero con nombres en lugar de ID's
// para mostrar un string con los detalles de la búsqueda mostrada
$arrayStringBusqueda = stringBusqueda($arrayWheres);

$itemsPage = 50;
$arrayOfertas = obtenerOfertas($arrayWheres, $arrayPrefUsuarioExtras, $arrayPrefUsuarioServicios, $arrayPrefUsuarioTiposHab, $itemsPage, $pagina);

// Añadir a wishlist
if(!empty($_GET['wlst']) && !empty($_SESSION['u_logueado']) ){
	// Botón "Add to wishlist"
	$id_Oferta = mysqli_real_escape_string(conectar(), $_GET['wlst']);
	ofertaWishlist($id_Oferta, $_SESSION['u_logueado']);
	$ok =  array (true, '2010');
}else{
	// No esta logueado
}

// Generamos un array con los ids de las ofertas para una consulta en ajax a la BD
// para saber que ofertas puede adquirir el usuario-------------------------------
/*foreach($arrayOfertas as $ofertas){
	
	$arrayIds[] = $ofertas['id'];
}
$arrayIds = json_encode($arrayIds);
$urlws = BASE_PATH . LIB . 'webservices/tienda.php';
echo $urlws;
$curl = curl_init($urlws);
curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $arrayIds);
$json_response = curl_exec($curl);
$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);
$response = json_decode($json_response, true);
echo $json_response;*/
/*echo '<pre>';
var_dump($arrayIds);
echo '</pre>';*/
//---------------------------------------------------------------------------------

// viene de "ofertaGracias" si intenta compartir una oferta ya compartida o de otra persona
if (!empty($_GET['error'])){
	$error = mysqli_real_escape_string(conectar() , $_GET['error']);
	$ok =  array (false, $error);
}

/*echo '<pre>';
print_r($arrayOfertas);
echo '</pre>';*/
?>