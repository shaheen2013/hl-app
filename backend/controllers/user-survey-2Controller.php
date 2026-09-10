<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//Contenido solo visible si logueado
include LIB.'logueado.php';
userLanding ();// Si no esta logueado lo manda a la landing

include_once LIB.'edad.php';
include_once LIB.'agregarPuntos.php';

if(!empty($_GET['id'])){
	$id_encuesta = mysqli_real_escape_string(conectar(), $_GET['id']);
	$arrayDatosHotel = obtenerNombreHotel($id_encuesta);
}
//Mirar si la encuesta ya esta hecha
if (encuestaHecha($id_encuesta) && encuestaSinShare($id_encuesta)){
	//encuesta hecha y sin share, lo mandamos a share
	header('Location: /'.$urlTree['user-survey-3'].'/?id='.$id_encuesta.'');
}

if (!empty ($_POST['save-survey'])){
	$rating = $_SESSION['rating'] = mysqli_real_escape_string(conectar(), $_POST['rating']);
	$positiveComment = mysqli_real_escape_string(conectar(), $_POST['positiveComment']);
	$negativeComment = mysqli_real_escape_string(conectar(), $_POST['negativeComment']);
	$id_encuesta = mysqli_real_escape_string(conectar(), $_POST['id']);

	// nos devuelve array con fecha de nacimiento y sexo
	$datosUsuario = obtenerDatosUsuario ($_SESSION['u_logueado']);
	//calculamos edad
	if ($datosUsuario['fecha_nacimiento']==0){
		$edad = 0;
	}else{
		$edad = calcular_edad ($datosUsuario['fecha_nacimiento']);
	}
	$id_hotel = obtenerIdHotel($id_encuesta);

	if (guardarEncuesta ($id_encuesta, $_SESSION['u_logueado'], $rating, $positiveComment, $negativeComment, $datosUsuario['sexo'], $edad)){
		//actualizar rating hotel
		actualizarRatingHotel($id_hotel, $rating);
		$puntos = obtenerPuntos('survey');
		agregarPuntosHl($_SESSION['u_logueado'], $puntos, 1, $id_hotel); // Puntos encuesta
		header('Location: /'.$urlTree['user-survey-3'].'/?id='.$id_encuesta.'');
	}else{
		// Encuesta ya realizada o que no es de este usuario
		header('Location: /'.$urlTree['user-survey-list'].'');
	}
}


?>