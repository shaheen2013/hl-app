<?php

// the URL for EAP is http://portal_server_ip?clientMac=client_mac&apMac=ap_mac&ssidName=ssid_name&t=time_since_epoch&radioId=Radio_id&site=site_name&redirectUrl=xx
// The URL for gateway is http://portal_server_ip?clientMac=client_mac&gatewayMac=gateway_mac&vid=vid&t=time_since_epoch&site=site_name&redirectUrl=xx

    //$clientMac = (!empty($_GET['clientMac']) ? $_GET['clientMac'] : '');
    $_SESSION['tp_clientMac'] = array_get($_GET, 'clientMac', array_get($_SESSION, 'tp_clientMac', FALSE));
    //$site = (!empty($_GET['site']) ? $_GET['site'] : '');
    $_SESSION['tp_site'] = array_get($_GET, 'site', array_get($_SESSION, 'tp_site', FALSE));
    //$time_since_epoch = (!empty($_GET['t']) ? $_GET['t'] : '');
    $_SESSION['tp_t'] = array_get($_GET, 't', array_get($_SESSION, 'tp_t', FALSE));
    //$redirectUrl = (!empty($_GET['redirectUrl']) ? $_GET['redirectUrl'] : '');
    $_SESSION['tp_redirectUrl'] = array_get($_GET, 'redirectUrlt', array_get($_SESSION, 'tp_redirectUrl', FALSE));
    //EAP
    //$apMac = (!empty($_GET['apMac']) ? $_GET['apMac'] : '');
    $_SESSION['tp_apMac'] = array_get($_GET, 'apMac', array_get($_SESSION, 'tp_apMac', FALSE));
    //$ssidName = (!empty($_GET['ssidName']) ? $_GET['ssidName'] : '');
    $_SESSION['tp_ssidName'] = array_get($_GET, 'ssidName', array_get($_SESSION, 'tp_ssidName', FALSE));
    //$radioId = (!empty($_GET['radioId']) ? $_GET['radioId'] : '');
    $_SESSION['tp_radioId'] = array_get($_GET, 'radioId', array_get($_SESSION, 'tp_radioId', FALSE));
    //Gateway
    //$gatewayMac = (!empty($_GET['gatewayMac']) ? $_GET['gatewayMac'] : '');
    $_SESSION['tp_gatewayMac'] = array_get($_GET, 'gatewayMac', array_get($_SESSION, 'tp_gatewayMac', FALSE));
    //$vid = (!empty($_GET['vid']) ? $_GET['vid'] : '');
    $_SESSION['tp_vid'] = array_get($_GET, 'vid', array_get($_SESSION, 'tp_vid', FALSE));

    function authClient_tplink_omada($configs){

        global $logOp;
        $processAuth = "Login";

        // Detected environment file cookies.
        if(ENV == 'production'){
            $cookie_file = "/var/www/html/app.hotelinking.com/tmp/unifi_cookie";
        }else{
            $cookie_file = "/tmp/unifi_cookie";
        }

        //Get Token
        $ch = curl_init();
        // post
        curl_setopt($ch, CURLOPT_POST, TRUE);
        // Set return to a value, not return to page
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // Set up cookies
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
        // Allow Self Signed Certs
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        // API Call
        curl_setopt($ch, CURLOPT_URL, array_get($configs, 'form_url') . "/login");
        curl_setopt($ch, CURLOPT_POSTFIELDS, "name=" . array_get($configs, 'username') ."&password=" . array_get($configs, 'password'));

        $res = curl_exec($ch);
        $resObj = json_decode($res);

        curl_close($ch);

        
        if(data_get($resObj, 'success') == true) {

            $processAuth = "Authorizing";

            //Prevent CSRF
            $token = $resObj->value;

            // Send user to authorize and the time allowed
            if(array_get($_SESSION, 'tp_gatewayMac') == NULL) {
                // For EAP, clientMac=client_mac&apMac=ap_mac&ssidName=ssid_name &radioId=Radio_id&site=site_name&time=expire_time&authType =4
                $authInfo = array(
                    'clientMac' => array_get($_SESSION, 'tp_clientMac'),
                    'apMac' => array_get($_SESSION, 'tp_apMac'),
                    'ssidName' => array_get($_SESSION, 'tp_ssidName'),
                    'radioId ' => array_get($_SESSION, 'mtp_radioIdac'),
                    'site ' => array_get($_SESSION, 'tp_site'),
                    'time' => (86400000 * array_get($configs, 'unifi_time', 7)), // Time session in milliseconds.
                    'authType ' => '4'
                    );
            } 
            else {
                // For gateway, clientMac=client_mac&gatewayMac=gateway_mac&vid=vid &site=site_name&time=expire_time&authType =4
                $authInfo = array(
                    'clientMac' => array_get($_SESSION, 'tp_clientMac'),
                    'gatewayMac' => array_get($_SESSION, 'tp_gatewayMac'),
                    'vid' => array_get($_SESSION, 'tp_vid'),
                    'site ' => array_get($_SESSION, 'tp_site'),
                    'time' => (86400000 * array_get($configs, 'unifi_time', 7)), // Time session in milliseconds.
                    'authType ' => '4'
                    );
            }
    
            $ch = curl_init();
            // post
            curl_setopt($ch, CURLOPT_POST, TRUE);
            // Set return to a value, not return to page
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            // Set up cookies
            curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
            curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
            // Allow Self Signed Certs    
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            // API Call
            curl_setopt($ch, CURLOPT_URL, array_get($configs, 'form_url') ."/extportal/". array_get($_SESSION, 'tp_site') ."/auth"."?token=".$token);
            $data = json_encode($authInfo);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($authInfo));
        
            $res = curl_exec($ch);
            $resObj = json_decode($res);
        
            if($resObj['success'] == true) {

                // Log the correct authorization of the client.
                $logOp->info('REDIRECT to TP-LINK', array('hotspot' => 'tplink_omada', 'mac_client' => array_get($_SESSION, 'tp_clientMac')));
                $report = array("success" => $success);

            }
        }

        if(data_get($resObj, 'success') != true) {

            $logOp->error('tplink_omadak - Error login');
            $report = array("error" => data_get($resObj, 'message', 'API tplink_omada - error not found'),
            "response" => $resObj,
            "process_auth" => $processAuth,
            "curl_error" => curl_error($ch),
            );

        }

        curl_close($ch);
        unset($ch);
        
        return $report;

    }

?>