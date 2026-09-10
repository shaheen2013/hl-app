<?php

if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}

$gateway = new ApiGatewayConnection();

$searchInput = array_get($_GET, 'search', '');

if (!empty($searchInput)) {
    try {
        $userBrand = json_decode($gateway->sendRequest([], HOTELINKING_ENDPOINT . "user_brands/$searchInput", 'GET'), true);
    } catch (Exception $e) {
        global $log;
        $log->error("Error creating new comment incident", [$e]);
    }
}

