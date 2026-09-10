<?php
// Basic libraries
include_once 'librerias.php';

// Disable indexcontrolval to be accesible as a webservice
define("INDEXCONTROLVAL", "1");

// Receive a post with some actions to perform & user_id and $hotel_id or die
if (empty($_POST['id_user']) || empty($_POST['id_hotel'])) {
    $log->error('Access to hotel loyalty webservice without all data needed, exiting');
    exit;
}

$chain_id = array_get($_POST, 'chain_id', null);
$user_id = array_get($_POST, 'id_user', null);
$hotel_id = array_get($_POST, 'id_hotel', null);
$room_id = array_get($_POST, 'room_id', null);
$emailsToSend = array_get($_POST, 'emails', null);

include_once RUTA_DIR . LIB . 'send_regular_customer.php';

$log->info('Starting regular costumer process');

sendRegularCostumerToEmailPlatform($hotel_id, $user_id, $room_id, $emailsToSend, $chain_id);
