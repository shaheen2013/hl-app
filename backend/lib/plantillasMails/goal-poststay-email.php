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
		'name' => 'body_content02',
		'content' => $goalEmailLang['Dear'].' '.$nombre
	),
	array(
		'name' => 'body_content03',
		'content' => $goalEmailLang['Share'].' <strong>'.$hotel['hotelName'].'</strong> '. $goalEmailLang['Appreciate']
	),
	array(
		'name' => 'body_content04',
		'content' => $goal['nombre_oferta']
	),
	array(
		'name' => 'body_content05',
		'content' => '<!--[if mso]><v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="'.$redeemOfferUrl.'" style="height:50px;v-text-anchor:middle;width:300px;" arcsize="100%" stroke="f" fillcolor="#65c3df"><w:anchorlock/><center><![endif]--><a href="'.$redeemOfferUrl.'" class="mobile-button" style="background-color:#65c3df;border-radius:50px;color:#ffffff;display:inline-block;font-family:sans-serif;font-size:20px;font-weight:bold;line-height:50px;text-align:center;text-decoration:none;width:300px;-webkit-text-size-adjust:none;">'.$goalEmailLang['Reedem'].'</a><!--[if mso]></center></v:roundrect><![endif]-->'
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