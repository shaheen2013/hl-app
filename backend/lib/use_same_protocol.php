<?php
function use_secure_protocol()
{
    $protocol =  false;
    if (isset($_SERVER['HTTPS']))
        if (strtoupper($_SERVER['HTTPS'])=='ON')
            $protocol =  true;

    return $protocol;
}