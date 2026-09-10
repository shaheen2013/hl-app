<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

// Obtenemos los usuario de la lista $id_list que tienen el campo BD marcado=1 
// (han sido marcados en la pantalla anterior)
// $type: tipo (LY, RF). Filtramos los email a los que todavia no se les ha mandado email del tipo actual
function obtenerListaUsuarios($id_list, $id_hotel, $type)
{
	$id_list = mysqli_real_escape_string(conectar(), $id_list);
	$id_hotel = mysqli_real_escape_string(conectar(), $id_hotel);
	$type = mysqli_real_escape_string(conectar(), $type);
	
	$arrayUsuarios = array();
	$sql = "SELECT hotel_list_users.email, hotel_list_users.puntos, hotel_list_users.idioma,
	hotel_list_users.total_spent, hotel_list_users.total_noches, hotel_list_users.nombre,
	hotel_list_users.email_valido
	FROM hotel_list
	INNER JOIN hotel_list_users ON hotel_list_users.id_list=hotel_list.id
	WHERE hotel_list.id='".$id_list."' AND hotel_list.id_hotel='".$id_hotel."' ";
	// NO debemos traer todos los usuarios de la lista. 
	// Filtramos por marcados y los que no se ha mandado email del tipo $type (LY, RF)
	$sql .=" AND hotel_list_users.marcado='1' AND enviado_".$type."='0' ";
	//echo $sql;
	$rs = mysqli_query (conectar(), $sql);
	$i=0;
	while ($row = mysqli_fetch_assoc($rs))
	{
		foreach ($row as $key=>$valor)
		{
			$arrayUsuarios[$i][$key]=$valor;	
		}
		$i++;
	}
	liberar($rs);
	return $arrayUsuarios;
}

// FX para marcar los email enviados de tipo $type (LY, RF)
// Ponemos el campo BD enviado_ly/_rf=1 para todos los campos con el campo BD "marcado"=1
// El campo marcado a sido activado en la pantalla anterior
function marcarEnviadosTipo($id_list, $type)
{
	$id_list = mysqli_real_escape_string(conectar(), $id_list);
	$type = mysqli_real_escape_string(conectar(), $type);
	
	$sql = "UPDATE hotel_list_users SET enviado_".$type."=1 
	WHERE hotel_list_users.id_list='".$id_list."' AND hotel_list_users.marcado='1' ";
	mysqli_query (conectar(), $sql);
}

function obtenerHotel($id)
{
	$sql = "SELECT id, hotelName FROM hoteles WHERE id='".$id."' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_array($rs);
	liberar($rs);
	return $row;
}

function obtenerUsuario ($email_usuario)
{
	$sql = 'SELECT id, nombre, DATE(created) AS created FROM users WHERE email="'.$email_usuario.'"';
	//echo $sql;
	$rs = mysqli_query (conectar(), $sql);
	$n_resultados = mysqli_num_rows($rs);
	if ($n_resultados == 0 )
	{
		// El usuario no existe en la BD
		$row['id']='';
		$row['nombre']='';
	}else{
		$row = mysqli_fetch_assoc($rs);
	}
	liberar($rs);
	return $row;
}

function usuarioVinculadoHotel($id_usuario, $id_hotel)
{
	$sql = "SELECT id FROM user_hotels 
	WHERE id_usuario='".$id_usuario."' AND  id_hotel='".$id_hotel."' ";
	$rs = mysqli_query (conectar(), $sql);
	$n_resultados=mysqli_num_rows($rs);
	liberar ($rs);
	if ($n_resultados==0)
	{
		return false;
	}else{
		return true;
	}
}

function borrarLista($id_list, $id_hotel)
{
	$id_list = mysqli_real_escape_string(conectar(), $id_list);
	$id_hotel = mysqli_real_escape_string(conectar(), $id_hotel);
	
	$sql = "SELECT id FROM hotel_list WHERE id='".$id_list."' 
	AND id_hotel='".$id_hotel."' ";
	$rs = mysqli_query (conectar(), $sql);
	$n_results = mysqli_num_rows($rs);
	liberar($rs);
	if ($n_results != 0 )
	{
		// si la lista es del hotel la borramos
		$sql2 = "DELETE FROM hotel_list WHERE id='".$id_list."' 
		AND id_hotel='".$id_hotel."' ";
		mysqli_query (conectar(), $sql2);
		$sql3 = "DELETE FROM hotel_list_users WHERE id_list='".$id_list."' ";
		mysqli_query (conectar(), $sql3);
		return true;
	}else{
		return false;
	}
}
?>