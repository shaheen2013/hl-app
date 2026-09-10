<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

// Acciones para crear oferta

include_once RUTA_DIR.MODEL.'ofertaAccionesModel.php';
include_once RUTA_DIR.LIB.'obtenerdatosHotel.php';
include_once RUTA_DIR.LIB.'fecha.php';

//Si un solo idioma ya tiene el campos 'nombre' ya es guardable
function guardableIdioma()
{
	$langs = obtenerIdStringLangsHotel($_SESSION['h_logueado']);
	foreach ($langs as $lang)
	{
		if( !empty($_SESSION[$lang]['nombre']) )
		{
			return true;
		}	
	}
	return false;
}

function comprobarGuardable(){
	if (!empty($_SESSION['offerMethod']) && $_SESSION['offerMethod']=='ref' && guardableIdioma()){
		//Si es una oferta de referral y al menos tiene un nambre de oferta en algún idioma
		$_SESSION['guardable']=1;
		return 1;
	}else if(!empty($_SESSION['offerMethod'])&& guardableIdioma() && spentAllotmentNights() ){
		// Si tiene el nombre, el offerMethod y los campos spent Allotment Nights válidos, es guardable
		$_SESSION['guardable']=1;
		return 1;
	}else{
		$_SESSION['guardable']=0;
		return 0;
	}
}

//Función para mirar si se crea una oferta sin dias de validez dentro del
// periodo que el hotel tiene abierto
function hotelCerradoFechaInicioFin($id_hotel, $fecha_inicio, $fecha_fin)
{
	$row = hotelCerradoFechaInicioFinBDFX($id_hotel);
	$explodeFechaInicio = explode ('-', $fecha_inicio);
	$explodeFechaFin = explode ('-', $fecha_fin);
	$hotelCerrado = true;
	if($explodeFechaInicio[2]==$explodeFechaFin[2]){
		//Mismo año
		$i = (int)($explodeFechaInicio[1]);//mes inicio
		$mes_fin =(int)($explodeFechaFin[1]);//mes fin
		while($i <= $mes_fin){
			if($row['mes'.$i]!='0'){
				$hotelCerrado = false;
				return $hotelCerrado;
			}
			$i++;
		}
	}else{
		//año distinto
		$i = (int)($explodeFechaInicio[1]);//mes inicio
		$mes_fin = (int)($explodeFechaFin[1]);//mes fin
		while($i <= '12'){
			if($row['mes'.$i]!='0'){
				$hotelCerrado = false;
				return $hotelCerrado;
			}
			$i++;
		}
		$i=1;
		while($i <= $mes_fin){
			if($row['mes'.$i]!='0'){
				$hotelCerrado = false;
				return $hotelCerrado;
			}
			$i++;
		}
	}
	return $hotelCerrado;	
}

// Todos los idiomas deben estar totalmente rellenados
function publicableIdioma()
{
	$langs = obtenerIdStringLangsHotel($_SESSION['h_logueado']);
	$totalLangsHotel = count($langs);
	$langsOk = 0;
	foreach ($langs as $lang)
	{
		//Si vaciamos el text area siempre queda un <br>, debemos limpiarlo
		if(!empty($_SESSION[$lang]['descripcion']) ){ 
			$descLimpio = strip_tags($_SESSION[$lang]['descripcion']);
		}else{
			$descLimpio = '';
		}
		if(!empty($_SESSION[$lang]['condiciones']) ){ 
			$condLimpio = strip_tags($_SESSION[$lang]['condiciones']);
		}else{
			$condLimpio = '';
		}
		if( !empty($_SESSION[$lang]['nombre']) && !empty($descLimpio) && !empty($condLimpio) )
		{
			$langsOk++;
		}	
	}
	if($langsOk == $totalLangsHotel){
		return true;
	}else{
		return false;
	}
}

function comprobarPublicable()
{
	if (!empty($_SESSION['offerMethod']) && $_SESSION['offerMethod']=='ref'){
		//Oferta de referral
		if (empty($_SESSION['offerMethod'])|| empty($_SESSION['offertype'])	|| empty($_SESSION['foto']) 
		|| empty($_SESSION['bookingEngineCode']) || !publicableIdioma() ) 
		{
			$_SESSION['publicable']=0;
			return 0;
		}else{
			$_SESSION['publicable']=1;
			return 1;
		}
	}else{
		//Oferta de adq/ret
		$row = comprobarPublicableBDFX();
		if (!empty($_SESSION['offerMethod'])&& $_SESSION['offerMethod']=='adq' && 
		($row['rating']==0 || $row['max_rango']==0 || $row['estrellas']==0 && 
		($row['jan']==0 && $row['feb']==0 && $row['mar']==0 && $row['apr']==0 && $row['may']==0 && $row['jun']==0 && $row['jul']==0 && $row['ago']==0 && $row['sep']==0 && $row['oct']==0 && $row['nov']==0 && $row['dece']==0 ))  ){
			$_SESSION['publicable']=0;
			return 0;
		}else if (empty($_SESSION['offerMethod'])|| empty($_SESSION['offertype'])
		|| empty($_SESSION['foto']) || empty($_SESSION['inicio']) || !publicableIdioma() ){
			$_SESSION['publicable']=0;
			return 0;
		}else if (!empty($_SESSION['offerMethod'])&& $_SESSION['offerMethod']=='ret' && empty($_SESSION['coste'])){
			$_SESSION['publicable']=0;
			return 0;
		}else if(!empty($_SESSION['offertype'])&& $_SESSION['offertype']=='chk' && empty($_SESSION['category']) && empty($_SESSION['subCategory'])){
			$_SESSION['publicable']=0;
			return 0;	
		}else if(!empty($_SESSION['offertype'])&& $_SESSION['offertype']=='des' && empty($_SESSION['descuento'])){
			$_SESSION['publicable']=0;
			return 0;
		}else if(!empty($_SESSION['fin']) && 
		hotelCerradoFechaInicioFin($_SESSION['h_logueado'], $_SESSION['inicio'], $_SESSION['fin'])){
			//Hotel cerrado 
			$_SESSION['publicable']=0;
			return 0;
		}else if(!empty($_SESSION['inicio']) && !empty($_SESSION['fin']) && girarFecha($_SESSION['inicio'])>girarFecha($_SESSION['fin']) && $_SESSION['fin']!='00-00-0000'){
			//fecha de inicio mayor que fecha fin
			$_SESSION['publicable']=0;
			return 0;
		}else if(empty($_SESSION['puntos'])){
			$_SESSION['publicable']=0;
			return 0;
		}else{
			$_SESSION['publicable']=1;
			return 1;
		}
	}
}

//Verifica si los campos 'Minimum spend', 'Allotment', 'Nights required' estan bien rellenados:
//son numericos y mayores o igual que 0
function spentAllotmentNights()
{
	if( ($_SESSION['coste']>=0 || $_SESSION['coste']=='') && ($_SESSION['cupo']>=0 || $_SESSION['cupo']=='')
	&& ($_SESSION['requerimientos']>=0 || $_SESSION['requerimientos']=='') ){
		return true;
	}else{
		return false;
	}	
}
?>