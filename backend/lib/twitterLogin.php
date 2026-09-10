<?php 
  include_once(LIB . 'twitter-async/EpiCurl.php'); 
  include_once(LIB . 'twitter-async/EpiOAuth.php'); 
  include_once(LIB . 'twitter-async/EpiTwitter.php'); 
  include_once(LIB . 'twitter-async/keyTwitter.php');
  $twitterObj = new EpiTwitter($consumer_key, $consumer_secret); 
  $authenticateUrl = $twitterObj->getAuthenticateUrl();
  header('Location: '.$authenticateUrl.'');
 ?>