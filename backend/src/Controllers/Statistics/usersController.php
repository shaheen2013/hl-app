<?php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}
include LANG . $_SESSION['userLang'] . '/statistics/users.php' ;
include_once RUTA_DIR . LIB . 'dashboards_search_session_management.php';
include_once RUTA_DIR . LIB . 'dashboards_helpers.php';
include_once APP . 'Services/Connections/ApiGatewayConnection.php';
include_once RUTA_DIR . LIB . 'shared_dashboards_models.php';

$view = $container->get('view');
$view->addData([
    'page_title' => $lang['stats clients'],
    'page_icon' => 'bar chart',
    'current_page' => 'statistics',
    'current_subPage' => 'users_dashboard'
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
    'usersInTime' => [
        'endpoint' => STATISTICS_ENDPOINT . 'brands/'. $brandId .'/visits/timeline',
        'method' => 'GET',
        'payload' => ['by' => $_SESSION['lapse'], 'from' => $from, 'to' => $to],
    ],
    'clients' => [
        'endpoint' => STATISTICS_ENDPOINT . 'brands/'. $brandId .'/clients',
        'method' => 'GET',
        'payload' => ['from' => $from, 'to' => $to],
    ],
    'source'   => [
        'endpoint' => STATISTICS_ENDPOINT . 'brands/'. $brandId .'/connections/source',
        'method' => 'GET',
        'payload' => ['from' => $from, 'to' => $to],
    ],
    'gender'   =>  [
        'endpoint' => STATISTICS_ENDPOINT . 'brands/'. $brandId .'/visits/gender',
        'method' => 'GET',
        'payload' => ['from' => $from, 'to' => $to],
    ],
    'accommodated'   =>  [
        'endpoint' => STATISTICS_ENDPOINT . 'brands/'. $brandId .'/visits/accommodated',
        'method' => 'GET',
        'payload' => ['from' => $from, 'to' => $to],
    ],
    'subscribed'   =>  [
        'endpoint' => STATISTICS_ENDPOINT . 'brands/'. $brandId .'/clients/subscribed',
        'method' => 'GET',
        'payload' => ['from' => $from, 'to' => $to],
    ],
    'country'   =>  [
        'endpoint' => STATISTICS_ENDPOINT . 'brands/'. $brandId .'/visits/country',
        'method' => 'GET',
        'payload' => ['from' => $from, 'to' => $to],
    ],
    'generations'   => [
        'endpoint' => STATISTICS_ENDPOINT . 'brands/'. $brandId .'/visits/generation',
        'method' => 'GET',
        'payload' => ['from' => $from, 'to' => $to],
    ],
    'devices'   => [
        'endpoint' => STATISTICS_ENDPOINT . 'brands/'. $brandId .'/connections/devices',
        'method' => 'GET',
        'payload' => ['from' => $from, 'to' => $to],
    ]
];

$promises = $gateway->sendAsyncRequests($requests);

$templateData['background'] = [
    'rgba(0, 0, 0, 1)',
    'rgba(163, 230, 53, 1)',
    'rgba(56, 247, 234, 1)',
    'rgba(113, 90, 255, 1)',
    'rgba(0, 0, 0, .6)',
    'rgba(0, 0, 0, .4)',
    'rgba(0, 0, 0, .2)',
];

$usersStatistics = $gateway->awaitAsyncRequests($promises);

// Users in time
$usersInTime = getResponse($usersStatistics, 'usersInTime');
$highterColumn = array_column($usersInTime, 'total_visits');
$templateData['steps'] = $highterColumn ? getDataSteps(max($highterColumn)) : 1;

$templateData['users_days'] = array_pluck($usersInTime, 'date'); 
$templateData['users'] = array_pluck($usersInTime, 'total_visits');

array_walk($templateData['users_days'], 'formatDate');

// Total users
$templateData['totalUsers'] = getResponse($usersStatistics, 'clients')->total_clients;

// Database value
$templateData['multiplier'] = 2.2;
$templateData['databaseValue'] = $templateData['totalUsers'] * $templateData['multiplier'];

// Sources
$sources = getResponse($usersStatistics, 'source');
$totalConnections = array_sum(array_column($sources, 'total_access'));

$formPercentage = computePercentage($sources, 'access_type', 'Form', 'total_access', $totalConnections);
$facebookPercentage = computePercentage($sources, 'access_type', 'Facebook', 'total_access', $totalConnections);
$templateData['sourcesData'] = [$facebookPercentage, $formPercentage];
$templateData['sourcesLabels'] = [$lang['facebook'] . $facebookPercentage . '%', $lang['form'] . $formPercentage . '%'];

// Gender
$genders = getResponse($usersStatistics, 'gender');
$totalVisits = array_sum(array_column($genders, 'total_users'));

$malePercentage = computePercentage($genders, 'gender', 'male', 'total_users', $totalVisits);
$femalePercentage = computePercentage($genders, 'gender', 'female', 'total_users', $totalVisits);
$othersPercentage = computePercentage($genders, 'gender', '', 'total_users', $totalVisits) + computePercentage($genders, 'gender', 'other', 'total_users', $totalVisits);
$templateData['genderData'] = [$malePercentage, $femalePercentage, $othersPercentage];
$templateData['genderLabels'] = [$lang['male'] . $malePercentage . '%', $lang['female'] . $femalePercentage . '%', $lang['others'] . $othersPercentage . '%'];

// Accommodated
$accommodated = getResponse($usersStatistics, 'accommodated');
$totalAccommodated = array_sum(array_column($genders, 'total_users'));
$accommodatedPercentage = computePercentage($accommodated, 'accommodated', 'True', 'total_users', $totalAccommodated);
$nonAccommodatedPercentage = computePercentage($accommodated, 'accommodated', 'False', 'total_users', $totalAccommodated);
$templateData['accommodatedData'] = [$accommodatedPercentage, $nonAccommodatedPercentage];
$templateData['accommodatedLabels'] = [$lang['accommodated'] . $accommodatedPercentage . '%', $lang['non accommodated'] . $nonAccommodatedPercentage . '%'];

// Subscribed
$subscribed = getResponse($usersStatistics, 'subscribed');
$totalSubscribed = array_sum(array_column($subscribed, 'total_users'));
$subscribedPercentage = computePercentage($subscribed, 'unsubscribed', 'False', 'total_users', $totalSubscribed);
$unsubscribedPercentage = computePercentage($subscribed, 'unsubscribed', 'True', 'total_users', $totalSubscribed);
$templateData['subscribedData'] = [$subscribedPercentage, $unsubscribedPercentage];
$templateData['subscribedLabels'] = [$lang['subscribed'] . $subscribedPercentage . '%', $lang['unsubscribed'] . $unsubscribedPercentage . '%'];

// Country
$countries = getResponse($usersStatistics, 'country');
$unknownUserCountries = array_first($countries, function ($key, $value) {
    return $value->country === 'Unknown';
});

$unknownUserCountries = !empty($unknownUserCountries) ? $unknownUserCountries->total_users : 0;
$countryVisits = array_sum(array_column($countries, 'total_users')) - $unknownUserCountries;
$templateData['countryData'] = [];
$templateData['countryLabels'] = [];

$countryIndex = 0;
foreach ($countries as $country) {
    if ($countryIndex > 5) {
        break;
    }

    if($country->country != 'Unknown'){
        array_push($templateData['countryData'], round(data_get($country, 'total_users', 0) * 100 / $countryVisits, 2));
        array_push($templateData['countryLabels'], $country->country);
    }

    $countryIndex ++;
}

// Devices
$devices = getResponse($usersStatistics, 'devices');
$genericDevices = array_first($devices, function ($key, $value) {
    return $value->device_brand === 'Generic';
});

$genericDevices = !empty($genericDevices) ? $genericDevices->total_devices : 0;
$devicesConnections = array_sum(array_column($devices, 'total_devices')) - $genericDevices;

$templateData['devicesData'] = [];
$templateData['devicesLabels'] = [];

$deviceIndex = 0;
foreach ($devices as $device) {
    if ($deviceIndex > 6) {
        break;
    }

    if($device->device_brand != 'Generic'){
        array_push($templateData['devicesData'], round(data_get($device, 'total_devices', 0) * 100 / $devicesConnections, 2));
        array_push($templateData['devicesLabels'], $device->device_brand);
    }

    $deviceIndex ++;
}

// Generation
$generations = getResponse($usersStatistics, 'generations');
$totalGenerations = array_sum(array_column($generations, 'total_users'));
$template_sub_data = [
    'Mature' => computePercentage($generations, 'generation', 'mature', 'total_users', $totalGenerations),
    'Baby boomers' => computePercentage($generations, 'generation', 'baby boomer', 'total_users', $totalGenerations),
    'Gen X' => computePercentage($generations, 'generation', 'generation x', 'total_users', $totalGenerations),
    'Millenials' => computePercentage($generations, 'generation', 'millenial', 'total_users', $totalGenerations),
    'Gen Z' => computePercentage($generations, 'generation', 'generation z', 'total_users', $totalGenerations)
];
asort($template_sub_data);

$template_sub_data = array_reverse($template_sub_data);
$templateData['generationLabels'] = array_keys($template_sub_data);
$templateData['generationData'] = array_values($template_sub_data);

$html = $view->render('views::statistics/users', $templateData);
$response->getBody()->write($html);
return $response;
