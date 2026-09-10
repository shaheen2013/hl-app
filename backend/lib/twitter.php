<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//require_once RUTA_DIR . LIB.'twitter-async/keyTwitter.php';
require_once RUTA_DIR . LIB.'twitter-async/tmhOAuth/tmhOAuth.php';

function actualizarDatosTwitter ($id_usuario, $followers, $username, $twitter_img, $id_twitter,$oauth_token, $oauth_token_secret, $email){
	
	$id_usuario = mysqli_real_escape_string(conectar(), $id_usuario);
	$followers = mysqli_real_escape_string(conectar(), $followers);
	$username = mysqli_real_escape_string(conectar(), $username);
	$twitter_img = mysqli_real_escape_string(conectar(), $twitter_img);
	$id_twitter = mysqli_real_escape_string(conectar(), $id_twitter);
	$oauth_token = mysqli_real_escape_string(conectar(), $oauth_token);
	$oauth_token_secret = mysqli_real_escape_string(conectar(), $oauth_token_secret);
	$email = mysqli_real_escape_string(conectar(), $email);
	
	$sql = "UPDATE user_twitter SET twitter_followers='".$followers."',twitter_user='".$username."',
	twitter_img='".$twitter_img."', id_twitter='".$id_twitter."', oauth_token='".$oauth_token."',
	oauth_token_secret='".$oauth_token_secret."', twitter_email='".$email."'
	WHERE id_usuario='".$id_usuario."' ";
	mysqli_query (conectar(), $sql);
	
	$sql2 = "UPDATE users SET img='".$twitter_img."', tw_followers='".$followers."' 
	WHERE id='".$id_usuario."' ";
	mysqli_query (conectar(), $sql2);
}

function getUserNameTW($id_usuario){
	$sql = "SELECT twitter_user FROM user_twitter WHERE id_usuario='".$id_usuario."' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	return $row['twitter_user'];
}

function getLastUserTweet($id_usuario){
	$userName = getUserNameTW($id_usuario);
	
	$keysTWUsuario = obtenerKeysUsuario($id_usuario);
	// Devuelve el id del ultimo tweet del usuario logueado a traves de twitter
	include LIB.'/twitteroauth/twitteroauth/twitteroauth.php';
	$keys = keysTwitter();
	$connection = new TwitterOAuth($keys['consumer_key'], $keys['consumer_secret'], 								$keysTWUsuario['oauth_token'], $keysTWUsuario['oauth_token_secret']);  
	$connection->host = 'https://api.twitter.com/1.1/'; 
	$tweet = $connection->get('/statuses/user_timeline.json?screen_name='.$userName.'&count=1');
	/*echo '<!--Twee<pre>';
	print_r($tweet);
	echo '</pre>-->';*/
	foreach($tweet as $tw){
		$tweet_id = $tw->id_str;
	}
	return $tweet_id;
}

function obtenerKeysUsuario($id_usuario){
	$sql = "SELECT oauth_token, oauth_token_secret FROM user_twitter WHERE id_usuario='".$id_usuario."' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	return $row;
}

// FX para enviar un tweet directamente con los tokens del usuario
function sendTweetByKeys($oauth_token, $oauth_token_secret, $text) {
	//obtiene nuestras keys de twitter (keyTwitter.php)
	$arrayKeysTwitter = keysTwitter();

	$tmhOAuth = new tmhOAuth(array(
	'consumer_key' => $arrayKeysTwitter['consumer_key'],
	'consumer_secret' => $arrayKeysTwitter['consumer_secret'],
	'user_token' => $oauth_token,
	'user_secret' => $oauth_token_secret
	));
	
	$code = $tmhOAuth->request('POST', $tmhOAuth->url('1.1/statuses/update'), 
	array('status' => $text));

	if ($code == 200) {//tweet enviado
		$error = 'tweet enviado';
		// getLastUserTweet (ID)
		$lastTweet = getLastUserTweet($id_usuario);
	}else{//tweet NO enviado
		$error = 'tweet no enviado error: '.$code;
		$lastTweet = '0';
	}
	
	$return = array('code' => $code, 'msg' => $error, 'id_tweet'=>$lastTweet);
	return $return;
}

