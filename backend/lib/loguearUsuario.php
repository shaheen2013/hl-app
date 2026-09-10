<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

include_once LIB.'idiomas.php';

//Loguear usuario
function loguearUsuario ($id)
{
	$_SESSION['u_logueado'] = $id;
	
	$sqlObtenerDatos = 'SELECT email, lang FROM users WHERE id="'.$id.'"';
	$rowObtenerDatos = mysqli_query (conectar(), $sqlObtenerDatos);
	$rsObtenerDatos = mysqli_fetch_array($rowObtenerDatos);
	liberar ($rowObtenerDatos);
	
	//Cargamos los datos necesarios del usuario en variables
	$email = $rsObtenerDatos['email'];
	
	//Cargamos el userLang en $_SESSION
	if(!empty($_SESSION['userLang']) || $rsObtenerDatos['lang']=='' )
	{
		$_SESSION['userLang'] = mirarIdiomaPlataforma($_SESSION['userLang']);
	}else{
		$_SESSION['userLang'] = mirarIdiomaPlataforma($rsObtenerDatos['lang']);
	}
	
	//De momento los usarios solo pueden ver las pantallas de Referral Tool
	$_SESSION['refTool']=true;
}

//Devuelve la pantalla que se muestra tras el login
function pantallaLogin($id_usuario='')
{
	$pantalla = 'user-ofertas';//Listado de cupones
	return $pantalla;
}
?>