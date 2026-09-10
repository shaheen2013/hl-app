<?php

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\JsonFormatter;

$aws = AWS;

$sdkParams = [
    'region' => $aws['region'],
    'version' => 'latest',
    'credentials' => [
        'key' => $aws['credentials']['key'],
        'secret' => $aws['credentials']['secret']
    ]
];

putenv('AWS_CSM_ENABLED=false');


$formatter = new JsonFormatter();

// Days to keep logs, 14 by default. Set to `null` to allow indefinite retention.
$retentionDays = 14;
$batchSize = 10000;
$tags = [];

//function to pass in as argument as a processor for the log
//adds relevant information (session_id, user_id, hotel_id) when available to the log
$pushHotelinkingInfo = function ($record) {
    $record['extra']['session_id'] = session_id();

    if (!empty($_SESSION['user']) && !empty($_SESSION['user']['id'])) {
        $record['extra']['user_id'] = $_SESSION['user']['id'];
    }

    if (!empty($_SESSION['hotel']['id'])) {
        $record['extra']['hotel_id'] = $_SESSION['hotel']['id'];
    }
    $record['extra']['brand_id'] = $_SESSION['brandID'] ?? null;

    $record['extra']['environment'] = ENV;

    $record['extra']['userAgent'] =  $_SERVER['HTTP_USER_AGENT'] ?? "";

    $record['extra']['hotel_name'] = $_SESSION['hotelInfo']['hotelName'] ?? "";

    return $record;
};


// Create the log instance and add the hotelinking pushProcessor
if (ENV === 'production' || ENV === 'dev') {
    $log = new Logger('production');
    $log->pushProcessor($pushHotelinkingInfo);
    $log->pushProcessor(new \Monolog\Processor\WebProcessor);

    // New logger for portal validations
    $logPortal = new Logger('portalProduction');
    $logPortal->pushProcessor($pushHotelinkingInfo);
    $logPortal->pushProcessor(new \Monolog\Processor\WebProcessor);

    $streamHandler = new StreamHandler(RUTA_DIR . 'logs/app.log', Logger::INFO);
    $streamHandler->setFormatter($formatter);
    $log->pushHandler($streamHandler);
    $logPortal->pushHandler($streamHandler);
} else {
    $log = new Logger('test');
    $log->pushProcessor($pushHotelinkingInfo);

    $logPortal = new Logger('portalTest');
    $logPortal->pushProcessor($pushHotelinkingInfo);

    $log->pushHandler(new StreamHandler(RUTA_DIR . 'logs/app.log', Logger::DEBUG));
    $logPortal->pushHandler(new StreamHandler(RUTA_DIR . 'logs/app.log', Logger::DEBUG));
}

//add clones of the $log instance as new channels for our log
//this enables us to easily filter different parts of our app
$logNav = $log->withName('navigation');
$logDB = $log->withName('database');
$logOp = $log->withName('operations');
