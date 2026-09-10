<?php
/*
*	FX para crear/comprobar la cookie en el cliente, para evitar mandar el satisfaction/review varias veces seguidas en sucesivos logins
*
*	@productosHotel (array) contiene los productos contratados por el hotel (review, satisfaction...)
*	@id_hotel (int) id del hotel en HL
*	@id_usuario (int) id del usuario en HL
*
*	Return:
*	@cookieProductoOpinion (boolean)
*		true : el usuario ya tiene una cookie de producto de opinion, evitamos mandar nuevamente
*		false : el usuario NO tiene una cookie de producto de opinion. Esto puede ser debido a que es la primera vez o que ya ha caducado la cookie (15 days)
*
*	Observaciones:
*		satisfaction y review son excluyentes. Un hotel no puede tener ambos.
*
*   @author: Jaume Cabrer
*/
function crearCookieProductoOpinion($productosHotel, $id_hotel, $id_usuario)
{
	if($productosHotel['satisfaction']==1 || $productosHotel['review']==1)
	{
	    $producto = ($productosHotel['satisfaction']==1 ? 'satisfaction' : 'review');
	    $nombreCookie = substr($producto, 0, 3).'_'.$id_hotel.'_'.$id_usuario;
	    if( !isset($_COOKIE[$nombreCookie]) )
	    {
	        // Creamos una cookie para saber que ya se ha generado un producto de opinión (review/satisfaction). Caduca a los 15 días.
	        setcookie ($nombreCookie, '1', strtotime('+15 days') ,'/', '', true, true);
	        $cookieProductoOpinion = false;
	    }else{
	        $cookieProductoOpinion = true;
	    }
	}else{
	    $cookieProductoOpinion = false;
	}

	return $cookieProductoOpinion;
}

/*
*	FX que llama al CURL asincrono llamado en stay-share desde:
*		- Login por email
*		- Login por Facebook
*		- Share por facebook
*
*	Param:
* 	@arrayParametros (array) array de parametros necesarios para enviar al WS
*
*	Si curlAsync devuelve appConnectTime=0, significa que no se ha producido el handshake con el webservice y por lo tanto no se ha transmitido la info.
*	en este caso, enviamos un email
*
*	Return:
*	@code (int)
*		200 conexión establecida satisfactoriamente
*		404 no se ha podido realizar la conexión
*
*   @author: Jaume Cabrer
*/
function stayCurl ($arrayParametros)
{
	$urlWebservice = SECURE_BASE_PATH . LIB . 'webservices/emails-webservice.php/';
	$parametros = http_build_query($arrayParametros);
	//echo $urlWebservice.'?'.$parametros; // <---------for testing
    require_once LIB . 'curl.php';
    $curl = NEW curlAsync($urlWebservice, $parametros);
    $result = $curl->curlExecute();
    if($result['appConnectTime'] == 0 && ENV!='test')
    {
    	// curlAsync no ha llegado a conectarse, se ha alcanzado el timeout. Enviamos info a email de errores.
    	/*$tipo = 'Stay CURL not connected';
    	$description = $tipo.'<br>URL: ' . $urlWebservice.'<br>Parametros: ' . $parametros.'<br>CURL result: ' . json_encode($result, true);
    	include_once LIB . 'errores.php';
    	enviarErrorEmail($tipo, $description);*/

    	$result['code'] = 404;
    }else{
    	$result['code'] = 200;
    }

    return $result;
}
?>