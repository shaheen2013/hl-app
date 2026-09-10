<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

$changed = false;

if($_POST){
    $changeMultipliers = changeMultipliers($_POST['singleClick'],$_POST['thousandImpressions'],$_POST['impressionsPercentage']);
    if($changeMultipliers){
        $changed = true;
    }
}

$multipliers = getMultipliers();