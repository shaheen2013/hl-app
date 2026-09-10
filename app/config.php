<?php
//BASEPATH
define('BASE_PATH', 'https://local.hotelinking.com/');
define('SECURE_BASE_PATH', 'https://local.hotelinking.com/');

$host = 'hlmysql8';

//Datos de la BBDD
define('HOST', $host);
define('USER', 'root');
define('PASS', 'secret');
define('DB', 'hotelinking_app_local');

//Datos de la BBDD Read Replica localhost (lectura y escritura)
define('HOST_RR', $host);
define('USER_RR', 'root');
define('PASS_RR', 'secret');
define('DB_RR', 'hotelinking_app_local');

//Datos BD emails (reviews...)
define('HOST_EMAILS', 'hldb');
define('USER_EMAILS', 'root');
define('PASS_EMAILS', 'secret');
define('DB_EMAILS', 'hotelinking_emails_local');

//Widget DB connection data
define('HOST_WIDGET', 'hldb');
define('USER_WIDGET', 'root');
define('PASS_WIDGET', 'secret');
define('DB_WIDGET', 'hotelinking_widget_api_local');


define('MAINTENANCE', 'off');

define('WIDGET_BUILDER_URL', 'https://d120aemmf7zt1a.cloudfront.net/builder/bundle.js');

//SENDEX
define('SENDEX_SCORE', 0.60);
define('LOWEST_SENDEX_SCORE', 0.40);
//zerobounce
define('ZERO_BOUNCE_API_KEY', getenv('ZERO_BOUNCE_API_KEY') ?: '');

//Environment
define('ENV', 'test');
define('TEST', 'false');
define('VERIFY_EMAILS', false);

//SALT
define('SALT', 'YUFVl5IdRFKkzhA1DSnFleoy9M2wKAQe');
define('IV', 'Adiemfr874ewdJY0');

 
define('FACEBOOK_PERMISSIONS',  ['public_profile', 'email', 'user_friends', 'user_gender', 'user_birthday', 'user_location']);
define('FACEBOOK_ENABLE', true);

// AWS S3 important for uploading to statics.hotelinking.com
$_ENV['AWS_REGION'] = 'eu-west-1';
$_ENV['AWS_VERSION'] = 'latest';
$_ENV['AWS_CLIENT_SECRET_KEY'] = getenv('AWS_CLIENT_SECRET_KEY') ?: '';
$_ENV['AWS_SERVER_PUBLIC_KEY'] = getenv('AWS_SERVER_PUBLIC_KEY') ?: '';
$_ENV['AWS_SERVER_PRIVATE_KEY'] = getenv('AWS_SERVER_PRIVATE_KEY') ?: '';
$_ENV['S3_BUCKET_NAME'] = 'statics.hotelinking.com';
$_ENV['S3_BUCKET_ENDPOINT'] = 'https://s3-eu-west-1.amazonaws.com/statics.hotelinking.com';
$_ENV['S3_ACCESS_KEY'] = getenv('S3_ACCESS_KEY') ?: '';

$_ENV['S3_IMAGES_BUCKET'] = (ENV !== 'production' ? ENV . '-' : '') . 'images.hotelinking.com';
$_ENV['S3_IMAGES_BUCKET_ENDPOINT'] = 'https://s3-eu-west-1.amazonaws.com/' . $_ENV['S3_IMAGES_BUCKET'];

define('MANDRILL_API_KEY', getenv('MANDRILL_API_KEY') ?: '');

// Datamatch
define('S3_DATAMATCH_BUCKET', ENV . '-datamatch');
define('S3_DATAMATCH_BUCKET_ENDPOINT', 'https://s3-eu-west-1.amazonaws.com/' . S3_DATAMATCH_BUCKET);



//AWS
define('AWS', [
    'credentials' => [
        'key' => getenv('AWS_ACCESS_KEY_ID') ?: '',
        'secret' => getenv('AWS_SECRET_ACCESS_KEY') ?: '',
    ],
    'region' => 'eu-west-1',
    'version' => 'latest',
    'app_client_id' => '57kcmqkrajb1r4b2trgvckepvf',
    'app_client_secret' => getenv('AWS_APP_CLIENT_SECRET') ?: '',
    'user_pool_id' => 'eu-west-1_BCVdT9Bb0',
    'username_field' => 'username',
    'group' => 'Hotelinking',
    'api_gateway' => 'https://local-internal.hotelinking.com/'
]);

