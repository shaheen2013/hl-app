<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

function calcular_edad($birth_date){
	return floor((time() - strtotime($birth_date))/31556926);
}
?>