<?php

use GuzzleHttp\Exception\ConnectException;


if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}


//If those parameters exists, store them in session
if (!empty($_GET['client-mac'])) {
    $_SESSION['client-mac'] = (!empty($_GET['client-mac']) ? urldecode($_GET['client-mac']) : '');
    $_SESSION['mac'] = preg_replace('~(..)(?!$)\.?~', '\1:', $_SESSION['client-mac']);

    $_SESSION['ip'] = (!empty($_GET['nasipaddress']) ? urldecode($_GET['nasipaddress']) : '');
    $_SESSION['site-id'] = (!empty($_GET['site-id']) ? urldecode($_GET['site-id']) : '');
    $_SESSION['hash'] = (!empty($_GET['hash']) ? urldecode($_GET['hash']) : '');
    $_SESSION['redirect-time'] = (!empty($_GET['redirect-time']) ? urldecode($_GET['redirect-time']) : '');

    $log->info('iBrowse session info', ['session' => $_SESSION, '$_GET' => $_GET]);

    $log->debug('iBrowse session info', [
        'session' => $_SESSION,
        '$_POST' => $_POST,
        'form' => [
            'username' => $datosWifiHotel['username'],
            'password'=> $datosWifiHotel['password']
        ]
    ]);
}

// Check if device is available for login
function authAvailable($ibrowse_data)
{
    global $logOp;

    $dataPost = [
        'client-mac' => array_get($_SESSION, 'client-mac'),
        'site-id' => array_get($_SESSION, 'site-id')
    ];

    $client = new GuzzleHttp\Client([
        'http_errors' => false,
        'headers' => [
            'Authorization' => ' Basic ' . base64_encode($ibrowse_data['username'] . ':' . $ibrowse_data['password']),
            'Content-Type' => 'application/json'
        ]
    ]);

    $response = $client->post('https://wifiadmin.s.ibrowse.com/sessions/external_status', [
        'json' => $dataPost
    ]);

    $httpcode = $response->getStatusCode();
    $response = json_decode($response->getBody(), true);

    if($httpcode === 200)
    {
        if($response['status']['name'] == "success")
        {
            $result = array('error' => 0, 'active' => $response['data']['active'], 'authenticated' => $response['data']['authenticated']);
        }
        else
        {
            $result = array('error' => 1, 'active' => $response['data']['active'], 'authenticated' => $response['data']['authenticated']);
            $logOp->error('Error in iBrowse status', $response);
        }
    } else
    {
        $result = array('error' => 1, 'error_code' => $httpcode);
        $logOp->error('Error in iBrowse status', $response);
    }

    return $result;
}

function sendAuth($user_id, $ibrowse_data)
{
    global $logOp;

    $dataPost = [
        'ids' => array($_SESSION['hotel']['brand_id'], $user_id),
        'client-mac' => $_SESSION['client-mac'],
        'site-id' => $_SESSION['site-id'],
        'nasipaddress' => $_SESSION['ip'],
        'redirect-time' => $_SESSION['redirect-time'],
        'hash' => $_SESSION['hash']
    ];

    $logOp->info('Sending auth to iBrowse', $dataPost);

    $client = new GuzzleHttp\Client([
        'http_errors' => false,
        'headers' => [
            'Authorization' => ' Basic ' . base64_encode($ibrowse_data['username'] . ':' . $ibrowse_data['password']),
            'Content-Type' => 'application/json'
        ]
    ]);

    try {
        $response = $client->post('https://wifiadmin.s.ibrowse.com/sessions/external_authentication', [
            'json' => $dataPost,
            'connect_timeout' => 6
        ]);
    } catch (ConnectException $e) {
        if (strpos($e->getMessage(), 'cURL error 28') !== false) {
            // Es un error de timeout
            
            $response = $client->post('https://wifiadmin.s.ibrowse.com/sessions/external_authentication', [
                'json' => $dataPost,
                'connect_timeout' => 6
            ]);
        }
    }

    $httpcode = $response->getStatusCode();
    $response = json_decode($response->getBody(), true);

    switch ($httpcode)
    {
        case 401:
            $result = array('status' => 'error', 'message' => $response['status']['message'], 'error_code' => $httpcode);
            $logOp->error('Error in iBrowse request', array('data' => $dataPost, 'error_code' => $httpcode, 'response' => $response));
            break;
        case 400:
            $result = array('status' => 'error', 'message' => $response['status']['message'], 'error_code' => $httpcode);
            $logOp->error('Error in iBrowse request', array('data' => $dataPost, 'error_code' => $httpcode, 'response' => $response));
            break;
        case 200:
            if($response["status"]["name"] == "success")
            {
                $result = array('status' => 'success', 'message' => $response);
                $logOp->info('iBrowse request sent with success', array('data' => $dataPost, 'response' => $response));
            } else
            {
                $result = array('status' => 'error', 'message' => 'Unknown error', 'error_code' => $httpcode);
                $logOp->error('Error in iBrowse request', array('data' => $dataPost, 'error_code' => $httpcode, 'response' => $response));
            }
            break;
        default:
            $result = array('status' => 'error', 'message' => 'Unknown error', 'error_code' => $httpcode);
            $logOp->error('Error in iBrowse request', array('data' => $dataPost, 'error_code' => $httpcode, 'response' => $response));
            break;
    }


    return $result;
    
}