<?php
//BASEPATH 
define('BASE_PATH', 'http://app.hotelinking.com/');
define('SECURE_BASE_PATH', 'https://app.hotelinking.com/');

//Datos de la BBDD default (lectura y escritura)
define ('HOST', 'z30hotelinkingdb01.c1yil9ynqbgr.eu-west-1.rds.amazonaws.com');
define ('USER', 'hotelinking');
define ('PASS', '');
define ('DB', 'hotelinking');
define('DB_PORT', '3306');

//Datos de la BBDD Read Replica (lectura y escritura)
//define ('HOST_RR', 'z30hotelinkingdb01.c1yil9ynqbgr.eu-west-1.rds.amazonaws.com');
define ('HOST_RR', 'z30hotelinkingdb02-rr.c1yil9ynqbgr.eu-west-1.rds.amazonaws.com');
define ('USER_RR', 'hotelinking');
define ('PASS_RR', '');
define ('DB_RR', 'hotelinking');
define('DB_RR_PORT', '3306');

//Datos BD emails (reviews...)
define('HOST_EMAILS', 'prod-emails-rds-db.chfffxqbkwdd.eu-west-1.rds.amazonaws.com');
define('USER_EMAILS', 'adminUser');
define('PASS_EMAILS', '');
define('DB_EMAILS', 'db');
define('EMAILS_PORT', '3306');

//Statistics DB connection data
define ('HOST_STATISTICS', 'z30hotelinkingdbstats01.srv.joopbox.com');
define ('USER_STATISTICS', 'hotelinkingstats');
define ('PASS_STATISTICS', '');
define ('DB_STATISTICS', 'hotelinkingstats');

//Statistics DB connection data
define ('HOST_STATISTICS_RR', 'z30hotelinkingdbstats02.srv.joopbox.com');
define ('USER_STATISTICS_RR', 'hotelinkingstats');
define ('PASS_STATISTICS_RR', '');
define ('DB_STATISTICS_RR', 'hotelinkingstats');

//Environment
define ('ENV', 'production');
define ('TEST', 'false');

// Hotelinking Integrations
define('INTEGRATIONS_ENDPOINT', 'integrations/');
define('INTEGRATIONS_URL', 'http://int.hotelinking.com/api/');
define('INTEGRATIONS_URL_FRONT', 'https://int.hotelinking.com/api/');
define('INTEGRATIONS_TOKEN', '');
define('INTEGRATIONS_ENABLE', true);
define('DATAMATCH_COLUMNS', '["pms_id","pax_type","first_name","last_name","gender","check_in","check_out","birthday","nationality","res_room_number","res_room_type","hotel_id","brand_id","hotel_name","document_id","address","city","province","postal_code","telephone","birth_country","residence_country","res_board","res_adults","res_children","res_juniors","res_babies","res_seniors","res_id","res_nights","res_agency","res_company","res_intermediary","res_channel","res_contract","res_date","res_amount","res_extras","res_currency","res_comments"]');
define('S3_DATAMATCH_BUCKET', 'production-datamatch');
define('S3_DATAMATCH_BUCKET_ENDPOINT', 'https://s3-eu-west-1.amazonaws.com/' . S3_DATAMATCH_BUCKET);

//SALT
define ('SALT', 'YUFVl5IdRFKkzhA1DSnFleoy9M2wKAQe');
define ('IV', 'Adiemfr874ewdJY0');

// define('FACEBOOK_PERMISSIONS',  ['public_profile', 'email', 'user_friends', 'user_gender', 'user_birthday', 'user_location']);
define('FACEBOOK_PERMISSIONS',  ['public_profile', 'email', 'user_birthday', 'user_location']);
define('FACEBOOK_ENABLE', false);
define('GOOGLE_PORTAL_CLIENT_ID', '');
define('GOOGLE_PORTAL_CLIENT_SECRET', '');

define('MANDRILL_API_KEY','');


//important for uploads to statics.hotelinking.com
$_ENV['AWS_REGION'] = 'eu-west-1';
$_ENV['AWS_VERSION'] = 'latest';
$_ENV['AWS_CLIENT_SECRET_KEY'] =  '';
$_ENV['AWS_SERVER_PUBLIC_KEY'] =  '';
$_ENV['AWS_SERVER_PRIVATE_KEY']=  '';
$_ENV['S3_BUCKET_NAME']= 'statics.hotelinking.com';
$_ENV['S3_BUCKET_ENDPOINT'] = 'https://s3-eu-west-1.amazonaws.com/statics.hotelinking.com';
$_ENV['S3_ACCESS_KEY'] = '';

