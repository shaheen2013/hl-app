<?php

function getChainIDByHotelID($hotel_id)
{
    global $log;
    $con = conectar(1);
    $hotel_id = mysqli_real_escape_string($con, $hotel_id);
    $cacheName = 'hotel_' . $hotel_id . '_chain';
    $cache = getFromCache($cacheName);
    if (!$cache) {
        $sql = "SELECT id_cadena FROM cadena_hotel WHERE id_hotel = " . $hotel_id;
        $result = lectura($sql, $con);
        if ($result) {
            setToCache($cacheName, $result, 31536000);
        }
        return $result;
    }
    return $cache->get();
}

function formatDate()
{
    $year = date('Y');
    $month = sprintf("%02d", date('m'));
    $day = sprintf("%02d", date('d'));
    $date = $year . $month . $day;
    return $date;
}

function formatTime()
{
    $hour = sprintf("%02d", date('H'));
    $minute = sprintf("%02d", date('i'));
    $time = $hour . $minute;
    return $time;
}

function getGenerationNames()
{
    return [
        'mature' => '1927-01-01',
        'baby boomer' => '1946-01-01',
        'generation x' => '1965-01-01',
        'millenial' => '1981-01-01',
        'generation z' => '2001-01-01'
    ];
}

function getDevicesBrands()
{
    return [
        'Apple',
        'Samsung',
        'Generic_Android',
        'Huawei',
        'LG',
        'Sony',
        'Motorola',
        'Generic',
        'Nokia',
        'Amazon',
        'HTC',
        'SonyEricsson',
        'Lenovo',
        'XiaoMi',
        'Asus',
        'Microsoft',
        'ZTE',
        'BlackBerry'
    ];
}

function getDeviceBrowsers()
{
    return [
        'Mobile Safari UI/WKWebView',
        'Chrome Mobile',
        'Samsung Internet',
        'Chrome',
        'Mobile Safari',
        'Facebook',
        'Edge',
        'Firefox',
        'Firefox Mobile',
        'IE',
        'IE Mobile',
        'Android',
        'Edge Mobile',
        'Amazon Silk',
        'Apple Mail',
        'Safari',
        'Kindle',
        'Opera',
        'Opera Mobile',
        'Yandex Browser',
        'Chrome Mobile',
        'BlackBerry WebKit',
        'UC Browser'
    ];
}

function getOs()
{
    return [
        'iOS',
        'Windows XP',
        'Windows Phone',
        'Windows 8.1',
        'Windows 7',
        'Windows 10',
        'Windows',
        'Ubuntu',
        'Mac OS X',
        'Linux',
        'Kindle',
        'Chrome',
        'BlackBerry',
        'Android'
    ];
}

function getDeviceTypes()
{
    return [
        'mobile',
        'laptop',
        'desktop',
        'tablet'
    ];
}

function getEmailTypesByTag()
{

    return [
        'review_email' => 'review',
        'satisfaction_email' => 'satisfaction',
        'satisfaction_thanks_email' => 'satisfaction_thanks',
        'satisfaction_warning_email' => 'warning',
        'birthday_email' => 'birthday',
        'stayoffer_email' => 'offer',
        'notify_birthday' => 'birthday_warning',
        'regular_client_email' => 'loyal_client_warning',
        'satisfaction_follow_up' => 'follow_up',
        'customized_satisfaction_email' => 'customized_satisfaction'
    ];
}

/**
 * Change any date format to Y-m-d.
 * If can't find any match return null.
 * 
 * @param string
 * @return string date "Y-m-d" or null
 */
function parseDateFormat($date, $params) {
    global $log;

    $date_formats = ["Y-m-d", "d-m-Y"];
    $date = str_replace("/","-",$date);

    foreach ($date_formats as $format) {
        // If any date format is found, return Y-m-d format.
        if (checkDateFormat($date, $format)) {
            $date = date_create_from_format($format,$date);
            return date_format($date,"Y-m-d");
        }
    };
    $log->error('Error Parsing Date', ['params' => $params]);
    return null;
}

function checkDateFormat($date, $format = 'Y-m-d')
{
    // If date is correct return true
    $newDate = DateTime::createFromFormat($format, $date);
    return $newDate && $newDate->format($format) === $date;
}