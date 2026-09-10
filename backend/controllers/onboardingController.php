<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

// Coge el primer elemento de la lista
foreach ($onboarding as $key => $value) {
    if($value == null){
        $element = $key;
        break;
    }
}

//Si el element es basic_info, redirecciona al profile 1
if($element == 'basic_info' && $url['dir1'] != $urlTree['hotel-profile']){
    header('location:/'. $urlTree['hotel-profile']);
}

//Si el element es hotel_profile, redirecciona al profile 2
if($element == 'hotel_profile' && $url['dir1'] != $urlTree['hotel-profile-2']){
    header('location:/'. $urlTree['hotel-profile-2']);
}
//Si el element es booking_info, redirecciona al profile 3
if($element == 'booking_info' && $url['dir1'] != $urlTree['hotel-profile-datos-de-reserva']){
    header('location:/'. $urlTree['hotel-profile-datos-de-reserva']);
}

//Si el element es landing_page, redirecciona al profile 3
if($element == 'landing_page' && $url['dir1'] != $urlTree['hotel-profile-datos-landing']){
    header('location:/'. $urlTree['hotel-profile-datos-landing']);
}

//Si el element es landing_page, redirecciona al profile 3
if($element == 'oferta_1'){
    if ($url['dir1'] == $urlTree['hotel-crear-oferta'] || $url['dir1'] == $urlTree['hotel-crear-detalle-oferta'] || $url['dir1'] == $urlTree['hotel-publicar-oferta']){
        if(empty($_SESSION['offerMethod'])){
            $ofertaStep = 1;
            if($url['dir1'] != $urlTree['hotel-crear-oferta']){
                header('location:/'. $urlTree['hotel-crear-oferta']);
            }
        }else{
            $ofertaStep = 2;
        }

        if (!empty($_SESSION['publicada'])){
            $ofertaStep = 3;
//Actualiza la base de datos de onboarding si la oferta es de retención
            if($_SESSION['offerMethod'] == 'ret'){
                updateOfertaStep($element, $_SESSION['h_logueado']);
            }
//borrar las variables de sesión que faltan de la oferta para dejar limpia la sesión
            unset($_SESSION['publicada']);
            unset($_SESSION['offerMethod']);
        }
    }else{
        header('location:/'. $urlTree['hotel-crear-oferta']);
    }
}
?>