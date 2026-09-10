<?php

require_once( __DIR__.'/../src/Services/Connections/ApiGatewayConnection.php');
require_once( __DIR__.'/../src/Services/Validator/DynamoDbConnection.php');
require_once( __DIR__.'/../src/Services/Validator/JsonSchemaValidator.php');
require_once( __DIR__.'/../src/Services/Validator/SchemaValidator.php');

function emitEvent($eventTopic, $eventName, $payload, $extraContext = [], $version = '1.0.0')
{
    global $log;

    $stream_enabled = defined('STREAM_EVENTS_ENABLED') ? STREAM_EVENTS_ENABLED : true;
    if ($stream_enabled) {
        $log->info("Emiting event from Hotelinking", ["eventName" => $eventName, "payload" => $payload]);
        
        $connection = createApiGatewayConnection();

        // Create complete payload event
        $event = createEventPayload($eventTopic, $eventName, $payload, $extraContext, $version);

        // Send Request
        try {
            $connection->sendRequest($event, STREAM_SUB_DOMAIN, 'POST');
        } catch(\Exception $e) {
            $log->debug("Validate and Send error", ["error" => $e, "event" => $event]);
        }
    } else {
        $log->debug("Event not sent because your environment configuration", ["eventName" => $eventName, "payload" => $payload, 'extraContext' => $extraContext]);
    }
}

function createApiGatewayConnection() {
    $validator = new JsonSchemaValidator();
    $schemaConnection = new DynamoDbConnection();
    $schemaValidator = new SchemaValidator($validator, $schemaConnection, SCHEMAS_TABLE);
    
    return new ApiGatewayConnection($schemaValidator);
}

function createEventPayload($eventTopic, $eventName, $payload, $extraContext, $version) {
    $brand_id = $_SESSION['brandID'] ?? $_SESSION['loggedBrandID'] ?? null;
    return [
        "schema" => "com.hotelinking/" . $eventTopic . "/" . $eventName . "/" . $version,
        "origin" => "hotelinking/$brand_id",
        "originalEntity" => "hotelinking",
        "eventSource" => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]",
        "context" => array_merge($extraContext, ["brandID" => $brand_id]), 
        "payload" => $payload
    ];
}
