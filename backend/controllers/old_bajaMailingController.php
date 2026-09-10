<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

if( !empty($_GET['email']) && !empty($_GET['token']) )
{
	$email = $_GET['email'];
	$token = $_GET['token'];
	$tokenData = generaraTokenBajaMailing($email);
	if($tokenData['token'] == $token)
	{
		//El token corresponde al email, procedemos a la baja
		quitarNotificaciones($tokenData['id_usuario']);
		echo 'Notificaciones quitadas<br>';
	}else{
		echo 'Forbidden<br>';
	}
}
?>