$_ENV['S3_IMAGES_BUCKET'] = (ENV !== 'production' ? ENV . '-' : '') . 'images.hotelinking.com';
$_ENV['S3_IMAGES_BUCKET_ENDPOINT'] = 'https://s3-eu-west-1.amazonaws.com/' . $_ENV['S3_IMAGES_BUCKET'];


define('VERIFY_EMAILS', true);

//maintenance
define ('MAINTENANCE', 'off');

//cache File system
 $cacheConfig = array(
     "path" => '/var/www/html/app.hotelinking.com/tmp/cache'
 );

define("REDIS_HOST", "red-re-y7jhusqwsnme.4e2iwa.ng.0001.euw1.cache.amazonaws.com" );
define("REDIS_PORT", 6379);
define("REDIS_PREFIX", "HL_APP_");

//Google API key
define('GOOGLE_API_KEY', '');

//SENDEX
define('SENDEX_SCORE', 0.60);
define('LOWEST_SENDEX_SCORE', 0.50);
//zerobounce
define('ZERO_BOUNCE_API_KEY', '');

define('MAX_HOTSPOT_TRIES', 5);
define('MAX_STAY_SHARE_TRIES', 50);

//AWS

define('AWS', [
    'credentials'       => [
        'key'    => '',
        'secret' => '',
    ],
    'region'            => 'eu-west-1',
    'version'           => 'latest',
    'app_client_id'     => '',
    'app_client_secret' => '',
    'user_pool_id'      => '',
    'username_field'    => 'username',
    'group'             => 'Hotelinking',
    'api_gateway'       => 'https://o013mn5aff.execute-api.eu-west-1.amazonaws.com/prod/'
]);

define('AWS_SUITE', [
    'credentials' => [
        'key' => '',
        'secret' => '',
    ],
    'region' => 'eu-west-1',
    'version' => 'latest',
    'app_client_id' => '',
    'app_client_secret' => '',
    'user_pool_id' => '',
]);

define('CLOUDWATCH_LOG_ENABLED', true);
define('CLOUDWATCH_LOG_GROUP_NAME', 'prod-hotelinking-app');
define('CLOUDWATCH_LOG_STREAM_NAME', 'prod-hotelinking-app');

define('CLOUDWATCH_LOG_PORTAL_GROUP_NAME', 'prod-reports-portal-pro');
define('CLOUDWATCH_LOG_PORTAL_STREAM_NAME', 'prod-reports-portal-pro');


define('IMAGES_CLOUDFRONT_DISTRIBUTION_ID','E27B75VE229RS5');
define('STREAM_SUB_DOMAIN', 'streams');

//HOTELINKING APIs
define('REPORTS_ENDPOINT', 'reports/');
define('WIDGET_ENV', 'prod');
define('WIDGET_ENDPOINT', 'widget/');
define('EMAIL_ENDPOINT', 'emails/');
define('HOTELINKING_ENDPOINT', 'hotelinking/');
define('NOC_ENDPOINT', 'noc/');
define('STATISTICS_ENDPOINT', 'stats/');
define('AUTOCHECKIN_ENDPOINT', 'autocheckin/');
define('SCHEMAS_TABLE', 'prod-eventSchemas');
define('SURVEYS_URL', 'https://surveys.hotelinking.com');
define('HL_API_ENDPOINT', 'hotelinking/');
define('EMAILS_ENDPOINT', 'emails/');
define('PAYMENTS_ENDPOINT', 'payments/');
define('STREAM_EVENTS_ENABLED', true);
define('WIFI_REDIRECT_URL', 'http://givemefreewifi.com');
define ('GTM_ACCOUNT_ID', '102796118');
define ('GOOGLE_OAUTH_REDIRECT_URL', 'https://app.hotelinking.com/private/private-edit-booking-engines/');
$_ENV['GTM_AUTH'] = 
    ["web" => [
        "client_id"=>"",
        "project_id"=>"high-age-218309",
        "auth_uri"=>"https://accounts.google.com/o/oauth2/auth",
        "token_uri"=>"https://oauth2.googleapis.com/token",
        "auth_provider_x509_cert_url"=>"https://www.googleapis.com/oauth2/v1/certs",
        "client_secret"=>"",
        "redirect_uris" => ["http://127.0.0.1/private/private-edit-booking-engines/","https://app.hotelinking.com/private/private-edit-booking-engines/","https://beta.hotelinking.com/private/private-edit-booking-engines/","https://dev.hotelinking.com/private/private-edit-booking-engines/"],
        "javascript_origins" => ["http://127.0.0.1","http://www.hotelinking.com","https://app.hotelinking.com","https://beta.hotelinking.com","https://dev.hotelinking.com"]
    ]
];
define('WIDGET_BUILDER_URL', 'https://dnune182x1ghb.cloudfront.net/builder/bundle.js');
include_once 'folders.php';
include_once 'facebook.php';

?>
