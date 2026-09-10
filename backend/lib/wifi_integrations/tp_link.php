<?php

	//TODO: This integration is not tested with a real team because we do not have any fortinet to do it. It remains to be done when one is available.

    // The MAC address of client.
    $_SESSION['mac'] = array_get($_GET, 'cid', array_get($_SESSION, 'mac', FALSE));
    // The MAC address AP of the EAP which client is connected to.
    $_SESSION['tp-link_ap'] = array_get($_GET, 'ap', array_get($_SESSION, 'tp-link_ap', FALSE));
    // The connected SSID name.
    $_SESSION['tp-link_ssid'] = array_get($_GET, 'ssid', array_get($_SESSION, 'tp-link_ssid', FALSE));
    // The number of seconds since the Epoch, 1970-01-01 00:00:00.
    $_SESSION['tp-link_t'] = array_get($_GET, 't', array_get($_SESSION, 'tp-link_t', FALSE));
    // The Radio is of the connected SSID where 0 represents 2.4G and 1 represents 5G.
    $_SESSION['tp-link_rid'] = array_get($_GET, 'rid', array_get($_SESSION, 'tp-link_rid', FALSE));
    // The name of site.
    $_SESSION['tp-link_site'] = array_get($_GET, 'site', array_get($_SESSION, 'tp-link_site', FALSE)); 

    function authClientTpLink($configs){

        global $logOp;
        $processAuth = "Login";

        // Detected environment file cookies.
        if(ENV == 'production'){
            $cookie_file = "/var/www/html/app.hotelinking.com/tmp/unifi_cookie";
        }else{
            $cookie_file = "/tmp/unifi_cookie";
        }

        // Send user to authorize and the time allowed
        $data = array(
            'name' => array_get($configs, 'username'), // User admin controller.
            'password' => array_get($configs, 'password'), // Password admin controller.
        );

        // Create new connection CURL.
        $ch = curl_init();

        // Configure connection CURL.
        curl_setopt($ch, CURLOPT_POST, TRUE); // Connection method POST.
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Set return to a value, not return to page.
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file); // Set up cookies.
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file); // Set up cookies.
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // Allow Self Signed Certs.
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); // Allow Self Signed Certs.
        curl_setopt($ch, CURLOPT_URL, array_get($configs, 'form_url') . "/login"); // API Call.
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data)); // Set parameters.

        // Send connection.
        $response = curl_exec($ch);
        $response = json_decode($response);

        $success = data_get($response, 'success', FALSE);

        if($success){

            $processAuth = "Authorizing";

            // Send user to authorize and the time allowed
            $data = array(
                'cid' => array_get($_SESSION, 'mac'), // MAC client.
                'ap' => array_get($_SESSION, 'tp-link_ap'), // MAC AP.
                'ssid' => array_get($_SESSION, 'tp-link_ssid'), // Name SSID.
                'rid' => array_get($_SESSION, 'tp-link_rid'), // The Radio is of the connected SSID where 0 represents 2.4G and 1 represents 5G.
                't' => array_get($_SESSION, 'tp-link_t'), // The number of seconds since the Epoch, 1970-01-01 00:00:00.
                'time' => (86400 * array_get($configs, 'unifi_time', 15)), // Time session in secongs.
            );

            // Configure connection CURL.
            curl_setopt($ch, CURLOPT_URL, array_get($configs, 'form_url') . "/extportal/" . array_get($_SESSION, 'tp-link_site') . "/auth"); // API Call.
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data)); // Set parameters.

            // Send connection.
            $response = curl_exec($ch);
            $response = json_decode($response);

            $success = data_get($response, 'success', FALSE);

            if($success){

                $processAuth = "Logout";

                // Configure connection CURL.
                curl_setopt($ch, CURLOPT_URL, array_get($configs, 'form_url') . "/logout"); // API Call.

                // Send connection.
                $response = curl_exec($ch);
                $response = json_decode($response);

                $success = data_get($response, 'success', FALSE);

                if($success){

                    // Log the correct authorization of the client.
                    $logOp->info('REDIRECT to TP-LINK', array('hotspot' => 'tp-link', 'mac_client' => array_get($_SESSION, 'mac')));

                    $report = array("success" => $success);

                }

            }

        }

        if(!$success){

            $logOp->error('TP-Link - Error login', [$data]);

            $report = array("error" => data_get($response, 'message', 'API TP-Link - error not found'),
                "response" => $response,
                "process_auth" => $processAuth,
                "curl_error" => curl_error($ch),
            );

        }

        // Clouse connection CURL.
        curl_close($ch);
        unset($ch);

        return $report;

    }

?>