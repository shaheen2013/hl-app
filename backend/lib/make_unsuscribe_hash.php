<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

function generate_hash($email,$guid, $cost=11){
    $salt=substr(base64_encode(openssl_random_pseudo_bytes(17)),0,22);
    $salt=str_replace("+",".",$salt);
    $param='$'.implode('$',array(
            "2y", //select the most secure version of blowfish (>=PHP 5.3.7)
            str_pad($cost,2,"0",STR_PAD_LEFT), //add the cost in two digits
            $salt
    ));
    $digest = $email . $guid;
    return crypt($digest,$param);
}

function validate_hash($digest, $hash)
{
    return crypt($digest, $hash)==$hash;
}

function createUrlUnsuscribe ($email, $guid)
{
    $digest = generate_hash($email, $guid);
    global $urltree;
	return SECURE_BASE_PATH . $urltree['unsuscribe'] . 'unsuscribe/?h=' . $digest  . '&m=' . $email;
}

?>
