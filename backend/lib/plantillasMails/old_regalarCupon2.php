<?php
// Plantilla. Email a usuario nuevo, texto extra para la invitación
$linkOffer = BASE_PATH.$urlTree['cupon'].'/'.string_sanitize($datosCupon['nombre_oferta']).'/'.$datosCupon['id_oferta'];

$txtExtra = $YourFriend.' '.$regalador['nombre'].' '.$txt1.' <a href="'.BASE_PATH.'">Hotelinking</a>, '.$txt2.' '.$datosCupon['hotelName'].' .<br><br>#
'.$txt3.'<br><br>#
'.$txt4.': <strong>'.$datosCupon['voucher'].'</strong>
<a href="'.$linkOffer.'">'.$txt5.'</a><br><br>';
?>