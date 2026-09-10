<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

// Borrar un email uno por uno por id
function borrarEmailInvitacion($id_list_user, $id_hotel)
{
	$id_list_user = mysqli_real_escape_string(conectar() , $id_list_user);
	$id_hotel = mysqli_real_escape_string(conectar() , $id_hotel);
	
	// Miramos que el id sea de una lista del mismo hotel
	$sql2 = "SELECT COUNT(hotel_list_users.id) AS n
	FROM hotel_list
	INNER JOIN hotel_list_users ON hotel_list_users.id_list=hotel_list.id 
	WHERE hotel_list.id_hotel='".$id_hotel."' AND hotel_list_users.id='".$id_list_user."' ";
	$rs2 = mysqli_query (conectar(), $sql2);
	$row2 = mysqli_fetch_assoc($rs2);
	liberar ($rs2);
	
	if($row2['n']=='1')
	{
		$sql = "DELETE FROM hotel_list_users WHERE id='".$id_list_user."' ";
		mysqli_query (conectar(), $sql);
		// code de feedback
		$result['code']='2031';
	}else{
		// code de feedback
		$result['code']='4002';
	}
	return $result;
}

// FX para borrar todos emails no válidos de una lista
function borrarTodosEmailsInvalidos($id_list, $id_hotel)
{
	$id_list = mysqli_real_escape_string(conectar() , $id_list);
	$id_hotel = mysqli_real_escape_string(conectar() , $id_hotel);
	
	// Miramos que el id sea de una lista del mismo hotel
	$sql2 = "SELECT COUNT(hotel_list.id) AS n
	FROM hotel_list
	WHERE id_hotel='".$id_hotel."' AND id='".$id_list."' ";
	$rs2 = mysqli_query (conectar(), $sql2);
	$row2 = mysqli_fetch_assoc($rs2);
	liberar ($rs2);
	
	if($row2['n']=='1')
	{
		$sql = "DELETE FROM hotel_list_users 
		WHERE id_list='".$id_list."' AND email_valido=0 ";
		mysqli_query (conectar(), $sql);
		// code de feedback
		$result['code']='2032';
	}else{
		// code de feedback
		$result['code']='4002';
	}
	return $result;
}

// El insert es llamado cuando:
// se crea la lista desde un archivo invitar-usuarios-2-ws (inserta asincronamente)
// el usuario ha puesto manuelmente o copia/pega usuarios en el text area. Se insertan en el modelo
function obtenerInsertHotelListUsers()
{
	$sql = "INSERT INTO hotel_list_users 
	(id_list, email, puntos, idioma, total_spent, total_noches, nombre, email_valido) VALUES ";
	return $sql;
}

// listName : nombre de la lista (nueva)
// arrayUsuarios: array con los datos de los usuario
// orden: oreden en que se deben guardar los campos de arrayUsuarios
// addToList: contiene id de lista. Esta añadiendo arrayUsuarios a una lista ya existente (ignorar listName)
function guardarListaUsuarios($id_hotel, $listName, $arrayUsuarios, $orden, $addToList)
{
	$addToList = mysqli_real_escape_string(conectar(), $addToList);
	
	if( $addToList != 0 )
	{
		// Esta añadiendo usuarios a lista ya existente
		$id_list = $addToList;
	}else{
		// Esta añadiendo usuarios a lista nueva
		$listName = mysqli_real_escape_string(conectar(), $listName);
	
		$fecha = dateHoy();
		$sql="INSERT INTO hotel_list (id_hotel, nombre, fecha, lista_acabada) 
		VALUES ('".$id_hotel."', '".$listName."', '".$fecha."', '1')";
		$link = conectar();
		mysqli_query ($link, $sql);
		$id_list = mysqli_insert_id($link);
	}
	
	foreach($arrayUsuarios as $usuario)
	{
		if (filter_var($usuario[$orden[0]], FILTER_VALIDATE_EMAIL))
		{
			$emailValido = 1;
		}else{
			$emailValido = 0;
		}
		$sql2 = obtenerInsertHotelListUsers();
		$sql2 .= "('".$id_list."', 
		'".mysqli_real_escape_string(conectar(), $usuario[$orden[0]])."', 
		'".mysqli_real_escape_string(conectar(), $usuario[$orden[1]])."', 
		'".mysqli_real_escape_string(conectar(), $usuario[$orden[2]])."', 	
		'".mysqli_real_escape_string(conectar(), $usuario[$orden[3]])."', 
		'".mysqli_real_escape_string(conectar(), $usuario[$orden[4]])."', 
		'".mysqli_real_escape_string(conectar(), $usuario[$orden[5]])."', 	
		'".$emailValido."')";
		mysqli_query (conectar(), $sql2);
		//echo $sql2;
	}
}

