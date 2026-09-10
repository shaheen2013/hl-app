<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

include_once LIB . 'obtenerdatosHotel.php';
include_once LIB . 'twitter.php';
include_once LIB . 'referral-share-actions.php';//guardarShareUsuario 
include_once LIB . 'socialMediaShareText.php';//social media share custom hotel text

//Recoge el GUID del hotel y busca la información del hotel
//obtenerIdHotelGUID($guid)
if(!empty($url['dir2'])){
	//es un GUID?
	$guidHotel = $url['dir2'];
	//Buscar hotel por GUID
	$hotel = obtenerIdHotelGUID($guidHotel);
	
	$transaction='';

	//Si no hay hotel 404
	if(empty($hotel)){
			header('Location: /'.$urlTree['404']);
	}else{

		//Si que existe el hotel, recogemos la info
		$datosHotel = getHotelData($hotel);

		//Obtener la oferta de Referral
		$ofertaReferralHotel = obtenerOfertaReferral($hotel);
		//Oferta post-stay hotel
		$ofertaPoststayHotel = obtenerOfertaStay($hotel, 'post');

		//Obtener ofertas de Goal
		$goals = obtenerGoalsHotel($hotel);

		//social media share custom hotel text
		$socialMediaShareText = getSocialMediaShareText($hotel, $_SESSION['userNavLang'], 'post');

		////////////////////////
		/////FACEBOOK///////////
		////////////////////////


		////////////////////////
		/////TWITTER////////////
		////////////////////////

		//Si quiere compartir con Twitter
		if (!empty ($_POST['twitterForm'])){  
			include (LIB . 'twitterLogin.php');
		}

		//Url (actual) a la que volver despues del callback de twitter.
		$_SESSION['twitterLoginRedirect']= 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

		//Twitter share
		if(!empty($_POST['refShareStep2twText']) && !empty($_SESSION['twd-id']) ){
			//Obtenemos id_usuario
			$id_usuario = obtenerIdUsuarioIdTwitter ($_SESSION['twd-id']);
			$tweetResult = sendTweet($id_usuario, $_POST['refShareStep2twText']);
			if($tweetResult[0]=='200'){
				//echo '--tweet enviado--';
				$resultGoal=shareStayGoalActions($id_usuario, $hotel, 'tw', $tweetResult[1], '4', $transaction);
				
				unset ($_SESSION['tw-msg']);
				if($resultGoal['code']=='401'){
					//Todavia tiene cupones stay sin canjear de ese hotel, le mostramos un msg de feedback
					$ok = array (false, '4055');
				}else if($resultGoal['code']=='200' || $resultGoal['code']=='404'){
					//Share ok feedback
					$ok = array (true, '2033');
				}
			}else{
				//echo '--tweet NO enviado--';
				$ok = array (false, '4056');
			}
		}
	}
}else{
	//Si no hay dir 2 404
	header('Location: /'.$urlTree['404']);
}
?>