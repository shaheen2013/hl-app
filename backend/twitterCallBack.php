<?php
!defined('INDEXCONTROLVAL')? define("INDEXCONTROLVAL", "1"):'';	
session_start();

include_once 'app/config.php';
include_once 'models/_indexModel.php';
include_once LIB.'agregarPuntos.php';
include_once LIB.'loguearUsuario.php';
include_once LANG.'en/errores.php';
include_once LIB.'crearNuevoUsuario.php';
include_once LIB.'twitter.php';
//include_once LIB.'fecha.php';
include_once LIB.'referrer.php';
include_once LANG.'en.php';
include_once LIB.'alertas.php';
include_once LIB.'vincularRedesSociales.php';
include_once LIB.'invitaciones.php';
include_once LIB.'guardarSpentNights.php';

include_once(LIB . 'twitter-async/EpiCurl.php');
include_once(LIB . 'twitter-async/EpiOAuth.php');
include_once(LIB . 'twitter-async/EpiTwitter.php');
include_once(LIB . 'twitter-async/keyTwitter.php');//keys winhotel / hotelinking

function existeUsuarioTwitter($id_twitter){
	$sql_usuario = "SELECT id_usuario, twitter_user FROM user_twitter WHERE id_twitter = '".$id_twitter."' ";
	$rs_usuario = mysqli_query (conectar(1), $sql_usuario);
	$usuario = mysqli_fetch_array($rs_usuario);
	liberar($rs_usuario);
	return $usuario;
}

