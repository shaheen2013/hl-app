<?php
include_once 'librerias.php';// Librerias básicas
// Restringir ips que pueden acceder

// Restringir ips que pueden acceder
include_once RUTA_DIR.LIB.'check_access.php';
checkIpAccess('hcdo', $_SERVER['REMOTE_ADDR']);

include_once RUTA_DIR.LIB.'subirArchivos.php';
include_once RUTA_DIR.LIB.'algoritmoPuntos.php';
include_once RUTA_DIR.LIB.'ofertaAcciones.php'; // Guardable / Publicable
include_once RUTA_DIR.LIB.'convertirDivisas.php';
include_once RUTA_DIR.LIB.'dolaresPuntos.php';
include_once RUTA_DIR.LIB.'fecha.php';


function actualizarPuntosAdq()
{
	if ($_SESSION['offerMethod']=='adq'){
		if (!empty($_SESSION['offerMethod']) && !empty($_SESSION['offertype']) ){
			// Calculamos los puntos
			if(!isset($_SESSION['inicio'])){
				$inicio = 0;
			}else{
				$inicio = girarFecha($_SESSION['inicio']);
			}
			if(!isset($_SESSION['fin'])){
				$fin = '00-00-0000';
			}else{
				$fin = girarFecha($_SESSION['fin']);
			}
			$_SESSION['puntos'] = calcularPuntosAdq($_SESSION['h_logueado'], $_SESSION['requerimientos'], $inicio, $fin, $_SESSION['offertype'], $_SESSION['descuento']);
			return ($_SESSION['puntos']);
		}
	}else{
		if (!empty($_SESSION['puntos'])){
			return ($_SESSION['puntos']);
		}else{
			return 'XXX';
		}
	}
}

//-Pantalla----------------------------------------------------
// Descuento --------------------------------------------------
if(isset($_POST['descuento']) && ($_POST['descuento']>=0) && is_numeric($_POST['descuento']) && $_POST['descuento']<=100)
{
	// Debe ser numerico, entre 0 y 100
	$_SESSION['descuento']= $_POST['descuento'];
	$_SESSION['puntos'] = actualizarPuntosAdq();
	echo $_SESSION['puntos'];
}else if(isset($_POST['descuento'])){
	// Si introduce un descuento no valido mostramos los puntos sin actualizar
	echo $_SESSION['puntos'];
}

//  Nombre descriptivo de la oferta  --------------------------
if(isset($_POST['name']))
{
	$nameLimpio= strip_tags($_POST['name']);
	if (strlen($nameLimpio)<1)
	{
		$_SESSION[$_SESSION['lang']]['nombre'] = '';
	}else{
		$_SESSION[$_SESSION['lang']]['nombre'] = $_POST['name'];
	}
}

