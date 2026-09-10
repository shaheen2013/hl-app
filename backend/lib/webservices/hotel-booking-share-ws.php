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

	//Si hay share data tienes que sabe de donde viene y apuntar a la tabla adecuada
	//Si no hay tipo de share poner en otro.
	if(!empty($eventData)){
		//Check desde donde viene el share
		switch ($eventData) {
			case '2':
				$shareType ='';
				break;
			case '3':
				$shareType ='stay_';
				break;
			case '4':
				$shareType ='post_stay_';
				break;
			case '5':
				$shareType ='landing_';
				break;
			default:
				$shareType ='other_';
				break;
		};
	}else{
		$shareType ='other_';
	}

	//iframe opens
	if($event === 'iframe_opens'){
		//Save Statistics
		$sql = "INSERT INTO hotel_statistics (id_hotel, ".$shareType."iframe_opens) VALUES ($hotel, 1) ";
		$sql .= "ON DUPLICATE KEY UPDATE ".$shareType."iframe_opens= ".$shareType."iframe_opens + 1 ";
		escritura($sql);
		$send = array('done' => 'iframe_opens');
		echo json_encode($send);
		exit;
	}

	//fb_click
	if($event === 'fb_click'){
		//Save Statistics
		$sql = "INSERT INTO hotel_statistics (id_hotel, ".$shareType."fb_clicks) VALUES ($hotel, 1) ";
		$sql .= "ON DUPLICATE KEY UPDATE ".$shareType."fb_clicks= ".$shareType."fb_clicks + 1 ";
		escritura($sql);
		$send = array('done' => 'fb_clicks');
		echo json_encode($send);
		exit;
	}

	//mail_click
	if($event === 'mail_click'){
		//Save Statistics
		$sql = "INSERT INTO hotel_statistics (id_hotel, ".$shareType."mail_clicks) VALUES ($hotel, 1) ";
		$sql .= "ON DUPLICATE KEY UPDATE ".$shareType."mail_clicks= ".$shareType."mail_clicks + 1 ";
		escritura($sql);
		$send = array('done' => 'mail_clicks');
		echo json_encode($send);
		exit;
	}

	// first click
	if($event === 'first_click'){
		//Save Statistics
		$sql = "INSERT INTO hotel_statistics (id_hotel, ".$shareType."first_click) VALUES ($hotel, 1) ";
		$sql .= "ON DUPLICATE KEY UPDATE ".$shareType."first_click= ".$shareType."first_click + 1 ";
		escritura($sql);
		$send = array('done' => 'first_click');
		echo json_encode($send);
		exit;
	}

	// second click
	if($event === 'second_click'){
		//Save Statistics
		$sql = "INSERT INTO hotel_statistics (id_hotel, ".$shareType."second_click) VALUES ($hotel, 1) ";
		$sql .= "ON DUPLICATE KEY UPDATE ".$shareType."second_click= ".$shareType."second_click + 1 ";
		escritura($sql);
		$send = array('done' => 'second_click');
		echo json_encode($send);
		exit;
	}

	// share
	 if($event === 'share'){
		//Save Statistics
		$sql = "INSERT INTO hotel_statistics (id_hotel, ".$shareType."share) VALUES ($hotel, 1) ";
		$sql .= "ON DUPLICATE KEY UPDATE ".$shareType."share = ".$shareType."share + 1 ";
		escritura($sql);
		if($eventData === '3'){
			$sql2 = "SELECT form_url, username, password FROM hotel_oferta_stay WHERE id_hotel = $idHotel";
			$row = lecturaArray($sql2);
			$send = array('done' => 'giving access to wifi', 'username' => $row['username'], 'password' => $row['password'], 'url' => $row['form_url']);
				echo json_encode($send);
		}else{
			$send = array('done' => 'share');
			echo json_encode($send);
		}
		exit;
	}

	// declined permissions FB2
	if($event === 'declined_permissions'){
		$permissions = (explode(",",$_POST['perm']));
		$sql = "INSERT INTO hotel_statistics (id_hotel";
		//'public_profile,email,user_friends,publish_actions'
		foreach ($permissions as $value) {
			$sql .= ",".$shareType."declined_".$value."";
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
			$sql .= "".$shareType."declined_$value = ".$shareType."declined_$value + 1";
			$comma += 1;
		};
		escritura($sql);
		$send = array('done' => 'declined_permissions');
		echo json_encode($send);
		exit;
	}

	//cancel FB1
	if($event === 'canceled'){
		//Save Statistics
		$sql = "INSERT INTO hotel_statistics (id_hotel, ".$shareType."canceled) VALUES ($hotel, 1) ";
		$sql .= "ON DUPLICATE KEY UPDATE ".$shareType."canceled= ".$shareType."canceled + 1 ";
		escritura($sql);
		$send = array('done' => 'canceled');
		echo json_encode($send);
		exit;	
	}

	//cancel reintent FB1
	if($event === 'hitReintent'){
		//Save Statistics
		$sql = "INSERT INTO hotel_statistics (id_hotel, ".$shareType."reintents) VALUES ($hotel, 1) ";
		$sql .= "ON DUPLICATE KEY UPDATE ".$shareType."reintents= ".$shareType."reintents + 1 ";
		escritura($sql);
		$send = array('done' => 'reintent');
		echo json_encode($send);
		exit;	
	}

}else{
	$send = array('error' => 'not enought data', 'data' => 'event');
	echo json_encode($send);
	exit;
}