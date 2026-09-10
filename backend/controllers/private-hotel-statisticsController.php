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

//first click
$fcWin = $hotelStatistics['pre_login'];
$fcLoss = $hotelStatistics['pre_iframe_opens'] - $fcWin;
($fcWin == 0 ? $fcPercent = 0 : $fcPercent = round($fcWin * 100 / $hotelStatistics['pre_iframe_opens'],0));

//second click
$scWin = $hotelStatistics['pre_second_click'];
$scLoss = $fcWin - $scWin;
($scWin == 0 ? $scPercent = 0 : $scPercent = round($scWin * 100 / $fcWin, 0));

//Shares
$shares = $hotelStatistics['pre_share'];
$sharesLoss = $hotelStatistics['pre_iframe_opens'] - $shares;
($shares == 0 ? $sharesPercent = 0 : $sharesPercent = round($shares * 100 / $hotelStatistics['pre_iframe_opens'], 0));

//cancels
$cancels = $hotelStatistics['pre_canceled'];
$reintents = $hotelStatistics['pre_reintents'];
$losts = $cancels - $reintents;
($cancels == 0 ? $cancelsPercent = 0 : $cancelsPercent = round($losts * 100 / $cancels, 0));

//denied permissions
$userFriends = $hotelStatistics['pre_declined_user_friends'];
$publicProfile = $hotelStatistics['pre_declined_public_profile'];
$email = $hotelStatistics['pre_declined_email'];
$publishActions = $hotelStatistics['pre_declined_publish_actions'];