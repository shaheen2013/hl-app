<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//contenido solo disponible si logueado
include LIB.'logueado.php';
hotelStaffLanding ();// Si no esta logueado lo manda a la landing
include LIB.'isIndependent.php';

include_once LIB.'generarUrlCorrecta.php';
include_once LIB.'subirArchivos.php';
include_once LIB.'sanitize.php';
include_once LIB.'crearHotel.php';
include_once LIB.'obtenerdatosHotel.php';

// For front purposes
$currentPage = 'chain-management';
$currentSubPage = 'add-hotel';

if(!empty($_GET['msg'])){
	if($_GET['msg']=='2017'){
		$ok = array (true, $_GET['msg']);
	} else if ($_GET['msg']=='4065'){
		$ok = array (false, $_GET['msg']);
	}
}

// datos nuevo hotel
if (!empty($_POST['hotelConfirmButton'])){
	$con = conectar();
	$name = mysqli_real_escape_string($con, $_POST['hotelName']);
	$street = mysqli_real_escape_string($con, $_POST['hotelStreet']);
	$city = mysqli_real_escape_string($con, $_POST['hotelCity']);
	$web = generarURLCorecta(mysqli_real_escape_string($con, $_POST['hotelWebsite']));
	//Datos de google places
	$place_name = mysqli_real_escape_string($con, $_POST['place_name']);
	$place_country = mysqli_real_escape_string($con, $_POST['place_country']);
	$place_adm_area = mysqli_real_escape_string($con, $_POST['place_adm_area']);
	$lat = round(mysqli_real_escape_string($con, $_POST['lat']), 7);
	$lng = round(mysqli_real_escape_string($con, $_POST['lng']), 7);
	$place_id = mysqli_real_escape_string($con, $_POST['place_id']);
	$country_name = mysqli_real_escape_string($con, $_POST['country_name']);

	//Inicialmente el hotel tiene el email de la cadena, se podrá modificar en su profile
	if (!empty($_SESSION['c_logueado'])){
		$tipo = 'cad';
		$email_cadena = obtenerEmail($_SESSION['c_logueado'], $tipo);
	}else{
		$tipo = 'hot';
		$email_cadena = obtenerEmail($_SESSION['h_logueado'], $tipo);
	}
    if (!empty($_SESSION['c_logueado'])){
        $id_cadena = $_SESSION['c_logueado'];
    }else if(!empty($_SESSION['id_cadena'])){
        $id_cadena = $_SESSION['id_cadena'];
    }

	$id_hotel = insertarHotel($name, $city, $web, $street, $place_name, $place_country, $place_adm_area,
	$lat, $lng, $place_id, '', $email_cadena, '', $id_cadena, $country_name);

	$brand = getHotelBrand($id_hotel);

	$_SESSION['brand_id'] = $brand['id'];

	if (!hotelDeCadena($_SESSION['h_logueado'])){ // Solo la primera vez
		// Crear cadena nueva si no existe
		$id_cadena=$_SESSION['id_cadena']=$_SESSION['c_logueado']=crearCadena($_SESSION['h_logueado'], $_SESSION['permisos']);
		// Vincular hotel logueado ya existente a la cadena
		if(vincularCadenaHotel($id_cadena, $_SESSION['h_logueado'])){
			// Pasar los puntos del hotel a la cadena

		} else {

			//Redireccionamos para borrar el $_POST + msg de error
			header('Location: /'.$urlTree['chain-management'].'/');

			exit;
		}
//		//Una vez vinculado el primer hotel vinculamos el nuevo hotel creado
        vincularCadenaHotel($id_cadena, $id_hotel);
	} else {

		// Vincular hotel nuevo a la cadena
		if(!vincularCadenaHotel($id_cadena, $id_hotel)){
			//Redireccionamos para borrar el $_POST + msg de error
			header('Location: /'.$urlTree['chain-management'].'/');

			exit;
		}
	}
	// Pasamos datos de cadena a hotel
	datosCadenaHotel($id_hotel);

	// We Log In as the new chain and with the Hotel in order to show hotel_suggest 
	$_SESSION['chain'] = array();
	$_SESSION['chain']['id'] = $id_cadena;

	$row = obtenerDatosCadenaLogin($id_cadena);

	if(isset($row)) {
		$result = loguearHotel(array_get($row, '0.id_hotel'), 1);
	}
	
	$_SESSION['chain']['brand_id'] = $result['hotel']['parent_brand_id'];

	//Redireccionamos para borrar el $_POST + msg
	header('Location: /'.$urlTree['chain-management'].'/?msg=2017');

	exit;

}

if(!empty($_SESSION['c_logueado'])){
	$arrayHoteles = obtenerHotelesCadena($_SESSION['c_logueado']);
}


/*echo '<pre>';
print_r($arrayHoteles);
echo '</pre>';*/
?>
