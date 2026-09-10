<?php 
	//Email de goal
$template_content = array(
	array(
		'name' => 'preheader_content00',
		'content' => '<a href="'.$urlHotel.'" target="_blank"><img alt="Logo" src="'.$hotelLogo.'" width="100" style="display: block; font-family: Helvetica, Arial, sans-serif; color: #666666; font-size: 16px;" border="0"></a>'
	),
	array(
		'name' => 'preheader_content01',
		'content' => $goalEmailLang['Thanks for visit us. Win deals sharing your experience']
	),
	array(
		'name' => 'header_content00',
		'content' => '<img src="'.$hotelBg.'" width="600" border="0" alt="Insert alt text here" style="display: block; padding: 0; color: #266e9c; text-decoration: none; font-family: Helvetica, arial, sans-serif; font-size: 16px;" class="img-max">'
	),
	array(
		'name' => 'body_content00',
		'content' => $goalEmailLang['Thanks for choosing us!']
	),
	array(
		'name' => 'body_content01',
		'content' => ''.$goalEmailLang['If you enjoyed at'].' '.$hotel['hotelName'].',<br>'.$goalEmailLang['refer your friends and win instant rewards'].''
	),
	array(
		'name' => 'body_content02',
		'content' => '<a href="'.$redeemOfferUrl.'" target="_blank" style="font-size: 16px; font-family: Helvetica, Arial, sans-serif; font-weight: normal; color: #ffffff; text-decoration: none; background-color: #5D9CEC; border-top: 15px solid #5D9CEC; border-bottom: 15px solid #5D9CEC; border-left: 25px solid #5D9CEC; border-right: 25px solid #5D9CEC; border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px; display: inline-block;" class="mobile-button">'.$goalEmailLang['Refer your friends and win rewards'].'</a>'
	),
	array(
		'name' => 'body_content03',
		'content' => $goalEmailLang['How it works']
	),
	array(
		'name' => 'body_content04',
		'content' => $goalEmailLang['If your experience has been positive, recommend us to your friends, family and social media followers. Make sure you include, with any share, your referral link provided below.']
	),
	array(
		'name' => 'body_content05',
		'content' => '<a href="'.$redeemOfferUrl.'" target="_blank" style="font-size: 16px; font-family: Helvetica, Arial, sans-serif; font-weight: normal; color: #48CFAD; text-decoration: none; border-top: 2px solid #48CFAD; border-bottom: 2px solid #48CFAD; border-left: 2px solid #48CFAD; border-right: 2px solid #48CFAD; border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px; display: inline-block; padding:10px 20px" class="mobile-button"><strong>'.$goal['promo_code'].'</strong></a>'
	),
	array(
		'name' => 'body_content13',
		'content' => $goalEmailLang['Check below the rewards you can earn, based on the number of friends you refer:']
	),
	array(
		'name' => 'body_content14',
		'content' => $goalsHtml
	),
	array(
    'name' => 'footer_content01',
    'content' => '<a href="'.$urlUnsuscribe.'"class="original-only" style="color: #666666; text-decoration: none;">Unsubscribe</a>'
  )
);

?>