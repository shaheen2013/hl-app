<?php
global $urlTree;
// Links
$link_a_puntos_log = BASE_PATH.$urlTree['user-points-log'];
$link_ficha_hotel = $urlHotel;
$link_tienda_adq = BASE_PATH.$urlTree['tienda'].'/?adre=adq';
//Email al referral informando de un check-in referido por él
$asunto2 = $arrayDatosEmail['nombre'].' '.$asun2;
$cuerpo2 = $hotelName.' '.$txt9.'!
<br><br>
'.$txt10.' '.$puntosRef.' '.$txt11.' <a href="'.$link_ficha_hotel.'">'.$hotelName.'</a>
<br><br>';
//$cuerpo2 .= $txt12 
$cuerpo2 .= '<br><br>
'.$txt13.' <a href="'.$link_a_puntos_log.'">Link</a> '.$txt14.'.
<br><br>';
//$cuerpo2 .= $txt15.' <a href="'.$link_tienda_adq.'">Link</a>, '.$txt16.'.';
$cuerpo2 .= '<br><br>';

// Texto de 
if(isset($txtOfRef)){
	$cuerpo2 .= $txtOfRef;
}else{
	$cuerpo2 .= $txt17.',';
}
?>