<?php //Miramos si esta definida la variable de control de index.php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}

if (empty($_SESSION['private'])) {
    header('Location: /');
}

function getBookingEngines () {
    $select_booking_engines = "SELECT id, name, gtm_container_id, gtm_workspace_id, gtm_variable_map FROM booking_engines";
    $booking_engines = lecturaArray($select_booking_engines);

    return $booking_engines;
}

function updateContainerId ($container_id, $booking_engine_id) {
    $update_map = "UPDATE booking_engines SET gtm_container_id='$container_id' WHERE id=$booking_engine_id";    
    return escritura($update_map);
}

function updateWorkspaceId ($workspace_id, $booking_engine_id) {
    $update_map = "UPDATE booking_engines SET gtm_workspace_id='$workspace_id' WHERE id=$booking_engine_id";    
    return escritura($update_map);
}

function insertNewBookingEngine ($booking_engine_name, $container_id, $workspace_id) {
    $insert_booking_engine = "INSERT INTO booking_engines (name, gtm_container_id, gtm_workspace_id) VALUES ('$booking_engine_name', $container_id, $workspace_id)"; 
    return escritura($insert_booking_engine);
}

function updateGTMVariablesMap($gtm_mapped_variables, $booking_engine_id) {
    $gtm = json_encode($gtm_mapped_variables);
    $update_map = "UPDATE booking_engines SET gtm_variable_map='$gtm' WHERE id=$booking_engine_id";
    
    return escritura($update_map);
}