function sendTweet($id_usuario, $text) {
	//obtiene nuestras keys de twitter (keyTwitter.php)
	$arrayKeysTwitter = keysTwitter();
	
	$keysTWUsuario = obtenerKeysUsuario($id_usuario);
	
	$tmhOAuth = new tmhOAuth(array(
	'consumer_key' => $arrayKeysTwitter['consumer_key'],
	'consumer_secret' => $arrayKeysTwitter['consumer_secret'],
	'user_token' => $keysTWUsuario['oauth_token'],
	'user_secret' => $keysTWUsuario['oauth_token_secret']
	));
	
	$code = $tmhOAuth->request('POST', $tmhOAuth->url('1.1/statuses/update'), 
	array('status' => $text));

	if ($code == 200) {//tweet enviado
		$error = 'tweet enviado';
	}else{//tweet NO enviado
		$error = 'tweet no enviado error: '.$code;
	}
	// getLastUserTweet (ID)
	$lastTweet = getLastUserTweet($id_usuario);
	$arrayTweet = array($code, $lastTweet);
	return $arrayTweet;
}

function authTwitter(){
	include_once LIB. '/twitter-async/EpiCurl.php';
	include_once LIB. '/twitter-async/EpiOAuth.php'; 
	include_once LIB. '/twitter-async/EpiTwitter.php'; 
	include_once LIB. '/twitter-async/keyTwitter.php';//keys winhotel / hotelinking 
	
	$keysTwitter = keysTwitter();
	
	$twitterObj = new EpiTwitter($keysTwitter['consumer_key'], $keysTwitter['consumer_secret']); 
	//$twitterObj = new EpiTwitter('N0IcIm2Akv2N0fTftZL8Q', 'FJNWRWx4i41cPzZCVJMQA7ppBkNTZbGovRbqFXImnHU'); 
	$authenticateUrl = $twitterObj->getAuthenticateUrl();
	
	//$url = explode ("=" ,$authenticateUrl);
	//$token = $url[1]; //contiene el token
	//echo 'Token: '.$token;
	
	/*try {
		$authenticateUrl = $twitterObj->getAuthenticateUrl( );
		echo $authenticateUrl;
	} catch ( EpiOAuthUnauthorizedException $e ) {
		echo 'Failed: ';
		echo $e->getMessage( );
		print_r( $e );
	}*/
	
	header('Location: '.$authenticateUrl.'');
}

function followHotelinkingOnTwitter($id_usuario){
	$keysTwitter = keysTwitter();
	$consumer_key = $keysTwitter['consumer_key'];
	$consumer_secret = $keysTwitter['consumer_secret'];
	
	include LIB.'/twitteroauth/twitteroauth/twitteroauth.php';
	
	$id_hotelinking = '2289550369';
	
	$keysTWUsuario = obtenerKeysUsuario($id_usuario);
	
	$twitteroauth = new TwitterOAuth(array(
	'consumer_key' => $keysTwitter['consumer_key'],
	'consumer_secret' => $keysTwitter['consumer_secret'],
	'user_token' => $keysTWUsuario['oauth_token'],
	'user_secret' => $keysTWUsuario['oauth_token_secret']
	));

	$test_create = $twitteroauth->post('friendships/create', array('follow'=>true,'user_id'=>$id_hotelinking));
}

//FX para obtener el id_usuario a partir de su id de twitter
function obtenerIdUsuarioIdTwitter($id_twitter){
	$sql = "SELECT id_usuario FROM user_twitter WHERE id_twitter='".$id_twitter."' ";
	$rs = mysqli_query (conectar(), $sql);
	$row = mysqli_fetch_assoc($rs);
	liberar($rs);
	return $row['id_usuario'];
}
?>