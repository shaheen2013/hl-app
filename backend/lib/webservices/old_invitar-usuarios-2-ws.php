<?php
include 'librerias.php';// Librerias básicas

//Este WS puede ser llamado por ajax o por CURL

if(empty($_GET['sc'])){
	//Viene de ajax. No tiene $_GET['sc'] (campo security de CURL)
	
	// Restringir ips que pueden acceder
	include_once RUTA_DIR . LIB . 'check_access.php';
	
	checkIpAccess('in-us-2', $_SERVER['REMOTE_ADDR']);
}else{
	//Viene de CURL
	
	// Restringir ips que pueden acceder
	include_once RUTA_DIR . LIB . 'ips_acceso.php';
	include_once RUTA_DIR . LIB . 'seguridadHotel.php';
	if ( !ips_acceso_webservice('in-us-2', $_SERVER['SERVER_ADDR']) && !empty($_GET['idHotel']) && !empty($_GET['sc']) && !leerSeguridadWSHotel($_GET['idHotel'], $_GET['sc']) )	{
		echo 'No direct access allowed.';
		exit;
	}else{
		// Constante para poder cargar las librerias
		!defined('INDEXCONTROLVAL')? define("INDEXCONTROLVAL", "1"):'';	
	}
}

include_once RUTA_DIR . LIB . 'fecha.php';
include_once RUTA_DIR . LIB . 'enviarEmail.php';
include_once RUTA_DIR . LIB . 'emailValidate.php';
include_once RUTA_DIR . LIB . 'invitar-usuarios-2-lib.php';
include_once RUTA_DIR . MODEL . 'invitar-usuarios-2Model.php';
include_once RUTA_DIR . LIB . 'obtenerdatosHotel.php';
include_once RUTA_DIR . LIB . 'webservices/msgFeedback.php';
include_once RUTA_DIR . LIB . 'seguridadHotel.php';

//Obtener selects para invitar-usuarios-2
function obtenerSelectIU2()
{
	// Miramos si el hotel tiene un solo producto
	$prod = mirarUnProductoHotel();
	include_once RUTA_DIR . LANG . $_SESSION['userLang'] . '/invitar-usuarios-2.php';
	if( $prod == 'RF' ){
		// Solo tiene RF. Select donde estan los campos a elegir (email, nombre, idioma...) para RF
		// Mostramos solo los campos que se utilizan en RF
		$select = array(
			'0' => $InvitarUsuario2Lang['Guest email'], 
			'2' => $InvitarUsuario2Lang['Language/Nationality'], 
			'5' => $InvitarUsuario2Lang['Guest name']
		);
	}else{
		// Select donde estan los campos a elegir (email, nombre, idioma...)
		$select = array(
			'0' => $InvitarUsuario2Lang['Guest email'], 
			'1' => $InvitarUsuario2Lang['Reward points given'], 
			'2' => $InvitarUsuario2Lang['Language/Nationality'], 
			'3' => $InvitarUsuario2Lang['Guest spend total'], 
			'4' => $InvitarUsuario2Lang['Guest total nights'],
			'5' => $InvitarUsuario2Lang['Guest name']
		);
	}
	return $select;
}


