<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

include_once LIB.'permisosUsuario.php';

include_once LIB.'sanitize.php';

function obtenerPreferenciasUsuario (){
	$sql = "SELECT minEstrellas AS es1, maxEstrellas AS es2, rango_inf AS pr1, rango_sup AS pr2 FROM users WHERE id='".$_SESSION['u_logueado']."' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	$arrayPreferenciasUsuario['es1']=$row['es1'];
	$arrayPreferenciasUsuario['es2']=$row['es2'];
	$arrayPreferenciasUsuario['pr1']=$row['pr1'];
	$arrayPreferenciasUsuario['pr2']=$row['pr2'];
	return($arrayPreferenciasUsuario);
}

function obtenerDecoracionesUsuario(){ // Decoraciones
	$sql = "SELECT id_decoracion FROM user_decoraciones WHERE id_usuario='".$_SESSION['u_logueado']."' ";
	$rs = mysqli_query (conectar(), $sql);
	$i=0;
	while ($row = mysqli_fetch_assoc($rs)){
		$arrayPreferenciasUsuario[]=$row['id_decoracion'];
		$i++;
	}
	liberar($rs);
	return ($arrayPreferenciasUsuario);
}

function obtenerExtrasUsuario(){ // Extras
	$sql="SELECT id_extra FROM user_extras WHERE id_usuario='".$_SESSION['u_logueado']."' ";
	$rs = mysqli_query (conectar(), $sql);
	$i=0;
	while ($row = mysqli_fetch_assoc($rs)){
		$arrayExtrasUsuario[]=$row['id_extra'];
		$i++;
	}
	liberar($rs);
	return ($arrayExtrasUsuario);
}

function obtenerServiciosUsuario(){// Servicios
	$sql = "SELECT id_servicio FROM user_servicios WHERE id_usuario='".$_SESSION['u_logueado']."' ";
	$rs = mysqli_query (conectar(), $sql);
	$i=0;
	while ($row = mysqli_fetch_assoc($rs)){
		$arrayServiciosUsuario[]=$row['id_servicio'];
		$i++;
	}
	liberar($rs);
	return ($arrayServiciosUsuario);
}

function obtenerTiposHotelUsuario(){// user_tipos_hotel
	$sql = "SELECT id_tipo_hotel FROM user_tipos_hotel WHERE id_usuario='".$_SESSION['u_logueado']."' ";
	$rs = mysqli_query (conectar(), $sql);
	$i=0;
	while ($row = mysqli_fetch_assoc($rs)){
		$arrayTiposHotelUsuario[]=$row['id_tipo_hotel'];
		$i++;
	}
	liberar($rs);
	return ($arrayTiposHotelUsuario);
}

function obtenerTiposHabUsuario(){// user_tipos_hab
	$sql = "SELECT id_tipo_hab FROM user_tipos_hab WHERE id_usuario='".$_SESSION['u_logueado']."' ";
	$rs = mysqli_query (conectar(), $sql);
	$i=0;
	while ($row = mysqli_fetch_assoc($rs)){
		$arrayTiposHabUsuario[]=$row['id_tipo_hab'];
		$i++;
	}
	liberar($rs);
	return ($arrayTiposHabUsuario);
}

function ofertaEnWishlist($id_oferta){
	$sql = "SELECT id FROM user_wishlist 
	WHERE id_oferta='".$id_oferta."' AND id_usuario='".$_SESSION['u_logueado']."' 
	LIMIT 1" ;
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	if($row['id']!=''){
		return true;
	}else{
		return false;
	}
}

