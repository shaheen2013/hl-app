<?php
//BASEPATH
define('BASE_PATH', 'http://app-api.dev.hotelinking.com/');
define('SECURE_BASE_PATH', 'https://app-api.dev.hotelinking.com/');

$host = 'hldb';
$hostMysql = '95.60.153.78';

//Datos de la BBDD - "RDS production like"
define('HOST', $hostMysql);
define('USER', 'root');
define('PASS', '');
define('DB', 'hotelinking_app_dev');
define('DB_PORT', '33067');


//Datos de la BBDD Read Replica hotelinking.local (lectura y escritura)
define('HOST_RR', $hostMysql);
define('USER_RR', 'root');
define('PASS_RR', '');
define('DB_RR', 'hotelinking_app_dev');
define('DB_RR_PORT', '33067');


//Datos BD emails (reviews...)
define('HOST_EMAILS', 'prod-emails-rds-db.chfffxqbkwdd.eu-west-1.rds.amazonaws.com');
define('USER_EMAILS', 'adminUser');
define('PASS_EMAILS', '');
define('DB_EMAILS', 'db');
define('EMAILS_PORT', '3306');


//SENDEX
define('SENDEX_SCORE', 0.60);
define('LOWEST_SENDEX_SCORE', 0.40);
//zerobounce
define('ZERO_BOUNCE_API_KEY', '');

//Environment
define('ENV', 'dev');
define('TEST', 'false');
define('VERIFY_EMAILS', true);
define('WIDGET_ENV', 'dev');
define('SURVEYS_URL','https://dev-surveys.hotelinking.com');
// Integrations
define('INTEGRATIONS_ENDPOINT', 'integrations/');
define('INTEGRATIONS_URL', 'hlintegrations-dev/api/');
define('INTEGRATIONS_URL_FRONT', 'https://dev-integrations.hotelinking.com/api/');
define('INTEGRATIONS_TOKEN', '');
define('DATAMATCH_COLUMNS', '["pms_id","pax_type","first_name","last_name","gender","check_in","check_out","birthday","nationality","res_room_number","res_room_type","hotel_id","brand_id","hotel_name","document_id","address","city","province","postal_code","telephone","birth_country","residence_country","res_board","res_adults","res_children","res_juniors","res_babies","res_seniors","res_id","res_nights","res_agency","res_company","res_intermediary","res_channel","res_contract","res_date","res_amount","res_extras","res_currency","res_comments"]');
define('INTEGRATIONS_ENABLE', true);

define('S3_DATAMATCH_BUCKET', 'dev-datamatch');
define('S3_DATAMATCH_BUCKET_ENDPOINT', 'https://s3-eu-west-1.amazonaws.com/' . S3_DATAMATCH_BUCKET);

//SALT
define('SALT', 'YUFVl5IdRFKkzhA1DSnFleoy9M2wKAQe');
define('IV', 'Adiemfr874ewdJY0');

define('FACEBOOK_PERMISSIONS',  ['public_profile', 'email','user_friends', 'user_gender', 'user_birthday', 'user_location']);
define('FACEBOOK_ENABLE', false);
define('GOOGLE_PORTAL_CLIENT_ID', '');
define('GOOGLE_PORTAL_CLIENT_SECRET', '');

//Google API Key
define('GOOGLE_API_KEY', '');

//Widget DB connection data
define ('HOST_WIDGET', $host);
define ('USER_WIDGET', 'root');
define ('PASS_WIDGET', '');
define ('DB_WIDGET', 'hotelinking_widget');
define('WIDGET_BUILDER_URL', 'https://d120aemmf7zt1a.cloudfront.net/builder/bundle.js');

// AWS S3
$_ENV['AWS_REGION'] = 'eu-west-1';
$_ENV['AWS_VERSION'] = 'latest';
$_ENV['AWS_CLIENT_SECRET_KEY'] = '';
$_ENV['AWS_SERVER_PUBLIC_KEY'] = '';
$_ENV['AWS_SERVER_PRIVATE_KEY'] = '';
$_ENV['S3_BUCKET_NAME'] = 'statics.hotelinking.com';
$_ENV['S3_BUCKET_ENDPOINT'] = 'https://s3-eu-west-1.amazonaws.com/statics.hotelinking.com';
// $_ENV['S3_MAX_FILE_SIZE'] = "9007199254740992";
$_ENV['S3_ACCESS_KEY'] = '';

$_ENV['S3_IMAGES_BUCKET'] = (ENV !== 'production' ? ENV . '-' : '') . 'images.hotelinking.com';
$_ENV['S3_IMAGES_BUCKET_ENDPOINT'] = 'https://s3-eu-west-1.amazonaws.com/' . $_ENV['S3_IMAGES_BUCKET'];

define('MANDRILL_API_KEY', '');
define('MAX_HOTSPOT_TRIES', 5);
define('MAX_STAY_SHARE_TRIES', 50);

define('REDIS_HOST', 'red-re-11r6m9qg5fjf3.ttobjx.0001.euw1.cache.amazonaws.com');
define('REDIS_PORT', 6379);

//cache File system
$cacheConfig = [
    "path" => '/tmp', // or in windows "C:/tmp/"
];

define('AWS', [


    'credentials' => [
    'key' => '',
    'secret' => '',
    ],
    'region' => 'eu-west-1',
    'version' => 'latest',
    'app_client_id' => '',
    'app_client_secret' => '',
    'user_pool_id' => '',
    'username_field' => 'username',
    'group' => 'Hotelinking',
    'api_gateway' => 'https://s4e89eysz1.execute-api.eu-west-1.amazonaws.com/dev/'
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
define('IMAGES_CLOUDFRONT_DISTRIBUTION_ID', 'E324O23VY1CC2H');

define('CLOUDWATCH_LOG_ENABLED', true);
define('CLOUDWATCH_LOG_GROUP_NAME', 'dev-hotelinking-app');
define('CLOUDWATCH_LOG_STREAM_NAME', 'dev-hotelinking-app');

define('CLOUDWATCH_LOG_PORTAL_GROUP_NAME', 'dev-reports-portal-pro');
define('CLOUDWATCH_LOG_PORTAL_STREAM_NAME', 'dev-reports-portal-pro');

define('REPORTS_ENDPOINT', 'reports/');
define('STATISTICS_ENDPOINT', 'stats/');
define('WIDGET_ENDPOINT', 'widget/');
define('HOTELINKING_ENDPOINT', 'hotelinking/');
define('NOC_ENDPOINT', 'noc/');
define('HL_API_ENDPOINT', 'hotelinking/');
define('EMAILS_ENDPOINT', 'emails/');
define('STREAM_SUB_DOMAIN', 'streams');
define('SCHEMAS_TABLE', 'dev-eventSchemas');
define('STREAM_EVENTS_ENABLED', true);
define('WIFI_REDIRECT_URL', 'http://givemefreewifi.com');
define('AUTOCHECKIN_ENDPOINT', 'autocheckin/');
define('PAYMENTS_ENDPOINT', 'payments/');
define ('MAINTENANCE', 'off');

include_once 'folders.php';
include_once 'facebook.php';
