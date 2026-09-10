<?php 

include_once 'librerias.php';// Librerias básicas

if(!empty($_POST['event'])){

	if(empty($_POST['hId'])){
		$send = array('error' => 'not enought data', 'data' => 'hid');
		echo json_encode($send);
		exit;
	}

	$idHotel = $_POST['hId'];
	$eventData = $_POST['shareData'];
	$event = $_POST['event'];

	$con = conectar(1);
    $hotel = mysqli_real_escape_string($con, $idHotel);
    $eventData = mysqli_real_escape_string($con, $eventData);
    $event = mysqli_real_escape_string($con, $event);
    desconectar($con);

	//Check event type and store in DDBB
	if($event === 'iframe_opens'|| $event === 'fb_click'|| $event === 'mail_click'|| $event === 'first_click'|| $event === 'second_click'|| $event === 'canceled'|| $event === 'reintent'|| $event === 'share' || $event === 'error')
	storeStatistics ($hotel, $event, $eventData);


	// declined permissions
	if($event === 'declined_permissions'){
		$permissions = (explode(",",$_POST['perm']));
		//unset because dont exit decline_user_birthday field
		unset($permissions['user_birthday']);
		$sql = "INSERT INTO hotel_statistics (id_hotel";
		//'public_profile,email,user_friends,publish_actions'
		foreach ($permissions as $value) {
			$sql .= ",".$eventData."_declined_".$value."";
		};
		$sql .= ") VALUES ($hotel";
		foreach ($permissions as $value) {
			$sql .= ",1";
		};
		$sql .= ") ";
		$sql .= "ON DUPLICATE KEY UPDATE ";
		$comma = 0;
		foreach ($permissions as $value) {
			if ($comma > 0){
				$sql .= ',';
			}
			$sql .= "".$eventData."_declined_$value = ".$eventData."_declined_$value + 1";
			$comma += 1;
		};
		//echo $sql; 
		escritura($sql);
		$send = array('done' => 'declined_permissions');
		echo json_encode($send);
		exit;
	}

}else{
	$send = array('error' => 'not enought data', 'data' => 'event');
	echo json_encode($send);
	exit;
}

///HELPERS///
function storeStatistics ($hotel, $action, $shareType){

	$dataAction = $shareType.'_'.$action;

	$sql = "INSERT INTO hotel_statistics (id_hotel, $dataAction) VALUES ($hotel, 1) ";
	$sql .= "ON DUPLICATE KEY UPDATE $dataAction = $dataAction + 1 ";
	escritura($sql);
	$send = array('done' => $action);
	echo json_encode($send);
	exit;
}
?>