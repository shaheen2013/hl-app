<?php
// Plantilla. Email aviso de que quedan 5 dias para caducar oferta no canjeada.

$asunto = $row['nombre'].' '.$asun;
$cuerpo = '<h2>'.$row['nombre'].',</h2>
<br>
'.$txt1.' <strong>'.$row['nombre_oferta'].'</strong> '.$txt2.'.<br>
'.$txt3.' '.$row['fecha_adquis'].' '.$txt4.'. <br>
'.$row['nombre_hotel'].' '.$txt5.' '.$row['fin'].' 
<br><br>
'.$txt6.'.
<br><br>
'.$txt7.' <a href="mailto:customerservice@hotelinking.com">'.$txt8.'</a><br><br>
'.$txt9.',<br>';
?>