// Fechas de inicio y fin de la oferta  -----------------------
if(!empty($_POST['inicio']))
{
	$_SESSION['inicio']= $_POST['inicio'];
	$_SESSION['puntos'] = actualizarPuntosAdq();
	echo $_SESSION['puntos'];
}
if(!empty($_POST['inicio_verif']))
{
	//Mirar si esta en periodo hotel cerrado
	$inicio= $_POST['inicio_verif'];
	if (!empty($_SESSION['fin']) && hotelCerradoFechaInicioFin($_SESSION['h_logueado'], $inicio,$_SESSION['fin']))
	{
		//Hotel cerrado
		include RUTA_DIR.LANG.'en/feedback.php';
		echo '<i class="fa fa-ban fa-4x blanco pull-left" ></i>
		<ul><li >'.$msg4035.'</li></ul>';
		$_SESSION['hotel_cerrado']=1;
	}else{
		$_SESSION['hotel_cerrado']=0;
	}
}
if(!empty($_POST['inicio_correcta']))
{
	//Mirar si la fecha inicio es superior a la fecha de fin
	$inicio= $_POST['inicio_correcta'];
	if(!empty($_SESSION['offerMethod']) && $_SESSION['offerMethod']!='ref' 
	&& !empty($_SESSION['fin']) && girarFecha($inicio) > girarFecha($_SESSION['fin']) ){
		//Fecha inicio superior fecha fin
		include RUTA_DIR.LANG.'en/feedback.php';
		echo '<i class="fa fa-ban fa-4x blanco pull-left" ></i>
		<ul><li >'.$msg4036.'</li></ul>';
		$_SESSION['fecha_incorrecta']=1;
	}else{
		$_SESSION['fecha_incorrecta']=0;
	}
}
if(!empty($_POST['fin']))
{
	$fin= $_POST['fin'];
	$_SESSION['fin']= $fin;
	$_SESSION['puntos'] = actualizarPuntosAdq();
	echo $_SESSION['puntos'];
}
if(!empty($_POST['fin_verif']))
{
	//Mirar si esta en periodo hotel cerrado
	$fin= $_POST['fin_verif'];
	if (!empty($_SESSION['inicio']) && hotelCerradoFechaInicioFin($_SESSION['h_logueado'], $_SESSION['inicio'],$fin))
	{
		//Hotel cerrado
		include RUTA_DIR.LANG.'en/feedback.php';
		echo '<i class="fa fa-ban fa-4x blanco pull-left" ></i>
		<ul><li >'.$msg4035.'</li></ul>';
		$_SESSION['hotel_cerrado']=1;
	}else{
		$_SESSION['hotel_cerrado']=0;
	}
}
if(!empty($_POST['fin_correcta']))
{
	//Mirar si la fecha fin es inferior a la fecha inicio
	$fin= $_POST['fin_correcta'];
	if(!empty($_SESSION['inicio']) && girarFecha($fin) < girarFecha($_SESSION['inicio']) ){
		//Fecha inicio superior fecha fin
		include RUTA_DIR.LANG.'en/feedback.php';
		echo '<i class="fa fa-ban fa-4x blanco pull-left" ></i>
		<ul><li >'.$msg4036.'</li></ul>';
		$_SESSION['fecha_incorrecta']=1;
	}else{
		$_SESSION['fecha_incorrecta']=0;
	}
}
//  Descripcion de la oferta  ----------------------------------
if(!empty($_POST['descripcion']))
{
	$descLimpio= strip_tags($_POST['descripcion']);
	if (strlen($descLimpio)<1){
		$_SESSION[$_SESSION['lang']]['descripcion']='';
	}else{
		$_SESSION[$_SESSION['lang']]['descripcion']=$_POST['descripcion'];
	}
}

//  Condiciones de la oferta  ----------------------------------
if(!empty($_POST['condiciones']))
{
	$condLimpio= strip_tags($_POST['condiciones']);
	if (strlen($condLimpio)<1){
		$_SESSION[$_SESSION['lang']]['condiciones']='';
	}else{
		$_SESSION[$_SESSION['lang']]['condiciones']=$_POST['condiciones'];
	}
}

//Cambio de Lang
if ( !empty($_POST['lang']) )
{
	$_SESSION['lang'] = $_POST['lang'];
	$langSelected = obtenerLang($_POST['lang']);
	//Actualizamos la imagen actual y el country
	$_SESSION['flagActual'] = $langSelected['img'];
	$_SESSION['countryActual'] = $langSelected['country'];
	
	$result['lang'] = '<img src="'.BASE_PATH . DIR_IMG . 'flags/' . $langSelected['img'] .'" alt="'.$langSelected['lang'].' flag" class="pl flag-icon"> <strong>'.$langSelected['country'].'</strong>'; 
	
	if(!empty($_SESSION[$_SESSION['lang']]['nombre']))//nombre
	{
		$result['nombre'] = $_SESSION[$_SESSION['lang']]['nombre'];
	}else{
		$result['nombre'] = '';
	}
	if(!empty($_SESSION[$_SESSION['lang']]['descripcion']))//descripcion
	{
		$result['descripcion'] = $_SESSION[$_SESSION['lang']]['descripcion'];
	}else{
		$result['descripcion'] = '';
	}
	if(!empty($_SESSION[$_SESSION['lang']]['condiciones']))//Condiciones
	{
		$result['condiciones'] = $_SESSION[$_SESSION['lang']]['condiciones'];
	}else{
		$result['condiciones'] = '';
	}
	echo json_encode($result);
}
//Miramos si un lang esta OK
if(!empty($_POST['checkLang']))
{
	$lang = $_POST['langToCheck'];
	//Si vaciamos el text area siempre queda un <br>, debemos limpiarlo
	if(!empty($_SESSION[$lang]['descripcion']))
	{
		$descLimpio= strip_tags($_SESSION[$lang]['descripcion']);
		$result['desc']=1;
	}else{
		$descLimpio= '';
		$result['desc']=0;
	}
	if(!empty($_SESSION[$lang]['condiciones']))
	{
		$condLimpio= strip_tags($_SESSION[$lang]['condiciones']);
		$result['cond']=1;
	}else{
		$condLimpio= '';
		$result['cond']=0;
	}
	if(!empty($_SESSION[$lang]['nombre']))
	{
		$result['nom']=1;
	}else{
		$result['nom']=0;
	}
	if(!empty($_SESSION[$lang]['nombre']) && $_SESSION[$lang]['nombre']!='' &&
	!empty($_SESSION[$lang]['descripcion']) && $descLimpio!='' &&
	!empty($_SESSION[$lang]['condiciones']) && $condLimpio!='' )
	{
		$_SESSION[$lang]['check']=1;
		$result['code']=200;
	}else{
		$_SESSION[$lang]['check']=0;
		$result['code']=400;
	}
	echo json_encode($result);
}

