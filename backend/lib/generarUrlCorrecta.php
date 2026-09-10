<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

// Generamos una url correcta del tipo (http://www.xxx.xxx) para links
function generarURLCorecta($url)
{
	if (substr($url, 0, 7) != 'http://' && substr($url, 0, 8) != 'https://' && $url!=''){
		/*if (substr($url, 0, 4) !=  'www.'){
			$url = 'http://www.'.$url;
		}else{*/
			$url = 'http://'.$url;
		//}
	}
	return $url;
}
?>