if (isset($_GET['oauth_token'])){
	echo '<!--1-->';
	$twitterObj = new EpiTwitter($consumer_key, $consumer_secret);
	echo '<!--2-->';
	$twitterObj->setToken($_GET['oauth_token']);
	echo '<!--3-->';
	$token = $twitterObj->getAccessToken();
	echo '<!--4-->';
	$twitterObj->setToken($token->oauth_token, $token->oauth_token_secret);
	echo '<!--5-->';
	$userdata = $twitterObj->get_accountVerify_credentials(array('include_email'=>'true'));
	echo '<!--6-->';
	$id_twitter = $userdata->id;
	$_SESSION['username'] = $userdata->screen_name;
	// Guardamos la imagen 400x400 en lugar de la 'normal'
	$_SESSION['image'] = str_replace('normal', '400x400', $userdata->profile_image_url);
	$followers= $userdata->followers_count;
	$_SESSION['name']= $userdata->name;
	$lang= $userdata->lang;
	$location= $userdata->location;
	$twEmail= $userdata->email;
	echo '<!--7-->';
	$oauth_token=$token->oauth_token;
	$oauth_token_secret=$token->oauth_token_secret;
	echo '<!--8-->';
	/*echo '<!--<pre>';
	print_r ($userdata);
	echo '</pre>-->';*/

	if (!isset ($_COOKIE['token_lg'])){
		$_COOKIE['token_lg'] = '0';
	}
	
	if(!empty($_SESSION['twitterLoginRedirect'])){
		//Actions para Share
		//$_SESSION['twitterLoginRedirect'] : pantalla de origen
		//En esta pantalla no guardamos nada ya que twitter no nos proporciona el email, que es un dato 
		// clave para nuestra APP
		// Los datos se guardarán en la pantalla de origen ya que si comparte con facebook obtenemos su email
		//Guardamos en SESSION los datos para su posterior uso en la pantalla de origen
				
		$_SESSION['twd-id'] = $id_twitter;//id_twitter
		$_SESSION['twd-nm'] = $_SESSION['name'];//name
		$_SESSION['twd-twu'] = $_SESSION['username'];//twitter_user
		$_SESSION['twd-fw'] = $followers;//followers
		$_SESSION['twd-img'] = $_SESSION['image'];//img tw
		$_SESSION['twd-lng'] = $lang;//lang
		$_SESSION['twd-lct'] = $location;//location
		$_SESSION['twd-ot'] = $oauth_token;//oauth_token
		$_SESSION['twd-ots'] = $oauth_token_secret;//oauth_token_secret
		$_SESSION['twd-eml'] = $twEmail;//Email de su cuenta de twitter
		//var para seber que hay que mostrar el box del mensaje de twitter
		$_SESSION['tw-msg'] = 1;
				
		header('Location: '.$_SESSION['twitterLoginRedirect']);
	}else{
		//Se está logueando. Actions para LOGIN
		//Comprobamos si existe en BD -------------------------------
		$usuario = existeUsuarioTwitter($id_twitter);
		echo '<!--9-->';
		if ( $usuario['twitter_user'] == "" ){
			//Usuario de Twitter no existe en la BD
			//comprobamos si tiene invitacion
			$sql_invitacion = "SELECT email, invitador, tipo_invitador, id_encuesta, user_encuestas.id_hotel
			FROM invitaciones_users 
			LEFT JOIN user_encuestas ON user_encuestas.id=invitaciones_users.id_encuesta
			WHERE token = '".$_COOKIE['token_lg']."'";
			$row_invitacion = mysqli_query (conectar(1), $sql_invitacion);
			$invitacion = mysqli_fetch_assoc($row_invitacion);
			$n_resultados_invitacion = mysqli_num_rows($row_invitacion);
			liberar ($row_invitacion);
			echo '<!--10-->';
			if ($n_resultados_invitacion == 0 ){
				//Mirar cookie vinculacion cuentas de usuario
				if (isset ($_COOKIE['token_lsm'])){
					$tokenLSM = $_COOKIE['token_lsm'];
				}else{
					$tokenLSM = '';
				}
				$sqlLSM = "SELECT id, id_usuario, red_social, ruta 
				FROM vincular_redes_sociales WHERE token ='".$tokenLSM."' ";
				//echo '<!--'.$sqlLSM.'-->';
				$rsLSM = mysqli_query (conectar(1), $sqlLSM);
				$n_resultados_LSM = mysqli_num_rows($rsLSM);
				$rowLSM = mysqli_fetch_assoc($rsLSM);
				liberar($rsLSM);
				echo '<!--11-->';
				if ($n_resultados_LSM == 0 ){
					// No invitado
					// Lo redireccionamos
					header('Location: /'.$urlTree['login'].'/?error=4037');
				}else{
					echo '<!--12-->';
					//Esta vinculando cuentas de redes sociales
					vincularTwitterUsuario($rowLSM['id_usuario'], $id_twitter, $_SESSION['username'],
					$followers, $_SESSION['image'], $lang, $location
					,$oauth_token, $oauth_token_secret);
					//borrar cookie
					borrarCookieLSM();
					//borrar BD
					borrarLSMdeBD($rowLSM['id']);
					//Activamos la alereta, si tiene mas de X followers -> avisamos al hotel
					if($invitacion['tipo_invitador']=='hot'){
						alertaFollowers($invitacion['invitador'], $id_usuario, $followers, 'twitter');
					}
					//redirigir a $rowLSM['ruta'] desde la que estaba vinculando las redes sociales
					header('Location: '.$rowLSM['ruta'].'');
				}
			}else{
				echo '<!--13-->';
				// Usuario invitado nuevo
				$id_usuario = crearNuevoUsuarioTwitter($id_twitter, $_SESSION['name'], $_SESSION['username'], $invitacion['email'], $followers, $_SESSION['image'], $lang, $location,$oauth_token, $oauth_token_secret);
	
				echo '<!--14-->';
				//Sumamos los puntos de las invitaciones pendientes
				sumarPuntosInvitacionesUsuario($invitacion['email']);
				//Agregamos total_noches y total_spent de las invitaciones al usuario
				agregarNochesYSpent($id_usuario);
				
				echo '<!--14.1-->';
				//Activamos la alereta, si tiene mas de X followers -> avisamos al hotel
				if($invitacion['tipo_invitador']=='hot'){
					alertaFollowers($invitacion['invitador'], $id_usuario, $followers, 'twitter');
				}
				echo '<!--14.2-->';
				//Borrar invitaciones usuario
	//-----			borrarInvitacionUsuario($invitacion['email']);
				//Logueamos al usuario nuevo
				//$array_usuario = mysqli_fetch_array ($row_usuario);
				echo '<!--14.3-->';
				loguearUsuario($id_usuario);
				// Lo redireccionamos
				if(!empty($_SESSION['url_visitada'])){
					$url_visitada = $_SESSION['url_visitada'];
					unset ($_SESSION['url_visitada']);
					header('Location: '. $url_visitada .'');
				}else{
					$pantalla = pantallaLogin();
					header('Location: /'.$urlTree[$pantalla]);
				}
			}
		}else{
			echo '<!--15-->';
			//Usuario ya existe en BD
			//Logueamos al usuario 
			loguearUsuario($usuario['id_usuario']);
			//Actualizar nº followers twitter
			actualizarDatosTwitter ($usuario['id_usuario'], $followers, $_SESSION['username'], $_SESSION['image'], $id_twitter,$oauth_token, $oauth_token_secret, $twEmail);
			// Lo redireccionamos
			if(!empty($_SESSION['url_no_login'])){
				//Si se ha logueado despues de intentar acceder a una ruta, ahora lo redirigimos
				$url_visitada = $_SESSION['url_no_login'];
				unset ($_SESSION['url_no_login']);
				header('Location: '. $url_visitada .'');
			}else{
				$pantalla = pantallaLogin();
				header('Location: /'.$urlTree[$pantalla]);
			}
		}
	}
}else{
	// Lo redireccionamos
	//header('Location: /login/');
	header('Location: /'.$urlTree['login'].'');
}
?>