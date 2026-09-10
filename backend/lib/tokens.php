<?php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}
/**
 * Generates and stores a token to show up landing iframe on booking engine
 * @param $user_id
 * @param $hotel_id
 * @return string
 */
function generateToken($user_id, $hotel_id)
{
    $con = conectar();
    $token = uniqid('hl_', true);
    $sql = "INSERT INTO referrer_tokens (id_usuario,id_hotel,token,fecha,share) VALUES ($user_id,$hotel_id,'$token',NOW(),1)";
    escritura($sql, $con);
    return $token;
}


function getReferrerTokenByUserId($user_id, $hotel_id){
  $con = conectar(1);
  $user_id = mysqli_real_escape_string($con, $user_id);
  $hotel_id = mysqli_real_escape_string($con, $hotel_id);
  $sql = "SELECT token FROM referrer_tokens WHERE id_usuario = $user_id AND id_hotel = $hotel_id ORDER BY fecha desc";
  $res = lectura($sql);
  return $res['token'] ?? null;
}

?>