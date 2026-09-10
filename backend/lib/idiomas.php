<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//include_once 'obtenerDatosUsuario.php';
//include_once 'obtenerdatosHotel.php';

//--------------------------------------------------------------------------------------//
// FX para mirar si tenemos la plataforma en un idioma (System), 
// Si lo tenemos, actualizamos $_SESSION['userLang'] 
// Si no lo tenemos, devolvemos el idioma por defecto (en)
//--------------------------------------------------------------------------------------//
function mirarIdiomaPlataforma($idioma)
{
	//Ponemos el idioma en minúsculas
	$idioma = strtolower($idioma);
	
	//Idiomas en los que esta la plataforma
	$idiomas = array(
		'en' => 'en',
		'es' => 'es',
		'de' => 'de',
		'fr' => 'fr',
		'ca' => 'ca',
		'it' => 'it',
        'zh' => 'zh',
		'bg' => 'bg'
	);	
	//Idioma por defecto
	$default = 'en';
	
	//Ojo si los indices son numéricos
	if ( !array_search($idioma, $idiomas) )
	{
		return $default;
	}else{
		return $idioma;
	}
}
?>