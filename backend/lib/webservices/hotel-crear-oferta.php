<?php
//session_start();
include 'librerias.php';// Librerias básicas

// Restringir ips que pueden acceder
include_once RUTA_DIR.LIB.'check_access.php';
checkIpAccess('hcdo', $_SERVER['REMOTE_ADDR']);
	
function obtenerArrayAdqRet($offerMethod){
	$sql = "SELECT id_tipo_oferta, tipo_adq_ret_".$_SESSION['userLang']." 
	FROM tipos_oferta WHERE ".$offerMethod."=1 ORDER BY tipo_adq_ret_".$_SESSION['userLang'];
	$rs = mysqli_query (conectar(), $sql);
	// Opción por defecto (para mostrar todos los selects seleccionados)
	if (empty($_SESSION['offertype'])){
		$_SESSION['offertype']= 'ngr';
	}
	//$option = '<option>Selecciona una opción</option>';
	$option = '';
	$i=0;
	while ($row = mysqli_fetch_assoc($rs)){
		$t=0;
		foreach ($row as $value){
			if ($t==0){
				$option.= '<option value="'.$value.'"';
				if(!empty($_SESSION['offertype']) && $_SESSION['offertype']== $value){
					$option .=' selected ';
				}
				$option.= '>';
			}else if($t==1){
				$option.= $value.'</option>';
			}
			$t=1;
		}
		$i++;
	}
	liberar ($rs);
	echo ($option);
}

function obtenerCategoria($id_tipo_oferta){
	$sql = "SELECT id_categoria_oferta, categoria_".$_SESSION['userLang']." 
	FROM categoria_oferta WHERE id_tipo_oferta='".$id_tipo_oferta."' 
	ORDER BY categoria_".$_SESSION['userLang']."";
	$rs = mysqli_query (conectar(), $sql);
	if ($id_tipo_oferta=='chk'){
		$sql2 = "SELECT categoria_".$_SESSION['userLang']." AS selectOne FROM categoria_oferta
		WHERE id_tipo_oferta='so' ";
		$rs2 = mysqli_query (conectar(), $sql2);
		$row2 = mysqli_fetch_assoc($rs2);
		liberar($rs2);
		$option = '<option value="0">'.$row2['selectOne'].'</option>';
	}else{
		$option = '';
	}
	$i=0;
	while ($row = mysqli_fetch_assoc($rs)){
		$t=0;
		foreach ($row as $value){
			if ($t==0){
				$option.= '<option value="'.$value.'"';
				if(!empty($_SESSION['category']) && $_SESSION['category']==$value || $_SESSION['offertype']!='chk' ){
					$option .=' selected ';
				}
				$option.= '>';
			}else if($t==1){
				$option.= $value.'</option>';
			}
			$t=1;
		}
		$i++;
	}
	liberar ($rs);
	echo ($option);
}

function obtenerSubcategoria($id_categoria){
	$sql = "SELECT id, subcategoria_oferta_".$_SESSION['userLang']." 
	FROM subcategoria_oferta WHERE id_categoria_oferta='".$id_categoria."' ORDER BY subcategoria_oferta_".$_SESSION['userLang'];
	$rs = mysqli_query (conectar(), $sql);
	/*if ($_SESSION['offertype'] =='chk'){
		$option = '<option>Select one</option>';
	}else{*/
		$option = '';
	//}
	$i=0;
	while ($row = mysqli_fetch_assoc($rs)){
		if($i==0){
			//Dejamos el primero seleccionado por si salta de pantalla
			echo $row['id'];
			$_SESSION['subCategory']=$row['id'];
		}
		$t=0;
		foreach ($row as $value){
			if ($t==0){
				$option.= '<option value="'.$value.'"';
				if(!empty($_SESSION['subCategory']) && $_SESSION['subCategory']==$value  || $_SESSION['offertype'] !='chk' ){
					$option .=' selected ';
				}
				$option.= '>';
			}else if($t==1){
				$option.= $value.'</option>';
			}
			$t=1;
		}
		$i++;
	}
	liberar ($rs);
	echo ($option);
}

// Datos minimos para crear una oferta de Adq:
// Estrellas, rating, precio max hab., (temporada)
function puedeCrearAdq($id_hotel){
	$sql = "SELECT estrellas, rating, max_rango, 
	jan, feb, mar, apr, may, jun, jul, ago, sep, oct, nov, dece
	FROM hoteles WHERE id='".$id_hotel."' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar ($rs);
	// no permitimos que rating, max_rango o estrellas esten a 0
	// tampoco permitimos que el hotel esté cerrado todos los meses
	if ($row['rating']==0 || $row['max_rango']==0 || $row['estrellas']==0 || 
	($row['jan']==0 && $row['feb']==0 && $row['mar']==0 && $row['apr']==0 && $row['may']==0 && $row['jun']==0 && $row['jul']==0 && $row['ago']==0 && $row['sep']==0 && $row['oct']==0 && $row['nov']==0 && $row['dece']==0 )){
		//No tiene los datos minimos
		$puedeCrearAdq = 0;
	}else{
		$puedeCrearAdq = 1;
	}
	echo $puedeCrearAdq;
}

//  Pantalla ----------------------------------------------------
//  Tipología y categorización de la oferta  --------------------
if(!empty($_POST['offerMethod'])){
	//Guardamos en session el contenido del input
	$_SESSION['offerMethod']=mysqli_real_escape_string(conectar(), $_POST['offerMethod']);
	$arrayAdqRet = (obtenerArrayAdqRet($_SESSION['offerMethod']));
	
	if (isset($_SESSION['puntos'])){
		unset($_SESSION['puntos']);	
	}
}

if(!empty($_POST['offertype'])){ // tipo de oferta
	$_SESSION['offertype']=mysqli_real_escape_string(conectar(), $_POST['offertype']);
	$arrayCategorias = (obtenerCategoria($_SESSION['offertype']));
	if ($_SESSION['offertype'] != 'des'){
		unset ($_SESSION['descuento']);
	}
	$_SESSION['category']='';
	$_SESSION['subCategory']='';
}

if(!empty($_POST['category'])){
	//Guardamos en session el contenido del input
	$_SESSION['category']=mysqli_real_escape_string(conectar(), $_POST['category']);
	$arraySubcategorias = (obtenerSubcategoria($_SESSION['category']));
}

if(!empty($_POST['subCategory'])){// id categoria
	//Guardamos en session el contenido del input
	$_SESSION['subCategory']=mysqli_real_escape_string(conectar(), $_POST['subCategory']);
}
if (!empty($_POST['puedeCrearAdq'])){
	puedeCrearAdq($_SESSION['h_logueado']);	
}
?>