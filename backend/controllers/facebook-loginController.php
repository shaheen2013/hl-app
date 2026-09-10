<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

include_once LIB.'Facebook/keys.php';
//require_once LIB.'facebook/autoload.php';

require_once( LIB.'Facebook/Facebook/FacebookSession.php' );
require_once( LIB.'Facebook/Facebook/FacebookRedirectLoginHelper.php' );
require_once( LIB.'Facebook/Facebook/FacebookRequest.php' );
/*require_once( LIB.'Facebook/Facebook/FacebookResponse.php' );
require_once( LIB.'Facebook/Facebook/FacebookSDKException.php' );
require_once( LIB.'Facebook/Facebook/FacebookRequestException.php' );
require_once( LIB.'Facebook/Facebook/FacebookAuthorizationException.php' );
require_once( LIB.'Facebook/Facebook/GraphObject.php' );
require_once( LIB.'Facebook/Facebook/GraphUser.php' );
require_once( LIB.'Facebook/Facebook/GraphSessionInfo.php' );
require_once( LIB.'Facebook/Facebook/FacebookOtherException.php' );*/

use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
/*use Facebook\FacebookResponse;
use Facebook\GraphUser;
use Facebook\FacebookRequestException;
use Facebook\FacebookSDKException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\GraphSessionInfo;
use Facebook\FacebookOtherException;*/
	
$arrayKeysFB = getFBKeys();
$api_key = $arrayKeysFB[0];
$api_secret = $arrayKeysFB[1];
$redirect_login_url = SECURE_BASE_PATH.'facebookCallBack.php';
	
FacebookSession::setDefaultApplication($api_key, $api_secret);
$helper = new FacebookRedirectLoginHelper( $redirect_login_url);
$sessionFB = $helper->getSessionFromRedirect();
	
$permissions = array(
	'email',
	//'user_location',
	//'user_birthday',
	'user_friends',
	'publish_actions'
);
$loginUrl = $helper->getLoginUrl($permissions);
header('Location: '.$loginUrl); 
?>