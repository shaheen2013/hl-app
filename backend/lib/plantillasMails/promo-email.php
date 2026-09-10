<?php 
//Email Landing
$asunto = $userName . $promoEmailLang['asunto'];
$template_content = array(

	array(
		'name' => 'preheader_content00',
		'content' => '<a href="'.$urlHotel.'" target="_blank"><img alt="Logo" src="'.$hotelLogo.'" width="100" style="display: block; font-family: Helvetica, Arial, sans-serif; color: #666666; font-size: 16px;" border="0"></a>'
	),
	array(
		'name' => 'preheader_content01',
		'content' => $promoEmailLang['preheader text left']
	),
	array(
		'name' => 'body_content00',
		'content' => $userName .', ' . $promoEmailLang['Thanks for choosing us!']
	),
	array(
		'name' => 'body_content01',
		'content' => $promoEmailLang['refer your friends and win instant rewards']
	),
	array(
		'name' => 'body_content05',
		'content' => $promoEmailLang['Were looking forward to having you back soon!'].'<br /><br />'.$promoEmailLang['Kind regards,'].'<strong><br />'.$datosHotel['hotelName'].'</strong>'
	),
	array(
		'name' => 'body_content06',
		'content' => '<a href="'.$redeemOfferUrl.'" target="_blank" style="font-size: 16px; font-family: Helvetica, Arial, sans-serif; font-weight: normal; color: #ffffff; text-decoration: none; background-color: #5D9CEC; border-top: 15px solid #5D9CEC; border-bottom: 15px solid #5D9CEC; border-left: 25px solid #5D9CEC; border-right: 25px solid #5D9CEC; border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px; display: inline-block;" class="mobile-button"><strong>'.$promoEmailLang['web del hotel'].'</strong></a>'
	),
	array(
		'name' => 'body_content07',
		'content' => $promoEmailLang['If your experience has been positive, recommend us to your friends, family and social media followers. Make sure you include, with any share, your referral link provided below.']
	),
	array(
    'name' => 'footer_content01',
    'content' => '<a href="'.$urlUnsuscribe.'"class="original-only" style="color: #666666; text-decoration: none;">Unsubscribe</a>'
	)
);
?>