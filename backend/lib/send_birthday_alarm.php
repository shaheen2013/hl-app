<?php

 function sendBirthdayAlarmToEmailPlatform($id_user, $id_hotel, $room_id, $user_gender, $email){
    $con = conectar(2);
    
    $id_user = mysqli_real_escape_string($con, $id_user);
    $id_hotel = mysqli_real_escape_string($con, $id_hotel);
    $room_id = mysqli_real_escape_string($con, $room_id);
    $user_gender = mysqli_real_escape_string($con,$user_gender);

    $sql = "INSERT INTO birthday_alarms (user_id, hotel_id, last_connection, send_date, gender, hotel_email";
    if($room_id != null && $room_id != ""){
        $sql = $sql.  ", room_id) VALUES (($id_user), ($id_hotel), NOW(), NOW(), '$user_gender', '$email', '$room_id' )";
    }
    else{
        $sql = $sql. ") VALUES (($id_user), ($id_hotel), NOW(), NOW(), '$user_gender', '$email')";
    }
    escritura($sql, $con);
 };

 function getBirthdayAlarmMails($id_hotel){
     $con = conectar(1);

     $id_hotel = mysqli_real_escape_string($con, $id_hotel);


     $sql = "SELECT birthdayAlertEmails, birthday_alarm_days_range FROM hoteles WHERE id = '$id_hotel'";
     $row = lectura($sql, $con);
     return $row;
 }

function getBirthdayProductConfigAlarm($brandID)
{
    $sql = "SELECT value as birthday_alarm 
        FROM
	        brand_product_config
        INNER JOIN 
            brand_product ON brand_product.id = brand_product_config.brand_product_id
        INNER JOIN 
            product_config ON product_config.id = brand_product_config.product_config_id
        WHERE 
            label = 'birthday_alarm' AND brand_id=$brandID";
    
    $query = lectura($sql);

    return $query;
}