// Guardar los cambios de los valores de los inputs en la BD
if (isset($_POST['valor']) && !empty($_POST['idTipo']) && leerSeguridadWSHotel($_POST['hotelId'], $_POST['wsSc']))
{
	$valor = mysqli_real_escape_string(conectar(), $_POST['valor']);
	$idTipo = mysqli_real_escape_string(conectar(), $_POST['idTipo']);
	$hotelId = mysqli_real_escape_string(conectar(), $_POST['hotelId']);
	$idList = mysqli_real_escape_string(conectar(), $_POST['idList']);

	// Sacar id & tipo 
	$pices = explode ('-', $idTipo);
	$tipo = $pices[0];
	!empty($pices[1])? $id = $pices[1] : $id ='';
	
	$result['type'] = $tipo;
	$result['value'] = $valor;
	$result['id'] = $idTipo;
	$result['code'] = 200;
	$result['msgError'] = '';
	
	$sql = "UPDATE hotel_list_users SET ".$tipo."='".$valor."' ";
	if($tipo=='email')
	{

		//Verificamos que el email sea válido si estamos en producción
		/*if(ENV == 'production')
		{
			// El environment es producción, verificamos email
			$resultEmail = validateEmail($valor);
		}else{
			// El environment NO es producción. 
			// Solo verificamos que el email esté bien formado
			if (!filter_var($valor, FILTER_VALIDATE_EMAIL)) {
				$resultEmail = array('code' => '400');
			}else{
				$resultEmail = array('code' => '200');
			}
		}*/
		//Solo verificamos que el email esté bien formado ya que el hotelero puede estar importando 
		// un listado de miles de usuarios y nos podemos pasar los limites de cuota de Kickbox
		if (!filter_var($valor, FILTER_VALIDATE_EMAIL)) {
			$resultEmail = array('code' => '400');
		}else{
			$resultEmail = array('code' => '200');
		}
		
		if($resultEmail['code']=='200')
		{
			// Email válido
			$sql .= ", email_valido=1";
			$result['msgError'] = msgFeedbackWs('2030', $_SESSION['userLang']);
		}else{
			// Email no válido
			$sql .= ", email_valido=0";
			$result['code'] = 400;
			$result['msgError'] = msgFeedbackWs('4047', $_SESSION['userLang']);
		}
	}else{
		$result['code'] = 200;
		$result['msgError'] = msgFeedbackWs('2007', $_SESSION['userLang']);
	}
	$sql .= " WHERE id='".$id."'";
	mysqli_query (conectar(), $sql);
	
	$result['validEmails'] = emailsValidos($hotelId, $idList, 1);
	$result['invalidEmails'] = emailsValidos($hotelId, $idList, 0);
	echo json_encode($result);
}

// Rellena dinamicamente los selects 
if (isset($_POST['field1']))
{
	$field1 = $_POST['field1'];
	$_SESSION['field1'] = $field1;
	
	$select = obtenerSelectIU2();
	
	$option = '<option value="6">Select one...</option>';
	foreach ($select as $key => $value){
		if($key!=$field1){
			$option .= '<option value="'.$key.'">'.$value.'</option>';
		}
	}
	echo $option;
}

if (isset($_POST['field2']))
{
	$field2 = $_POST['field2'];
	$_SESSION['field2'] = $field2;
	
	$select = obtenerSelectIU2();
	
	$option = '<option value="6">Select one...</option>';
	foreach ($select as $key => $value)
	{
		if($key!=$field2 && $key != $_SESSION['field1'])
		{
			$option .= '<option value="'.$key.'">'.$value.'</option>';
		}
	}
	echo $option;
}

if (isset($_POST['field3']))
{
	$field3 = $_POST['field3'];
	$_SESSION['field3'] = $field3;
	
	$select = obtenerSelectIU2();
	
	$option = '<option value="6">Select one...</option>';
	foreach ($select as $key => $value)
	{
		if($key!=$field3 && $key!=$_SESSION['field1']&& $key!=$_SESSION['field2'])
		{
			$option .= '<option value="'.$key.'">'.$value.'</option>';
		}
	}
	echo $option;
}

if (isset($_POST['field4']))
{
	$field4 = $_POST['field4'];
	$_SESSION['field4'] = $field4;
	
	$select = obtenerSelectIU2();
	
	$option = '<option value="6">Select one...</option>';
	foreach ($select as $key => $value)
	{
		if($key!=$field4&&$key!=$_SESSION['field1']&&$key!=$_SESSION['field2']&&$key!=$_SESSION['field3'])
		{
			$option .= '<option value="'.$key.'">'.$value.'</option>';
		}
	}
	echo $option;
}

if (isset($_POST['field5']))
{
	$field5 = $_POST['field5'];
	$_SESSION['field5'] = $field5;
	
	$select = obtenerSelectIU2();
	
	$option = '<option value="6">Select one...</option>';
	foreach ($select as $key => $value)
	{
		if($key!=$field5&&$key!=$_SESSION['field1']&&$key!=$_SESSION['field2']&&$key!=$_SESSION['field3']&&$key!=$_SESSION['field4'])
		{
			$option .= '<option value="'.$key.'">'.$value.'</option>';
		}
	}
	echo $option;
}

//-------------------------- Archivo subido -------------
function obtenerDatosHotelIU2WS($id_hotel)
{
	$sql = "SELECT email, name, lang FROM hoteles WHERE id='".$id_hotel."' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	return $row;
}

