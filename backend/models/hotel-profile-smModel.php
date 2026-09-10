<?php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//Get facebook fan page
function getFacebookFanPage ($id){
    $id = mysqli_real_escape_string(conectar(1), $id);
    
    $sql = "SELECT facebook_page FROM hoteles WHERE id = $id";
    $rs = mysqli_query (conectar(), $sql) or die(mysqli_error(conectar(1)));
    $row = mysqli_fetch_assoc($rs);
    liberar($rs);

    return $row['facebook_page'];
}

//Save facebook Fan Page
function saveFacebookFanPage ($id , $fanPage){
    $fp = mysqli_real_escape_string(conectar(), $fanPage);
    $sql = "UPDATE hoteles SET facebook_page = '$fp' WHERE id = $id";
    $link = mysqli_query (conectar(), $sql) or die(mysqli_error(conectar()));
    
    //Borramos de cache
    deleteCacheByTag('hotel_facebook_' . $id);

    return $link;
}

// FX para guardar el texto que se publica al hacer share en cada una de las pantallas de share 
// $page=pre, stey, post
function guardarSMShareText($id_hotel, $lang, $pre, $stay, $post)
{
    $id_hotel = mysqli_real_escape_string(conectar(1), $id_hotel);
    $lang = mysqli_real_escape_string(conectar(1), $lang);
    $pre = mysqli_real_escape_string(conectar(1), $pre);
    $stay = mysqli_real_escape_string(conectar(1), $stay);
    $post = mysqli_real_escape_string(conectar(1), $post);

    $sql = "SELECT id FROM hotel_share_text WHERE id_hotel=$id_hotel AND lang='$lang' ";
    $rs = mysqli_query (conectar(), $sql) or die(mysqli_error());
    $row = mysqli_fetch_assoc($rs);
    liberar($rs);

    if(!empty($row['id'])){
        $sql2 = "UPDATE hotel_share_text SET pre='".$pre."', stay='".$stay."', post='".$post."' WHERE id='".$row['id']."' ";
    }else{
        $sql2 = "INSERT INTO hotel_share_text (id_hotel, lang, pre, stay, post) 
        VALUES ('".$id_hotel."', '".$lang."', '".$pre."', '".$stay."', '".$post."')";
    }
    mysqli_query (conectar(), $sql2) or die(mysqli_error());

    //Borramos de cache
    deleteCacheByTag('hotel_facebook_' . $id_hotel);

    return;
}