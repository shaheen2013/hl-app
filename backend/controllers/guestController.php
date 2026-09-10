<?php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}
/**
 * Unifi integration
 * Unifi only redirect external portal to a /guest/s/SITENAME type of urls,
 * we reserve this url to unifi
 */

global $log;
$log->info("Reach to guestController to make Unifi redirect", ["GET" => $_GET, "Referrer" => array_get($_SERVER, 'HTTP_REFERER')]);

//Check info is present or exit
if(empty($_GET['id']) || empty($_GET['ap'])){
    echo 'There is an error, please contact staff';
    exit;
}
//get parameters
$_SESSION['mac'] = $_GET['id'];          //user's mac address
$_SESSION['unifi_ap'] = $_GET['ap'];          //AP mac
$_SESSION['unifi_ssid'] = $_GET['ssid'];      //ssid the user is on
$_SESSION['unifi_time'] = $_GET['t'];         //time the user attempted a request of the portal
$_SESSION['unifi_refURL'] = $_GET['url'];     //url the user attempted to reach

//Check URL SITENAME
$unifiSite = $url['dir3'];
//All unifi sites must be name as "hotelinking_(idOfHotel)"
//search SITEID in DDBB and return GUID
$guid = unifiGetGuidByHotelId($unifiSite);

if($guid){
    //redirect to stay share
    ob_start();
    header("Location: " . BASE_PATH . "stay-share/" . $guid . "/?mac=" . $_SESSION['mac']);
    exit();
}else{
    echo 'There is an error, please contact staff';
    exit;
}
