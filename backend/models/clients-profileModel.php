<?php //Miramos si esta definida la variable de control de index.php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}

include_once LIB . 'paramsUrl.php';


function getSatisfactionPuntMin($id_hotel)
{
    $con = conectar(1);

    $sql = "SELECT  puntMin FROM hotel_satisfaction WHERE id_hotel = '$id_hotel'";

    $row = lectura($sql, $con);

    return $row['puntMin'];
}

