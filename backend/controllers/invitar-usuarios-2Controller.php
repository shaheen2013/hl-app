<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//Contenido solo visible si logueado
include LIB.'logueado.php';
hotelStaffLanding ();// Si no esta logueado lo manda a la landing

include_once LIB . 'paginacion2.php';
include_once LIB . 'fecha.php';
include_once LIB . 'subirArchivos.php';
include_once LIB . 'sanitize.php';
include_once LIB . 'obtenerdatosHotel.php';
include_once LIB . 'invitar-usuarios-2-lib.php';
include_once LIB . 'borrarSession.php';
include_once LIB . 'paramsUrl.php';
include_once LIB . 'seguridadHotel.php';

// Miramos si el hotel tiene un solo producto (MK, RF, LY) o tiene varios (0)
$prod = mirarUnProductoHotel();

$ready = false;
//Inicializamos $arrayEmailPuntos
$arrayEmailPuntos = array();

$nombre_archivo = '';

// Borrar un email uno por uno por id
if( !empty($_GET['del']) && $_GET['del']!='all' )
{
	$result = borrarEmailInvitacion($_GET['del'], $_SESSION['h_logueado']);
	// Feedback
	if($result['code']=='2031')
	{
		$ok = array (true, $result['code']);
	}else{
		$ok = array (false, $result['code']);
	}
}

// Borrar todos los emails inválidos de la lista dir2
if( !empty($_GET['del']) && $_GET['del']=='all' && !empty($url['dir2']) && $url['dir2']!='/')
{
	$result = borrarTodosEmailsInvalidos($url['dir2'], $_SESSION['h_logueado']);
	// Feedback
	if($result['code']=='2032')
	{
		$ok = array (true, $result['code']);
	}else{
		$ok = array (false, $result['code']);
	}
}

function hayDatosEnBlanco($array){
	foreach($array as $fila){
		if($_SESSION['permisos']['LY'] == '1'){
			//quitamos el email valido, no cuenta como dato en blanco
			unset($fila[6]);
		}else{
			//quitamos los campos de RF, en RF siempre estrán en blanco
			unset($fila[3]);
			unset($fila[4]);
			unset($fila[5]);
			unset($fila[6]);//email valido
		}
		foreach($fila as $valor){
			if($valor=='0' || $valor==''){
				return true;
				break;
			}
		}
	}
	return false;
}

//------------------------------------------------------
// Esta guardando una lista
//------------------------------------------------------
if ( !empty($_POST['saveUsers']) )
{ 
	// Guardar lista de usuarios
	$listName = string_sanitize($_POST['listName']);
	$ruta = $_POST['ruta'];
	$archivo = $_POST['archivo'];

	// Orden en el que debe ir el array
	//(campos a ordenar: nombre, email, idioma, puntos, total spent, total nights (6))
	$o1 = $_POST['field-1'];
	$o2 = $_POST['field-2'];
	$o3 = $_POST['field-3'];
	$o4 = $_POST['field-4'];
	$o5 = $_POST['field-5'];
	$o6 = $_POST['field-6'];
	
	if($_SESSION['permisos']['LY'] == '1'){
		$orden = array($o1, $o2, $o3, $o4, $o5, $o6);
	}else{
		$orden = array($o1, $o2, $o3);
	}
	$orden = array_flip($orden);
	//asort($orden); // Ordenar array
	
	if(!empty($_POST['addToList']) && $_POST['addToList']!=0 )
	{
		// Esta añadiendo a lista existente
		$addToList = $_POST['addToList'];
	}else{
		$addToList = '0';
	}
	
	if (empty($ruta) && empty($nombre))
	{
		// no es un archivo
		guardarListaUsuarios($_SESSION['h_logueado'],$listName, $_SESSION['arrayEmailPuntos'], $orden, $addToList);
	}else{
		
		//Creamos una cadena para seguridad del webservice
		$wsSec = seguridadWSHotel($_SESSION['h_logueado']);
		
		//es un archivo
		//ejecutar script y tratar archivo asincronamente
		$ch = curl_init();
 		
		$urlCurl = BASE_PATH.'lib/webservices/invitar-usuarios-2-ws.php/?archivo='.$archivo.'&nombre='.$listName.'&f1='.$o1.'&f2='.$o2.'&f3='.$o3.'&addToList='.$addToList;
		if($_SESSION['permisos']['LY'] == '1'){
			$urlCurl .= '&f4='.$o4.'&f5='.$o5.'&f6='.$o6;
		}
		$urlCurl .= '&id_hotel='.$_SESSION['h_logueado'].'&sc='.$wsSec;
		//$urlCurl = BASE_PATH.'lib/webservices/invitar-usuarios-2-ws.php/';
		//echo $urlCurl;
	
		curl_setopt($ch, CURLOPT_URL, $urlCurl);
		//curl_setopt($ch, CURLOPT_POST, 1);
		//curl_setopt($ch, CURLOPT_POSTFIELDS,"postvar1=value1&postvar2=value2&postvar3=value3");	
		curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 3);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_exec($ch);
		curl_close($ch);
	}
	// Borramos datos de session
	borrarSessionInvitarUsuarios();
	// Redireccionamos
	header('Location: '.$urlTree['invite-users-drafts'].'/?save=ok');
}

