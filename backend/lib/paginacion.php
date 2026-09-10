<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

// v0.1 Admite arrays bidimensionales
// v0.2 Admite arrays bidimensionales con indices no numericos (2014-04-11)
// v0.3 Bug arreglado (2014-09-04)

function paginacion($array, $pagina, $elementosPagina){
	$total = count($array);
	$keys = array_keys($array[0]);//array con las keys del array a paginar
	$subTotal = count($keys);
	$paginas = $total/$elementosPagina;
	$enteroPagina = explode ('.', $paginas);
	if(!empty($enteroPagina[1])){
		//guardamos el nº de paginas en session para pintar la paginacion
		$_SESSION['paginas'] = $paginas = $enteroPagina[0]+1;
	}else{
		$_SESSION['paginas'] = $paginas;
	}
	if ($paginas <= 0){
		$paginas=1;
		return ($array);
	}else{
		$elementoInicial = $pagina * $elementosPagina - $elementosPagina;
		$elementoFinal = $pagina * $elementosPagina - 1;
		//echo ' i: '.$elementoInicial.' : '.$elementoFinal;
		$i=$elementoInicial;
		$s=0;
		while($i <= $elementoFinal){
			if(!empty($array[$i])){
				$t=0;
				while($t < $subTotal){
					$arrayPaginado[$s][$keys[$t]] = $array[$i][$keys[$t]];
					$t++;
				}
				$s++;
			}
			$i++;
		}
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
	
	//Devuelve el array paginado
	return ($arrayPaginado);
}

// Paginación
if(!empty($_GET['pag']) && $_GET['pag'] > $_SESSION['paginas']){
	$pagina = $_SESSION['paginas'];
}else if(!empty($_GET['pag'])){
	$pagina = mysqli_real_escape_string(conectar(), $_GET['pag']);
}else{
	$pagina = 1;
}
?>