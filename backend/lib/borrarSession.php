<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

include_once RUTA_DIR . LIB . 'obtenerdatosHotel.php';

// Borrar datos de session utilizados en "hotel crear (detalle) oferta"
// No borramos offerMethod ya que se necesita para el On Boarding
function borrarSessionCrearOferta($id_hotel)
{
	$id_hotel = mysqli_real_escape_string(conectar(), $id_hotel);
	
	//Datos oferta sin langs
	$datos = array ('category', 'offertype', 'subCategory', 'inicio', 'fin', 'requerimientos', 'cupo', 'descuento', 'coste', 'puntos', 'foto', 'estado', 'id_oferta', 'ruta_tmp', 'ruta', 'id_oferta', 'publicable', 'guardable', 'editada', 'guardada', 'foto_nueva', 'dir_tmp', 'lang'
	,'hotel_cerrado', 'fecha_incorrecta', 'moneda', 'bookingEngineCode'	, 
	'flagActual', 'countryActual');
	//Se ha borrado el offerMethod de esta lista por que necesitamos comprobarlo en el onboarding
	
	//Miramos si ya ha creado su primera oferta en el onboarding
	$sql = "SELECT oferta_1 FROM onboarding WHERE id_hotel='".$id_hotel."' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	if(!empty($row) && $row['oferta_1']=='1' || $_SESSION['permisos']['LY'] == 0){
		//Ya ha creado su primera oferta del onboarding, podemos resetear 'offerMethod'
		array_push($datos, 'offerMethod');
	}
	
	$n = count ($datos);
	$i=0;
	while ($i < $n){
		if(isset($_SESSION[$datos[$i]])){
			unset($_SESSION[$datos[$i]]);
		}
		$i++;
	}
	
	//Langs-------------
	$langsHotel = obtenerIdStringLangsHotel($id_hotel);
	foreach($langsHotel as $lang)
	{
		unset ($_SESSION[$lang]);

	}
}

function borrarSessionProfileLanding($id_hotel)
{
	$id_hotel = mysqli_real_escape_string(conectar(), $id_hotel);
	
	//Datos oferta sin langs
	$datos = array ('lang-landing', 'flagActual-landing', 'countryActual-landing');
	
	$n = count ($datos);
	$i=0;
	while ($i < $n){
		if(isset($_SESSION[$datos[$i]])){
			unset ($_SESSION[$datos[$i]]);
		}
		$i++;
	}
	//Langs todos-------------
	//$langsHotel = obtenerIdStringLangsHotel($id_hotel);
	$langsHotel = array ('en', 'es', 'de', 'fr');

	foreach($langsHotel as $lang)
	{
		if(isset($_SESSION[$lang.'-landing'])){
			unset ($_SESSION[$lang.'-landing']);
		}
	}
}

// FX para borrar los datos de SESSION de las pantallas/WS:
// invitar-usuarios
// invitar-usuarios-2
// invitar-usuarios-result
function borrarSessionInvitarUsuarios()
{
	//Datos (Indices de variables $_SESSION a borrar)
	$datos = array ('arrayEmailPuntos', 'n', 'field1', 'field2', 'field3', 'field4', 'field5');
	
	foreach($datos as $dato){
		if(isset($_SESSION[$dato])){
			unset ($_SESSION[$dato]);
		}
	}
}

//FX para unset de array con indices de session
function borrarArraySession($array){
	foreach($array as $dato){
		if(isset($_SESSION[$dato])){
			unset ($_SESSION[$dato]);
		}
	}
}

// FX para borrar datos temporales de session
// $fechaInicial: Fecha inicial a portir de la que debemos contrar para caducar la session
// $unit: unidad de tiempo 'min', 'hour', 'day'
// $amount: cantidad de unidades de tiempo a esperar para caducar la session (2h, 30min ...)
// $data: array con los indices de session a caducar
// Devuelve: code, message
function borrarSessionTemporal($fechaInicial, $amount, $unit, $data){
	//Libreria parar funciones de fechas
	include_once RUTA_DIR . LIB . 'fecha.php';
	
	$fechaActual = time();
	
	//Calculamos la unidad de tiempo que ha pasado
	$tiempoPasado = compararUnixTime($fechaInicial, $fechaActual, $unit);
	
	if($tiempoPasado['amount'] >= $amount){
		//debemos caducar datos
		borrarArraySession($data);
		$result['code']='200';
		$result['message']='Session data expired';
	}else{
		//NO debemos caducar
		$result['code']='401';
		$result['message']='Session data not expired';
	}
	return $result;
}
?>