<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

  //Check cardId
  function getUserGUID($email)
  {
    $email = mysqli_real_escape_string(conectar(), $email);

    $sql = "SELECT guid 
    FROM users 
    INNER JOIN user_guid ON user_guid.id_usuario = users.id
    WHERE email='$email'";
    $rs = mysqli_query (conectar(), $sql);
    $row = mysqli_fetch_assoc($rs);
    liberar ($rs);
    return $row['guid'];
  }

  //Unsuscribe User
  function unsuscribeUser($email)
  {
    $email = mysqli_real_escape_string(conectar(), $email);
    $sql = "UPDATE users SET notif_hotelinking = 0 WHERE email = '$email'";
    $rs = mysqli_query (conectar(), $sql);
    if(!$rs){
      return false;
    }
    return true;
  }
?>