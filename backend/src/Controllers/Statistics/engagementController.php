<?php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}

include_once MODEL . 'engagementModel.php';
include LANG . $_SESSION['userLang'] . '/statistics/engagement.php' ;
include_once RUTA_DIR . LIB . 'dashboards_search_session_management.php';
include_once RUTA_DIR . LIB . 'shared_dashboards_models.php';
include_once RUTA_DIR . LIB . 'shared_dashboards_controllers.php';
include_once APP . 'Services/Connections/ApiGatewayConnection.php';

$view = $container->get('view');
$view->addData([
    'page_title' => $lang['stats engagement'],
    'page_icon' => 'comments',
    'current_page' => 'statistics',
    'current_subPage' => 'engagement_dashboard'
]);

if(isset($_GET['lapse'])){
    $_SESSION['lapse'] = $_GET['lapse'];
}

if (!isset($_SESSION['lapse'])){
    $_SESSION['lapse'] = 'month';
}

$gateway = new ApiGatewayConnection();
$brandId = !empty($_SESSION['chainSearch']) ? $_SESSION['loggedParentBrandID'] : $_SESSION['loggedBrandID'];

$from = getDateRangeStart();
$to = getDateRangeEnd();

$requests = [
    'emails' => [
        'endpoint' => STATISTICS_ENDPOINT . 'brands/'. $brandId .'/emails',
        'method' => 'GET',
        'payload' => ["email_type" => "client",'by' => $_SESSION['lapse'], 'from' => $from, 'to' => $to],
    ],
    'interactions' => [
        'endpoint' => STATISTICS_ENDPOINT . 'brands/'. $brandId .'/emails/interaction',
        'method' => 'GET',
        'payload' => ["email_type" => "client",'by' => $_SESSION['lapse'], 'from' => $from, 'to' => $to],
    ]
];

$promises = $gateway->sendAsyncRequests($requests);
  
$engagementData['sentBackground'] = 'rgba(0,0,0,0.5)';
$engagementData['opensBackground'] = 'rgba(163,230,53,0.5)';
$engagementData['clicksBackground'] = 'rgba(56,247,234,0.5)';

$engagementData['sentBorder'] = 'rgba(0,0,0,1)';
$engagementData['opensBorder'] = 'rgba(163,230,53,1)';
$engagementData['clicksBorder'] = 'rgba(56, 247, 234, 1)';

$engagementStatistics = $gateway->awaitAsyncRequests($promises);

$emails = getResponse($engagementStatistics, 'emails', true);
$interactions = getResponse($engagementStatistics, 'interactions', true);

$emailsDates = array_pluck($emails, 'date');
$interactionDates = array_pluck($interactions, 'date');
$engagementData['dates']  = array_values(array_unique(array_merge($emailsDates, $interactionDates)));

$engagementData['satisfaction'] = getEmailInfo($emails, $interactions, 'satisfaction');
$engagementData['review'] = getEmailInfo($emails, $interactions, 'review');
$engagementData['birthday'] = getEmailInfo($emails, $interactions, 'birthday');
$engagementData['offer'] = getEmailInfo($emails, $interactions, 'stay_offer');



// Render view
$html = $view->render('views::statistics/engagement', $engagementData);
$response->getBody()->write($html);
return $response;