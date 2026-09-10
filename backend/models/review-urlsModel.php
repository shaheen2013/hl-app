<?php
//Guardar las URLS de reviews del hotel
function guardarUrlsReview($arrayUrlsReview)
{
    $arrayData = obtenerDatosPostReviewUrls();

    $con = conectar(2);
    //Limpiamos los campos de array
    $arrayUrlsReview = escapeArray($arrayUrlsReview, $con, false);

    // insert / update
    $sql = "INSERT INTO urls (hotel_id, ";
    foreach ($arrayData as $dato) {
        $sql .= $dato . ", ";
    }
    $sql = substr($sql, 0, -2);// Quitamos última coma
    $sql .= ") VALUES (";
    foreach ($arrayUrlsReview as $url) {
        $sql .= " '" . $url . "', ";
    }
    $sql = substr($sql, 0, -2);
    $sql .= ") ";

    $sql .= " ON DUPLICATE KEY UPDATE ";
    foreach ($arrayData as $dato) {
        $sql .= $dato . "='" . $arrayUrlsReview[$dato] . "', ";
    }
    $sql = substr($sql, 0, -2);
    escritura($sql, $con);
}

//Obtener las URls del review del hotel
function obtnerUrlsReviewHotel($hotel_id)
{
    global $log;
    $sql = "SELECT * FROM urls WHERE hotel_id= $hotel_id LIMIT 1";
    $log->debug($sql);
    $row = lectura($sql, '', true, 2);
    return ($row);
}

?>