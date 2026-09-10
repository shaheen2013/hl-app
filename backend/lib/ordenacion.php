<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

function toggleArray(){ // Arrays
	// Campo ASC DESC (Toggle)
	if(empty($_SESSION['ascdesc'])){
		$_SESSION['ascdesc'] = 'DESC';
		$sort = 1;
	}else if ($_SESSION['ascdesc']=='DESC'){
		$_SESSION['ascdesc'] = 'ASC';
		$sort = '';
	}else if ($_SESSION['ascdesc']=='ASC'){
		$_SESSION['ascdesc'] = 'DESC';
		$sort = 1;
	}else{
		$_SESSION['ascdesc'] = 'DESC';
		$sort = 1;
	}
	return ($sort);
}

function toggle(){
	// Campo ASC DESC (Toggle)
	if(empty($_SESSION['ascdesc'])){
		$sort = $_SESSION['ascdesc'] = 'DESC';
	}else if ($_SESSION['ascdesc']=='DESC'){
		$sort = $_SESSION['ascdesc'] = 'ASC';
	}else if ($_SESSION['ascdesc']=='ASC'){
		$sort = $_SESSION['ascdesc'] = 'DESC';
	}else{
		$sort = $_SESSION['ascdesc'] = 'DESC';
	}
	return ($sort);
}

function orderMultiDimensionalArray ($toOrderArray, $field, $inverse = false) {  
    // para ordenación de fechas
	if (substr($field, 0, 5)=='fecha'){
		$field = $field.'_ord';
	}
	$position = array();
    $newRow = array();
    foreach ($toOrderArray as $key => $row) {  
            $position[$key]  = $row[$field];  
            $newRow[$key] = $row;  
    }  
	
	if ($inverse) {  
		arsort($position);  
	} else {  
		asort($position);  
	}
    $returnArray = array();  
    foreach ($position as $key => $pos) {       
        $returnArray[] = $newRow[$key];  
    }  
    return $returnArray;  
}
?>