$hayEmailInvalido = false;
$hayDatosEnBlanco = false;

//------------------------------------------------------
// Esta importando usuarios a lista, 
// debe elegir a que campo pertenece cada columna
//------------------------------------------------------
if (!empty($_POST['inviteList']) && empty($_POST['inviteUsersFile']) )
{
	//Usuario ha introducido una lista manualmente o por copia/pega
	// Tratamos la lista de usuarios
	$idiomaDefecto = $_SESSION['userLang']; //idioma del browser

	// explode salto de linea
	$lineas = explode (chr(10), $_POST['inviteList']);
	$m = count($lineas);
	$i = 0;
	while ($i < $m)
	{
		$result = separarCampos($lineas[$i]);
		$explodeEmailPuntos = $result[0];
	
		if( $result[1] != '200' ){
			// Lista mal formada
			$ok = array(false, $result[1]);
			//Inicializamos array para evitar notices
			$arrayEmailPuntos = $result[0];
			break;
		}
		
		if (!empty($explodeEmailPuntos[0]))
		{
			$arrayEmailPuntos[$i][0]=mysqli_real_escape_string(conectar(), $explodeEmailPuntos[0]);
			if (!empty($explodeEmailPuntos[1]) && $explodeEmailPuntos[1]!="")
			{
				$arrayEmailPuntos[$i][1]=mysqli_real_escape_string(conectar(), $explodeEmailPuntos[1]);
			}else{
				$arrayEmailPuntos[$i][1] = 0;
				$hayDatosEnBlanco = true;
			}
			if (!empty($explodeEmailPuntos[2]) && $explodeEmailPuntos[2]!="")
			{
				$arrayEmailPuntos[$i][2]=mysqli_real_escape_string(conectar(), $explodeEmailPuntos[2]);
			}else{
				$arrayEmailPuntos[$i][2] = 0;
				$hayDatosEnBlanco = true;
			}
			if (!empty($explodeEmailPuntos[3]) && $explodeEmailPuntos[3]!="")
			{
				$arrayEmailPuntos[$i][3]=mysqli_real_escape_string(conectar(), $explodeEmailPuntos[3]);
			}else{
				$arrayEmailPuntos[$i][3] = 0;
				$hayDatosEnBlanco = true;
			}
			if (!empty($explodeEmailPuntos[4]) && $explodeEmailPuntos[4]!="")
			{
				$arrayEmailPuntos[$i][4]=mysqli_real_escape_string(conectar(), $explodeEmailPuntos[4]);
			}else{
				$arrayEmailPuntos[$i][4] = 0;
				$hayDatosEnBlanco = true;
			}
			if (!empty($explodeEmailPuntos[5]) && $explodeEmailPuntos[5]!="")
			{
				$arrayEmailPuntos[$i][5] =
				mysqli_real_escape_string(conectar(), $explodeEmailPuntos[5]);
			}else{
				$arrayEmailPuntos[$i][5] = 0;
				$hayDatosEnBlanco = true;
			}
			// Inicialmente no sabemos que campo es email, asi que lo damos por válido
			$arrayEmailPuntos[$i][] = 1;
		}
		$i++;
	}
	
	$_SESSION['arrayEmailPuntos'] = $arrayEmailPuntos;

	// creamos un temp del array con 2 campos para que el usuario elija que es cada columna
	$arrayTemp[] = $arrayEmailPuntos[0];
	if (!empty($arrayEmailPuntos[1]))
	{
		$arrayTemp[] = $arrayEmailPuntos[1];
	}
	// Inicializamos la paginación default
	$_SESSION['paginas']=1;
	$_SESSION['primeraPagina']=1;
	$_SESSION['ultimaPagina']=0;
	
	$arrayEmailPuntos = /*$_SESSION['arrayTemp'] =*/ $arrayTemp;
	$ruta_archivo = $nombre_archivo = 0; // No es un archivo -> no tiene ruta
	
}else if (!empty($_FILES['inviteUsersFile']) ){
	// Usuario ha subido archivo
	// crear $arrayEmailPuntos temporal con 2 lineas
	$arrayEmailPuntos = array();
	if(!empty($_FILES['inviteUsersFile']['tmp_name'])){
		$fp = fopen($_FILES['inviteUsersFile']['tmp_name'], 'r');// abrimos archivo 
		$i=0;
		while (!feof($fp) && $i<2)
		{
			$line = fgets($fp, 2048);
			//$explodeEmailPuntos = explode(chr(9), $line);
			
			$result = separarCampos($line);
			$explodeEmailPuntos = $result[0];
			if( $result[1] != 200 ){
				// Lista mal formada
				$ok = array(false, $result[1]);
				//Inicializamos array para evitar notices
				$arrayEmailPuntos = $result[0];
				break;
			}

			$arrayEmailPuntos[$i][0]=$explodeEmailPuntos[0];
			$arrayEmailPuntos[$i][1]=$explodeEmailPuntos[1];
			!empty($explodeEmailPuntos[2]) ? $arrayEmailPuntos[$i][2]=$explodeEmailPuntos[2] : $arrayEmailPuntos[$i][2]='';
			!empty($explodeEmailPuntos[3]) ? $arrayEmailPuntos[$i][3]=$explodeEmailPuntos[3] : $arrayEmailPuntos[$i][3]='';
			!empty($explodeEmailPuntos[4]) ? $arrayEmailPuntos[$i][4]=$explodeEmailPuntos[4] : $arrayEmailPuntos[$i][4]='';
			!empty($explodeEmailPuntos[5]) ? $arrayEmailPuntos[$i][5]=$explodeEmailPuntos[5] : $arrayEmailPuntos[$i][5]='';
			$arrayEmailPuntos[$i][6]=1;
			$i++;
			
		}
		fclose($fp);// cerramos archivo 
		
		// Subir archivo al servidor
		$ruta_archivo = DIR_FILES_HOTEL . $_SESSION['h_logueado'] . '/temp';
		$_FILES['inviteUsersFile']['name'] = archivoExtension($_FILES['inviteUsersFile']['name']);
		$nombre_archivo = $_FILES['inviteUsersFile']['name'];
		if(!file_exists($ruta_archivo))
		{
			mkdir ($ruta_archivo, 0777, true);
		}
		subirArchivo($_FILES['inviteUsersFile'], $ruta_archivo, $nombre_archivo);
	}else{
		//No ha subido ninguna lista, redireccionamos a la pantalla anterior
		header('Location: /'.$urlTree['invitar-usuarios'].'/?error=4052');
	}
	// Inicializamos la paginación default
	$_SESSION['paginas']=1;
	$_SESSION['primeraPagina']=1;
	$_SESSION['ultimaPagina']=0;	
}
// -----------------------------------------------------------------
// FILTROS URL 
// -----------------------------------------------------------------
// Array con todos los parametros permitidos en esta pantalla
if($_SESSION['permisos']['LY'] == '1'){
	$arrayWheres = array ( // contiene todos los WHERES para la búsqueda SQL
		'noLy' => '', 		// no se le ha mandado email de Loyalty
		'noRf' => '',		// no se le ha mandado email de referral
		'noMail' => '',		// no se le ha mandado ningún mail
		'lang' => '',		// lang del usuario
		'points1' => '',	// puntos desde
		'points2' => '',	// points hasta
		'nights1' => '',	// noches desde
		'nights2' => '',	// noches hasta
		'spent1' => '',		// total spent desde
		'spent2' => ''		// total spent hasta
	);
}else{
	$arrayWheres = array ( // contiene todos los WHERES para la búsqueda SQL
		'noRf' => '',	// no se le ha mandado email de referral
		'noMail' => '',	// no se le ha mandado ningún mail
		'lang' => ''	// lang del usuario
	);		
}

