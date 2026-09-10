<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

	include_once LIB.'enviarEmail.php';
	//Si hay form
	//Evitamos que haga spam de F5
	if(!empty($_SESSION['ip'])){
		header("Location: ".$urlTree['landing']);
	}

	if(!empty($_POST) && !array_has($_POST, 'relogin_hotel_id')){
		//echo '--ok, hay POST <br><br>';
		//Si es correcto
		if(!empty($_POST['hotelWebSite']) && !empty($_POST['hotelPhone']) && !empty($_POST['categoriaHotel']) && $_POST['categoriaHotel'] > 0){
			$categoriaHotelInvite = $_POST['categoriaHotel'];
			//echo '--ok, todo ok<br><br>';
			if($categoriaHotelInvite < 3){
				//echo '-- tiene demasiado poca categoría<br><br>';
				//Si el rating del hotel es menos a 3 estrellas, rechazar
				$msg ="rechazar";
				//Aún así introduce el hotel en la base de datos
				insertarHotel($_POST['id'], $_POST['hotelWebSite'],$_POST['hotelPhone'],$_POST['categoriaHotel']);
				$code = genString();
				//echo '-- Lo meto en la base de datos<br><br>';
				return $msg;
			}else{
				//Si es mayor igual o mayor a 3 estrellas Growth Haking time!
				//echo '-- Ok, datos correctos<br><br>';
				//echo '-- Lo meto en la base de datos<br><br>';
				$code = genString();
				$insertaHotel = insertarHotel($_POST['id'], $_POST['hotelWebSite'],$_POST['hotelPhone'],$_POST['categoriaHotel']);
				$emailHotel = getHotelEmail($_POST['id']);
				include_once LANG.$_SESSION['userLang'].'/email/invitacion-hotel-landing.php';
				include_once LIB.'plantillasMails/hotelWelcomeMail.php';
				//Si todo está OK, mandamos un mail de invitación
				if ($insertaHotel){
					if(!empty($_SESSION['referral'])){
						addReferral($_SESSION['referral'], $urlTree['landing']);
					};
					mandarEmailMandrill('helpdesk@hotelinking.com','hotelinking',$asunto2,$cuerpo2);//->HL
					mandarEmailMandrillPlantilla($emailHotel, $_POST['hotelWebSite'], $asunto, $cuerpo);//->US
				}else{
					$msg = 'Insert error';
					return $msg;
				}
				$exito = true;
			}
		}else{
			//Si algún campo es incorrecto devolverlo al paso anterior
			//Recoge los datos que ha rellenado para que no tenga que rellenarlos de nuevo.
			header("Location: ".$urlTree['landing-invite-hotel']."/?website=".$_POST['hotelWebSite']."&phone=".$_POST['hotelPhone']."");
		}
	}else{
		//Si el formulario está vacio devolverlo a la landing
		header("Location: ".$urlTree['landing']);
	}

function genString() {
	//Generamos un código, el número es lo largo.
	$code = bin2hex(openssl_random_pseudo_bytes(6));
	//Si el código no existe lo asignamos, si ya existe, asignamos otro
	$codetester = false;
	while ( $codetester == false) {
		$codetester = testCode($code, $_POST['id']);
	};
	return $code;
}

//añadimos un referral al usuario del código
function addReferral($referral, $redirect){
	$referralok = updateReferrals($referral);
	$_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
	$yourCode = BASE_PATH . 'landing/?referral=' .$_SESSION['referral'];
	$UnsubUrl = BASE_PATH . 'unsuscribe-email/?email=' .$referralok['email'];
	//en función del número de referrals que tenga, le mandamos un mail diferente:
	$isUnsub = getUnsubState($referralok['id']);
	if ($isUnsub == '0'){
		include_once LANG.$_SESSION['userLang'].'/email/growthHacking-mails.php';
		switch ($referralok['referrals']) {
			case $referralok['referrals'] == 1:
				include_once LIB.'plantillasMails/1stinvite.php';
				mandarEmailMandrillPlantilla($referralok['email'], $emailHotel, $asunto1st, $cuerpo1st, 'standard-template');//->US
				break;
			case $referralok['referrals'] == 2:
				include_once LIB.'plantillasMails/2ndinvite.php';
				mandarEmailMandrillPlantilla($referralok['email'], $emailHotel, $asunto2nd, $cuerpo2nd, 'standard-template');//->US
				break;
			case $referralok['referrals'] == 3:
				include_once LIB.'plantillasMails/3rdinvite.php';
				mandarEmailMandrillPlantilla($referralok['email'], $emailHotel, $asunto3rd, $cuerpo3rd, 'standard-template');//->US
				break;
			default:
				# code...
				break;
		}
	}
}

?>
