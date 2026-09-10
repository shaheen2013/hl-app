<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

if (!empty ($_POST['submitInvite'])){
	include_once LIB.'enviarEmail.php';
	$hotelWebsite = mysqli_real_escape_string(conectar() , $_POST['hotelWebsite']);
	$email = mysqli_real_escape_string(conectar() ,  $_POST['email']);
	guardarDatosUserLanding($hotelWebsite, $email);

	include_once LANG.'email/invitacion-hotel-landing-en.php';
	include_once LIB.'plantillasMails/hotelWelcomeMail.php';
	mandarEmailMandrill('helpdesk@hotelinking.com','hotelinking',$asunto2,$cuerpo2);//->HL
	mandarEmailMandrillPlantilla($email, $hotelWebsite, $asunto, $cuerpo);//->US

	header ('Location: invite-thanks-hotel');
}
?>