// Incorrect email: filtro para traer las filas con emails incorrectos
// $prod: producto que tiene contratado, 0 varios else RF, MK, LY
function obtenerConsultaEmail($id_hotel, $id_lista, $incorrectEmails=NULL)
{
	$id_lista = mysqli_real_escape_string(conectar() , $id_lista);
	
	
	$sql[0] = "SELECT hotel_list_users.nombre, hotel_list_users.email, hotel_list_users.idioma,";
	$sql[0] .= "hotel_list_users.puntos, hotel_list_users.total_spent, hotel_list_users.total_noches, ";
	$sql[0] .= "hotel_list_users.email_valido, 
	hotel_list_users.id ";
	$sql[1] = " FROM hotel_list 
	INNER JOIN hotel_list_users ON hotel_list_users.id_list=hotel_list.id
	WHERE hotel_list.id='".$id_lista."' AND id_hotel='".$id_hotel."' ";
	if(isset($incorrectEmails))
	{
		$sql[1] .= " AND email_valido='".$incorrectEmails."' ";
	}
	return $sql;
}

// incorrectEmails: 0 filtra por los mails incorrectos, 0 filtra por los mails correctos, NULL no filtra
// filtros (array) (pueden ir combinados entre sí)
//		- noLy: usuarios a los que no se les ha mandado el mail de Loyalty
//		- noRf: usuarios a los que no se les ha mandado el mail de Referral
//		- noMail: usuarios a los que no se les ha mandado nungún mail (ni LY ni RF)
//		- lang: filtrar usuarios por un determinado lang
//		- points: filtrar usuarios por puntos
//		- nights: filtrar usuarios por noches
//		- spent: filtrar usuarios por total spent
function obtenerLista($id_hotel, $id_lista, $itemsPage, $pagina, $incorrectEmails, $filtros)
{
	$id_lista = mysqli_real_escape_string(conectar() , $id_lista);
	$itemsPage = mysqli_real_escape_string(conectar() , $itemsPage);
	$pagina = mysqli_real_escape_string(conectar() , $pagina);
	
	$consulta = obtenerConsultaEmail($id_hotel, $id_lista, $incorrectEmails);
	$sql = $consulta[0];
	$sql2 = $consulta[1];
	
	// Miramos si el array $filtros esta vacio
	foreach ($filtros as $key => $value) {
		if ($value==''){
			$arrayFiltrosVacio=true;
		}else{
			$arrayFiltrosVacio=false;
			break;
		}
	}

	// Aplicar filtros WHERE a la SELECT si $filtros no está vacio
	$sqlFiltros = '';
	if($arrayFiltrosVacio==false)
	{
		// No se ha mandado LY
		!empty($filtros['noLy']) && $filtros['noLy']!='' ? $sqlFiltros .=" AND hotel_list_users.enviado_ly='0' " : '';
		// No se ha mandado RF
		!empty($filtros['noRf']) && $filtros['noRf']!='' ? $sqlFiltros .=" AND hotel_list_users.enviado_rf='0' " : '';
		// No se ha mandado ni RF ni LY (noMail)
		!empty($filtros['noMail']) && $filtros['noMail']!='' ? $sqlFiltros .=" AND hotel_list_users.enviado_rf='0' AND hotel_list_users.enviado_ly='0'" : '';
		// Lang
		if(!empty($filtros['lang']) && $filtros['lang']!='' && $filtros['lang']!='undefined'){
			$lang = mysqli_real_escape_string(conectar() , $filtros['lang']);
			$lang!='' ? $sqlFiltros .=" AND hotel_list_users.idioma='".$lang."' " : '';
		}
		// PUNTOS
		if(!empty($filtros['points1']) && $filtros['points1']!=''){
			$points1 = mysqli_real_escape_string(conectar() , $filtros['points1']);
		}else{
			$points1 = '';
		}
		if(!empty($filtros['points2']) && $filtros['points2']!=''){
			$points2 = mysqli_real_escape_string(conectar() , $filtros['points2']);
		}else{
			$points2 = '';
		}
		if(!empty($points1) && !empty($points2)){
			// Puntos1 y puntos2: entre puntos1 y puntos2
			$sqlFiltros .= " AND (hotel_list_users.puntos BETWEEN '".$points1."' AND '".$points2."' ) ";
		}else if(!empty($points1)){
			// Solo puntos1: >= que puntos 1
			$sqlFiltros .= " AND hotel_list_users.puntos >= '".$points1."'  ";
		}else if(!empty($points2)){
			// Solo puntos2: <= que puntos 2
			$sqlFiltros .= " AND hotel_list_users.puntos <= '".$points2."'  ";			
		}
		// NOCHES
		if(!empty($filtros['nights1']) && $filtros['nights1']!=''){
			$nights1 = mysqli_real_escape_string(conectar() , $filtros['nights1']);
		}else{
			$nights1 = '';
		}
		if(!empty($filtros['nights2']) && $filtros['nights2']!=''){
			$nights2 = mysqli_real_escape_string(conectar() , $filtros['nights2']);
		}else{
			$nights2 = '';
		}
		if(!empty($nights1) && !empty($nights2)){
			// Noches1 y noches2: entre noches1 y noches2
			$sqlFiltros .= " AND (hotel_list_users.total_noches BETWEEN '".$nights1."' AND '".$nights2."' ) ";
		}else if(!empty($nights1)){
			// Solo noches1: >= que noches1
			$sqlFiltros .= " AND hotel_list_users.total_noches >= '".$nights1."'  ";
		}else if(!empty($nights2)){
			// Solo noches2: <= que noches2
			$sqlFiltros .= " AND hotel_list_users.total_noches <= '".$nights2."'  ";			
		}
		// TOTAL SPENT
		if(!empty($filtros['spent1']) && $filtros['spent1']!=''){
			$spent1 = mysqli_real_escape_string(conectar() , $filtros['spent1']);
		}else{
			$spent1 = '';
		}
		if(!empty($filtros['spent2']) && $filtros['spent2']!=''){
			$spent2 = mysqli_real_escape_string(conectar() , $filtros['spent2']);
		}else{
			$spent2 = '';
		}
		if(!empty($spent1) && !empty($spent2)){
			// spent1 y spent2: entre spent1 y spent2
			$sqlFiltros .= " AND (hotel_list_users.total_spent BETWEEN '".$spent1."' AND '".$spent2."' ) ";
		}else if(!empty($spent1)){
			// Solo noches1: >= que spent1
			$sqlFiltros .= " AND hotel_list_users.total_spent >= '".$spent1."'  ";
		}else if(!empty($spent2)){
			// Solo noches2: <= que spent2
			$sqlFiltros .= " AND hotel_list_users.total_spent <= '".$spent2."'  ";			
		}
	}
	
	// Fin filtros
	
	$inicio = $itemsPage*$pagina-$itemsPage;
	$sql3 = " LIMIT ".$inicio.",".$itemsPage;
	//echo $sql.$sql2.$sqlFiltros.$sql3;
	$rs = mysqli_query (conectar(), $sql.$sql2.$sqlFiltros.$sql3) or die(mysqli_error());
	$arrayListas = array();
	$i=0;
	while($row = mysqli_fetch_assoc($rs))
	{
		$t=0;
		foreach ($row as $key=>$valor)
		{
			$arrayListas[$i][$t] = $valor;
			$t++;
		}
		$i++;
	}
	liberar ($rs);
	
	$sql0 = "SELECT COUNT(email) as N ";
	paginacion2($sql0.$sql2.$sqlFiltros, $pagina, $itemsPage);
	
	$result['list'] = $arrayListas;//Lista
	$result['sqlFiltros'] = $sqlFiltros;
	$result['total'] = count($arrayListas);
	return $result;
}

