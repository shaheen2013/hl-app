<?php
global $urlTree;
//Plantilla. Email a regalador de cupón
$link_tienda = BASE_PATH.$urlTree['tienda'];
$asunto=$asun;
$cuerpo='<h2>'.$txt1.' '.$arrayDatosEmail['nombre'].',</h2>
<br><br>
'.$txt2.' <strong>'.$datosCupon['voucher'].'</strong> '.$txt3.'.
'.$txt4.'.
<br><br>
'.$txt5.' '.$datosCupon['puntos'].' '.$txt6.' <strong>'.$datosCupon['hotelName'].'</strong>.
<br><br>
'.$txt7.' <a href="'.$link_tienda.'">LINK</a>
<br><br>
'.$txt8.',<br><br>';
?>