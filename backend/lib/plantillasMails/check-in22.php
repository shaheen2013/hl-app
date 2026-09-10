<?php
// Plantilla
// Canjear varios cupones a la vez
$asunto2 = $asun2.' '.$datosCupon[0]['nombre_hotel'];
$cuerpo1 = '<h2>'.$txt1.' '.$datosEmail['nombre'].',</h2>
<br><br>
'.$txt6.': <br><br>';
$cuerpo2 = '';
foreach($datosCupon as $cupon){
	$link_oferta = BASE_PATH.$urlTree['oferta'].'/'.string_sanitize($cupon['nombre_oferta']).'/'.$cupon['id_oferta'];
	$link_hotel = $urlHotel;
	$link_tienda = BASE_PATH.$urlTree['tienda'].'/?hot='.$cupon['id_hotel'];
	$cuerpo2 .= '<a href="'.$link_oferta.'">'.$cupon['nombre_oferta'].'</a> '.$txt7.' <a href="'.$link_hotel.'">'.$cupon['nombre_hotel'].'</a><br>';
}
$cuerpo2 .= '<br>'.$txt8.' <a href="'.$link_tienda.'">'.$cupon['nombre_hotel']. '</a>.
<br><br>
'.$txt5.',';
?>