function guardarListaUsuariosArchivo($id_hotel, $listName, $archivoUsuarios, $orden, $addToList)
{	
	$id_hotel = mysqli_real_escape_string(conectar(), $id_hotel);
	$listName = mysqli_real_escape_string(conectar(), $listName);
	$archivoUsuarios = mysqli_real_escape_string(conectar(), $archivoUsuarios);

	if(file_exists($archivoUsuarios))
	{
		if( $addToList != 0 )
		{
			// Esta añadiendo usuarios a lista ya existente
			$id_list = $addToList;
		}else{
			// Guardamos los datos de la lista nueva
			$fecha = dateHoy();
			$sql="INSERT INTO hotel_list (id_hotel, nombre, fecha) 
			VALUES ('".$id_hotel."', '".$listName."', '".$fecha."')";
			$link = conectar();
			mysqli_query ($link, $sql);
			$id_list = mysqli_insert_id($link);
		}
		
		// Guardamos los usuarios de la lista
		$sql2 = obtenerInsertHotelListUsers();//INSER INTO ...
		$sql3 = "";
		$fp = fopen($archivoUsuarios, 'r');// abrimos archivo
		$i=0;$total=0;
		while (!feof($fp)){
			$line = fgets($fp, 2048);
			
			$result = separarCampos($line);
			$explodeEmailPuntos = $result[0];
			
			//contamos los elementos que tiene la linea, 
			//si esta vacia la saltamos ya que puede que al final del archivo se cuelen lineas muertas
			$count = count($explodeEmailPuntos); 
			if($count>1){
				//Verificamos que el email sea válido
				
				//Verificamos que el email sea válido si estamos en producción
				/*if(ENV == 'production')
				{
					// El environment es producción, verificamos email
					$resultEmail = validateEmail($explodeEmailPuntos[$orden[0]]);
				}else{
					// El environment NO es producción. 
					// Solo verificamos que el email esté bien formado
					if (!filter_var($explodeEmailPuntos[$orden[0]], FILTER_VALIDATE_EMAIL)) {
						$resultEmail = array('code' => '400');
					}else{
						$resultEmail = array('code' => '200');
					}
				}*/
				// Solo verificamos que el email esté bien formado ya que el hotelero puede estar importando 
				// un listado de miles de usuarios y nos podemos pasar los limites de cuota de Kickbox
				if (!filter_var($valor, FILTER_VALIDATE_EMAIL)) {
					$resultEmail = array('code' => '400');
				}else{
					$resultEmail = array('code' => '200');
				}
				
				if ($resultEmail['code']=='200'){
					$emailValido = 1;
				}else{
					$emailValido = 0;
				}
				$sql3 .= " 
				('".$id_list."', ";
				// Generamos dinamicamente los campos del insert
				for ($i = 0; $i <= 5; $i++) {
					if(!empty($explodeEmailPuntos[$orden[$i]])){
						$campo = mysqli_real_escape_string(conectar(), $explodeEmailPuntos[$orden[$i]]);
						$sql3 .= " '".$campo."', ";
					}else{
						$sql3 .= " '', ";
					}
				}			
				$sql3 .= "'".$emailValido."'),";	
				$total++;
			}
			$i++;
			
			if($i==1000)
			{
				// Cada 1000 realizamos un insert en la BD
				$i=0;
				$sql3 = substr($sql3, 0, -1);// quitamos última coma
				mysqli_query (conectar(), $sql2.$sql3);
				$sql3 = '';// Vaciamos los campos
			}
		}
		$sql3 = substr($sql3, 0, -1);// quitamos última coma
		if(!empty($sql3))
		{
			mysqli_query (conectar(), $sql2.$sql3);
			//echo '<br>'.$sql2.$sql3;
		}
		//echo '<br>'.$sql2.$sql3;
		fclose($fp);// <--- cerramos archivo 
		
		//actualizamos la lista como acabada
		$sql4 = "UPDATE hotel_list SET lista_acabada=1 WHERE id='".$id_list."' ";
		//echo $sql4;
		mysqli_query (conectar(), $sql4);
		$datos_lista = array($id_list, $total);
	}else{
		//fichero no existe
		$datos_lista = array($addToList, '0');
	}
	return $datos_lista;// Devuelve el total de invitaciones guardadas
}

//Importar desde archivo
//El archivo debe ser de tipo txt o csv
if (isset($_GET['archivo']) && isset($_GET['nombre']) && isset($_GET['id_hotel']) )
{
	//$ruta = mysqli_real_escape_string(conectar(), $_POST['ruta']);
	$archivo = $_GET['archivo'];
	$nombre = $_GET['nombre'];// nombre de la lista
	$id_hotel = $_GET['id_hotel'];// id_hotel
	$o1 = $_GET['f1'];
	$o2 = $_GET['f2'];
	$o3 = $_GET['f3'];
	$addToList = $_GET['addToList'];
	
	if($_SESSION['permisos']['LY'] == '1')
	{
		$o4 = $_GET['f4'];
		$o5 = $_GET['f5'];
		$o6 = $_GET['f6'];
	}else{
		//Asignamos los otros campos ord
		$o4 = '1';
		$o5 = '3';
		$o6 = '4';
	}
	$orden = array($o1, $o2, $o3, $o4, $o5, $o6);
	
	$orden = array_flip($orden);
	asort($orden); // Ordenar array
	
	//Ruta del archivo subido
	$rutaArchivo = RUTA_DIR . DIR_FILES_HOTEL . $id_hotel.'/temp/'.$archivo;
	
	// inserta datos
	$datos_lista = guardarListaUsuariosArchivo($id_hotel, $nombre, $rutaArchivo, $orden, $addToList);
	// borrar archivo
	unlink ($rutaArchivo);
	
	//Mandar email al hotelero de que la operación ha acabado
	$datosHotel = obtenerDatosHotelIU2WS($id_hotel);

	if(!empty($datosHotel['email'])){
		include_once RUTA_DIR . LANG . $datosHotel['lang'].'/email/invitar-usuarios-2.php';
		include_once RUTA_DIR . LIB . 'plantillasMails/invitar-usuarios-2.php';
		mandarEmailMandrillPlantilla($datosHotel['email'], $datosHotel['name'], $asunto, $cuerpo, 'standard-template');
	}
}

// Añadir nueva linea para insertar usuario nuevo en la lista
if (!empty($_POST['addNewLine']) && leerSeguridadWSHotel($_POST['hotelId'], $_POST['wsSc']) && !empty($_POST['idList']) )
{
	$hotelId = mysqli_real_escape_string(conectar(), $_POST['hotelId']);
	$idList = mysqli_real_escape_string(conectar(), $_POST['idList']);
	$prod = mysqli_real_escape_string(conectar(), $_POST['prod']);
	
	// Insertar nueva entrada en la lista
	$id = insertatNuevoIU2($idList);
	
	// Linea a insertar al final de la tabla
	// Campo delete
	$delete = '<td><a href="'.$urlTree['invitar-usuarios-2'] . '/' . $idList . '/?del=' . $id .'" class="btn btn-warning"><i class="fa fa-times"></i></a>';
	// Campos basicos: nombre, email, lang
	$basico = '
	<tr class="table-row">
		<td><input type="text" class="form-control" data-type="nombre-'.$id.'" name="nombre-0" class="warning-action"></td>
		<td><input type="email" class="form-control has-error"  data-type="email-'.$id.'" id="email-'.$id.'" name="email-0"></td>
		<td><input type="text" class="form-control" data-type="idioma-'.$id.'" id="idioma-'.$id.'" name="idioma-0"></td>';
	// Campos extra: points, total_spent, total_nights
	$extra = '<td><input type="text" class="form-control" data-type="puntos-'.$id.'" name="puntos-0" class="warning-action"></td>
		<td><input type="text" class="form-control"  data-type="total_spent-'.$id.'" id="total_spent-'.$id.'" name="total_spent-0"></td>
		<td><input type="text" class="form-control" data-type="total_noches-'.$id.'" id="total_noches-'.$id.'" name="total_noches-0"></td>';
	//checkbox marcado (para enviar)
	$marcado ='<td><input type="checkbox" class="markedToSend" name="marcado-'.$id.'" checked="checked" value="0" data-type="marcado-'.$id.'" id="marcado-'.$id.'"></td>';
	//Cierre de tabla + delete + marcado 
	$cierreTabla = $delete.$marcado.'</td></tr>';
	
	if($prod == 'RF')
	{
		$result['row'] = $basico . $cierreTabla;
	}else{
		$result['row'] = $basico . $extra . $cierreTabla;
	}
	
	echo json_encode($result);
}
?>