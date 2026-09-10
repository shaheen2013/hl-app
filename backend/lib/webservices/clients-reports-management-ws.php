<?php
include 'librerias.php'; // Librerias básicas
// Restringir ips que pueden acceder
include_once RUTA_DIR . LIB . 'check_access.php';
include_once APP . 'Services/Connections/ApiGatewayConnection.php';
checkIpAccess('lo-ma', $_SERVER['REMOTE_ADDR']);
global $log;
if ($_POST) {

    if (array_get($_POST, 'action') == 'save') {
        $offer_goal_id = array_get($_POST, 'automatic_report_id');
        $hotel_id = array_get($_POST, 'hotel_id');
        $chain_id = array_get($_POST, 'chain_id');

        include_once RUTA_DIR . LIB . 'curateEmailsString.php';

        $emailList = array_get($_POST, 'emails');
        // $log->debug("Emails: " . $emailList);

        $emailsCurated = curateEmailsString($emailList);
        $emailsCurated = (!empty($emailsCurated) ? implode(",", $emailsCurated) : "");
        // $log->debug("Emails curated: " . $emailsCurated);

        $emails = $emailsCurated;

        $report_type = array_get($_POST, 'report_type');
        $frequency = array_get($_POST, 'frequency');

        include_once '../../' . MODEL . 'clients-reports-managementModel.php';

        $previousReports = getAutomaticReports($hotel_id, $chain_id);
        foreach ($previousReports as $previousReport) {
            if (
                array_get($previousReport, 'emails') == $emails &&
                array_get($previousReport, 'frequency') == $frequency &&
                array_get($previousReport, 'report_type') == $report_type
            ) {
                echo 'error';
                return;
            }
        }

        $id_offer_goal = saveAutomaticReports($offer_goal_id, $hotel_id, $chain_id, $emails, $report_type, $frequency);
        echo $id_offer_goal;
    }
    if (array_get($_POST, 'action') == 'delete') {
        $offer_goal_id = array_get($_POST, 'automatic_report_id');
        include_once '../../' . MODEL . 'clients-reports-managementModel.php';
        $id_offer_goal = deleteAutomaticReports($offer_goal_id);
        echo 'deleted';
    }
    // Todo: If more products are added to reports, improve this
    if (array_get($_POST, 'action') == 'portal_pro_save') {

        $brandId = array_get($_POST, 'brandId');
        $productId = array_get($_POST, 'productId');
        $emailList = array_get($_POST, 'emails');
        $interval = array_get($_POST, 'interval');
        $chainId = array_get($_POST, 'chainId', "");
        $method = array_get($_POST, 'method');


        include_once RUTA_DIR . LIB . 'curateEmailsString.php';
        include_once '../../' . MODEL . 'clients-reports-managementModel.php';

        if (!$emailList || !$interval) {
            return;
        }

        $emailsCurated = curateEmailsString($emailList);

        $emails = $emailsCurated;

        savePortalProAutomaticReports($brandId, $productId, [
            'parent_id' => $chainId,
            'email_subscriptions' => $emails,
            'interval' => $interval,
            'format' => 'csv',
            'active' => true
        ], $method);
    }
    if (array_get($_POST, 'action') == 'portal_pro_delete') {

        $brandId = array_get($_POST, 'brandId');
        $productId = array_get($_POST, 'productId');

        include_once RUTA_DIR . LIB . 'curateEmailsString.php';
        include_once RUTA_DIR . MODEL . 'clients-reports-managementModel.php';

        deletePortalProAutomaticReports($brandId, $productId);
    }
} else {
    echo 'error';
}
