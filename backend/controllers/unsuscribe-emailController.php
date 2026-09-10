<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

$error = true;
$step = 0;
if (!empty($_GET)){
	//a esta Url se tiene que llegar con un email, si no es así rebotar a la landing.
	if(!empty($_GET['email'])){
		//Generamos el enlace
		//Con el código de referral del estilo de: hotelinking.com/unsuscribe-email/?m=x@x.com&c=342dsf34
		//Pedimos el código al modelo
		$mailCode = getEmailCode($_GET['email']);
		if($mailCode != NULL){
			$error = false;
			//generamos la URL
			$_SESSION['unsubUrl'] = BASE_PATH . $urlTree['unsuscribe-email'] . '/?m=' . $_GET['email'] . '&c=' . $mailCode['ref_code'];
			//envia un email de confirmación para hacer unsuscribe
		}else{
			//Si no hay email, mandamos un error.
			$error = true;	}

	}

	//Segundo paso, le ha dado al botón, mandamos mail
	if(!empty($_GET['confirm']) && !empty($_SESSION['unsubUrl'])){
		//echo $_SESSION['unsubUrl'];
		//Incluimos el idioma de los emails
		include_once LIB.'enviarEmail.php';
		include_once LANG.'en/email/unsub-email.php';
		include_once LIB.'plantillasMails/unsuscribe-email.php';
		mandarEmailMandrillPlantilla($_GET['confirm'], '', $asuntoUn, $cuerpoUn, 'standard-template');//->US
		$error = false;
		$step = 1;
	}

	//Tercer paso, vuelve del mail, hacemos unsub
	if(!empty($_GET['m']) && !empty($_GET['c'])){
		//echo 'vuelve del mail';
		//Pasamos la info de unsub al modelo
		$unsubUser = unsubUser($_GET['m'], $_GET['c']);
		//echo $unsubUser;
		if(!$unsubUser){
			$error = true;
		}else{
			$error = false;
			$step = 2;
		}
	}

}else{
	header('location:'.$urlTree['landing']);
}
?>