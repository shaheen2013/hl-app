<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//Contenido solo visible si logueado
include LIB.'logueado.php';
hotelStaffLanding ();// Si no esta logueado lo manda a la landing

include_once LIB.'subirArchivos.php';
include_once LIB.'sanitize.php';
include_once LIB.'obtenerdatosHotel.php';

// Funcion para ver si tiene todos los campos obligatorios rellenados
function camposObligatorios($datosHotel,$datosTemporada,$arrayHotelTiposHab,$arrayHotelServicio,$arrayHotelExtras, $arrayFotosHotel){
	if( $datosHotel['n_habitaciones']=='0' || 
	($datosTemporada['jan']=='0' && $datosTemporada['feb']=='0' && $datosTemporada['mar']=='0' 
	&& $datosTemporada['apr']=='0' && $datosTemporada['may']=='0' && $datosTemporada['jun']=='0' 
	&& $datosTemporada['jul']=='0' && $datosTemporada['ago']=='0' && $datosTemporada['sep']=='0' 
	&& $datosTemporada['oct']=='0' && $datosTemporada['nov']=='0' && $datosTemporada['dece']=='0') 
	|| count_chars(strip_tags($datosHotel['descripcion']))<'250'
	|| count_chars(strip_tags($datosHotel['conditions'], ' '))<'100'
	|| $datosHotel['tipo_hotel']=='0' || $datosHotel['decoracion']=='0' 
	|| empty($arrayHotelTiposHab[0]['id_tipo_hab']) 
	|| empty($arrayHotelServicio[0]['id_servicio'])
	|| empty($arrayHotelExtras[0]['id_extra'])
	|| count($arrayFotosHotel)==0){
		return false;		
	}else{
		return true;
	}
}
function get_num_of_words($string) {
    $string = preg_replace('/\s+/', ' ', trim($string));
    $words = explode(" ", $string);
    return count($words);
}

if (!empty($_GET['del'])){
	$id_foto = mysqli_real_escape_string(conectar(), $_GET['del']);
	// borrar Foto HD
	$nombreFoto = obtenerNombreFoto($_SESSION['h_logueado'], $id_foto);
	$rutaFoto = DIR_IMG_FICHA_HOTEL.$_SESSION['h_logueado'].'/';
	unlink ($rutaFoto.$nombreFoto);
	borrarThumbnail($rutaFoto, $nombreFoto);
	// borrar foto BD
	borrarFotoBD($_SESSION['h_logueado'], $id_foto);
	$ok = array (true, '2022');
	//Redireccionamos a esta misma url para borrar $_GET
	header('Location: /'.$urlTree['hotel-profile-2']);
}

if (!empty($_GET['pri'])){
	$id_foto = mysqli_real_escape_string(conectar(), $_GET['pri']);
	$nombreFoto = obtenerNombreFoto($_SESSION['h_logueado'], $id_foto);
	priorizarFoto($_SESSION['h_logueado'], $id_foto);
	$ok = array (true, '2023');
}

if (!empty($_POST['save-profile-2'])){
	//estrellas
	$estrellas = mysqli_real_escape_string(conectar(), $_POST['estrellas']);
	//Rango precios
	$minRango = mysqli_real_escape_string(conectar(), $_POST['minPriceRange']);//hidden
	$maxRango = mysqli_real_escape_string(conectar(), $_POST['maxPriceRange']);
	//$hotelPriceRange = mysqli_real_escape_string(conectar(), $_POST['hotelPriceRange']);
	//n habitaciones
	$numHabitaciones = mysqli_real_escape_string(conectar(), $_POST['numHabitaciones']);
	//Temporada
	$jan = mysqli_real_escape_string(conectar(), $_POST['jan']);
	$feb = mysqli_real_escape_string(conectar(), $_POST['feb']);
	$mar = mysqli_real_escape_string(conectar(), $_POST['mar']);
	$apr = mysqli_real_escape_string(conectar(), $_POST['apr']);
	$may = mysqli_real_escape_string(conectar(), $_POST['may']);
	$jun = mysqli_real_escape_string(conectar(), $_POST['jun']);
	$jul = mysqli_real_escape_string(conectar(), $_POST['jul']);
	$ago = mysqli_real_escape_string(conectar(), $_POST['ago']);
	$sep = mysqli_real_escape_string(conectar(), $_POST['sep']);
	$oct = mysqli_real_escape_string(conectar(), $_POST['oct']);
	$nov = mysqli_real_escape_string(conectar(), $_POST['nov']);
	$dece = mysqli_real_escape_string(conectar(), $_POST['dece']);

	$hotelType = mysqli_real_escape_string(conectar(), $_POST['hotelType']);
	$hotelDecoration = mysqli_real_escape_string(conectar(), $_POST['hotelDecoration']);
	$hotelDescription=mysqli_real_escape_string(conectar(),$_POST['hotelDescriptionText']);
	$hotelConditions=mysqli_real_escape_string(conectar(), $_POST['hotelConditionsText']);

	insertarDatosDB2 ($estrellas, $minRango, $maxRango, $numHabitaciones, $hotelType, $hotelDecoration, $hotelDescription, $hotelConditions,
	//Temporada
	$jan, $feb, $mar, $apr, $may, $jun, $jul, $ago, $sep, $oct, $nov, $dece
	);

	//Tipos de habitaciones
	if (isset ($_POST['roomType'])){
		insertarTiposHabHotel($_SESSION['h_logueado'], $_POST['roomType']);
	}else{
		borrarTiposHabHotel($_SESSION['h_logueado']);
	}

	//Extras en habitaciones
	if (isset ($_POST['roomExtra'])){
		insertarExtrasHotel($_SESSION['h_logueado'], $_POST['roomExtra']);
	}else{
		borrarExtrasHotel($_SESSION['h_logueado']);
	}

	//Servicios hotel
	if (isset ($_POST['hotelServices'])){
		insertarServiciosHotel($_SESSION['h_logueado'], $_POST['hotelServices']);
	}else{
		borrarServiciosHotel($_SESSION['h_logueado']);
	}

	//crear carpeta fotos hotel
	$ruta = DIR_IMG_FICHA_HOTEL.$_SESSION['h_logueado']."/";
	if (!file_exists($ruta)){
		 mkdir($ruta, 0777);
		 //mkdir($ruta.'logo/', 0777);
	}
	
	$nFotosHotel = count ($_FILES['fotosDelHotel']['name']);

	//Subir fotos hotel
	if ($_FILES['fotosDelHotel']['name'][0]!=''){
		$maxSize=8388608; //8MB
		
		// Sanitizamos el nombre de la imagen
		$t=0;
		while ($t < $nFotosHotel){
			$_FILES['fotosDelHotel']['name'][$t] = archivoExtension($_FILES['fotosDelHotel']['name'][$t]);
			$t++;
		}
		
		//Subimos las imgs
		subirImagenes($ruta, $_FILES['fotosDelHotel'], $maxSize);

		$i=0;
		 
		while($i < $nFotosHotel){
			$extension = pathinfo($_FILES['fotosDelHotel']['name'][$i], PATHINFO_EXTENSION);
			if(($extension == 'jpg') || ($extension == 'JPG') || ($extension == 'gif') || ($extension == 'GIF') || ($extension == 'png') || ($extension == 'PNG') ){
				guardarNombreImg($_SESSION['h_logueado'], $_FILES['fotosDelHotel']['name'][$i]);
			}
			$i++;
		}
	}
	$ok = array (true, '2007');
}

