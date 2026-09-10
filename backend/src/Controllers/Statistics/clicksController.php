<?php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}
include LANG . $_SESSION['userLang'] . '/statistics/clicks.php' ;
include_once RUTA_DIR . LIB . 'dashboards_helpers.php';
include_once RUTA_DIR . LIB . 'dashboards_search_session_management.php';
include_once RUTA_DIR . LIB . 'shared_dashboards_models.php';
include_once APP . 'Services/Connections/ApiGatewayConnection.php';

$view = $container->get('view');
$view->addData([
    'page_title' => $lang['stats clicks'],
    'page_icon' => 'mouse pointer',
    'current_page' => 'statistics',
    'current_subPage' => 'clicks_and_impressions_dashboard'
]);

$gateway = new ApiGatewayConnection();
$brandId = !empty($_SESSION['chainSearch']) ? $_SESSION['loggedParentBrandID'] : $_SESSION['loggedBrandID'];

$from = getDateRangeStart();
$to = getDateRangeEnd();

$requests = [
    'socialMediaConnections' => [
        'endpoint' => STATISTICS_ENDPOINT . 'brands/'. $brandId .'/connections/source',
        'method' => 'GET',
        'payload' => ['source_type' => 'facebook', 'from' => $from, 'to' => $to],
    ],
    'socialMediaFriends' => [
        'endpoint' => STATISTICS_ENDPOINT . 'brands/'. $brandId .'/clients/facebook/friends',
        'method' => 'GET',
        'payload' => ['from' => getDateFormat($_SESSION['rangeStart']), 'to' => getDateFormat($_SESSION['rangeEnd'])],
    ],
    'publications' => [
        'endpoint' => STATISTICS_ENDPOINT . 'brands/'. $brandId .'/social-media/publications',
        'method' => 'GET',
        'payload' => ['from' => $from, 'to' => $to],
    ],
    'avgPublications'   => [
        'endpoint' => STATISTICS_ENDPOINT . 'brands/'. $brandId .'/social-media/avg-publications',
        'method' => 'GET',
        'payload' => ['from' => $from, 'to' => $to],
    ],
    'impressions'   => [
        'endpoint' => STATISTICS_ENDPOINT . 'brands/'. $brandId .'/social-media/impressions',
        'method' => 'GET',
        'payload' => ['from' => $from, 'to' => $to],
    ],
    'leads'   => [
        'endpoint' => STATISTICS_ENDPOINT . 'brands/'. $brandId .'/bookings/funnel',
        'method' => 'GET',
        'payload' => ['source_action' => 'share', 'from' => $from, 'to' => $to],
    ]
];

$promises = $gateway->sendAsyncRequests($requests);
$clicksStatistics = $gateway->awaitAsyncRequests($promises);

// Get total users from social media
$templateData['socialMediaUsers'] = getResponse($clicksStatistics, 'socialMediaConnections')[0]->total_access ?? 0;

// Get total friends from Facebook
$templateData['averageFacebookFriends'] = getResponse($clicksStatistics, 'socialMediaFriends')->total_friends ?? 0;

// Get total shares from facebook
$templateData['socialMediaPublications'] = getResponse($clicksStatistics, 'publications')->total_publications ?? 0;
$templateData['averageSocialMediaShares'] = getResponse($clicksStatistics, 'avgPublications')->avg_publications ?? 0;

// Get average impressions
$impressions = getResponse($clicksStatistics, 'impressions');
$templateData['impressions'] = $impressions->total_impressions ?? 0;

// Get average impressions value
$templateData['impressionsValue'] = $impressions->value ?? 0;

// Get total clicks
$leads =  getResponse($clicksStatistics, 'leads');
$templateData['leads'] = $leads->total_actions ?? 0;

// Get total clicks value
$templateData['leadsValue'] = $leads->value ?? 0;

// Get bookingEngineIntegrated
$hotel_id = array_get($_SESSION,'h_logueado', array_get($_SESSION,'staff_id_hotel'));
$templateData['booking_engine_integrated'] = getBookingEngineIntegrated($hotel_id);

// Render template
$html = $view->render('views::statistics/clicks', $templateData);
$response->getBody()->write($html);
return $response;