// Creamos array con los filtros para el WHERE de la SQL. 
// Solo contiene filtros permitidos en $arrayWheres, el resto son ignorados
if(!empty($_POST['formFilters'])){
	$filtros = crearArrayFiltros($_POST, $arrayWheres, 1);
}else{
	$filtros = $arrayWheres;
}


//Feedback filtros: (pueden ir combinados entre sí)
// points2 menor que points1
if ( (!empty($filtros['points1'])) && (!empty($filtros['points2'])) &&
		 $filtros['points2'] < $filtros['points1'])
{
	!isset($ok)? $ok = array(false, '4049'): $ok[] = '4049';
}
// nights2 menor que nights1
if ( (!empty($filtros['nights1'])) && (!empty($filtros['nights2'])) &&
		$filtros['nights2'] < $filtros['nights1'])
{
	!isset($ok)? $ok = array(false, '4050'): $ok[] = '4050';
}
// spent2 menor que spent1
if ( (!empty($filtros['spent1'])) && (!empty($filtros['spent2'])) &&
		$filtros['spent2'] < $filtros['spent1'])
{
	!isset($ok)? $ok = array(false, '4051'): $ok[] = '4051';
}
// -----------------------------------------------------------------
// Fin :: FILTROS URL 
// -----------------------------------------------------------------


