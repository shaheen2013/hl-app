<?php
/*
*	FX para gestionar el envio de errores a un email
*
*	@asunto(string): descripción corta del tipo de error. 
*	@description(string): descripción del error
*/
function enviarErrorEmail($asunto, $description)
{
	$email='support@hotelinking.com';
	$description = '['.date('Y-m-d - H:i:s').'] Environment: '.ENV.'<br><br>'.$description;
	include_once RUTA_DIR . LIB . 'enviarEmail.php';
   	mandarEmailMandrill($email, 'Hotelinking', $asunto, $description);
}

function enviarWarningEmail($asunto, $description){
    $email='operaciones@hotelinking.com';
    $description = '['.date('Y-m-d - H:i:s').'] Environment: '.ENV.'<br><br>'.$description;
    include_once RUTA_DIR . LIB . 'enviarEmail.php';
    mandarEmailMandrill($email, 'Hotelinking', $asunto, $description);
}

/*
* 	FX para notificar por email errores de la base de datos
*
*	@tipo: tipo de consulta a base de datos (escritura, lecura ...)
*	@query : query que ha generado el error
*	@bd : base de datos sobre la que se ejecuta la query
*	@error: error mysqli
*/
function errorBD($tipo, $query, $bd, $error)
{
	global $logDB;
	$logDB->error($tipo, array('query' => $query, 'error' => $error));
	
	$asunto = 'Error '.$tipo;
	$pantalla = substr(SECURE_BASE_PATH, 0, -1). $_SERVER['REQUEST_URI'].$_SERVER['QUERY_STRING'];
    $description = $asunto.'<br>Pantalla: '.$pantalla.'<br>Sql: '.$query.'<br>DB: '.$bd.'<br>Error: '.$error;
    include_once RUTA_DIR . LIB . 'errores.php';
    enviarErrorEmail($asunto, $description);
}
?>