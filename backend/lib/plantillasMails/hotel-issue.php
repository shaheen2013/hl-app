<?php
// Plantillas emails

$cuerpo1 = $txt1.': <a href="'.$urlUsuario.'" >'.$arrayDatosEmail['nombre'].'</a> '.$txt2.': <a href="mailto:'.$arrayDatosEmail['email'].'" >'.$arrayDatosEmail['email'].'</a> '.$txt3.': <a href="'.$urlHotel.'" >'.$arrayDatosHotel['hotelName']. '</a> <br />--------------------------------------------------------------
<br /><br /><br />';

$cuerpo2 = $txt4.'.<br>'.$txt5.' <strong>'.$arrayDatosHotel['hotelName'].'</strong> '.$txt6.'.
<br><br>'.$txt7.'.<br><br>'.$txt8.',<br>';
?>