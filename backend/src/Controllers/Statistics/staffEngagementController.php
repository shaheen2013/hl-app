<?php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}

include_once LANG . $_SESSION['userLang'] . '/statistics/staffEngagement.php';
include_once MODEL . 'staffEngagementModel.php';
include_once RUTA_DIR . LIB . 'dashboards_search_session_management.php';
include_once RUTA_DIR . LIB . 'shared_dashboards_models.php';
include_once RUTA_DIR . LIB . 'shared_dashboards_controllers.php';
include_once APP . 'Services/Connections/ApiGatewayConnection.php';

$view = $container->get('view');
$view->addData([
    'title' => $lang['stats staffEngagement'],
    'page_title' => $lang['stats staffEngagement'],
    'page_icon' => 'address book',
    'current_page' => 'statistics',
    'current_subPage' => 'staff_engagement_dashboard'
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
        'payload' => ["email_type" => "staff",'by' => $_SESSION['lapse'], 'from' => $from, 'to' => $to],
    ],
    'interactions' => [
        'endpoint' => STATISTICS_ENDPOINT . 'brands/'. $brandId .'/emails/interaction',
        'method' => 'GET',
        'payload' => ["email_type" => "staff",'by' => $_SESSION['lapse'], 'from' => $from, 'to' => $to],
    ]
];

$promises = $gateway->sendAsyncRequests($requests);

$staffEngagementData['sentBackground'] = 'rgba(0,0,0,0.5)';
$staffEngagementData['opensBackground'] = 'rgba(163,230,53,0.5)';
$staffEngagementData['clicksBackground'] = 'rgba(56,247,234,0.5)';

$staffEngagementData['sentBorder'] = 'rgba(0,0,0,1)';
$staffEngagementData['opensBorder'] = 'rgba(163,230,53,1)';
$staffEngagementData['clicksBorder'] = 'rgba(56, 247, 234, 1)';


$engagementStatistics = $gateway->awaitAsyncRequests($promises);

$emails = getResponse($engagementStatistics, 'emails', true);
$interactions = getResponse($engagementStatistics, 'interactions', true);

$staffEngagementData['warning'] = getEmailInfo($emails, $interactions, 'satisfaction_warning');
$staffEngagementData['birthdayWarning'] = getEmailInfo($emails, $interactions, 'birthday_warning');
$staffEngagementData['loyalWarning'] = getEmailInfo($emails, $interactions, 'loyalty_warning');

// Render view
$html = $view->render('views::statistics/staffEngagement', $staffEngagementData);
$response->getBody()->write($html);
return $response;