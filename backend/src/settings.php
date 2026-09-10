<?php
return [
    'settings' => [
        'debug' => ENV === "production" ? false : true,
        'displayErrorDetails' => ENV === "production" ? false : true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header
        'determineRouteBeforeAppMiddleware' => true,
        'logger' => [
            'name' => 'slim-app',
            'path' => 'php://stdout',
            'level' => \Monolog\Logger::DEBUG,
        ],
        'db' => [
            'driver' => 'mysql',
            'host' => HOST,
            'database' => DB,
            'username' => USER,
            'password' => PASS,
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
        ]
    ],
];