// Valido: 1 emails válidos, 0 emails no válidos
// $sqlFiltros: filtros WHERE que se le aplican a la consulta
// Tambien se filtra por los que estan marcados manualmente para enviar(OJO no solo los que el email es válido)
function emailsValidos($id_hotel, $id_lista, $valido, $sqlFiltros='')
{
	$id_hotel = mysqli_real_escape_string(conectar() , $id_hotel);
	$id_lista = mysqli_real_escape_string(conectar() , $id_lista);
	
	$consulta = obtenerConsultaEmail($id_hotel, $id_lista, $valido);
	
	$sql = "SELECT COUNT(hotel_list_users.id) AS n ";
	$sql .= $consulta[1];
	$sql .= $sqlFiltros;
	// Filtramos ademas por los que estan marcados para enviar
	$sql .= " AND marcado='1'"; 
	//echo $sql.'<br>';
	$rs = mysqli_query (conectar(), $sql) or die(mysqli_error());
	$row = mysqli_fetch_assoc($rs);
	liberar ($rs);
	return $row['n'];
}

function obtenerNombreLista($id_hotel, $id_lista)
{
	$id_hotel = mysqli_real_escape_string(conectar() , $id_hotel);
	$id_lista = mysqli_real_escape_string(conectar() , $id_lista);
	
	$sql = "SELECT nombre FROM hotel_list 
	WHERE id='".$id_lista."' AND id_hotel='".$id_hotel."' ";
	$rs = mysqli_query (conectar(), $sql) or die(mysqli_error());
	$row = mysqli_fetch_assoc($rs);
	liberar ($rs);
	return $row['nombre'];
}

