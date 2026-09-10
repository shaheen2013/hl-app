<?php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//Save in hotel statistics if conversions comes from Facebook or email
function saveStatistics($fb = null, $hotel)
{
	$con = conectar();    
    $fb = mysqli_real_escape_string($con, $fb);
    $hotel = mysqli_real_escape_string($con, $hotel);
    
    $column = ($fb == '1' ? 'landing_fb_success' : 'landing_mail_success');
    //Save Statistics
    $sql = "INSERT INTO hotel_statistics (id_hotel, $column) VALUES ($hotel, 1) ";
    $sql .= "ON DUPLICATE KEY UPDATE $column = " . $column . " + 1";
   	escritura($sql, $con, false);
    desconectar($con);

    return true;
}