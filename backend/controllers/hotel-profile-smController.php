<?php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;};
//Contenido solo visible si logueado
include LIB . 'logueado.php';
hotelStaffLanding ();// Si no esta logueado lo manda a la landing

include_once LIB.'obtenerdatosHotel.php';

//save facebook fan page
if(isset($_POST['facebookFP']))
{
    $fpResult = saveFacebookFanPage($_SESSION['h_logueado'], $_POST['facebookFP']);
    if($fpResult){
        $ok = array(true, '2007');
    }else{
        $ok = array(false, '4002');
    }
}

if(isset($_POST['shareMediaTextLang']) && (isset($_POST['pre']) ||  isset($_POST['stay']) || isset($_POST['post'])))
{   
    $shareMediaTextLang = $_POST['shareMediaTextLang'];
    isset($_POST['pre'])? $pre = $_POST['pre'] : $pre='';
    isset($_POST['stay'])? $stay = $_POST['stay'] : $stay='';
    isset($_POST['post'])? $post = $_POST['post'] : $post='';
    guardarSMShareText($_SESSION['h_logueado'], $shareMediaTextLang, $pre, $stay, $post);
    // Feedback
    $ok = array(true, '2007');
}

//Get facebook Fan page
$fbFanPage = getFacebookFanPage($_SESSION['h_logueado']);
$shareMediaTextLang = '';

// obtenermos todos los langs del hotel
$idiomasHotel = obtenerLangsHotel($_SESSION['h_logueado']);
empty($_POST['shareMediaTextLang'])? $actualLang = $idiomasHotel[0]['lang'] : $actualLang = $_POST['shareMediaTextLang'];