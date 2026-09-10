<?php
// Fx para obtener satisfaction del hotel
function obtenerSatisfHotel($id_hotel)
{
    global $log;
    // $cacheName = 'hotel_satisfaction_' . $id_hotel;
    // $cache = getFromCache($cacheName);
    // if(!$cache){
        $con = conectar(1);
        $id_hotel = mysqli_real_escape_string($con, $id_hotel);
        $sql = "SELECT diasEnvio AS diasEnvioSatisf, send_hour AS send_hourSatisf, puntMin AS puntMinSatisf, total_followup_email AS followupEmails, warning_email, ignoreRating, sendThanksMail, sendToNonCustomers, filter_warning FROM hotel_satisfaction WHERE id_hotel=$id_hotel";
        $row = lectura($sql, $con);
        // if ($row) {
        //     setToCache('hotel_satisfaction_' . $id_hotel, $row, 31536000);
        // }
    // }else{
    //     $row = $cache->get();
    // }

    // $log->addDebug('Satisfaction data from hotel is : ' . json_encode($row));
    return $row;
}