//------------------------------------------------------
// Viene de una lista guardada (invite-users-drafts)
//------------------------------------------------------
if(!empty($url['dir2']) && $url['dir2']!='/')
{
	$id_lista = $url['dir2'];
	$itemsPage = 100;
	if(isset($_GET['email']) && $_GET['email']=='0' ){
		$incorrectEmails = 0;
	/*}else if(!empty($_GET['email']) && $_GET['email']=='1' ){
		$incorrectEmails = 1;*/
	}else{
		$incorrectEmails = NULL;
	}
	
	$result = obtenerLista($_SESSION['h_logueado'], $id_lista, $itemsPage, $pagina, $incorrectEmails, $filtros);
	//Marcar los emails de $result como marcado. Para saber cuales deben enviarse. El resto debe tener 0
	// Por ajax se puede modificar uno por uno (se guarda en BD)
	marcarResult($result['sqlFiltros'], $id_lista);
	
	$arrayEmailPuntos = $result['list'];
	$nombreLista = obtenerNombreLista($_SESSION['h_logueado'], $id_lista);
	
	$total_filas = $result['total'];
	
	// Si algún dato esta en blanco mostramos warning / error
	if (hayDatosEnBlanco($arrayEmailPuntos))
	{
		!isset($ok)? $ok = array(false, '3006'): $ok[] = '3006';
	}
	
	// Emails válidos Y marcados manualmente
	$emailValidos = $_SESSION['n']=emailsValidos($_SESSION['h_logueado'], $id_lista, 1, $result['sqlFiltros']);
	// Emails no válidos Y marcados manualmente
	$emailNoValidos = emailsValidos($_SESSION['h_logueado'], $id_lista, 0, $result['sqlFiltros']);
	//Creamos una cadena para seguridad del webservice
	$wsSec = seguridadWSHotel($_SESSION['h_logueado']);
	
	// Langs para filtro lang, solo mostramos si esta visualizando una lista ya guardada
	$langs = obtenerLangsTodosLista($id_lista);
	
	$ready = true;
}

// Listas para el select de añadir a lista existente
$listas = obtenerListas($_SESSION['h_logueado']);

//$arrayPaginado = paginacion($arrayEmailPuntos, $pagina, '100');
$arrayPaginado = $arrayEmailPuntos;
//Inicializamos $rowCount
$rowCount = 0;

/*echo '<pre>';
print_r ($_POST);
echo '</pre>';*/
?>