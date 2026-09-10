<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

	//Include language from browser
	include LANG . $_SESSION['userLang'] . '/landing-new.php';
	$landingMailError = false;
	//Si hay un error muestra el mensaje de error
	//Si hay un referral
	if(!empty($_GET)){
		if(!empty($_GET['error']) && $_GET['error'] == 'mail_error' ){
			$landingMailError = true;
		}else if(!empty($_GET['referral'])){
			$_SESSION['referral'] = $_GET['referral'];
		}
	}

 ?>