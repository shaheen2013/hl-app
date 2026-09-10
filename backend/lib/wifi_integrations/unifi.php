<?php

function sendAuthorization($unifiData)
{

    // Start Curl for login
    global $logOp;
    $ch = curl_init();
    // We are posting data
    curl_setopt($ch, CURLOPT_POST, true);
    // Set up cookies
    if (ENV == 'production') {
        $cookie_file = "/var/www/html/app.hotelinking.com/tmp/unifi_cookie";
    } else {
        $cookie_file = "/tmp/unifi_cookie";
    }
    //curl_setopt($ch, CURLOPT_VERBOSE, 1);
    //curl_setopt($ch, CURLOPT_STDERR, $fp);
    //$fp = fopen('/var/www/html/app.hotelinking.com/tmp/curl-debug.log', 'w');

    $logOp->info('config curl Unifi');
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
    // Allow Self Signed Certs
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    //Return transfer
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Force SSL3 only
    //curl_setopt($ch, CURLOPT_SSLVERSION, 6);
    // Login to the UniFi controller
    curl_setopt($ch, CURLOPT_URL, $unifiData['form_url'] . "/api/login");

    $data = json_encode(array("username" => $unifiData['username'], "password" => $unifiData['password']));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

    // send login command
    $result = curl_exec($ch);
    $result = json_decode($result);

    $status = data_get($result, 'meta.rc', false);

    if ($status == "ok") {

        // Send user to authorize and the time allowed
        $data = json_encode(array(
            'cmd' => 'authorize-guest',
            'mac' => $_SESSION['mac'],
            'ap_mac' => $_SESSION['unifi_ap'],
            'minutes' => (1440 * $unifiData['unifi_time'])));

        // Send the command to the API
        curl_setopt($ch, CURLOPT_URL, $unifiData['form_url'] . '/api/s/' . $unifiData['unifi_site_id'] . '/cmd/stamgr');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        // Added a timeout operation
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $result = curl_exec($ch);
        $result = json_decode($result);
        $status = data_get($result, 'meta.rc', false);

        if ($status == "ok") {

            // Logout of the UniFi Controller
            curl_setopt($ch, CURLOPT_URL, $unifiData['form_url'] . '/api/logout');
            $result = json_decode(curl_exec($ch));
            $status = data_get($result, 'meta.rc', false);

        }

    }

    if ($status == "ok") {
        $logOp->info('REDIRECT to UNIFI', array('data' => $data, 'hotspot' => 'unifi'));
        $response = array("success" => $status);
    } elseif ($status == "error") {
        $response = array("error" => data_get($result, 'meta.msg', 'API Unifi - error not found'),
            "response" => json_encode($result),
            "curl_error" => curl_error($ch),
        );
        $logOp->error('UNIFI error', array('data' => $response, 'hotspot' => 'unifi'));
    } else {
        $response = array("error" => "API Unifi - error not found",
            "response" => json_encode($result),
            "curl_error" => curl_error($ch),
        );
        $logOp->error('UNIFI error', array('data' => $response, 'hotspot' => 'unifi'));
    }

    curl_close($ch);
    unset($ch);

    return $response;

}