// FX para insertar un nuevo registro en la tabla de usuarios (viene de WS)
// Devuelve el id insertado
function insertatNuevoIU2($id_lista)
{
	$id_lista = mysqli_real_escape_string(conectar() , $id_lista);
	
	$sql = "INSERT INTO hotel_list_users (id_list) VALUES ('".$id_lista."')";
	$link = conectar();
	mysqli_query ($link, $sql);
	$id = mysqli_insert_id($link);
	return($id);
}

//FX para obtener todos los langs de la lista 
// Solo mira los que coinciden con nuestra lista de langs
function obtenerLangsTodosLista($id_lista)	
{
	$id_lista = mysqli_real_escape_string(conectar() , $id_lista);
	
	$langs = array();
	
	$sql = "SELECT lang.lang, lang.country 
	FROM hotel_list_users 
	INNER JOIN lang ON lang.lang=hotel_list_users.idioma
	WHERE id_list='".$id_lista."'
	ORDER BY country ASC";
	$rs = mysqli_query (conectar(), $sql) or die(mysqli_error());
	while($row = mysqli_fetch_assoc($rs))
	{
		$langs[$row['lang']]=$row['country'];
	}
	liberar($rs);
	return $langs;
}

// Al aplicar los filtros debemos marcar los elementos de la lista filtrados para enviar
// debemos poner el campo 'marcado' de la tabla 'hotel_list_users' a 1 en todos los campos de $result
// el resto debe ser 0
// $filtros contiene el filtro SQL actual de elementos filtrados
// $id_lista: id de la lista
// en la misma pantalla y por ajax se podra desmarcar uno por uno
function marcarResult($filtros, $id_lista)
{
	$id_lista = mysqli_real_escape_string(conectar() , $id_lista);
	
	//Inicializamos todos los campos a 0
	$sql = "UPDATE hotel_list_users SET marcado=0 WHERE id_list='".$id_lista."' ";
	mysqli_query (conectar(), $sql) or die(mysqli_error());
	
	// Marcamos a 1 todos los campos filtrados previamente
	$sql2 = "UPDATE hotel_list_users SET marcado=1 WHERE id_list='".$id_lista."' ";
	$sql2 .= $filtros;
	
	//echo '<br>'.$sql2.'<br>';
	mysqli_query (conectar(), $sql2) or die(mysqli_error());
}

function obtenerListas($id_hotel)
{
	$id_hotel = mysqli_real_escape_string(conectar() , $id_hotel);
	
	$listas = array();
	
	$sql = "SELECT id, nombre FROM hotel_list WHERE id_hotel='".$id_hotel."' ORDER BY nombre ASC";
	$rs = mysqli_query (conectar(), $sql);
	$i=0;
	while($row = mysqli_fetch_assoc($rs))
	{
		foreach ($row as $key=>$valor)
		{
			$listas[$i][$key]=$valor;
		}
		$i++;
	}
	//echo $sql;
	liberar ($rs);
	return $listas;
}
?>