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