define('AWS_SUITE', [
    'credentials' => [
        'key' => getenv('AWS_SUITE_ACCESS_KEY_ID') ?: '',
        'secret' => getenv('AWS_SUITE_SECRET_ACCESS_KEY') ?: '',
    ],
    'region' => 'eu-west-1',
    'version' => 'latest',
    'app_client_id' => '1fj4hnj84ap5l24t5e9iaofn25',
    'app_client_secret' => getenv('AWS_SUITE_APP_CLIENT_SECRET') ?: '',
    'user_pool_id' => 'eu-west-1_6zlqVwqri',
]);

// Cloudfront ID for dev
define('IMAGES_CLOUDFRONT_DISTRIBUTION_ID', 'E324O23VY1CC2H');

// HOTELINKING APIs
define('REPORTS_ENDPOINT', 'reports/');
define('WIDGET_ENDPOINT', 'widget/');
define('EMAIL_ENDPOINT', 'emails/');
define('HOTELINKING_ENDPOINT', 'hotelinking/');
define('EMAILS_ENDPOINT', 'emails/');
define('AUTOCHECKIN_ENDPOINT', 'autocheckin/');
define('STATISTICS_ENDPOINT', 'stats/');
define('NOC_ENDPOINT', 'noc/');
define('INTEGRATIONS_ENDPOINT', 'integrations/');
define('PAYMENTS_ENDPOINT', 'payments/');

// HOTELINKING integrations
define('INTEGRATIONS_URL_FRONT', 'https://local-integrations.hotelinking.com/api/');
define('INTEGRATIONS_TOKEN', '68f83510c9c005929fa1443dad40d384b9ab96b86ff21a2be40798d9577ca02f');
define('DATAMATCH_COLUMNS', '["pms_id","pax_type","first_name","last_name","gender","check_in","check_out","birthday","nationality","res_room_number","res_room_type","hotel_id","brand_id","hotel_name","document_id","address","city","province","postal_code","telephone","birth_country","residence_country","res_board","res_adults","res_children","res_juniors","res_babies","res_seniors","res_id","res_nights","res_agency","res_company","res_intermediary","res_channel","res_contract","res_date","res_amount","res_extras","res_currency","res_comments"]');
define('MAX_HOTSPOT_TRIES', 3);
define('MAX_STAY_SHARE_TRIES', 50);
define('INTEGRATIONS_ENABLE', true);



//cache File system
$cacheConfig = array(
    "path" => '/tmp', // or in windows "C:/tmp/"
);

//Google API key
define('GOOGLE_API_KEY', getenv('GOOGLE_API_KEY') ?: '');

//GTM integrations with google api
define('GOOGLE_PORTAL_CLIENT_ID', '206628568605-o542irpdcu31h8h1eeqphln6afds3ifq.apps.googleusercontent.com');
define('GOOGLE_PORTAL_CLIENT_SECRET', getenv('GOOGLE_PORTAL_CLIENT_SECRET') ?: '');
define('GTM_ACCOUNT_ID', '');
define('GOOGLE_OAUTH_REDIRECT_URL', 'http://local.hotelinking.com/private/private-edit-booking-engines/');
$_ENV['GTM_AUTH'] =
    [
        "web" => [
            "client_id" => "852619483954-gobklcki7u8jgdi01leaig8urnp2m07k.apps.googleusercontent.com",
            "project_id" => "high-age-218309",
            "auth_uri" => "https://accounts.google.com/o/oauth2/auth",
            "token_uri" => "https://oauth2.googleapis.com/token",
            "auth_provider_x509_cert_url" => "https://www.googleapis.com/oauth2/v1/certs",
            "client_secret" => "",
            "redirect_uris" => ["http://local.hotelinking.com/private/private-edit-booking-engines/", "https://app.hotelinking.com/private/private-edit-booking-engines/", "https://beta.hotelinking.com/private/private-edit-booking-engines/", "https://dev.hotelinking.com/private/private-edit-booking-engines/"],
            "javascript_origins" => ["http://local.hotelinking.com", "http://www.hotelinking.com", "https://app.hotelinking.com", "https://beta.hotelinking.com", "https://dev.hotelinking.com"]
        ]
    ];

define('WIFI_REDIRECT_URL', 'http://givemefreewifi.com');
define('CLOUDWATCH_LOG_ENABLED', false);
define('CLOUDWATCH_LOG_GROUP_NAME', 'local-hotelinking-app');
define('CLOUDWATCH_LOG_STREAM_NAME', 'local-hotelinking-app');

define('CLOUDWATCH_LOG_PORTAL_GROUP_NAME', 'local-reports-portal-pro');
define('CLOUDWATCH_LOG_PORTAL_STREAM_NAME', 'local-reports-portal-pro');

define('STREAM_SUB_DOMAIN', 'streams');
define('SCHEMAS_TABLE', 'dev-eventSchemas');
define('SURVEYS_URL', 'http://localhost:8080');

include_once 'folders.php';
include_once 'facebook.php';
