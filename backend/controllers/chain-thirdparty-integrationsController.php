<?php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}
//Get chain id
$chain_id = $_SESSION["c_logueado"];
//Check savings
if($_POST){
    //pushtech
    if(isset($_POST['pustechToken']) && isset($_POST['pustechToken'])){
        set_pushtech_info('chain', $chain_id, $_POST['pustechToken'], $_POST['pustechSecret']);
        $ok = array(true, '2007');
    }
}
//Get thirdparty integration info
$pushtech = get_pushtech_info('chain', $chain_id);
