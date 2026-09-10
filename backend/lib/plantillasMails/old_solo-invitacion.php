<?php
//$urlLogin
	$template_content = array(
		array(
		'name' => 'body_content00',
		'content' => $soloInvitacion['text']. ' '
		),
		array(
		'name' => 'cta_content00',
		'content' => '<a href="'.$urlLogin.'" title="Share your experience" id="cta_button" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; background: #65c3df; border-radius: 20px; box-shadow: 0 3px 0 #157c9a; color: #FFF; display: block; font-size: 12px; font-weight: normal; margin: 20px auto; padding: 10px 20px; text-decoration: none; width: 150px; display:block; margin:10px auto" >'.$soloInvitacion['boton'].'</a>'
		),
		array(
		'name' => 'logo_content00',
		'content' => '<img src="' . $hotelLogo . '" alt="hotel logo">'
		),
		array(
		'name' => 'name_content00',
		'content' => '<h2>' . $arrayDatosHotel['hotelName'] . '</h2>'
		)
	);
?>