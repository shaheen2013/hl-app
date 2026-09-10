<?php

use Carbon\Carbon;

if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}

include LANG . $_SESSION['userLang'] . '/statistics/loyalty.php';
include_once RUTA_DIR . LIB . 'dashboards_helpers.php';
include_once RUTA_DIR . LIB . 'dashboards_search_session_management.php';
include_once RUTA_DIR . LIB . 'shared_dashboards_models.php';
include_once RUTA_DIR . LIB . 'shared_dashboards_models.php';
include_once APP . 'Services/Connections/ApiGatewayConnection.php';

$view = $container->get('view');
$view->addData([
    'page_title'      => $lang['Loyalty stats'],
    'page_icon'       => 'window restore outline icon',
    'current_page'    => 'statistics',
    'current_subPage' => 'stats_loyalty'
]);

if(isset($_GET['lapse'])){
    $_SESSION['lapse'] = $_GET['lapse'];
}

if (!isset($_SESSION['lapse'])){
    $_SESSION['lapse'] = 'month';
}

$gateway = new ApiGatewayConnection();
$brandId = !empty($_SESSION['chainSearch']) ? $_SESSION['loggedParentBrandID'] : $_SESSION['loggedBrandID'];

$from = getDateFormat($_SESSION['rangeStart']);
$to = getDateFormat($_SESSION['rangeEnd']);

$requests = [
    'timeline' => [
        'endpoint' => STATISTICS_ENDPOINT . 'brands/'. $brandId .'/clients/loyal-timeline',
        'method' => 'GET',
        'payload' => ['by' => $_SESSION['lapse'], 'from' => $from, 'to' => $to],
    ],
    'info' => [
        'endpoint' => STATISTICS_ENDPOINT . 'brands/'. $brandId .'/clients/loyal-avg',
        'method' => 'GET',
        'payload' => ['from' => $from, 'to' => $to],
    ]
];

$promises = $gateway->sendAsyncRequests($requests);

$loyaltyStatistics = $gateway->awaitAsyncRequests($promises);

// Loyalty Timeline
$loyalUsersInTime = getResponse($loyaltyStatistics, 'timeline');
$templateData['recurrentDays'] = array_pluck($loyalUsersInTime, 'date'); 
$templateData['recurrentClientsTimeline'] = array_pluck($loyalUsersInTime, 'count');
$templateData['steps'] = !empty($templateData['recurrentClientsTimeline']) ? getDataSteps(max($templateData['recurrentClientsTimeline'])) : 0;

// Relative recurrent clients
$loyalInfo = getResponse($loyaltyStatistics, 'info');
$templateData['recurrentAvg'] = data_get($loyalInfo, 'recurrent_avg'); 

// Total recurrent clients
$templateData['recurrentClients'] = data_get($loyalInfo, 'recurrent_clients');

array_walk($templateData['recurrentDays'], 'formatDate');

$html = $view->render('views::statistics/loyalty', $templateData);
$response->getBody()->write($html);
return $response;

function safeJsonParser($object, $assoc = false)
{
    if ($object) {
        return \GuzzleHttp\json_decode($object, $assoc);
    }

    return [];
}
