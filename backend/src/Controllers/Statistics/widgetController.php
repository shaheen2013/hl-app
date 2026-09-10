<?php

use App\Models\Hotel;
use GuzzleHttp\Psr7\Request;

if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}

include_once RUTA_DIR . LIB . 'dashboards_helpers.php';
include_once RUTA_DIR . LIB . 'dashboards_search_session_management.php';
include_once RUTA_DIR . LIB . 'shared_dashboards_models.php';
include_once RUTA_DIR . LIB . 'shared_dashboards_models.php';
include_once APP . 'Services/Connections/ApiGatewayConnection.php';
include_once RUTA_DIR . LIB . 'obtenerdatosHotel.php';

global $log;
$gateway = new ApiGatewayConnection();
$getParams = [];

$hotel = Hotel::where('id', '=', array_get($_SESSION, 'h_logueado'))->first();
$brand = $hotel->brand;

$widgetByChild = getChildActive($gateway, $brand->id);
try {
    if ($widgetByChild) {
        $widget = safeJsonParser($gateway->sendRequest(['value' => $brand->id], WIDGET_ENDPOINT . 'dynamic/widgets/brand_id', 'GET'), true);
    } else {
        $widget = safeJsonParser($gateway->sendRequest(['value' => $hotel->brand->parent_id], WIDGET_ENDPOINT . 'dynamic/widgets/brand_id', 'GET'), true);
    }
} catch (\Exception $e) {
    $widget = null;
}


$widgetId = array_get($widget, 'id');
if ($_SESSION['rangeStart']) {
    $getParams['from'] = date_format(date_create($_SESSION['rangeStart']), 'Ymd');
}

if ($_SESSION['rangeEnd']) {
    $getParams['to'] = date_format(date_create($_SESSION['rangeEnd']), 'Ymd');
}

$this->view->addData([
    'page_title' => 'Widget performance',
    'page_icon' => 'window restore outline icon',
    'current_page' => 'statistics',
    'current_subPage' => 'widget_performance'
]);

$template_data = ['widgetId' => $widgetId];

if ($widgetId) {
    //users by age labels
    $template_sub_data = [];
    $template_sub_data = safeJsonParser($gateway->sendRequest($getParams, WIDGET_ENDPOINT . "stats/widget/$widgetId/visitors/by-generation", 'GET'), true);
    asort($template_sub_data);

    $widgetBookings = safeJsonParser($gateway->sendRequest($getParams, WIDGET_ENDPOINT . "stats/widget/$widgetId/bookings", 'GET'),true);

    $amountBookings = 0;
    foreach ($widgetBookings as $widgetBooking) {
        $amountBookings += $widgetBooking['amount'];
    }
    $template_data['amountBookings']=$amountBookings;
    $template_data['totalBookings']= sizeof($widgetBookings);

    $template_sub_data = array_reverse($template_sub_data);
    $template_data['age_labels'] = array_keys($template_sub_data);
    $template_data['age_label'] = '% ';
    $template_data['age_data'] = array_values($template_sub_data);

    //Users by age bar background color
    $template_data['age_backgroundColor'] = [
        'rgba(113, 90, 255, 1)',
        'rgba(102, 81, 229, 1)',
        'rgba(85, 67, 191, 1)',
        'rgba(56, 45, 127, 1)',
        'rgba(28, 22, 64, 1)'
    ];

    $usersByCountry = safeJsonParser($gateway->sendRequest($getParams, WIDGET_ENDPOINT . "stats/widget/$widgetId/visitors/all-country", 'GET'), true);
    $template_country_data = [];
    foreach ($usersByCountry as $item) {
        try {
            $clues['locale'] = array_get($item, 'locale');
            $country_name = $clues['locale'];
            if (array_has($template_country_data, $country_name)) {
                $template_country_data[$country_name] += $item['users'];
            } else {
                $template_country_data[$country_name] = $item['users'];
            }
        } catch (Exception $e) {
            $log->error("Error getting country_id", ['error' => $e]);
        }
    }

    asort($template_country_data);

    $template_data['country_labels'] = array_keys($template_country_data);
    $template_data['country_label'] = '% ';
    $template_data['country_data'] = array_values($template_country_data);

    //Users by country bar background color
    $template_data['country_backgroundColor'] = [
        'rgba(113, 90, 255, 1)',
        'rgba(102, 81, 229, 1)',
        'rgba(85, 67, 191, 1)',
        'rgba(56, 45, 127, 1)',
        'rgba(28, 22, 64, 1)'
    ];

    $template_data['leads'] = (int) safeJsonParser($gateway->sendRequest($getParams, WIDGET_ENDPOINT .  "stats/widget/$widgetId/leads", 'GET'));

    $referrals = safeJsonParser($gateway->sendRequest($getParams, WIDGET_ENDPOINT . "stats/widget/$widgetId/referrals", 'GET'), true);
    $template_referral_data = array_combine(array_column($referrals, 'referrer'), array_column($referrals, 'num'));
    $template_data['referrals_labels'] = array_keys($template_referral_data);
    $template_data['referrals_label'] = '% ';
    $template_data['referrals_data'] = array_values($template_referral_data);
    $template_data['referrals_backgroundColor'] = [
        'rgba(113, 90, 255, 1)',
        'rgba(102, 81, 229, 1)',
        'rgba(85, 67, 191, 1)',
        'rgba(56, 45, 127, 1)',
        'rgba(28, 22, 64, 1)'
    ];
}


return $this->view->render('views::statistics/widget', $template_data);

function getChildActive($gateway, $brandID) {

    $endpointResponse = $gateway->sendRequest([], HOTELINKING_ENDPOINT . 'brands/' . $brandID . '/products/14/configuration', 'GET');

    $widgetByChainConfig = [];
    if(trim($endpointResponse) !== '') {
        $widgetByChainConfig = safeJsonParser($endpointResponse, true);
    }
                                
    return empty($widgetByChainConfig) || !array_get($widgetByChainConfig, 'value', false);
}