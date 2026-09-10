<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

function obtenerEstrellasTrends($id_hotel)
{
	$sql = "SELECT minEstrellas, maxEstrellas
	FROM user_hotels
	INNER JOIN users ON users.id=user_hotels.id_usuario
	WHERE user_hotels.id_hotel='".$id_hotel."' 
	AND minEstrellas!=0 AND maxEstrellas!=0";
	$rs = mysqli_query (conectar(), $sql);
	$total_clientes = mysqli_num_rows($rs);
	$arrayTotalEstrellasTrends = array ('1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0, '7' => 0);
	$i=0;
	while ($row = mysqli_fetch_assoc($rs))
	{
		foreach ($row as $key=>$valor)
		{
			$arrayEstrellas[$i][$key] = $valor;
		}
		$estrella = $arrayEstrellas[$i]['minEstrellas'];
		while ($estrella <= $arrayEstrellas[$i]['maxEstrellas'])
		{
			$arrayTotalEstrellasTrends[$estrella] = $arrayTotalEstrellasTrends[$estrella]+1;
			$estrella++;
		}
		$i++;
	}
	
	foreach($arrayTotalEstrellasTrends as $key=>$valor)
	{
		if($total_clientes==0)
		{
			$arrayEstrellasTrends[$key] = 0;
		}else{
			$arrayEstrellasTrends[$key] = round(($valor/$total_clientes)*100, 2);
		}
	}
	liberar ($rs);
	return $arrayEstrellasTrends;
}

function obtenerPrecioTrends($id_hotel)
{
	$sql = "SELECT rango_inf, rango_sup ";
	$sql2 = " FROM user_hotels
	INNER JOIN users ON users.id=user_hotels.id_usuario
	WHERE user_hotels.id_hotel='".$id_hotel."' 
	AND rango_sup!=0";
	//echo $sql;
	$rs = mysqli_query (conectar(), $sql.$sql2);
	
	$sql0 = "SELECT COUNT(DISTINCT(users.id)) AS n ";
	$rs2 = mysqli_query (conectar(), $sql0.$sql2);
	//echo $sql0.$sql2;
	$row2 = mysqli_fetch_assoc($rs2);
	liberar($rs2);
	$totalUsuarios = $row2['n'];
	
	$arrayRangoPrecios = array('0-300'=>'0', 
			'300-500'=>'0', 
			'500-1000'=>'0',
			'1000-3000'=>'0', 
			'3000-5000'=>'0'
			);
	
	$i=0;
	$nRangoPrecios = count($arrayRangoPrecios);
	while ($row = mysqli_fetch_assoc($rs))
	{
		//echo $row['rango_inf'].' - '.$row['rango_sup'].'<br>';
		foreach($arrayRangoPrecios as $key=>$value){
			$rangos = explode('-', $key);	
			//echo $rangos[0].' -> '.$rangos[1].': ('.$row['rango_inf'].' >= '.$rangos[0].' && '.$row['rango_inf'].' <= '.$rangos[1].') || 
			//('.$row['rango_sup'].' >= '.$rangos[0]. '&& '.$row['rango_sup'].' <= '.$rangos[1].')';
			
			if(($row['rango_inf']>=$rangos[0] && $row['rango_inf']<=$rangos[1]) || 
			($row['rango_sup']>=$rangos[0] && $row['rango_sup']<=$rangos[1]) ||
			($row['rango_inf']<=$rangos[0] && $row['rango_sup']>=$rangos[1])
			){
				//echo ' SI<br>';
				$arrayRangoPrecios[$key]=$arrayRangoPrecios[$key]+1;
			}else{
				//echo ' NO<br>';
			}
		}
		//echo '<br>';
		$i++;
	}
	liberar($rs);
	
	// %
	foreach($arrayRangoPrecios as $key=>$value)
	{
		if($totalUsuarios==0)
		{
			$arrayRangoPrecios[$key] = 0;
		}else{
			$arrayRangoPrecios[$key] = round(($value*100)/$totalUsuarios, 2);
		}
	}
	return $arrayRangoPrecios;
}

function obtenerTipoHotel($id_hotel)
{
	$sql = "SELECT COUNT(user_tipos_hotel.id_tipo_hotel) AS n, tipo_".$_SESSION['userLang']." AS tipo ";
	$sql2 = "FROM tipos_hotel
	LEFT JOIN user_tipos_hotel ON user_tipos_hotel.id_tipo_hotel=tipos_hotel.id_tipo_hotel
	LEFT JOIN user_hotels ON user_hotels.id_usuario=user_tipos_hotel.id_usuario
	WHERE user_hotels.id_hotel='".$id_hotel."'  OR user_tipos_hotel.id_tipo_hotel is NULL ";
	$sql3 = " GROUP BY tipo ORDER BY n DESC";
	//echo $sql.$sql2.$sql3;
	$rs = mysqli_query (conectar(), $sql.$sql2.$sql3);
	
	$sql0 = "SELECT COUNT(DISTINCT(user_hotels.id_usuario)) AS n ";
	$rs2 = mysqli_query (conectar(), $sql0.$sql2);
	
	$row2 = mysqli_fetch_assoc($rs2);
	liberar($rs2);
	$totalUsuarios = $row2['n'];
	
	$i=0; $array = array();
	while ($row = mysqli_fetch_assoc($rs))
	{
		foreach ($row as $key=>$valor)
		{
			if($totalUsuarios == 0)
			{
				$array[$row['tipo']] = 0;
			}else{
				$array[$row['tipo']] = round(($row['n']*100)/$totalUsuarios, 2);
			}
		}
		$i++;
	}
	return $array;
}

function obtenerDecoracionHotel($id_hotel)
{
	$sql = "SELECT COUNT(user_decoraciones.id_decoracion) AS n, 
	decoracion_".$_SESSION['userLang']." AS decoracion ";
	$sql2 = " FROM tipos_hotel_decoracion
	LEFT JOIN user_decoraciones ON user_decoraciones.id_decoracion=tipos_hotel_decoracion.id_decoracion
	LEFT JOIN user_hotels ON user_hotels.id_usuario=user_decoraciones.id_usuario
	WHERE user_hotels.id_hotel='".$id_hotel."' OR user_decoraciones.id_decoracion IS NULL ";
	$sql3 = " GROUP BY decoracion ORDER BY n DESC";
	//echo $sql.$sql2.$sql3.'<br>';
	$rs = mysqli_query (conectar(), $sql.$sql2.$sql3);
	
	$sql0 = "SELECT COUNT(DISTINCT(user_hotels.id_usuario)) AS n ";
	$rs2 = mysqli_query (conectar(), $sql0.$sql2);
	//echo $sql0.$sql2;
	$row2 = mysqli_fetch_assoc($rs2);
	liberar($rs2);
	$totalUsuarios = $row2['n'];
	
	$i=0; $array = array();
	while ($row = mysqli_fetch_assoc($rs))
	{
		foreach ($row as $key=>$valor)
		{
			if($totalUsuarios == 0)
			{
				$array[$row['decoracion']] = 0;
			}else{
				$array[$row['decoracion']] = round(($row['n']*100)/$totalUsuarios, 2);
			}
		}
		$i++;
	}
	liberar($rs);
	return $array;
}

function obtenerTipoHab($id_hotel)
{
	$sql = "SELECT COUNT(user_tipos_hab.id_tipo_hab) AS n, 
	tipo_hab_".$_SESSION['userLang']." AS tipo_hab ";
	$sql2 = " FROM tipos_hab_hotel
	LEFT JOIN user_tipos_hab ON user_tipos_hab.id_tipo_hab=tipos_hab_hotel.id_tipo_hab
	LEFT JOIN user_hotels ON user_hotels.id_usuario=user_tipos_hab.id_usuario
	WHERE user_hotels.id_hotel='".$id_hotel."' OR user_tipos_hab.id_tipo_hab is NULL ";
	$sql3 = " GROUP BY tipo_hab ORDER BY n DESC";
	//echo $sql.$sql2.$sql3.'<br>';
	$rs = mysqli_query (conectar(), $sql.$sql2.$sql3);
	
	$sql0 = "SELECT COUNT(DISTINCT(user_hotels.id_usuario)) AS n ";
	$rs2 = mysqli_query (conectar(), $sql0.$sql2);
	//echo $sql0.$sql2;
	$row2 = mysqli_fetch_assoc($rs2);
	liberar($rs2);
	$totalUsuarios = $row2['n'];
	
	$i=0; $array = array();
	while ($row = mysqli_fetch_assoc($rs))
	{
		foreach ($row as $key=>$valor)
		{
			if($totalUsuarios == 0)
			{
				$array[$row['tipo_hab']] = 0; 
			}else{
				$array[$row['tipo_hab']] = round(($row['n']*100)/$totalUsuarios, 2);				
			}
		}
		$i++;
	}
	liberar($rs);
	return $array;
}

function obtenerRoomFeatures($id_hotel)
{
	$sql = "SELECT COUNT(user_extras.id_extra) AS n, 
	extra_".$_SESSION['userLang']." AS extra ";
	$sql2 = " FROM extras_hotel
	LEFT JOIN user_extras ON user_extras.id_extra=extras_hotel.id_extra
	LEFT JOIN user_hotels ON user_hotels.id_usuario=user_extras.id_usuario
	WHERE user_hotels.id_hotel='".$id_hotel."' OR user_extras.id_extra is NULL ";
	$sql3 = " GROUP BY extra ORDER BY n DESC";
	//echo $sql.$sql2.$sql3.'<br>';
	$rs = mysqli_query (conectar(), $sql.$sql2.$sql3);
	
	$sql0 = "SELECT COUNT(DISTINCT(user_hotels.id_usuario)) AS n ";
	$rs2 = mysqli_query (conectar(), $sql0.$sql2);
	//echo $sql0.$sql2;
	$row2 = mysqli_fetch_assoc($rs2);
	liberar($rs2);
	$totalUsuarios = $row2['n'];
	
	$i=0; $array = array();
	while ($row = mysqli_fetch_assoc($rs))
	{
		foreach ($row as $key=>$valor)
		{
			if($totalUsuarios == 0)
			{
				$array[$row['extra']] = 0;
			}else{
				$array[$row['extra']] = round(($row['n']*100)/$totalUsuarios, 2);
			}
		}
		$i++;
	}
	liberar($rs);
	return $array;
}

function obtenerHotelServices($id_hotel)
{
	$sql = "SELECT COUNT(user_servicios.id_servicio) AS n, 
	servicio_".$_SESSION['userLang']." AS servicio ";
	$sql2 = " FROM servicios_hotel
	LEFT JOIN user_servicios ON user_servicios.id_servicio=servicios_hotel.id_servicio
	LEFT JOIN user_hotels ON user_hotels.id_usuario=user_servicios.id_usuario
	WHERE user_hotels.id_hotel='".$id_hotel."' OR user_servicios.id_servicio is NULL ";
	$sql3 = " GROUP BY servicio ORDER BY n DESC";
	//echo $sql.$sql2.$sql3.'<br>';
	$rs = mysqli_query (conectar(), $sql.$sql2.$sql3);
	
	$sql0 = "SELECT COUNT(DISTINCT(user_hotels.id_usuario)) AS n ";
	$rs2 = mysqli_query (conectar(), $sql0.$sql2);
	//echo $sql0.$sql2;
	$row2 = mysqli_fetch_assoc($rs2);
	liberar($rs2);
	$totalUsuarios = $row2['n'];
	
	$i=0; $array = array();
	while ($row = mysqli_fetch_assoc($rs))
	{
		foreach ($row as $key=>$valor)
		{
			if($totalUsuarios == 0)
			{
				$array[$row['servicio']] = 0;	
			}else{
				$array[$row['servicio']] = round(($row['n']*100)/$totalUsuarios, 2);	
			}			
		}
		$i++;
	}
	liberar($rs);
	return $array;
}

function obtenerRecibirOfertas($id_hotel)
{
	$sql = "SELECT COUNT(user_categoria_oferta.id_tipo_oferta) AS n, 
	categoria_".$_SESSION['userLang']." AS categoria ";
	$sql2 = " FROM categoria_oferta
	LEFT JOIN user_categoria_oferta ON categoria_oferta.id_categoria_oferta=user_categoria_oferta.id_tipo_oferta 
	LEFT JOIN user_hotels ON user_hotels.id_usuario=user_categoria_oferta.id_usuario
	WHERE user_hotels.id_hotel='".$id_hotel."' OR user_categoria_oferta.id_tipo_oferta is NULL ";
	$sql3 = " GROUP BY categoria ORDER BY n DESC";
	//echo $sql.$sql2.$sql3.'<br>';
	$rs = mysqli_query (conectar(), $sql.$sql2.$sql3);
	
	$sql0 = "SELECT COUNT(DISTINCT(user_hotels.id_usuario)) AS n ";
	$rs2 = mysqli_query (conectar(), $sql0.$sql2);
	//echo $sql0.$sql2;
	$row2 = mysqli_fetch_assoc($rs2);
	liberar($rs2);
	$totalUsuarios = $row2['n'];
	
	$i=0; $array = array();
	while ($row = mysqli_fetch_assoc($rs))
	{
		foreach ($row as $key=>$valor)
		{
			if($totalUsuarios == 0)
			{
				$array[$row['categoria']] = 0;
			}else{
				$array[$row['categoria']] = round(($row['n']*100)/$totalUsuarios, 2);				
			}
		}
		$i++;
	}
	liberar($rs);
	return $array;
}
?>