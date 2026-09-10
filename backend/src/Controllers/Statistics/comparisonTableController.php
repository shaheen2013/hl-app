<?php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}
include LANG . $_SESSION['userLang'] . '/statistics/comparisonTable.php' ;
include_once MODEL . 'comparisonTableModel.php';
include_once RUTA_DIR . LIB . 'dashboards_search_session_management.php';
include_once RUTA_DIR . LIB . 'shared_dashboards_models.php';
include_once APP . 'Services/Connections/ApiGatewayConnection.php';

$view = $container->get('view');
$view->addData([
    'page_title'      => $lang['comparative hotels'],
    'page_icon'       => 'table',
    'current_page'    => 'statistics',
    'current_subPage' => 'comparison_dashboard'
]);

if(isset($_GET['lapse'])){
    $_SESSION['lapse'] = $_GET['lapse'];
}

if (!isset($_SESSION['lapse'])){
    $_SESSION['lapse'] = 'month';
}

$gateway = new ApiGatewayConnection();

$from = getDateRangeStart();
$to = getDateRangeEnd();

$comparisonDataCacheName = 'comparisonData' . $_SESSION['loggedParentBrandID'] . $from . $to;
$comparisonPageCacheName = 'comparisonPage' . $_SESSION['loggedParentBrandID'] . $from . $to;
$comparisonFinishedCacheName = 'comparisonFinished' . $_SESSION['loggedParentBrandID'] . $from . $to;

$comparisonFinished = getFromCache($comparisonFinishedCacheName) ? getFromCache($comparisonFinishedCacheName)->get() : false;
$comparisonPage = getFromCache($comparisonPageCacheName) ? getFromCache($comparisonPageCacheName)->get() : 1;

$page = isset($_POST['page']) ? 
    $_POST['page'] :
    $comparisonPage;

if (!$comparisonFinished || isset($_POST['page'])) {
    $actualComparisonData = safeJsonParser(
        $gateway->sendRequest(
            ['from' => $from, 'to' => $to, "page" => $page], 
            STATISTICS_ENDPOINT . 'brands/'. $_SESSION['loggedParentBrandID'] . "/comparison", 
            'GET'
        ), 
        true
    );

    $comparisonCache = getFromCache($comparisonDataCacheName) ? getFromCache($comparisonDataCacheName)->get() : [];
    $comparisonData = array_merge($comparisonCache, $actualComparisonData);

    setToCache($comparisonDataCacheName, $comparisonData, 1800);
    setToCache($comparisonFinishedCacheName, empty($actualComparisonData), 1800);
    setToCache($comparisonPageCacheName, $page, 1800);
}

$templateData = [];
$templateData['comparisonFinished'] = getFromCache($comparisonFinishedCacheName) ? getFromCache($comparisonFinishedCacheName)->get() : false;
$templateData['comparisonPage'] = getFromCache($comparisonPageCacheName) ? getFromCache($comparisonPageCacheName)->get() : 1;

foreach (getFromCache($comparisonDataCacheName)->get() as $brandName => $data) {

    $totalConnections = array_sum(array_column(array_get($data, 'connection_sources', []), 'total_access'));
    $days = floor(array_get($data, 'survey_reputation.avg_timelapse_response')/24);
    $hours = round((array_get($data, 'survey_reputation.avg_timelapse_response')/24 - $days) * 24);
    $satisfactionInfo = get_email_data(array_get($data, 'emails', []), 'satisfaction');
    $reviewInfo = get_email_data(array_get($data, 'emails', []), 'review');
    $satisfactionWarningInfo = get_email_data(array_get($data, 'emails', []), 'satisfaction_warning');

    $templateData['comparisonData'][$brandName]['users'] = array_get($data, 'total_users.total_clients');
    $templateData['comparisonData'][$brandName]['database_value'] = array_get($data, 'database_value.value');
    $templateData['comparisonData'][$brandName]['form_connections'] = computePercentage(array_get($data, 'connection_sources'), 'access_type', 'Form', 'total_access', $totalConnections);
    $templateData['comparisonData'][$brandName]['facebook_connections'] = computePercentage(array_get($data, 'connection_sources'), 'access_type', 'Facebook', 'total_access', $totalConnections);
    $templateData['comparisonData'][$brandName]['shares'] = array_get($data, 'social_media_publications.total_publications');
    $templateData['comparisonData'][$brandName]['shares_ratio'] = array_get($data, 'avg_social_media_publications.avg_publications');
    $templateData['comparisonData'][$brandName]['impressions'] = array_get($data, 'social_media_impressions.total_impressions');
    $templateData['comparisonData'][$brandName]['impressions_value'] = array_get($data, 'social_media_impressions.value');
    $templateData['comparisonData'][$brandName]['survey_avg'] = array_get($data, 'survey_reputation.avg');
    $templateData['comparisonData'][$brandName]['survey_response_time_avg'] = $days.' ' .$lang['days'] . ' ' . $lang['and'] . ' ' . $hours . ' ' . $lang['hours'];
    $templateData['comparisonData'][$brandName]['satisfaction_sent'] = array_get($satisfactionInfo, 'sent', 0);
    $templateData['comparisonData'][$brandName]['satisfaction_opens'] = getAverage(array_get($satisfactionInfo, 'opens', 0), array_get($satisfactionInfo, 'sent', 0));
    $templateData['comparisonData'][$brandName]['satisfaction_clicks'] = getAverage(array_get($satisfactionInfo, 'clicks', 0), array_get($satisfactionInfo, 'sent', 0));
    $templateData['comparisonData'][$brandName]['review_sent'] = array_get($reviewInfo, 'sent', 0);
    $templateData['comparisonData'][$brandName]['review_opens'] = getAverage(array_get($reviewInfo, 'opens', 0), array_get($reviewInfo, 'sent', 0));
    $templateData['comparisonData'][$brandName]['review_clicks'] = getAverage(array_get($reviewInfo, 'clicks', 0), array_get($reviewInfo, 'sent', 0));
    $templateData['comparisonData'][$brandName]['satisfaction_warning_sent'] = array_get($satisfactionWarningInfo, 'sent', 0);
    $templateData['comparisonData'][$brandName]['satisfaction_warning_opens'] = getAverage(array_get($satisfactionWarningInfo, 'opens', 0), array_get($satisfactionWarningInfo, 'sent', 0));
}

// Render template
$html = $view->render('views::statistics/comparisonTable', $templateData);
$response->getBody()->write($html);
return $response;

function safeJsonParser($object, $assoc = false)
{
    if ($object) {
        try{
        return \GuzzleHttp\json_decode($object, $assoc);
        }catch (Exception $e){
            return [];
        }
    }
    return [];
}

function get_email_data($emails, $emailSearch) {
    return array_first($emails, function ($key, $email) use ($emailSearch) {
        return array_get($email, 'email_type__name') == $emailSearch;
    });
}