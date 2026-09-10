<?php
include_once 'librerias.php';// Librerias básicas

//Variable de control para saber si ha pasado por index.php
define("INDEXCONTROLVAL", "1");

/*
*	Subcode errors:
*	
*	4011 : missing fbid
*	4012 : missing fbname
*	4013 : offer already redeemed by user
*	4014 : missing hlid
*	4015 : email empty
*	4016 : incorrect email format
*	4017 : email already in use
*	4018 : Auto referrer attempt
*	4019 : missing referrer_id
*/

include_once RUTA_DIR . LIB . 'crearNuevoUsuario.php';
include_once RUTA_DIR . LIB . 'referral-share-actions.php';
include_once RUTA_DIR . LIB . 'cookies.php';
include_once RUTA_DIR . LIB . 'tokens.php';

// Check si se ha llamado como web service
if(!empty($_POST) && !array_has($_POST, 'relogin_hotel_id')){

	//if fbemail is empty check if we have a user in HL with this facebook id
	if ($_POST['fbemail'] == 'undefined' && !empty($_POST['fbid']) && !empty($_POST['hlid'])){
		$user = checkFacebookUserEmail($_POST['fbid']);
		if (!empty($user)){
			$user_id = $user['id_usuario'];
			$hotel_id = $_POST['hlid'];
			//Crear link para share
			$urlShare = generarUrl($user['id_usuario'], 0, $_POST['hlid'], 1);
			$result['code']='200';
			$result['message']='OK';	
			$result['url']=$urlShare['url'];
			$result['user_id']=$user_id;
			$result['hotel_id']=$hotel_id;
			$referrer_token = getReferrerTokenByUserId($user_id, $hotel_id);
			$result['referrer_token'] = $referrer_token ? $referrer_token : generateToken($user_id, $hotel_id); 
			$result['subcodes'][]=array('code' =>'','message'=>'');//subcodes vacio
			echo json_encode($result);
			exit;
		}
	}
	if(!empty($_POST['fbid']) && !empty($_POST['fbname']) && (!empty($_POST['fbemail']) && $_POST['fbemail']!=='undefined')  && !empty($_POST['hlid'])){
		//Verificar datos correctos
		if( !filter_var($_POST['fbemail'], FILTER_VALIDATE_EMAIL)  )
		{
			// El email de FB no tiene el formato correcto 
			$subcodes[]=array('code' => '4016','message' => 'incorrect email format');//incorrect email format

			$result['code']='401';
			$result['message']='Some incorrect data';
			$result['subcodes'] = $subcodes;// array

			echo json_encode($result);
		}else{		
			// Datos correctos
			$hotel_id = $_POST['hlid']; 		// Id del hotel que realiza el action

			//user facebook public profile data
			$fbid = array_get($_POST, 'fbid'); 																			//facebook user ID
			$fbname = array_get($_POST, 'fbname');																			//facebook name
			$fbemail = array_get($_POST,'fbemail');																		//facebook email
			$fbfriends = array_get($_POST, 'fbfriends', 0 );  					//facebook friends
			$gender = array_get($_POST, 'gender',  '');								//facebook gender
			$fbLocale = array_get($_POST, 'locale', NULL);							//facebook locale			
			$lang = array_get($_POST, 'lang', 'en');										//lang
			$fbUserImage = 'https://graph.facebook.com/'.$fbid.'/picture?width=200';							//img
			$fbLink = 'https://www.facebook.com/app_scoped_user_id/'.$fbid.'/';									//link	
			$facebook_location_name = array_get($_POST, 'facebook_location_name');
			$facebook_location_id = array_get($_POST, 'facebook_location_id');
			$birthday = array_get($_POST, 'birthday', '');						//birthday


			// Check if this email is valid email
			if (filter_var($fbemail, FILTER_VALIDATE_EMAIL)) {
				if (ENV == 'production'|| VERIFY_EMAILS) {
						include_once RUTA_DIR . LIB . 'emailValidate.php';
						// El environment es producción, verificamos email
						$resultEmail = validateEmail($fbemail);
						if($resultEmail['valid']){
								$validEmail=true;
						} else {
							$result['code']='401';
							$result['message']='Forbidden action';;
							$result['subcodes'] = array('code' =>'4015','message'=>'Email not valid');
							echo json_encode($result);
							exit;
						}
				} else {
						// El environment NO es producción. No verificamos email, directamente permitimos envio.
						$resultEmail = array('code' => '200');
						$validEmail=true;
				}
			}

			$sendex = array_get($resultEmail, 'sendex', 0.0);
			$emailResult = array_get($resultEmail, 'result', 'risky');

				// crear usuario
			$facebook_user = array(
				'email' =>  $fbemail ,
				'name' => $fbname,
				'lang' => $lang,
				'gender' => $gender,
				'birthday' => $birthday,
				'hotel_id' => $hotel_id,
				'locale'=> $fbLocale,
				'facebook_id' =>$fbid,
				'facebook_link'=> $fbLink,
				'facebook_picture' => $fbUserImage,
				'facebook_friends' => $fbfriends,
				'facebook_location_id'=> $facebook_location_id,
				'facebook_location_name'=> $facebook_location_name
			);

			$user = createNewUser($facebook_user, $sendex, $emailResult, 'facebook');

			if ($user){
				$log->debug('user is ', array('user'=>$user));
			}

			if ($user['id']){
				//Create or update Facebook user
				$facebook_user['id'] = $user['id'];
				$res = upsertFacebookUser($facebook_user);

				// $log->debug('upsertFacebookUser response : '. $res);// if($rsSaveUser['code']=='200' || $rsSaveUser['code']=='300'){

						$urlShare = generarUrl($user['id'], 0, $hotel_id, 1);
						$result['code']='200';
						$result['message']='OK';	
						$result['url']=$urlShare['url'];
						$result['user_id']=$user['id'];
						$result['hotel_id']=$hotel_id;
						$referrer_token = getReferrerTokenByUserId($user['id'], $hotel_id);

						$result['referrer_token'] = $referrer_token ? $referrer_token : generateToken($user['id'], $hotel_id); 

					$result['subcodes'][]=array('code' =>'','message'=>'');//subcodes vacio

			}

			echo json_encode($result);
		}
	}else {
		//Faltan datos minimos necesarios de FB  
		$subcodes = '';
		if (empty($_POST['fbid']))
			$subcodes[]=array('code' =>'4011','message'=>'missing fbid');//Falta fbid

		if (empty($_POST['fbname']))
			$subcodes[]=array('code' =>'4012','message'=>'missing fbname');//Falta fbname

		if (empty($_POST['hlid']))
			$subcodes[]=array('code' =>'4014','message'=>'missing hlid');//Falta hlid

		if ( $_POST['fbemail']=='undefined' || empty($_POST['fbemail']) )
				$subcodes[]= array('code' =>'4015','message'=>'email empty');//email vacío o 'undefined'

		$result['code']='401';
		$result['message']='Some missing data';	
		$result['subcodes'] = $subcodes;// array
		
		echo json_encode($result);
	}
}
?>