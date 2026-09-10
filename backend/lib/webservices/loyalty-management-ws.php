<?php
include 'librerias.php';// Librerias básicas
require_once __DIR__ . '/../../src/Services/Connections/ApiGatewayConnection.php';
$gateway = new ApiGatewayConnection();

// Restringir ips que pueden acceder
include_once RUTA_DIR.LIB.'check_access.php';
checkIpAccess('lo-ma', $_SERVER['REMOTE_ADDR']);

if($_POST){
    if(array_get($_POST, 'action') == 'save'){

        $brandID = array_get($_POST, 'brand_id');
        $offerID = array_get($_POST, 'offer_goal_id');
        $rewardType = array_get($_POST, 'offer_type');
        $rewardID = array_get($_POST, 'offer_id');
        $product = array_get($_POST, 'product');
        $numberVisits = array_get($_POST, 'n_triggers');
        $daysToExpire = array_get($_POST, 'days_to_expire');

        try {
            $offerSearch = $offerID ? '/'.$offerID : '';

            $offerID = $gateway->sendRequest([
                'number_visits' => $numberVisits,
                'reward_id' => $rewardID,
                'reward_type' => $rewardType,
                'days_to_expire' => $daysToExpire,
            ], 
                HOTELINKING_ENDPOINT . 'brand/' . $brandID . '/offer' . $offerSearch, 
                'PUT');
    
            echo $offerID;
        } catch (Exception $e) {
            global $log;
            $log->info("Error inserting new Loyalty Offer", [$e]);
            echo 'error';
        }
        
    }
    if(array_get($_POST, 'action') == 'stay_time_save'){
        $chain_id=array_get($_POST, 'chain_id');
        $chain_stay_time=array_get($_POST, 'chain_stay_time');
        include_once '../../'.MODEL.'loyalty-managementModel.php';
        $chain_id = updateChainStayTime($chain_id,$chain_stay_time);
        echo $chain_id;
    }
    if(array_get($_POST, 'action') == 'delete'){
        try {
            $gateway->sendRequest(null, 
                HOTELINKING_ENDPOINT . 'brand/' . array_get($_POST, 'brandID') . '/offer/' . array_get($_POST, 'offerID'), 
                'DELETE');
    
            echo 'deleted';
        } catch (Exception $e) {
            global $log;
            $log->info("Error deleting Loyalty Offer", [$e]);
            echo 'error';
        }
    }
}
else{
    echo 'error';
}
?>