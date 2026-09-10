<?php
include_once RUTA_DIR.LIB.'ips_acceso.php';

// FX para definir la constante de control INDEXCONTROLVAL, 
//para poder acceder a lib, controllers, models, views...
function defineIndexControlVal(){
	!defined('INDEXCONTROLVAL')? define("INDEXCONTROLVAL", "1"):'';	
}

//$apiAcces: 0 -> llamada interna 
//			 3787814a95a9460b9101c00240c40f5392a5a2862a479d4c8df6dc4ba7df4b36 -> llamada desde api (hash fijo)
function checkIpAccess($webservice, $hostname, $apiAccessHash=0){
	if(ENV != 'test'){
        //$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
        $apiAccess = ips_acceso_webservice($webservice, $hostname);
        if (!$apiAccess){
			echo 'No direct access allowed. 1';
			exit;
			return false;
		}else{
			if($apiAccessHash == '3787814a95a9460b9101c00240c40f5392a5a2862a479d4c8df6dc4ba7df4b36'){
				//Llamada desde una api
				defineIndexControlVal();
			}else{
				//echo '--'.strtolower($_SERVER['HTTP_X_REQUESTED_WITH']).'--';
				if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' ) {
					//Llamada al WS interna con ajax. 
					defineIndexControlVal();
				}else{
					//Esta accediendo al WS desde la URL. No por ajax
					echo 'No direct access allowed. 2';
					exit;
					return false;
				}
			}
		}
    }else{
		defineIndexControlVal();
	}
	return true;
}