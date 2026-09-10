<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

// Falta: total elementos

/*function paginacion($totalElementos, $pagina, $elementosPagina){
	$elementoInicial = $pagina * $elementosPagina - $elementosPagina;
	$elementoFinal = $pagina * $elementosPagina - 1;
	//Devuelve: LIMI x, y
}*/

function paginacion2($consulta, $pagina, $elementosPagina){
	//echo '<br>'.$consulta;
	$rsConsulta = mysqli_query (conectar(1), $consulta);
	
	if($rsConsulta === FALSE) { 
		$totalElementos = 0;
	}else{
		$rowConsulta = mysqli_fetch_assoc($rsConsulta);
		global $log;
		$log->debug("",[$rowConsulta]);
		liberar ($rsConsulta);
		$totalElementos = $rowConsulta['N'];
	}
	
	$paginas = $totalElementos/$elementosPagina;
	$enteroPagina = explode ('.', $paginas);
	if(!empty($enteroPagina[1])){
		//guardamos el nº de paginas en session para pintar la paginacion
		$_SESSION['paginas'] = $paginas = $enteroPagina[0]+1;
	}else{
		$_SESSION['paginas'] = $paginas;
	}
	
	//guardamos si es primera pagina o ultima para pintar la paginacion
	//para desactivar <<
	if ($pagina == 1){
		$_SESSION['primeraPagina']=1;
	}else{
		$_SESSION['primeraPagina']=0;
	}
	//para desactivar >>
	if ($pagina == $paginas){
		$_SESSION['ultimaPagina']=1;
	}else{
		$_SESSION['ultimaPagina']=0;
	}
	return $totalElementos;
}

function paginacion_group_by($consulta, $pagina, $elementosPagina){
    //echo '<br>'.$consulta;
    $rsConsulta = lecturaArray( $consulta);

    if($rsConsulta === FALSE) {
        $totalElementos = 0;
    }else{
        global $log;
        $log->debug("",[sizeof($rsConsulta)]);
        $totalElementos = sizeof($rsConsulta);
    }

    $paginas = $totalElementos/$elementosPagina;
    $enteroPagina = explode ('.', $paginas);
    if(!empty($enteroPagina[1])){
        //guardamos el nº de paginas en session para pintar la paginacion
        $_SESSION['paginas'] = $paginas = $enteroPagina[0]+1;
    }else{
        $_SESSION['paginas'] = $paginas;
    }

    //guardamos si es primera pagina o ultima para pintar la paginacion
    //para desactivar <<
    if ($pagina == 1){
        $_SESSION['primeraPagina']=1;
    }else{
        $_SESSION['primeraPagina']=0;
    }
    //para desactivar >>
    if ($pagina == $paginas){
        $_SESSION['ultimaPagina']=1;
    }else{
        $_SESSION['ultimaPagina']=0;
    }
}
/*function paginacion3 ($consulta, $pagina, $elementosPagina){		
	$rsConsulta = mysqli_query (conectar(1), $consulta);
	//$rowConsulta = mysqli_fetch_assoc($rsConsulta);
	$totalElementos = $n_resultados = mysqli_num_rows($rsConsulta);
	liberar ($rsConsulta);

	$paginas = $totalElementos/$elementosPagina;
	$enteroPagina = explode ('.', $paginas);
	if(!empty($enteroPagina[1])){
		//guardamos el nº de paginas en session para pintar la paginacion
		$_SESSION['paginas'] = $paginas = $enteroPagina[0]+1;
	}else{
		$_SESSION['paginas'] = $paginas;
	}
	//guardamos si es primera pagina o ultima para pintar la paginacion
	//para desactivar <<
	if ($pagina == 1){
		$_SESSION['primeraPagina']=1;
	}else{
		$_SESSION['primeraPagina']=0;
	}
	//para desactivar >>
	if ($pagina == $paginas){
		$_SESSION['ultimaPagina']=1;
	}else{
		$_SESSION['ultimaPagina']=0;
	}
}*/

// Paginación
if(!empty($_GET['pag']) && $_GET['pag'] > $_SESSION['paginas']){
	$pagina = $_SESSION['paginas'];
}else if(!empty($_GET['pag'])){
	$pagina = mysqli_real_escape_string(conectar(1), $_GET['pag']);
}else{
	$pagina = 1;
}
?>