$arrayHotelProfile2 = obtenerDatosHotelProfile2($_SESSION['h_logueado']);
$arrayHotelExtras = obtenerDatosHotelExtras($_SESSION['h_logueado']);
$arrayHotelTiposHab = obtenerDatosHotelTiposHab($_SESSION['h_logueado']);
$arrayHotelServicios = obtenerDatosHotelServicios($_SESSION['h_logueado']);
$arrayHotelTemporada = obtenerDatosHotelTemporada($_SESSION['h_logueado']);
//Url del hotel con GUID	
$urlGuidHotel = obtenerUrlGUIDHotel($_SESSION['h_logueado']);

$arrayTiposHotel = obtenerTiposHotel();
$arrayTiposDecoracion = obtenerTiposDecoracion();

//Total servicios / tipos hab. / extras de BD
$arrayExtrasHotel = obtenerExtrasHotel();//Room features
$arrayTiposHabHotel = obtenerTiposHabHotel();
$arrayServiciosHotel = obtenerServiciosHotel();
// Fotos del hotel
$arrayFotosHotel = obtenerFotosHotel($_SESSION['h_logueado']);

//rellenamos el array con 1 y 0 para marcar los checkbox de extras habitacion hotel
$nHotelExtras = count($arrayHotelExtras);//extras que tiene el hotel
$nExtrasHotel = count($arrayExtrasHotel);//total extras que puede tener un hotel
$i=0;
while($i<$nExtrasHotel){
	$t=0;
	while($t<$nHotelExtras){
		if ($arrayExtrasHotel[$i]['id_extra']==$arrayHotelExtras[$t]['id_extra']){
			$arrayExtrasHotel10[$arrayExtrasHotel[$i]['id_extra']]=1;
			break;
		}else{
			$arrayExtrasHotel10[$arrayExtrasHotel[$i]['id_extra']]=0;
		}
		$t++;
	}
	$i++;
}

//rellenamos el array con 1 y 0 para marcar los checkbox de tipos de habitacion hotel
$nHotelTiposHab = count($arrayHotelTiposHab);//extras que tiene el hotel
$nTiposHabHotel = count($arrayTiposHabHotel);//total extras que puede tener un hotel
$i=0;
while($i<$nTiposHabHotel){
	$t=0;
	while($t<$nHotelTiposHab){
		if ($arrayTiposHabHotel[$i]['id_tipo_hab']==$arrayHotelTiposHab[$t]['id_tipo_hab']){
			$arrayTiposHabHotel10[$arrayTiposHabHotel[$i]['id_tipo_hab']]=1;
			break;
		}else{
			$arrayTiposHabHotel10[$arrayTiposHabHotel[$i]['id_tipo_hab']]=0;
		}
		$t++;
	}
	$i++;
}

//rellenamos el array con 1 y 0 para marcar los checkbox de servicios hotel
$nHotelServicios = count($arrayHotelServicios);//extras que tiene el hotel
$nServiciosHotel = count($arrayServiciosHotel);//total extras que puede tener un hotel
$i=0;
while($i<$nServiciosHotel){
	$t=0;
	while($t<$nHotelServicios){
		if ($arrayServiciosHotel[$i]['id_servicio']==$arrayHotelServicios[$t]['id_servicio']){
			$arrayServiciosHotel10[$arrayServiciosHotel[$i]['id_servicio']]=1;
			break;
		}else{
			$arrayServiciosHotel10[$arrayServiciosHotel[$i]['id_servicio']]=0;
		}
		$t++;
	}
	$i++;
}

if (!camposObligatorios($arrayHotelProfile2, $arrayHotelTemporada, $arrayHotelTiposHab,$arrayHotelServicios, $arrayHotelExtras, $arrayFotosHotel)){
	$ok = array (false, '4024');
}else{
	hotelProfile2Onboarding($_SESSION['h_logueado']);
}
/*echo '<pre>';
print_r($arrayFotosHotel);
echo '</pre>';*/
?>