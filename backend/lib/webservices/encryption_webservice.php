<?php
//allow requests from anywhere
header('Access-Control-Allow-Origin: *');

define("INDEXCONTROLVAL", "1");
include_once 'librerias.php';

// Encrypt
if (!empty($_GET['v'] && empty($_GET['decrypt']))) {
    $plaintext = $_GET['v'];
    $hlti_array = json_decode($plaintext, true);
    $logOp->info('Setting HLTI', $hlti_array);
    echo encrypt_decrypt('encrypt', $_GET['v']);
}

// Decrypt
if (!empty($_GET['v']) && !empty($_GET['decrypt'])){
    $decrypted = encrypt_decrypt('decrypt', $_GET['v']);
    $logOp->debug('decrypted cookie is ' . $decrypted);
    echo $decrypted;
}
/**
 * simple method to encrypt or decrypt a plain text string
 * initialization vector(IV) has to be the same when encrypting and decrypting
 *
 * @param string $action: can be 'encrypt' or 'decrypt'
 * @param string $string: string to encrypt or decrypt
 *
 * @return string
 */
function encrypt_decrypt($action, $string) {
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $secret_key = SALT;
    $secret_iv = IV;
    // hash
    $key = hash('sha256', $secret_key);

    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secret_iv), 0, 16);
    if ( $action == 'encrypt' ) {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    } else if( $action == 'decrypt' ) {
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }
    return $output;
}