//bookingEngineCode
if ( !empty($_POST['bookingEngineCode']) )
{
	//El BEC no puede tener espacios en blanco ya que se utiliza como parametro url
	$_SESSION['bookingEngineCode'] = str_replace(' ', '', $_POST['bookingEngineCode']);
}

//  Datos adicionales  -----------------------------------------
if(isset($_POST['cupo']) && ($_POST['cupo']>=0)){
	$_SESSION['cupo']= $_POST['cupo'];
	//echo $_SESSION['cupo'];
}
if(isset($_POST['coste']) && ($_POST['coste']>=0) && isset($_POST['currency']))
{
	$_SESSION['coste']= $_POST['coste'];
	$currency = $_SESSION['moneda'] = $_POST['currency'];
	if ($_SESSION['offerMethod']=='ret')
	{
		// Calculamos puntos oferta de retencion
		if($currency=='USD')
		{
			$_SESSION['puntos'] = calcularDolarPuntos($_SESSION['coste']);
		}else{
			$usd = convertirDivisas($_SESSION['coste'], $currency, 'USD');
			$usd = round($usd);
			$_SESSION['puntos'] = calcularDolarPuntos($usd);
		}
		echo $_SESSION['puntos'];
	}else{
		echo $_SESSION['puntos'];
	}	
}
if(isset($_POST['requerimientos']) && ($_POST['requerimientos']>=0))// Noches requeridas
{ 
	$_SESSION['requerimientos']= $_POST['requerimientos'];
	$_SESSION['puntos'] = actualizarPuntosAdq();
	echo $_SESSION['puntos'];
}/*else if(isset($_POST['requerimientos'])){
	echo $_SESSION['puntos'];
}*/

//----------------------------------------------
// Post que viene del boton guardar
if (!empty($_POST['guardable']) && $_POST['guardable']='check')
{
	$_SESSION['guardable'] = comprobarGuardable();
	echo $_SESSION['guardable'];
}
// Post que viene del boton publicar
if (!empty($_POST['publicable']) && $_POST['publicable']='check')
{
	$_SESSION['publicable'] = comprobarPublicable();
	echo $_SESSION['publicable'];
}
//La imagen viene en formato base64
if ( !empty($_POST['croppedImg']) )
{
	//Convertir imagen 
	//$img = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $_POST['croppedImg']));
	$img = str_replace('data:image/jpeg;base64,', '', $_POST['croppedImg']);
	$img = str_replace(' ', '+', $img);
	$data = base64_decode($img);
	//Nombre nuevo para la imgen resultante
	$_SESSION['foto'] = $fotoCropped ='cropped_' .$_SESSION['foto'];
	//Subir imagen
	file_put_contents( RUTA_DIR . $_SESSION['ruta_tmp'] . $fotoCropped, $data);
}
?>