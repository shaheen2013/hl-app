<?php

if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}

function getResponse($requests, $path, $assoc = false)
{
    if (array_get($requests, $path.'.value')) {
        $response = array_get($requests, $path.'.value')->getBody();

        if ($response) {
            return \GuzzleHttp\json_decode($response, $assoc);
        }
    }

    global $log;
    $log->error("Can't access to body on promise", ["requests" => $requests, "path" => $path]);
    
    return [];
}

function getAverage($partial, $total) 
{
    if (!$total) {
        return 0;
    }

    return round($partial * 100 / $total, 2);
}

function getEmailData($data, $emailType) 
{
    return array_values(
        array_where($data, function ($key, $email) use ($emailType) {
            return array_get($email, 'email_type') == $emailType;
        })
    );
}

function fillStatisticsDates($dates, $data, $searcher)
{
    return array_map(function($date) use ($data, $searcher) {
        $position = array_search($date, array_pluck($data, 'date'));
        return array_get($data, $position.'.'.$searcher, 0);
    }, $dates);
}

function getEmailInfo($emails, $interactions, $key)
{
    $emailDataSent = getEmailData($emails, $key);
    $emailInteractions = getEmailData($interactions, $key);

    $data = [];
    if ($emailDataSent || $emailInteractions) {
        $emailsDates = array_pluck($emailDataSent, 'date');
        $interactionDates = array_pluck($emailInteractions, 'date');
        $data['dates']  = array_values(array_unique(array_merge($emailsDates, $interactionDates)));
        usort($data['dates'], "sortDate");

        $data['opens'] =  fillStatisticsDates($data['dates'], $emailInteractions, 'opens');
        $data['clicks'] = fillStatisticsDates($data['dates'], $emailInteractions, 'clicks');
        $data['sent'] = fillStatisticsDates($data['dates'], $emailDataSent, 'sent');
        $data['opens_avg'] = getAverage(array_sum($data['opens']), array_sum($data['sent']));
        $data['clicks_avg'] = getAverage(array_sum($data['clicks']), array_sum($data['sent']));
        $data['steps'] = getDataSteps(max($data['sent'])); 

        array_walk($data['dates'], 'formatDate');

        return $data;
    }
}

function getDateFormat($date)
{
    return !empty($date) ? date_format(date_create($date), 'Y-m-d') : null;

}

function getDatesQuery($table, $datesSearch, $limit = false)
{
    global $log;
    // Format dates as IDs in dates dimension table

    if (!$limit){
        $rangeStart = !empty($datesSearch['rangeStart']) ? date_format(date_create($datesSearch['rangeStart']), 'Ymd') : FALSE;
    } else {
        // Put a limit on search no longer than the date
        $rangeStart = !empty($datesSearch['rangeStart']) ? date_format(date_create($datesSearch['rangeStart']), 'Ymd') : FALSE;
        if ($rangeStart){
            if ($rangeStart < $limit)
                $rangeStart = $limit;
        }
    }
    $rangeEnd = !empty($datesSearch['rangeEnd']) ? date_format(date_create($datesSearch['rangeEnd']), 'Ymd') : FALSE;

    // Return query part
    if ($rangeStart && $rangeEnd) {
        return ' ' . $table . '.dim_date_id BETWEEN ' . $rangeStart . ' AND ' . $rangeEnd;
    } else if ($rangeStart && !$rangeEnd) {
        return ' ' . $table . '.dim_date_id >= ' . $rangeStart;
    }
}

function getSystemDatesQuery($field, $datesSearch)
{
    global $log;
    // Format dates as IDs in dates dimension table
    $rangeStart = !empty($datesSearch['rangeStart']) ? date_format(date_create($datesSearch['rangeStart']), 'Y-m-d') : FALSE;
    $rangeEnd = !empty($datesSearch['rangeEnd']) ? date_format(date_create($datesSearch['rangeEnd']), 'Y-m-d') : FALSE;

    if ($rangeStart && $rangeEnd) {
        return " ($field BETWEEN '$rangeStart' AND '$rangeEnd')";
    } else if ($rangeStart && !$rangeEnd) {
        return " ($field >= '$rangeStart')";
    }
}

function createCacheName($name, $id_chain = null, $id_hotel = null, $dates = null)
{
    $cacheName = $id_chain ? $name . '_chain_' . $id_chain : $name . '_hotel_' . $id_hotel;
    $rangeStart = $dates['rangeStart'] ?? null;
    $rangeEnd = $dates['rangeEnd'] ?? null;
    $cacheName = ($rangeStart || $rangeEnd) ? $cacheName . '_' . md5(serialize($dates)) : $cacheName;
    return $cacheName;
}

function getDataSteps($number)
{
    switch ($number) {
        case $number > 100000:
            $steps = 50000;
            break;
        case $number > 50000:
            $steps = 20000;
            break;
        case $number > 25000:
            $steps = 10000;
            break;
        case $number > 10000:
            $steps = 5000;
            break;
        case $number > 5000:
            $steps = 2000;
            break;
        case $number > 2500:
            $steps = 1000;
            break;
        case $number > 1000:
            $steps = 500;
            break;
        case $number > 500:
            $steps = 200;
            break;
        case $number > 250:
            $steps = 100;
            break;
        case $number > 100:
            $steps = 50;
            break;
        case $number > 50:
            $steps = 20;
            break;
        case $number > 25:
            $steps = 10;
            break;
        case $number > 10:
            $steps = 5;
            break;
        default:
            $steps = 1;
    }
    return $steps;
}

function getPercentage($number, $total_number, $digits)
{
    if ($total_number == 0 || $number == 0)
        return 0;

    $p = $number * 100 / $total_number;

    return number_format($p, $digits);
}

function debugLog($message, $data)
{
    global $log;
    is_array($data) ?  $log->debug($message, $data) :  $log->debug($message . ' -> ' . $data );
}

function computePercentage ($array, $searcher, $search, $number, $total) {
    if (!$total) {
        return 0;
    }

    $search = array_first($array, function ($key, $value) use ($searcher, $search) {
        return data_get($value, $searcher) === $search;
    });

    return round(data_get($search, $number, 0) * 100 / $total, 2);
}

function filterNullValues($array, $key)
{
    return array_filter($array, function ($val) use ($key) {
        return !is_null(data_get($val, $key));
    });
}

function computeAvg($array, $total) 
{
    $allAvg = array_map(function ($element) use ($total) {
        return ($element->count/$total) * $element->avg;
    }, $array );
    
    return number_format(array_sum($allAvg), 2);
}

function computeCount($array, $key) 
{
    return array_sum(array_column($array, $key));
}

function formatDate(&$date) 
{
    $date = substr($date, 0, 10);
}

function sortDate( $a, $b ) {
    return strtotime($a) - strtotime($b);
}

function createStepsForRates($rate)
{
    if (!empty($rate)){
        $review_max_value = max($rate);
        return getDataSteps($review_max_value);
    }
    return 0;
}

function thousandsCurrencyFormat($num) {

    if($num>1000) {
  
          $x = round($num);
          $x_number_format = number_format($x);
          $x_array = explode(',', $x_number_format);
          $x_parts = array('K', 'M', 'B', 'T');
          $x_count_parts = count($x_array) - 1;
          $x_display = $x;
          $x_display = $x_array[0] . ((int) $x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '');
          $x_display .= $x_parts[$x_count_parts - 1];
  
          return $x_display;
  
    }
  
    return $num;
  }