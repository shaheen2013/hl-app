<?php
global $log;
$cookie_name = array_get($_GET,'name','test');
$cookie_value = array_get($_GET,'value','funciona');
$path = array_get($_GET,'path','');

$log->debug('',$_GET);
setcookie($cookie_name, $cookie_value, [
    'expires' => time() + (86400 * 365),
    'path' => "/app/$path",
    'samesite' => 'None',
    'secure' => true,
    'httponly' => true
]); // 86400 = 1 day