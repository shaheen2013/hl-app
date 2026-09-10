<?php
// Plantilla invitación staff del hotel
global $urlTree;
$urlInvitacion = BASE_PATH. $urlTree['verificar-email'].'/?token='.$token.'&tp=stf&e='.$email;
$asunto = $asun;

if (count($datosHotel) > 1) {
    $hotel_list = array_reduce($datosHotel, function ($text, $hotel) {
        return $text.'<li>'.$hotel["hotelName"].'</li>';
    },'<br>');
} else {
    $hotel_list = '<strong>'.array_get($datosHotel,'0.hotelName').'</strong>';
}


$cuerpo = '<h2>'.$txt1.' '.$staff['nombre'].', </h2>
<br><br>
'.$txt2.'<ul>'.$hotel_list.'</ul><br>'.$txt3.'
<br><br>
'.$txt4.' <strong>Hotelinking</strong> '.$txt5.':<br>
'.$txt6.': '.$email.'<br>
'.$txt7.': '.$pass.'<br>
<br><br>
'.$txt8.' <strong>Hotelinking</strong> '.$txt9.' <a href="'.$urlInvitacion.'">Link</a>
<br><br>
'.$txt10.',';
?>