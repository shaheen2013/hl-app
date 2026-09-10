<?php

if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

if(isset($_GET)){
    if(isset($_GET['cache'])){

        if(isset($_GET['g-recaptcha-response'])){
            $captcha=$_GET['g-recaptcha-response'];
        }

        if($captcha){

            if ($_GET['cache'] === 'clean'){
                include_once RUTA_DIR . LIB . 'cache.php';
                $log->warning('Cache cleared');
                $cleanCache = $InstanceCache->clear();
            }

        }


    }
}