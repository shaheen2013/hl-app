<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//contenido solo disponible si logueado
include LIB.'logueado.php';
hotelStaffLanding ();// Si no esta logueado lo manda a la landing

// For front purposes
$currentSubPage = "hotel-profile-datos-landing";

include_once LIB.'subirArchivos.php';
include_once LIB.'sanitize.php';
include_once LIB.'obtenerdatosHotel.php';
include_once LIB.'borrarSession.php';

// FX para mirar si todos los langs estan rellenados
function todosLangsRellenados($idiomasHotel)
{
	foreach ($idiomasHotel as $idioma){
		if(empty($_SESSION[$idioma['lang'].'-landing']['tagline'])){
			return false;
		}
	}
	return true;
}

function camposObligatoriosLanding($arrayDatosHotel, $idiomasHotel)
{
	if(!todosLangsRellenados($idiomasHotel) || empty($arrayDatosHotel['fotoBg']) )
	{
		return false;
	}else{
		return true;
	}
}

if (!empty ($_POST['hotelConfirmButton']) )
{

	// ---------- fotoBg Landing-------------------
	//Subir fotoBg
	$errorImg[0]='200';
	if ($_FILES['landingBg']['name']!=''){
		//crear carpeta
		$rutaBg = DIR_IMG_FICHA_HOTEL.$_SESSION['h_logueado']."/fotoBg/";
		if (!file_exists($rutaBg))
		{
			 mkdir($rutaBg, 0777, true);
		}
		// Sanitizamos el nombre
		$_FILES['landingBg']['name'] = archivoExtension($_FILES['landingBg']['name']);
		$fotoBg = $_FILES['landingBg']['name'];
		$maxSize=10485760; //10MB
		// Subimos la img (tb la reduce de tamaño)
		$errorImg = subirImagen($rutaBg, $_FILES['landingBg'], $maxSize);
		if($errorImg[0]=='200'){//Si la imagen tiene extension y tamaño correctos
			// borramos del disco el logo anterior
			$imgAnterior = obtenerImgBgFull($_SESSION['h_logueado']);
			if($imgAnterior != $fotoBg){
				borrarBgAnterior($_SESSION['h_logueado'], $imgAnterior);
			}
			actualizarFotoLanding($fotoBg);
		}else{
			// La imagen no tiene extension correcta o es demasiado grande
			//echo 'error IMG';
		}
	}else{
		$fotoBg = obtenerImgBg($_SESSION['h_logueado']);
	}

	
	if($errorImg[0]=='400')
	{
		if($errorImg[1]=='2'){
			$ok = array (false, '4032');//Extensión de img incorrecta
		}else if($errorImg[1]=='3'){
			$ok = array (false, '4033');//Img excede el tamaño max permitido
		}
	}else if($errorImg[0]=='200'){
		$ok = array (true, '2007');
	}
	else{
        $ok = array (false, '4067');//Error al recibir los datos
    }
}

$background_img = obtenerImgBg($_SESSION['h_logueado']);//hotel-ficha/id_h/fotoBg, img actual

?>