<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

/* returns the shortened url */
function get_bitly_short_url($url,$format='txt') {
	//Desde Febrero de 2016 hemos cambiado a google api url shortener por problemas de quota con bitly
	$data = curl_get_result($url);
	return $data['id'];
}

/* returns a result form url */
function curl_get_result($url) {
	//La llamada es a google api, por problemas de quota con bit ly
	$ch = curl_init();
	$timeout = 3;
	$googleApiKey = defined('GOOGLE_API_KEY') ? GOOGLE_API_KEY : '';
	curl_setopt($ch,CURLOPT_URL,'https://www.googleapis.com/urlshortener/v1/url?key=' . $googleApiKey);
	curl_setopt($ch,CURLOPT_POST,1);
	curl_setopt($ch,CURLOPT_POSTFIELDS,json_encode(array("longUrl"=>$url)));
	curl_setopt($ch,CURLOPT_HTTPHEADER,array("Content-Type: application/json"));
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	// Execute the post
	$result = curl_exec($ch);
	// Close the connection
	curl_close($ch);
	// Return the result
	return json_decode($result,true);
}

?>