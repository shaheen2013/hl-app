<?php

include_once 'librerias.php'; 
// Restringir ips que pueden acceder
include_once RUTA_DIR.LIB.'check_access.php';
checkIpAccess('hl_app', $_SERVER['REMOTE_ADDR']);

header('Content-Type: application/json');

if(array_has($_POST, 'user_id') && array_has($_POST, 'hotel_id') && array_has($_POST, 'gdpr_events')){
    $user_id = array_get($_POST, 'user_id');
    $hotel_id = array_get($_POST, 'hotel_id');
    $gdpr_events = array_get($_POST, 'gdpr_events');


    if($user_id && $hotel_id && $gdpr_events){
      $events = array();

      //map over the gdpr events and return a str type : ("user_id", "hotel_id", "event", "date")
      $events = array_map(function($event){
        $values = join('","', array_values($event));
        $str = '("'. $values .'")';
        return $str;
      }, $gdpr_events);


      try {
        $sql = "INSERT INTO gdpr_history(event, created_at, user_id, hotel_id) VALUES " . join(',',$events);
        $con = conectar();
        $id = escritura($sql, $con);
        echo json_encode(array('success'=> true));
      } catch (Exception $e){
        sendErrorAndExit('could not create gdpr events');
      }

    }

} else {
  sendErrorAndExit('not enough information');
}




function sendErrorAndExit($errorMsg){
  header("HTTP/1.0 500 Internal Server Error"); 
  echo json_encode(array("error" => $errorMsg ));
  exit();
}
