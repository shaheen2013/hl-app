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
    'page_title'      => $lang['Loyalty visitors by date'],
    'page_icon'       => 'window restore outline icon',
    'current_page'    => 'statistics',
    'current_subPage' => 'loyalty_visitors'
]);

if(isset($_GET['lapse'])){
    $_SESSION['lapse'] = $_GET['lapse'];
}

if (!isset($_SESSION['lapse'])){
    $_SESSION['lapse'] = 'month';
}

$gateway = new ApiGatewayConnection();
$brandId = !empty($_SESSION['chainSearch']) ? $_SESSION['loggedParentBrandID'] : $_SESSION['loggedBrandID'];

$page = $_POST['page'] ?? 1;
$results_per_page = $_POST['resultsList'] ?? 10;

$set_range = (empty($_GET['date']) || (!empty($_POST['rangeStart']) || !empty($_POST['rangeEnd'])));

if ($set_range) {
    $from = !empty($_SESSION['rangeStart']) ? date_format(date_create($_SESSION['rangeStart']), 'Y-m-d') : null;
    $to = !empty($_SESSION['rangeEnd']) ? Carbon::parse($_SESSION['rangeEnd'])->endOfDay()->format('Y-m-d H:i:s') : null;
} else {
    $lapse = $_GET['lapse'] ?? 'day';
    $from = $_GET['date'] ?? null;

    switch ($lapse) {
        case 'day':
            $to = ($from) ? Carbon::parse($from)->endOfDay()->format('Y-m-d H:i:s') : null;
            break;

        case 'month':
            $to = ($from) ? Carbon::parse($from)->endOfMonth()->endOfDay()->format('Y-m-d H:i:s') : null;
            break;

        case 'year':
            $to = ($from) ? Carbon::parse($from)->endOfYear()->endOfDay()->format('Y-m-d H:i:s') : null;
            break;
    }

    $_SESSION['rangeStart'] = $from ? Carbon::parse($from)->toFormattedDateString() : $_SESSION['rangeStart'];
    $_SESSION['rangeEnd'] = $to ? Carbon::parse($to)->toFormattedDateString() : $_SESSION['rangeEnd'];
}

$visitors = safeJsonParser($gateway->sendRequest(['from' => $from, 'to' => $to, 'page' => $page, 'per-page' => $results_per_page], STATISTICS_ENDPOINT . "brands/{$brandId}/clients/loyal", 'GET'), true);

$templateData = [
    'visitors'       => data_get($visitors, 'data') ?? [],
    'numberPages'    => ceil(data_get($visitors, 'total')/$results_per_page) ?? 0,
    'page'           => $page,
    'resultsPerPage' => $results_per_page
];

$html = $view->render('views::statistics/loyaltyVisitors', $templateData);
$response->getBody()->write($html);
return $response;

function safeJsonParser($object, $assoc = false)
{
    if ($object) {
        return \GuzzleHttp\json_decode($object, $assoc);
    }

    return [];
}
