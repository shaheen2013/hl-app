<?php
function obtenerDatosHotelShare($id_hotel)
{
	$con = conectar();
    $id_hotel = mysqli_real_escape_string($con, $id_hotel);
    $sql = "SELECT hoteles.id, hoteles.hotelName, IFNULL(hoteles.logo, 0) AS logo, 
    fotoBg, twitterAccount
    FROM hoteles
    WHERE hoteles.id='" . $id_hotel . "' ";
    $row = lectura($sql, $con);
    $row['hotelName_san'] = string_sanitize($row['hotelName']);
    return $row;
}
?>