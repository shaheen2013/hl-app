<?php //Miramos si esta definida la variable de control de index.php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}
//Contenido solo visible si logueado
include LIB . 'logueado.php';
hotelStaffLanding();// Si no esta logueado lo manda a la landing

include_once LIB . 'fecha.php';

// For front purposes
$currentPage = 'clients-management';
$currentSubPage = 'statistics';

$id = array_get($_SESSION,'h_logueado', array_get($_SESSION,'staff_id_hotel'));
$_SESSION['tipo'] = $tipo = 'h';
$_SESSION['startDate'] = !empty($_SESSION['startDate']) ? $_SESSION['startDate'] : date('Y-m-d', strtotime('-1 year'));
$_SESSION['endDate'] = !empty($_SESSION['endDate']) ? $_SESSION['endDate'] : date('Y-m-d');



// If date ranges add to query
if (!empty($_GET['startDate']) && !empty($_GET['endDate'])){
    $_SESSION['startDate'] = $_GET['startDate'];
    $_SESSION['endDate'] = $_GET['endDate'];
}

// If get change values
if ($_GET) {

    if ($_GET['type'] == 'c') {
        $id = $_SESSION['c_logueado'];
        $_SESSION['tipo'] = $tipo = 'c';
    }
}

$kpisReferrals = kpisReferrals($id, $tipo, $_SESSION['startDate'], $_SESSION['endDate']);
$multipliers = getMultipliers();

//Permissions
$satisfaction_permissions = $kpisReferrals['satisfaction'];

// print_r($kpisReferrals);

//Marketing Kpis
$total_users = print_number_count(array_get(getTotalUsers($id, $tipo, $_SESSION['startDate'], $_SESSION['endDate']), 'totalUsers') );
//$total_users = print_number_count($kpisReferrals['usuarios_email'] + $kpisReferrals['usuarios_fb']);
$total_referrers = print_number_count($kpisReferrals['total_referrers']);
$referrals_identificados = print_number_count($kpisReferrals['referrals_no_anonimos']);
$referrals_anonimos = print_number_count($kpisReferrals['referrals_anonimos']);
$clicks_unicos = print_number_count($kpisReferrals['clicks_unicos']);
$sign_ups = print_number_count($kpisReferrals['sign_ups']);
$reservas_finalizadas = print_number_count($kpisReferrals['reservas_finalizadas']);
$valor_reservas = print_number_count($kpisReferrals['valor_reservas']);
$total_referrals = print_number_count($kpisReferrals['total_referrals']);
$total_sm_friends = print_number_count($kpisReferrals['total_sm_friends']);
$total_shares = print_number_count($kpisReferrals['user_shares']);
$ppcValue = print_number_count($kpisReferrals['clicks_unicos'] * $multipliers['single_click_price']);
$total_impression_original = ($kpisReferrals['total_sm_friends'] / 100) * $multipliers['impressions_percents'];
$total_impressions = print_number_count($total_impression_original);
$total_sm_friends_value_orig = ($total_impression_original / 1000) * $multipliers['thousand_impressions_price'];

//Satisfaction Kpis
if ($satisfaction_permissions == 1) {
    $total_reviews = print_number_count($kpisReferrals['review_send']);
    $total_reviews_done = $kpisReferrals['perc_satisfaction_done'];
    $total_reviews_not_done = 100 - $total_reviews_done;
    $media_satisfaction = $kpisReferrals['satisfaction_media'];
}

if (intval($total_sm_friends_value_orig) == 0)
    $total_sm_friends_value_orig = 0;

$total_sm_friends_value = print_number_count($total_sm_friends_value_orig);
//Urls para share de hotelDesk & user
include_once LIB.'obtenerdatosHotel.php';
$guidHotel = obtenerGUIDHotel(array_get($_SESSION,'h_logueado', array_get($_SESSION,'staff_id_hotel')));

// Datamatch activated verification
$datamatch_activated = checkDatamatchActivated($_SESSION['h_logueado']);


//Acorta los números por K, M, B
function print_number_count($number)
{
    $units = array('', 'K', 'M', 'B');
    $power = $number > 0 ? floor(log($number, 1000)) : 0;
    if ($power > 0)
        return @number_format($number / pow(1000, $power), 1, '.', ' ') . ' ' . $units[$power];
    else
        return @number_format($number / pow(1000, $power), 0, '', '');
}