<?php
function createStatisticsOutput($email_types, $statistics_email_types, $email_rates, $emails_sent, $email_rates_formated, $dates)
{
    $emails_statistics_output = [];

    foreach ($email_types as $type) {
        $email_types_name = $statistics_email_types[$type];
        $email_rate = array_filter($email_rates, function ($var) use ($type) {
            return ($var['email_type'] == $type);
        });

        $emails_sent_type = array_filter($emails_sent, function ($var) use ($type) {
            return ($var['email_type'] == $type);
        });

        if ($email_rate) {
            foreach ($email_rate as $rate) {
                $email_rates_formated[$type][$rate[$_SESSION['lapse']]]['opens'] = array_get($email_rates_formated, $rate[$_SESSION['lapse']] . '.opens', 0) + $rate['opens'];
                $email_rates_formated[$type][$rate[$_SESSION['lapse']]]['clicks'] = array_get($email_rates_formated, $rate[$_SESSION['lapse']] . '.clicks') + $rate['clicks'];
                $email_rates_formated[$type][$rate[$_SESSION['lapse']]]['date'] = $rate[$_SESSION['lapse']];
            }
        }
        if ($emails_sent_type) {
            foreach ($emails_sent_type as $email_sent) {
                $email_rates_formated[$type][$email_sent[$_SESSION['lapse']]]['sent'] = array_get($email_rates_formated, $email_sent[$_SESSION['lapse']] . '.sent', 0) + $email_sent['count'];
            }
        }
        $emails_statistics_output['date'] = $dates;
        $emails_statistics_output[$email_types_name]['opens'] = [];
        $emails_statistics_output[$email_types_name]['clicks'] = [];
        $emails_statistics_output[$email_types_name]['sent'] = [];
        foreach ($dates as $date) {
            array_push($emails_statistics_output[$email_types_name]['opens'], array_get($email_rates_formated, $type . '.' . $date . '.opens', 0));
            array_push($emails_statistics_output[$email_types_name]['clicks'], array_get($email_rates_formated, $type . '.' . $date . '.clicks', 0));
            array_push($emails_statistics_output[$email_types_name]['sent'], array_get($email_rates_formated, $type . '.' . $date . '.sent', 0));
        }

    }

    return $emails_statistics_output;
}
function createStatisticsOutput2($emails_sent, $email_types, $dates)
{
    $emails_statistics_output = [];
    $emails_statistics_output['date'] = $dates;
    foreach ($email_types as $type) {
        $i = 0;
        foreach ($emails_sent as $email) {
            $emails_statistics_output[$type]['opens'][$i] = array_get($email, $type.'_emails_opens');    
            $emails_statistics_output[$type]['clicks'][$i] = array_get($email, $type.'_emails_clicks');    
            $emails_statistics_output[$type]['sent'][$i] = array_get($email, $type.'_emails');   
            $i++; 
        }
    }

    return $emails_statistics_output;
}

function createAverages($emails_statistics_output, $kpi)
{
    $opens = array_sum(array_get($emails_statistics_output, $kpi.'.opens', []));
    $clicks = array_sum(array_get($emails_statistics_output, $kpi.'.clicks', []));
    $sent = array_sum(array_get($emails_statistics_output, $kpi.'.sent', []));

    $emails_statistics_output[$kpi . '_average_opens'] = getPercentage($opens, $sent, 2);
    $emails_statistics_output[$kpi . '_average_clicks'] = getPercentage($clicks, $opens, 2);

    return $emails_statistics_output;
}

function createPeriod($initial_date,$final_date)
{
    return new DatePeriod(
        new DateTime($initial_date),
        new DateInterval('P1D'),
        $final_date,
        DatePeriod::EXCLUDE_START_DATE
    ); 
}
