<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}
//Contenido solo visible si logueado
include LIB.'logueado.php';
hotelStaffLanding ();// Si no esta logueado lo manda a la landing
include_once LIB.'charts-common.php';//Charts que se repiten en otras pantallas
//Surveys VS Checkouts
$surveysVSCheckouts = surveysVSCheckouts($_SESSION['h_logueado']);
//Social media conversion
$smConversion = socialMediaConversion($_SESSION['h_logueado']);
//Social media reach
$smReach = socialMediaReach($_SESSION['h_logueado']);
//Surveys Progression
$surveysProgression = surveysProgression($_SESSION['h_logueado']);
// Reputation by age. 1º param. 0-18, 2º param. 19-30, 3º param. 30-54, 4º param. >50 
$reputationByAge = reputationByAge($_SESSION['h_logueado']);
// Rating Progression
$RatingProgression = ratingProgression($_SESSION['h_logueado']);