// Ofertas tienda -------------------------------------------------------------
function obtenerOfertas($arrayWheres, $arrayPrefUsuarioExtras, $arrayPrefUsuarioServicios, $arrayPrefUsuarioTiposHab, $itemsPage, $pagina ){
	$arrayOfertas = array();
	
	$sql = "SELECT DISTINCT hotel_oferta.id,
	hotel_oferta.puntos , hotel_oferta.inicio, hotel_oferta.fin,
	hotel_oferta.img, hotel_oferta.descuento, hotel_oferta.requerimientos,
	case when oferta_lang.nombre is null 
    then  oferta_en.nombre 
    else oferta_lang.nombre end as nombre,
	
	hotel_oferta.adq_ret, hotel_oferta.id_tipo_oferta,
	CASE WHEN hotel_oferta.cupo!=0 THEN hotel_oferta.cupo-hotel_oferta.adquiridas 
	WHEN hotel_oferta.cupo=0 THEN 1000 END AS quedan,
	categoria_oferta.categoria_es AS categoria, categoria_oferta.id_categoria_oferta,
	hoteles.hotelName, hoteles.estrellas, hoteles.rating, hoteles.city, 
	hoteles.id AS id_hotel, ";
	// Preferencias usuario activadas (campos)
	if(!empty($_SESSION['pref_usuario']) && $_SESSION['pref_usuario']==1){
		// Rango precios
		$sql .= " hoteles.min_rango, hoteles.max_rango, ";
		// Tipo de hotel
		$sql .= " hoteles.tipo_hotel, ";
		// Decoración hotel
		$sql .= " hoteles.decoracion, ";
		// Tipos de habitaciones
		// Extras en habitaciones
		// Servicios hotel
	}
	$sql .= " IFNULL (hoteles.logo,0) AS logo ";
	$sql2 = " FROM hotel_oferta
	INNER JOIN categoria_oferta ON 	hotel_oferta.id_categoria=categoria_oferta.id_categoria_oferta
	INNER JOIN hoteles ON hotel_oferta.id_hotel=hoteles.id
	LEFT JOIN hotel_oferta_lang as oferta_en on hotel_oferta.id = oferta_en.id_oferta  and oferta_en.lang='en' 
    LEFT JOIN hotel_oferta_lang as oferta_lang on hotel_oferta.id = oferta_lang.id_oferta and oferta_lang.lang='".$_SESSION['userNavLang'] . "'  ";
	// Cadena 
	$sql2 .= " LEFT JOIN cadena_hotel ON cadena_hotel.id_hotel=hoteles.id ";
	$sql2 .= " LEFT JOIN cadena ON cadena.id=cadena_hotel.id_cadena ";
	// Preferencias usuario activadas (tablas)
	if(!empty($_SESSION['pref_usuario']) && $_SESSION['pref_usuario']==1){
		// Tipos de habitaciones
		$sql2 .= " LEFT JOIN hotel_tipos_hab ON hoteles.id=hotel_tipos_hab.id_hotel ";
		// Extras en habitaciones
		$sql2 .= " LEFT JOIN hotel_extras ON hoteles.id=hotel_extras.id_hotel ";
		// Servicios hotel
		$sql2 .= " LEFT JOIN hotel_servicios ON hoteles.id=hotel_servicios.id_hotel ";
	}
	
	foreach ($arrayWheres as $key => $value) {
		if ($value==''){
			$arrayWheresVacio=true;
		}else{
			$arrayWheresVacio=false;
			break;
		}
	}
	$sql2 .=" WHERE ";
	$t=0;
	if ($arrayWheresVacio==false){
		
		// Lugar (Ciudad, provincia, pais, natural_feature)
		if ($arrayWheres['place_type']!='' && $arrayWheres['place_type']=='locality'){
			//Búsqueda por localidad
			$sql2 .=" ( hoteles.place_name = '".$arrayWheres['place_name']."' )";
			$t=1;
		}else if ($arrayWheres['place_type']!='' && $arrayWheres['place_type']=='country'){
			//Búsqueda por país
			$sql2 .=" ( hoteles.place_country = '".$arrayWheres['place_country']."' )";
			$t=1;
		}else if ($arrayWheres['place_type']!='' && $arrayWheres['place_type']=='administrative_area_level_1'){
			//Búsqueda por provincia
			$sql2 .=" ( hoteles.place_adm_area = '".$arrayWheres['place_adm_area']."' )";
			$t=1;
		}else if ($arrayWheres['place_type']!='' && $arrayWheres['place_type']=='natural_feature'){
			//Búsqueda por natural_feature (seguramente isla). Búscamos entre coordenadas
			$sql2 .=" ( (hoteles.lat BETWEEN  '".$arrayWheres['latSW']."' AND '".$arrayWheres['latNE']."')
			AND (hoteles.lng BETWEEN  '".$arrayWheres['lngSW']."' AND '".$arrayWheres['lngNE']."') )";
			$t=1;	
		}else if ($arrayWheres['cty']!='' && $arrayWheres['place_type']=='' ){
			$sql2 .=" ( hoteles.place_name LIKE '%".$arrayWheres['cty']."%' 
			OR hoteles.city LIKE '%".$arrayWheres['cty']."%' ) ";
			$t=1;
		}
		
		// Hotel (ID)
		if ($arrayWheres['hot']!=''){
			if($t==1){
				$sql2 .=" AND ";
			}
			$sql2 .=" hoteles.id='".$arrayWheres['hot']."' ";
			$t=1;
		}
		// Hotel/Cadena (Nombre) Restringir longitud búsqueda (3)??
		if ($arrayWheres['hcn']!=''){
			if($t==1){
				$sql2 .=" AND ";
			}
			$sql2 .=" ((hoteles.hotelName) LIKE ('%".$arrayWheres['hcn']."%') ";
			$sql2 .= "OR (cadena.nombre)  LIKE ('%".$arrayWheres['hcn']."%')) ";
			$t=1;
		}
		// Cadena (ID)
		if ($arrayWheres['cad']!=''){
			if($t==1){
				$sql2 .=" AND ";
			}
			$sql2 .=" cadena_hotel.id_cadena='".$arrayWheres['cad']."' ";
			$t=1;
		}
		
		if ($arrayWheres['from']!=''){//fecha_desde----------------------
			if($t==1){
				$sql2 .=" AND ";
			}
			$sql2 .=" hotel_oferta.inicio<='".girarFecha($arrayWheres['from'])."' 
			AND (hotel_oferta.fin>='".girarFecha($arrayWheres['from'])."'
			OR hotel_oferta.fin=0000-00-00) ";
			$t=1;
		}
		if ($arrayWheres['till']!=''){//fecha_hasta----------------------
			if($t==1){
				$sql2 .=" AND ";
			}
			$sql2 .=" (hotel_oferta.inicio<='".girarFecha($arrayWheres['till'])."' 
			AND hotel_oferta.fin>='".girarFecha($arrayWheres['till'])."'
			OR hotel_oferta.fin=0000-00-00) ";
			$t=1;
		}
		//puntos_desde 
		if ($arrayWheres['pt1']!=''){
			if($t==1){
				$sql2 .=" AND ";
			}
			$sql2 .=" hotel_oferta.puntos >= '".$arrayWheres['pt1']."' ";
			$t=1;
		}
		//puntos_hasta
		if ($arrayWheres['pt2']!=''){
			if($t==1){
				$sql2 .=" AND ";
			}
			$sql2 .=" hotel_oferta.puntos <='".$arrayWheres['pt2']."'  ";
			$t=1;
		}
		//estrellas desde (tb en preferencias de usuario)
		if ($arrayWheres['es1']!='' ){
			if($t==1){
				$sql2 .=" AND ";
			}
			$sql2 .=" hoteles.estrellas >= '".$arrayWheres['es1']."' ";
			$t=1;
		}
		//estrellas hasta (tb en preferencias de usuario)
		if ($arrayWheres['es2']!=''){
			if($t==1){
				$sql2 .=" AND ";
			}
			$sql2 .=" hoteles.estrellas <= '".$arrayWheres['es2']."' ";
			$t=1;
		}
		// Rating
		if ($arrayWheres['rat1']!='' && $arrayWheres['rat2']!=''){
			if($t==1){
				$sql2 .=" AND ";
			}
			$sql2 .=" hoteles.rating BETWEEN ".$arrayWheres['rat1']." 
			AND ".$arrayWheres['rat2']." ";
			$t=1;
		}
		//Adquisición, retención (adq / ret)
		if ($arrayWheres['adre']!=''){
			if($t==1){
				$sql2 .=" AND ";
			}
			$sql2 .=" hotel_oferta.adq_ret = '".$arrayWheres['adre']."' ";
			$t=1;
		}
		// Requerimientos (noches=0)
		// Solo muestra las ofertas que no requieren pasar ninguna noche
		if ($arrayWheres['req']=='0'){
			if($t==1){
				$sql2 .=" AND ";
			}
			$sql2 .=" hotel_oferta.requerimientos = '0' "; 
			$t=1;
		}
		// Categorias
		if ($arrayWheres['cat'] !=''){
			if($t==1){
				$sql2 .=" AND ";
			}
			$sql2 .=" categoria_oferta.id_categoria_oferta='".$value."'";
			$t=0;
		}
		/*$c=0; // Version futura, se podran elegir varias categorias a la vez
		foreach ($arrayMinCategorias as $key => $value){
			if ($arrayWheres[$value] !=''){
				if($t==1){
					$sql .=" AND ";
				}
				if($c==1){
					$sql .=" OR ";
				}
				$sql .=" categoria_oferta.id_categoria_oferta='".$value."'";
				$t=0;
				$c=1;
			}
		}*/
		// Preferencias usuario activadas (WHEREs)------------------------------------
		// Extras en habitaciones
		if(!empty($arrayPrefUsuarioExtras)){
			foreach ($arrayPrefUsuarioExtras as $key2 => $value2){
				if($t==1){
					$sql2 .=" AND (";
				}
				if($c==1){
					$sql2 .=" OR ";
				}
				$sql2 .= " hotel_extras.id_extra = '".$key2."' ";
				$t=0;
				$c=1;
			}
			$sql2 .=")";
			$t=1;
			$c=0;
		}
		// Rango precios desde -> hasta
		if (!empty($arrayWheres['pr1']) && $arrayWheres['pr1']!='' && !empty($arrayWheres['pr2']) && $arrayWheres['pr2']!=''){
			if($t==1){
				$sql2 .=" AND ";
			}
			$sql2 .=" hoteles.min_rango BETWEEN '".$arrayWheres['pr1']."' AND 
			'".$arrayWheres['pr2']."' ";
			$t=1;
		}
		// Servicios hotel
		if(!empty($arrayPrefUsuarioServicios)){
			foreach ($arrayPrefUsuarioServicios as $key2 => $value2){
				if($t==1){
					$sql2 .=" AND (";
				}
				if($c==1){
					$sql2 .=" OR ";
				}
				$sql2.= " hotel_servicios.id_servicio = '".$key2."' ";
				$t=0;
				$c=1;
			}
			$sql2 .=")";
			$t=1;
			$c=0;
		}
		// Tipos de habitaciones
		if(!empty($arrayPrefUsuarioTiposHab)){
			foreach ($arrayPrefUsuarioTiposHab as $key2 => $value2){	
				
				if($t==1){
					$sql2 .=" AND (";
				}
				if($c==1){
					$sql2 .=" OR ";
				}		
				$sql2 .= " hotel_tipos_hab.id_tipo_hab = '".$key2."' ";
				$t=0;
				$c=1;
			}
			$sql2 .=")";
			$t=1;
			$c=0;
		}
		//---------------------------------------------------------
		// $sql2 .= " AND ";
	}
	// Mostramos solo las publicadads (estado = 1)!!!
	// $sql2 .= " estado=1 ";
	//Mostramos las que no son de referral
	// $sql2 .= " AND hotel_oferta.adq_ret != 'ref' ";
	// Mostramos solo las ofertas que no han caducado
	$sql2 .= " AND (hotel_oferta.fin>= CURRENT_DATE OR hotel_oferta.fin=0000-00-00) ";
	// Mostramos solo si tienen quota left >1
	$sql3 = " GROUP BY hotel_oferta.id HAVING quedan>=1 ";
	
	$inicio = $itemsPage*$pagina-$itemsPage;
	$sql4 = " LIMIT ".$inicio.",".$itemsPage;
	//echo $sql.$sql2.$sql3.$sql4;	
	
	$rs = mysqli_query (conectar(), $sql.$sql2.$sql3.$sql4);
	$i=0;
	while ($row = mysqli_fetch_assoc($rs)){
		foreach ($row as $key=>$valor){
			if ($key == 'inicio' || $key == 'fin' ){
				$arrayOfertas[$i][$key] = girarFecha($valor);
				$fecha = explode ('-', $valor);
				$arrayOfertas[$i][$key.'_ord'] = $fecha[0].$fecha[1].$fecha[2];
			}else if ($key == 'nombre'){
				$arrayOfertas[$i][$key] = $valor;
			}else if ($key == 'logo'){
				$arrayOfertas[$i][$key] = 'small_'.$valor;
			}else if ($key == 'img'){
				$arrayOfertas[$i][$key] = 'med_'.$valor;
			}else if ($key == 'hotelName'){
				$arrayOfertas[$i][$key] = $valor;
				$arrayOfertas[$i]['hotel_nombre_san'] = string_sanitize($valor);
			}else if ($key == 'id'){// puede adquirir oferta
				$arrayOfertas[$i][$key] = $valor;
				if(!empty($_SESSION['u_logueado'])){
					if(puedeAdquirirOferta($valor, $_SESSION['u_logueado'])){//----puedeAdquirirOferta
						$arrayOfertas[$i]['puedeAdquirir']='1';
					}else{
						$arrayOfertas[$i]['puedeAdquirir']='0';
					}
					if(ofertaEnWishlist($valor)){//------------wishlist
						$arrayOfertas[$i]['wishlist']='1';
					}else{
						$arrayOfertas[$i]['wishlist']='0';
					}
				}else{
					$arrayOfertas[$i]['puedeAdquirir']='0';
					$arrayOfertas[$i]['wishlist'] = '0';
				}
			}else{
				$arrayOfertas[$i][$key] = htmlentities($valor, ENT_QUOTES, "ISO-8859-1");
			}
		}
		$arrayOfertas[$i]['url']= string_sanitize($row['nombre']);
		$arrayOfertas[$i]['urlGUID']= obtenerUrlGUIDHotel($row['id_hotel']);
		$i++;
	}
	$sql0 = "SELECT COUNT(DISTINCT(hotel_oferta.id)) as N ";
	paginacion2($sql0.$sql2, $pagina, $itemsPage);
	liberar($rs);
	return ($arrayOfertas);
}

