<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

	//Hay que poner esta página en HTTPS
	if(!empty($_POST['email'])){
		//Si ha mandado el mail lo guardamos y le dejamos continuar
		//Si no, le damos un error y lo devolvemos a la página anterior
		$hotelEmail = $_POST['email'];
		$stepOne = true;
		//Inserta el email en la base de datos
		$insertaEmail = insertaEmail($hotelEmail);
		if(!$insertaEmail){
		 	header("Location: ".$urlTree['landing']."/?error=mail_error");
		}else{
			//Mandar un mail de aviso a Comercial
			include_once LIB.'enviarEmail.php';
			include_once LANG.$_SESSION['userLang'].'/email/invitacion-hotel-landing.php';
			mandarEmailMandrill('helpdesk@hotelinking.com','hotelinking',$asunto3,$cuerpo3);//->HL
			//guarda en sesión el invite para que no se haga bruteforce
			if(!empty($_SESSION['id-invite'])){
				$idInvite = $_SESSION['id-invite'];
			}
		}
	}else{
		$stepOne = false;
	}

	if(!empty($_GET['website']) && !empty($_GET['phone'])){
		$stepOne = true;
		$fillRating = true;
		$idInvite = $_SESSION['id-invite'];
	}
 ?>