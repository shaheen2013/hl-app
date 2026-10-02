<?php

// BASEPATH
define('BASE_PATH', getenv('APP_URL') ?: 'http://localhost:9005/');
define('SECURE_BASE_PATH', getenv('APP_SECURE_URL') ?: 'http://localhost:9005/');

$host = getenv('DB_HOST') ?: 'hlmysql8';

// Database connection settings
define('HOST', $host);
define('USER', getenv('DB_USERNAME') ?: 'root');
define('PASS', getenv('DB_PASSWORD') ?: 'secret');
define('DB', getenv('DB_DATABASE') ?: 'hotelinking_app_local');
define('DB_PORT', getenv('DB_PORT') ?: '3306');

// Read Replica connection settings
define('HOST_RR', $host);
define('USER_RR', getenv('DB_USERNAME') ?: 'root');
define('PASS_RR', getenv('DB_PASSWORD') ?: 'secret');
define('DB_RR', getenv('DB_DATABASE') ?: 'hotelinking_app_local');
define('DB_RR_PORT', getenv('DB_PORT') ?: '3306');

// Database emails
define('HOST_EMAILS', $host);
define('USER_EMAILS', getenv('DB_USERNAME') ?: 'root');
define('PASS_EMAILS', getenv('DB_PASSWORD') ?: 'secret');
define('DB_EMAILS', getenv('DB_DATABASE') ?: 'hotelinking_app_local');
define('EMAILS_PORT', getenv('DB_PORT') ?: '3306');

// Widget DB
define('HOST_WIDGET', $host);
define('USER_WIDGET', getenv('DB_USERNAME') ?: 'root');
define('PASS_WIDGET', getenv('DB_PASSWORD') ?: 'secret');
define('DB_WIDGET', getenv('DB_DATABASE') ?: 'hotelinking_app_local');

define('MAINTENANCE', 'off');

// Generic SSO Integration Settings
define('HR_SSO_VERIFY_URL', getenv('HR_SSO_VERIFY_URL') ?: 'https://hr.mediusware.xyz/api/demo/validate/');
define('MOCK_SSO', getenv('MOCK_SSO') !== false ? filter_var(getenv('MOCK_SSO'), FILTER_VALIDATE_BOOLEAN) : true);

// Redis
define('REDIS_HOST', getenv('REDIS_HOST') ?: 'redis');
define('REDIS_PORT', (int)(getenv('REDIS_PORT') ?: 6379));

// Environment
define('ENV', getenv('APP_ENV') ?: 'dev');
define('TEST', 'false');
define('VERIFY_EMAILS', false);

// Security SALT
define('SALT', 'YUFVl5IdRFKkzhA1DSnFleoy9M2wKAQe');
define('IV', 'Adiemfr874ewdJY0');

define('FACEBOOK_PERMISSIONS', ['public_profile', 'email', 'user_friends', 'user_gender', 'user_birthday', 'user_location']);
define('FACEBOOK_ENABLE', false);

// Integrations
define('INTEGRATIONS_ENDPOINT', 'integrations/');
define('INTEGRATIONS_URL', 'hlintegrations-dev/api/');
define('INTEGRATIONS_URL_FRONT', 'https://dev-integrations.hotelinking.com/api/');
define('INTEGRATIONS_TOKEN', '');
define('INTEGRATIONS_ENABLE', false);

// Cache
$cacheConfig = [
    "path" => sys_get_temp_dir(),
];

// AWS S3 Placeholders for local dev
$_ENV['AWS_REGION'] = 'eu-west-1';
$_ENV['AWS_VERSION'] = 'latest';
$_ENV['AWS_CLIENT_SECRET_KEY'] = '';
$_ENV['AWS_SERVER_PUBLIC_KEY'] = '';
$_ENV['AWS_SERVER_PRIVATE_KEY'] = '';
$_ENV['S3_BUCKET_NAME'] = 'statics.hotelinking.com';
$_ENV['S3_BUCKET_ENDPOINT'] = 'https://s3-eu-west-1.amazonaws.com/statics.hotelinking.com';
$_ENV['S3_IMAGES_BUCKET'] = 'images.hotelinking.com';
$_ENV['S3_IMAGES_BUCKET_ENDPOINT'] = 'https://s3-eu-west-1.amazonaws.com/images.hotelinking.com';

define('MANDRILL_API_KEY', '');
define('MAX_HOTSPOT_TRIES', 5);
define('MAX_STAY_SHARE_TRIES', 50);
define('WIDGET_BUILDER_URL', '');
define('SENDEX_SCORE', 0.60);
define('LOWEST_SENDEX_SCORE', 0.40);
define('ZERO_BOUNCE_API_KEY', '');
define('SURVEYS_URL', 'http://localhost:8080');
define('S3_DATAMATCH_BUCKET', 'dev-datamatch');
define('S3_DATAMATCH_BUCKET_ENDPOINT', 'https://s3-eu-west-1.amazonaws.com/dev-datamatch');

include_once 'folders.php';
include_once 'facebook.php';

// Constants restored from the original config (missing after the SSO commit)
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
    'api_gateway' => getenv('API_URL') ?: 'https://hotelinking-api.mediusware.xyz/'
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

define('IMAGES_CLOUDFRONT_DISTRIBUTION_ID', 'E324O23VY1CC2H');

define('REPORTS_ENDPOINT', 'reports/');
define('WIDGET_ENDPOINT', 'widget/');
define('EMAIL_ENDPOINT', 'emails/');
define('HOTELINKING_ENDPOINT', 'hotelinking/');
define('EMAILS_ENDPOINT', 'emails/');
define('AUTOCHECKIN_ENDPOINT', 'autocheckin/');
define('STATISTICS_ENDPOINT', 'stats/');
define('NOC_ENDPOINT', 'noc/');
define('PAYMENTS_ENDPOINT', 'payments/');
define('DATAMATCH_COLUMNS', '["pms_id","pax_type","first_name","last_name","gender","check_in","check_out","birthday","nationality","res_room_number","res_room_type","hotel_id","brand_id","hotel_name","document_id","address","city","province","postal_code","telephone","birth_country","residence_country","res_board","res_adults","res_children","res_juniors","res_babies","res_seniors","res_id","res_nights","res_agency","res_company","res_intermediary","res_channel","res_contract","res_date","res_amount","res_extras","res_currency","res_comments"]');

define('GOOGLE_API_KEY', getenv('GOOGLE_API_KEY') ?: '');
define('GOOGLE_PORTAL_CLIENT_ID', '206628568605-o542irpdcu31h8h1eeqphln6afds3ifq.apps.googleusercontent.com');
define('GOOGLE_PORTAL_CLIENT_SECRET', getenv('GOOGLE_PORTAL_CLIENT_SECRET') ?: '');
define('GTM_ACCOUNT_ID', '');
define('GOOGLE_OAUTH_REDIRECT_URL', BASE_PATH . 'private/private-edit-booking-engines/');
define('WIFI_REDIRECT_URL', 'http://givemefreewifi.com');

define('CLOUDWATCH_LOG_ENABLED', false);
define('CLOUDWATCH_LOG_GROUP_NAME', 'staging-hotelinking-app');
define('CLOUDWATCH_LOG_STREAM_NAME', 'staging-hotelinking-app');
define('CLOUDWATCH_LOG_PORTAL_GROUP_NAME', 'staging-reports-portal-pro');
define('CLOUDWATCH_LOG_PORTAL_STREAM_NAME', 'staging-reports-portal-pro');

define('STREAM_SUB_DOMAIN', 'streams');
define('SCHEMAS_TABLE', 'dev-eventSchemas');