// Para la función "stringBusqueda"
function obtenerNombreHotel($id_hotel){
	$sql = "SELECT hotelName FROM hoteles WHERE id='".$id_hotel."' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	return ($row['hotelName']);
}

// Para la función "stringBusqueda"
/*function obtenerNombreCiudad($id_ciudad){
	$sql = "SELECT nombre_es FROM ciudad WHERE id='".$id_ciudad."' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	return ($row['nombre_es']);
}*/

// Para la función "stringBusqueda"
function obtenerNombreCadena($id_cadena){
	$sql = "SELECT nombre FROM cadena WHERE id='".$id_cadena."' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	return ($row['nombre']);
}

// Devuelve array como el de wheres pero con nombres en lugar de ID's
// para mostrar un string con los detalles de la búsqueda mostrada
function stringBusqueda($arrayWheres){
	foreach ($arrayWheres as $key => $value){
		/*if ($key == 'cty'){
			$arrayStringBusqueda['cty_name']=obtenerNombreCiudad($value);
			$arrayStringBusqueda[$key]=$value;
		}else */if($key == 'hot'){
			$arrayStringBusqueda['hot_name']=obtenerNombreHotel($value);
			$arrayStringBusqueda[$key]=$value;
		}else if ($key == 'cad'){
			$arrayStringBusqueda['cad_name']=obtenerNombreCadena($value);
			$arrayStringBusqueda[$key]=$value;
		}else{
			$arrayStringBusqueda[$key]=$value;
		}
	}
	return ($arrayStringBusqueda);
}

function obtenerWhislist($id_usuario){
	$sql = "SELECT id_oferta
	FROM user_wishlist
	WHERE id_usuario='".$id_usuario."' ";
	$rs = mysqli_query (conectar(), $sql);
	$n=0;
	$arrayWhislist = array();
	while ($row = mysqli_fetch_assoc($rs)){
		$arrayWhislist[$n]=$row['id_oferta'];
		$n++;
	}
	liberar($rs);
	return ($arrayWhislist);
}

// obtenemos las categorias que son de checkin
function obtenerCategorias(){
	$sql = "SELECT id_categoria_oferta, 
	categoria_".$_SESSION['userLang']." AS categoria
	FROM categoria_oferta
	WHERE id_tipo_oferta = 'chk'";
	$rs = mysqli_query (conectar(), $sql);
	$i=0;
	while ($row = mysqli_fetch_assoc($rs)){
		$arrayCategorias[$i]['id']=$row['id_categoria_oferta'];
		$arrayCategorias[$i]['categoria']=$row['categoria'];
		$i++;
	}
	liberar($rs);
	return($arrayCategorias);
}
?>