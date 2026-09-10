<?php 
	//Email de goal
	$template_content = array(
		array(
			'name' => 'header_content01',
			'content' => '<td align="center" valign="bottom" background="'. $hotelBg . '" class="img-background mobile-image-td"><!--[if gte mso 9]><v:rect xmlns:v="urn:schemas-microsoft-com:vml" fill="true" stroke="false" style="width:600px;height:350px;"><v:fill type="frame" size="100%,100%" src="{{OFFER_IMAGE}}" color="#ffffff" /><v:textbox inset="260px,250px,0px,0px"><![endif]--><!--[if gte mso 9]></v:textbox></v:rect><![endif]--><img alt="Logo" src="'. $hotelLogo .'" width="110" height="110px" style="display: block;height: 110px !important;width: 110px !important;border-radius: 100px 100px 100px 100px !important;border: 5px solid !important;color:white !important;background-color: white !important;"><td>'
		),
		array(
			'name' => 'body_content01',
			'content' => $goalEmailLang['Thanks for choosing us!']
		),
		array(
			'name' => 'body_content09',
			'content' => $goalEmailLang['Dear'].' '.$nombre
		),
		array(
			'name' => 'body_content02',
			'content' => ''.$goalEmailLang['In name'].' <strong>'.$hotel['hotelName'].'</strong> '.$goalEmailLang['Appreciate'].''
		),
		array(
			'name' => 'body_content03',
			'content' => $goal['nombre_oferta']
		),
		array(
			'name' => 'body_content04',
			'content' => $cupon
		),
		array(
			'name' => 'body_content05',
			'content' => $goalEmailLang['Show']
		),
		array(
			'name' => 'body_content06',
			'content' => $goalEmailLang['Thanks']
		),
		array(
			'name' => 'body_content07',
			'content' => $hotel['hotelName']
		),
		array(
			'name' => 'body_content08',
			'content' => '<a href="'.$urlUnsuscribe.'">'.$goalEmailLang['Unsubscribe'].'</a>'
		)
	);

?>