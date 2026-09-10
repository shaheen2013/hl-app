<?php
// No direct acccess allowed
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}

// Retrieve request data
$pushtech_data = checkPushtechVariables($_GET);
$log->info('REDEEM CAMPAIGN: A new campaign entered', $pushtech_data);

// Include libraries for get info from hotel and chains
include_once RUTA_DIR . LIB . 'obtenerdatosHotel.php';
include_once RUTA_DIR . LIB . 'obtenerDatosCadena.php';
include_once RUTA_DIR . LIB . 'referrer.php';

$query = [
    'hltr' => 'pushtech',
    'hlui' => $pushtech_data['user_id'] ? (int) $pushtech_data['user_id'] : null,
    'hlch' => $pushtech_data['chain_id'] ? (int) $pushtech_data['chain_id'] : null,
    'hlho' => $pushtech_data['hotel_id'] ? (int) $pushtech_data['hotel_id'] : null,
    'hlpuid' => $pushtech_data['pushtech_user_id'],
    'hlpaid' => $pushtech_data['pushtech_account_id'],
    'hlpid' => $pushtech_data['pushtech_campaign_id']
];
// Redirect to Campaign URL or 404 if not set
isset($pushtech_data['url']) ? redirectUrlCampaign($pushtech_data['url'], $query) : redirect404();

// Functions
function checkPushtechVariables($request = null)
{
    global $log;
    $log->debug('checking pushtech params from request', $request);
    if (!isset($request)) {
        $log->error('REDEEM CAMPAIGN: Calling redeem email campaign without request, redirecting 404');
        redirect404();
    }

    $p = array();

    $p['pushtech_campaign_id'] = array_get($_GET, 'campaign', null);
    $p['pushtech_user_id'] = array_get($_GET, 'puid', null);
    $p['pushtech_account_id'] = array_get($_GET, 'paid', null);
    // Some of the hotelinking variables are coming from pushtech like placeholders : %recipient.tag_xx_xxxx%, remove placeholders.
    $p['hotel_id'] = strpos($_GET['hid'], 'recipient.tag') ? null : array_get($_GET, 'hid', null);
    $p['chain_id'] = strpos($_GET['cid'], 'recipient.tag') ? null : array_get($_GET, 'cid', null);
    $p['user_id'] = strpos($_GET['uid'], 'recipient.tag') ? null : array_get($_GET, 'uid', null);
    $p['url'] = !empty($_GET['url']) ? urldecode($_GET['url']) : null;

    if (!isset($p['pushtech_account_id'])) {
        $log->error('REDEEM CAMPAIGN: There is no enought Pushtech data for tracking, trying to redirect to URL', $p);
        isset($p['url']) ? redirectUrlCampaign($p['url']) : redirect404();
        exit();
    }

    return $p;
}

function redirectUrlCampaign($urlCampaign, $query = null)
{

    global $log;
    if (isset($query)) {
        $u = parse_url($urlCampaign);
        $log->debug('Disected url', $u);
        // Recreate URL and add new params
        $query = http_build_query($query);
        $url = $u['scheme'] . '://' . $u['host'];
        $url .= isset($u['path']) ? $u['path'] : '';
        $url .= isset($u['query']) ? '?' . $u['query'] . '&' . $query : '?' . $query;
        $url .= isset($u['fragment']) ? '#' . $u['fragment'] : '';
    } else {
        $url = $urlCampaign;
    }

    $log->info('REDEEM CAMPAIGN: Redirecting user to: ' . $url);
    header("Location: " . $url);
    exit();
}

function redirect404()
{
    global $urlTree;
    global $log;
    $log->error('Redirecting 404');
    header("location:/" . $urlTree['404']);
    exit;
}
