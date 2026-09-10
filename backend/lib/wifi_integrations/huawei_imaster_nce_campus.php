<?php

    // Example of the params provided by huawei: /portal?apmac=1c3cd415d370&uaddress=10.140.20.81&umac=16ab7c2f0eee&authType=10&lang=zh_CN&vendor=huawei&ssid=VEVTVA==&pushPageId=4db4f07039464de3a21f6620fd9b3005&nodeIp=10.0.0.15&siteId=c78fcb45-a134-4668-97fb-0674fdeee6af
    $_SESSION['apmac'] = array_get($_SESSION, 'apmac', array_get($_GET, 'apmac', ''));
    $_SESSION['uaddress'] = array_get($_SESSION, 'uaddress', array_get($_GET, 'uaddress', ''));
    $_SESSION['mac'] = array_get($_SESSION, 'mac', array_get($_GET, 'umac', ''));
    $_SESSION['ssid'] = array_get($_SESSION, 'ssid', array_get($_GET, 'ssid', ''));


    $_SESSION['huawei_host'] = array_get($_SESSION, 'huawei_host', array_get($datosWifiHotel, 'form_url'));
    $_SESSION['huawei_username'] = array_get($_SESSION, 'huawei_username', array_get($datosWifiHotel, 'username'));
    $_SESSION['huawei_password'] = array_get($_SESSION, 'huawei_password', array_get($datosWifiHotel, 'password'));
    
    function authHuaweiImasterNceCampus() {
        $token = getToken($_SESSION['huawei_host'], $_SESSION['huawei_username'], $_SESSION['huawei_password']);

        if (!$token) {
            return [
                "errcode" => 1,
                "message" => "GetToken failed"
            ];
        }

        $payload = [
            "terminalMac"   => $_SESSION['mac'], 
            "deviceMac"     => $_SESSION['apmac'],
            "ssid"          => $_SESSION['ssid'],
            "userName"      => array_get($_SESSION, 'user.email') . "-" . $_SESSION['mac'],
            "terminalIpV4"  => $_SESSION['uaddress']
        ];

        return authorize($_SESSION['huawei_host'], $token, $payload);
    }

    function getToken($host, $username, $password)
    {
        $ch = curl_init($host . "/controller/v2/tokens");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            "userName" => $username,
            "password" => $password
        ]));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        if ($response === false) {
            global $log; $log->error("Error on Huawei curl get token", ["error" => curl_error($ch)]);
            return false;
        }

        curl_close($ch);

        $data = json_decode($response, true);

        if (isset($data['data']['token_id'])) {
            return $data['data']['token_id'];
        } else {
            global $log; $log->error("Token not found on Huawei get token", ["response" => $response]);
            return false;
        }
    }

    /**
     * Authorize with token
     */
    function authorize($host, $token, $payload)
    {
        $ch = curl_init($host . "/controller/cloud/v2/northbound/accessuser/haca/authorization");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'X-ACCESS-TOKEN: ' . $token
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        if ($response === false) {
            global $log; $log->error("Error on Huawei curl authorize", ["error" => curl_error($ch)]);
            return [
                "errcode" => 2,
                "message" => "Huawei authorize failed"
            ];
        }

        curl_close($ch);

        return json_decode($response, true);
    }

?>