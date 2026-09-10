<?php

//Obtain hotel satisfaction configuration
include_once LIB . 'obtenerDatosSatisfaction.php';

/**
 * Fx para guardar satisfaction del hotel
 * @param $id_hotel // hotel id
 * @param $diasEnvio // days that satisfaction email is send from checkin
 * @param $send_hour // hours that satisfaction email is send from checkin
 * @param $puntMin // Wich score must me below to send email
 * @param $warning_email // email to send warning
 *  @param $followupEmails // number of emails to send if user dont answer the satisfaction survey
 */
function guardarSatisfactionHotel($id_hotel, $diasEnvio, $send_hour, $puntMin, $warning_email, $sendThanksMail, $sendToNonCustomers, $followupEmails, $filterWarning)
{
    $con = conectar();
    $diasEnvio = mysqli_real_escape_string($con, $diasEnvio);
    $send_hour = mysqli_real_escape_string($con, $send_hour);
    $puntMin = mysqli_real_escape_string($con, $puntMin);
    $warning_email = mysqli_real_escape_string($con, $warning_email);
    $sendThanksMail = mysqli_real_escape_string($con, $sendThanksMail);
    $followupEmails = mysqli_real_escape_string($con, $followupEmails);
    $filterWarning = mysqli_real_escape_string($con, $filterWarning);
    $sql = "INSERT INTO hotel_satisfaction (
                id_hotel,
                diasEnvio,
                send_hour,
                puntMin,
                warning_email,
                sendThanksMail,
                SendToNonCustomers,
                total_followup_email,
                filter_warning)
            VALUES (
                '$id_hotel', 
                '$diasEnvio',
                '$send_hour',
                '$puntMin',
                '$warning_email',
                '$sendThanksMail',
                '$sendToNonCustomers',
                '$followupEmails',
                '$filterWarning')
		    ON DUPLICATE KEY UPDATE diasEnvio='$diasEnvio', send_hour='$send_hour', puntMin='$puntMin', warning_email='$warning_email', sendThanksMail='$sendThanksMail', sendToNonCustomers='$sendToNonCustomers', total_followup_email='$followupEmails', filter_warning='$filterWarning'";
    escritura($sql, $con);

    //Delete cache
    deleteCacheByKey('hotel_satisfaction_' . $id_hotel);
}

/**
 * save same email for all hotels in chain
 * @param $email // email to send warning
 * @param $arrayHoteles // hotels to update in bulk
 */
function storeEmailInAllHotels($email, $arrayHoteles)
{
    $con = conectar();
    $email = mysqli_real_escape_string($con, $email);
    $sql = "INSERT INTO `hotel_satisfaction` (`id_hotel`, `warning_email`) VALUES ";
    //for each id in chain insert or update the email
    $numItems = count($arrayHoteles);
    $i = 0;
    foreach($arrayHoteles as $hotel) {

        //delete cache
        deleteCacheByKey('hotel_satisfaction_' . $hotel['id']);

        $id = $hotel['id'];
        if(++$i === $numItems) {
            $sql .= "($id, '$email') ";
        }else{
            $sql .= "($id, '$email'), ";
        }
    }

    $sql .= "ON DUPLICATE KEY UPDATE warning_email = '$email'";
    escritura($sql, $con);
}

?>