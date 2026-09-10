<?php 
switch (ENV) {
	case 'production':
		define('FACEBOOK_APP_ID', '101957470159657');
		define('FACEBOOK_SHARE_TYPE','hotelinking-login');
		define('FACEBOOK_APP_SECRET','');
		break;
	default:
		define('FACEBOOK_APP_ID', '192661831089220');
		define('FACEBOOK_SHARE_TYPE','hotelinking-beta');
		define('FACEBOOK_APP_SECRET','');
		break;
}

//Login permissions
$permissions = ['public_profile','email','user_friends'];
//Profile fields
$fields = 'id,name,email,friends,locale,age_range,gender';
?>