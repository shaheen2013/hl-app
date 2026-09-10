<?php
//Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}
//Si está logueado
if (empty($_SESSION['private'])) {
    header('Location: /');
}
if(!empty($_GET) && !empty($_GET['id'])){
    $idHotel = $_GET['id'];
}

//Get Hotel Name
$hotelStatistics = getHotelStatistics($idHotel);

//landing iframe opens
$landingIframeOpens = $hotelStatistics['landing_iframe_opens'];
$facebookConversions = $hotelStatistics['landing_fb_success'];
$emailConversions = $hotelStatistics['landing_mail_success'];
$noConversions = 9;

//facebook click
$fcWin = $hotelStatistics['landing_fb_clicks'];
($fcWin == 0 ? $fcPercent = 0 : $fcPercent = round($fcWin * 100 / $hotelStatistics['landing_iframe_opens'],0));

//email click
$emailWin = $hotelStatistics['landing_mail_clicks'];
($emailWin == 0 ? $emailPercent = 0 : $emailPercent = round($fcWin * 100 / $hotelStatistics['landing_iframe_opens'],0));

//facebook success
$fbSuccess = $hotelStatistics['landing_fb_success'];
($fbSuccess == 0 ? $fbConversions = 0 : $fbConversions = round($fbSuccess * 100 / $fcWin,0));
($fbConversions <= 0 ? $fbLoss = 0 : $fbLoss = 100 - $fbConversions);

//mail success
$mailSuccess = $hotelStatistics['landing_mail_success'];
($mailSuccess == 0 ? $mailConversions = 0 : $mailConversions = round($mailSuccess * 100 / $emailWin,0));
($mailConversions <= 0 ? $mailLoss = 0 : $mailLoss = 100 - $mailConversions);

//cancels
$cancels = $hotelStatistics['landing_canceled'];
$reintents = $hotelStatistics['landing_reintents'];
$losts = $cancels - $reintents;
($cancels == 0 ? $cancelsPercent = 0 : $cancelsPercent = round($losts * 100 / $cancels, 0));

//denied permissions
$userFriends = $hotelStatistics['landing_declined_user_friends'];
$publicProfile = $hotelStatistics['landing_declined_public_profile'];
$email = $hotelStatistics['landing_declined_email'];
$publishActions = $hotelStatistics['landing_declined_publish_actions'];