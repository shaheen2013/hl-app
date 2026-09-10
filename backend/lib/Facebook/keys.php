<?php
// Keys de hotelinking.com 
$appId = FACEBOOK_APP_ID;
$appSecret = FACEBOOK_APP_SECRET;

function getFBKeys(){
	global $appId, $appSecret;
	$arrayKeysFB[]=$appId;
	$arrayKeysFB[]=$appSecret;
	return $arrayKeysFB;
}
?>