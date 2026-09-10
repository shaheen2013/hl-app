<?php
// email de la hotel-landing cuando piden invitación
$asunto = 'Invite request received successfuly!';
$saludo = 'Thank you!,';
$txt1 = 'You are a step closer to secure a spot once Hotelinking goes live. We are building an awesome product, working day and night to make it available as soon as possible.';
$referral = 'Want to manage <strong>10k loyal guests</strong> for free for a life time (worth 126 USD/mo)?';
$referral2 = 'Get free hotels to sign up with this unique URL';
$liTitle = 'Hotelinking will';
$li1 = 'Help you get more loyal guests';
$li2 = 'Attract and retain most valuable and influential guests';
$li3 = 'Post customized offers to your guests, key to amaze millennials';
$li4 = 'Launch a customized hotel loyalty program, free of plastic cards, all online, on the phone, on the cloud, immediate gratification';
$li5 = 'Your satisfied guests will be able to easily share hotel reviews on social media. We will track for you all referrals. Your guests will become the most powerful salesforce you have ever had. And the best thing, is that you will be able to track results and reward the best';
$li6 = 'Create a viral loop, so new guests will come by word of mouth';
$li7 = 'Help you increase the occupation rate';
$li8 = 'Increase the number of direct bookings';
$li9 = 'Differentiate your hotel from OTAs';

$txt2 = 'Now is our job to review the hotel website you provided. If your hotel meets all or more than one of the following, keep calm and smile';
$li10 = 'The hotel has something unique that makes your guests love it';
$li11 = 'You online reputation is over 8 across multiple platforms (tripadvisor, booking, expedia)';
$li12 = 'The hotel has a strong online presence, and the website looks great';
$li13 = 'The hotel is located in a beautiful spot (even it is a city, an island or an isolated mountain)';

$txt3 = 'For now, that’s all we wanted to say. On the slightest whim, please drop us a line';
$txt4 = 'or tweet us';
$txt5 = 'We are planning to send you more emails to keep you up to date about our progress, screenshots, launching dates, pricing, and anything that will be useful until the day the product is available';

//Email que recibe Hotelinking notificando peticion de invitación
$asunto2='petición invitación';
$cuerpo2='Nueva petición de invitación de hotel: '.$emailHotel.' - '.$_POST['hotelWebSite'] . ' - ' . $_POST['hotelPhone'] . ' - ' . 'http://hotelinking.com/landing/?referral='.$code;

//Email que recibe hotelinking cuando hay solo un email
$asunto3='petición invitación (solo email)';
$cuerpo3='Nueva petición de invitación de hotel (solo email): '.$hotelEmail;

?>

