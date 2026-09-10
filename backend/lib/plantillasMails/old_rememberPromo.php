<?php
// -- Asunto
$asunto = $rememberPromo['hi'].' '.$row['nombre'].', '.$rememberPromo['Redeem Your Deal at'].' '.$row['nombre_hotel'];
// -- Cuerpo
$cuerpo = $rememberPromo['hi'].' <b>'.$row['nombre'].'</b>,
<br>
'.$rememberPromo['main text'].' <b>'.$row['nombre_hotel'].'</b>. '. $rememberPromo['To redeem just'].'
<br>
<ul>';
foreach($promos as $promo)
{
	//Url reserva
	$urlReserva = SECURE_BASE_PATH . $urlTree['redeem-offer'].'/?hlhid='.$row['guid'].'&hlpc='.$promo['token'];
	//Lista
	$cuerpo .= '<li>'.$promo['name'].''./*$rememberPromo['promo code']*/''.': <b>'./*$promo['token']*/''.'</b>  
	<a href="'.$urlReserva.'">'.$rememberPromo['redeem now'].'</a></li>';
}
$cuerpo .= '</ul>
'.$rememberPromo['We will send you'].'<br>';


//Formato de la plantilla para "mandarEmailMandrillPlantillaSoloContenido"
$template_content = array(
		array(
		'name' => 'body_content00',
		'content' => $cuerpo
		),
		array(
		'name' => 'cta_content00',
		'content' => ''
		),
		array(
		'name' => 'logo_content00',
		'content' => '<img src="' . $hotelLogo . '" alt="hotel logo">'
		),
		array(
		'name' => 'name_content00',
		'content' => '<h2>' . $row['nombre_hotel'] . '</h2>'
		),
		array(
		'name' => 'unsuscribe_content',
		'content' => '<small>'.$rememberPromo['Don´t want to receive more notifications'].'?: <a href="'.$urlBaja.'" style="color:#818181">'.$rememberPromo['unsubscribe from notifications'].'</a></small>'
		)
	);

?>