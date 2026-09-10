<?php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}
include_once LANG . $_SESSION['userLang'] . DS . 'hlpc-validator.php';
$username = null;
$promoCode = null;
$langs = null;
$lang = $_SESSION['userLang'];
$offerName = null;
// Promo is valid?
$validPromo = false;
// check if get is coming
if(isset($_GET['hlpc'])){
    // check if promo Exists
    $promoCode = getPromocode($_GET['hlpc']);
    
    global $log;

    if($promoCode){
        //If promocode is not used
        if($promoCode['transaction'] == '-' || $promoCode['transaction'] == null || $promoCode['transaction'] == ''){
            $validPromo = true;
            $username = $promoCode['nombre'];
            // Check if lang is OK or set Default lang of the offer
            $langs = getOfferLangs($promoCode['id_oferta']);

            if (!in_array($lang, $langs)) {
                $lang = 'en';
            }
            // Get offer
            $offerName = getOffer($promoCode['id_oferta'], $lang);

            $log->info(array($promoCode, $langs, $offerName));
			
			//Promo del booking engine para que el usuario lo copie/pegue en la web
			//Obtener el parametro _GET segun el bookieng engine del hotel
			$getParamBE = getParamBE($promoCode['id_hotel']);
			!empty($_GET[$getParamBE])? $BEPromo = urldecode($_GET[$getParamBE]) : $BEPromo = '';
        }
    }
}else{
    exit;
}