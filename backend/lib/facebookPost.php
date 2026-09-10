<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

require_once( LIB.'Facebook/Facebook/FacebookSession.php' );
require_once( LIB.'Facebook/Facebook/FacebookRequest.php' );
require_once( LIB.'Facebook/Facebook/FacebookResponse.php' );
require_once( LIB.'Facebook/Facebook/GraphObject.php' );
require_once( LIB.'Facebook/Facebook/FacebookSDKException.php' );
require_once( LIB.'Facebook/Facebook/FacebookRequestException.php' );
require_once( LIB.'Facebook/Facebook/FacebookPermissionException.php' );
require_once( LIB.'Facebook/Facebook/FacebookOtherException.php' );

use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\GraphObject;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookPermissionException;
use Facebook\FacebookOtherException;

include_once LIB.'Facebook/keys.php';
include_once LIB.'facebook.php';

function postFacebook($id_usuario, $texto, $urlShare, $UrlFotoBgHotel){
	
	// Token de usauario para publicar en su muro, caduca a los 60 dias
	$token_fb = obtenerKeyFBUsuario($id_usuario);
	
	$arrayMessage = array(
		'link' => $urlShare,
		'message' => $texto,
		'picture' => $UrlFotoBgHotel
	);
	
	try {
		$sessionFB = new FacebookSession($token_fb);
		$postFb = new FacebookRequest($sessionFB, 'POST',  '/me/feed', $arrayMessage);
		$response = $postFb->execute();
		$graphObject = $response->getGraphObject();
		// $idPostFB contiene el id_del_usuario + el id_del_post (9898878_46489797945)
		$idPostFB = $graphObject->getProperty('id');
	} catch(FacebookRequestException $e) {
		$idPostFB='';
	}
	return $idPostFB;
}
?>