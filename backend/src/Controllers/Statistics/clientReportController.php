<?php

include_once RUTA_DIR . LIB . 'dashboards_helpers.php';
include_once MODEL . 'engagementModel.php';
include_once RUTA_DIR . LIB . 'shared_dashboards_models.php';
include_once RUTA_DIR . LIB . 'shared_dashboards_controllers.php';
include_once RUTA_DIR . LIB . 'loguearHotel.php';
include_once APP . 'Services/Connections/ApiGatewayConnection.php';

    $view = $container->get('view');
    $view->addData([
        'title' => '',
        'page_title' => 'Reporte estadístico',
        'page_icon' => 'bar chart',
        'current_page' => 'statistics',
        'current_subPage' => 'users_dashboard'
    ]);

    if (isset($_GET['hotel'])) {
        $_SESSION['h_logueado'] = obtenerIdHotelGUID($_GET['hotel']);
        $datediff = false;
        $brandName = '';
        if (array_get($_GET, 'chainSearch')) {
            $_SESSION['chainSearch'] = true;
            $_SESSION['c_logueado'] = $_GET['chainSearch'];
            $brandName = array_get(getChainName($_SESSION['c_logueado']), 'nombre');

        } else {
            $_SESSION['chainSearch'] = false;
            $_SESSION['c_logueado'] = false;
        }
        $_SESSION['rangeStart'] = array_get($_GET, 'origin_date', false);
        $_SESSION['rangeEnd'] = array_get($_GET, 'finish_date', false);

        if ($_SESSION['rangeStart']) {
            $now = time(); // or your date as well
            $your_date = strtotime($_SESSION['rangeStart']);
            $datediff = $now - $your_date;
            $datediff = round($datediff / (60 * 60 * 24));
        }

        if ($datediff and $datediff < 120) {
            $_SESSION['lapse'] = 'day';
        } else {
            $_SESSION['lapse'] = 'month';
        }


        $hotel = $_SESSION['hotel'] = obtenerDatosHotelLogin($_SESSION['h_logueado']);
        $lang =  $template_data['hotelLang'] = array_get($hotel, 'lang', 'en');
        $brandName = $brandName ? $brandName : $brandName = array_get($_SESSION, 'hotel.name');
        $view->addData([
            'title' => 'Hotelinking - ' . array_get($_SESSION, 'hotel.name'),
            'hotel_name' => $brandName,
            'hotel_logo' => array_get($_SESSION, 'hotel.logo'),
            'url' => 'http://localhost'
        ]);

        $chain_id = !empty($_SESSION['chainSearch']) ?  array_get($_SESSION,'c_logueado') : NULL;
        $hotel_id = array_get($_SESSION,'h_logueado', array_get($_SESSION,'staff_id_hotel'));
        $brand_id = empty($_SESSION['chainSearch']) ? ($_SESSION['hotel']['brand_id'] ?? null) : ($_SESSION['hotel']['parent_brand_id'] ?? null);

        $from = getDateRangeStart();
        $to = getDateFormat($_SESSION['rangeEnd']) ?? date("Y-m-d", strtotime("tomorrow"));
        
        $gateway = new ApiGatewayConnection();
        $requests = [
            'usersInTime' => [
                'endpoint' => STATISTICS_ENDPOINT . 'brands/'. $brand_id .'/visits/timeline',
                'method' => 'GET',
                'payload' => ['by' => $_SESSION['lapse'], 'from' => $from, 'to' => $to],
            ],
            'clients' => [
                'endpoint' => STATISTICS_ENDPOINT . 'brands/'. $brand_id .'/clients',
                'method' => 'GET',
                'payload' => ['from' => $from, 'to' => $to],
            ],
            'source'   => [
                'endpoint' => STATISTICS_ENDPOINT . 'brands/'. $brand_id .'/connections/source',
                'method' => 'GET',
                'payload' => ['from' => $from, 'to' => $to],
            ],
            'gender'   =>  [
                'endpoint' => STATISTICS_ENDPOINT . 'brands/'. $brand_id .'/visits/gender',
                'method' => 'GET',
                'payload' => ['from' => $from, 'to' => $to],
            ],
            'accommodated'   =>  [
                'endpoint' => STATISTICS_ENDPOINT . 'brands/'. $brand_id .'/visits/accommodated',
                'method' => 'GET',
                'payload' => ['from' => $from, 'to' => $to],
            ],
            'subscribed'   =>  [
                'endpoint' => STATISTICS_ENDPOINT . 'brands/'. $brand_id .'/clients/subscribed',
                'method' => 'GET',
                'payload' => ['from' => $from, 'to' => $to],
            ],
            'country'   =>  [
                'endpoint' => STATISTICS_ENDPOINT . 'brands/'. $brand_id .'/visits/country',
                'method' => 'GET',
                'payload' => ['from' => $from, 'to' => $to],
            ],
            'generations'   => [
                'endpoint' => STATISTICS_ENDPOINT . 'brands/'. $brand_id .'/visits/generation',
                'method' => 'GET',
                'payload' => ['from' => $from, 'to' => $to],
            ],
            'devices'   => [
                'endpoint' => STATISTICS_ENDPOINT . 'brands/'. $brand_id .'/connections/devices',
                'method' => 'GET',
                'payload' => ['from' => $from, 'to' => $to],
            ],
            'socialMediaConnections' => [
                'endpoint' => STATISTICS_ENDPOINT . 'brands/'. $brand_id .'/connections/source',
                'method' => 'GET',
                'payload' => ['source_type' => 'facebook', 'from' => $from, 'to' => $to],
            ],
            'socialMediaFriends' => [
                'endpoint' => STATISTICS_ENDPOINT . 'brands/'. $brand_id .'/clients/facebook/friends',
                'method' => 'GET',
                'payload' => ['from' => $from, 'to' => $to],
            ],
            'publications' => [
                'endpoint' => STATISTICS_ENDPOINT . 'brands/'. $brand_id .'/social-media/publications',
                'method' => 'GET',
                'payload' => ['from' => $from, 'to' => $to],
            ],
            'avgPublications'   => [
                'endpoint' => STATISTICS_ENDPOINT . 'brands/'. $brand_id .'/social-media/avg-publications',
                'method' => 'GET',
                'payload' => ['from' => $from, 'to' => $to],
            ],
            'scoreTimeline' => [
                'endpoint' => STATISTICS_ENDPOINT . 'brands/'. $brand_id .'/survey/score-timeline',
                'method' => 'GET',
                'payload' => ['by' => $_SESSION['lapse'], 'from' => $from, 'to' => $to],
            ],
            'reputation' => [
                'endpoint' => STATISTICS_ENDPOINT . 'brands/'. $brand_id .'/survey/reputation',
                'method' => 'GET',
                'payload' => ['from' => $from, 'to' => $to],
            ],
            'answersByScore' => [
                'endpoint' => STATISTICS_ENDPOINT . 'brands/'. $brand_id .'/survey/answers-by-score',
                'method' => 'GET',
                'payload' => ['from' => $from, 'to' => $to],
            ],
            'answersByGender' => [
                'endpoint' => STATISTICS_ENDPOINT . 'brands/'. $brand_id .'/survey/answers-by-gender',
                'method' => 'GET',
                'payload' => ['from' => $from, 'to' => $to],
            ],
            'answersByCountry' => [
                'endpoint' => STATISTICS_ENDPOINT . 'brands/'. $brand_id .'/survey/answers-by-country',
                'method' => 'GET',
                'payload' => ['from' => $from, 'to' => $to],
            ],
            'answersByGeneration' => [
                'endpoint' => STATISTICS_ENDPOINT . 'brands/'. $brand_id .'/survey/answers-by-generation',
                'method' => 'GET',
                'payload' => ['from' => $from, 'to' => $to],
            ],
            'answersByCategories' => [
                'endpoint' => STATISTICS_ENDPOINT . 'brands/'. $brand_id .'/survey/answers-by-category',
                'method' => 'GET',
                'payload' => ['from' => $from, 'to' => $to, 'lang' => $lang],
            ],
            'answersByQuestions' => [
                'endpoint' => STATISTICS_ENDPOINT . 'brands/'. $brand_id .'/survey/answers-by-question',
                'method' => 'GET',
                'payload' => ['from' => $from, 'to' => $to, 'lang' => $lang],
            ],
            'multiresponseAnswers' => [
                'endpoint' => STATISTICS_ENDPOINT . 'brands/'. $brand_id .'/survey/multiresponse-answers',
                'method' => 'GET',
                'payload' => ['from' => $from, 'to' => $to, 'lang' => $lang],
            ],
            'loyalty-timeline' => [
                'endpoint' => STATISTICS_ENDPOINT . 'brands/'. $brand_id .'/clients/loyal-timeline',
                'method' => 'GET',
                'payload' => ['by' => $_SESSION['lapse'], 'from' => $from, 'to' => $to],
            ],
            'loyalty-info' => [
                'endpoint' => STATISTICS_ENDPOINT . 'brands/'. $brand_id .'/clients/loyal-avg',
                'method' => 'GET',
                'payload' => ['from' => $from, 'to' => $to],
            ],
            'emails' => [
                'endpoint' => STATISTICS_ENDPOINT . 'brands/'. $brand_id .'/emails',
                'method' => 'GET',
                'payload' => ['by' => $_SESSION['lapse'], 'from' => $from, 'to' => $to],
            ],
            'interactions' => [
                'endpoint' => STATISTICS_ENDPOINT . 'brands/'. $brand_id .'/emails/interaction',
                'method' => 'GET',
                'payload' => ['by' => $_SESSION['lapse'], 'from' => $from, 'to' => $to],
            ]
        ];
        
        $promises = $gateway->sendAsyncRequests($requests);
        $statisticsData = $gateway->awaitAsyncRequests($promises, $requests, 3);
        $template_data['background'] = [
            'rgba(0, 0, 0, 1)',
            'rgba(163, 230, 53, 1)',
            'rgba(56, 247, 234, 1)',
            'rgba(113, 90, 255, 1)',
            'rgba(0, 0, 0, .6)',
            'rgba(0, 0, 0, .4)',
            'rgba(0, 0, 0, .2)',
        ];

        $template_data['chainSearch'] = $chain_id;

        // Get USER statistics
        $clientsLang =  include LANG . $lang . '/statistics/users.php';

        $usersInTime = getResponse($statisticsData, 'usersInTime');
        $highterColumn = array_column($usersInTime, 'total_visits');
        $template_data['user_steps'] = $highterColumn ? getDataSteps(max($highterColumn)) : 1;

        $template_data['users_days'] = array_pluck($usersInTime, 'date'); 
        $template_data['users'] = array_pluck($usersInTime, 'total_visits');

        array_walk($template_data['users_days'], 'formatDate');

        // Total users
        $template_data['totalUsers'] = data_get(getResponse($statisticsData, 'clients'), 'total_clients');

        // Sources
        $sources = getResponse($statisticsData, 'source');
        $totalConnections = array_sum(array_column($sources, 'total_access'));

        $formPercentage = computePercentage($sources, 'access_type', 'Form', 'total_access', $totalConnections);
        $facebookPercentage = computePercentage($sources, 'access_type', 'Facebook', 'total_access', $totalConnections);
        $template_data['sourcesData'] = [$facebookPercentage, $formPercentage];
        $template_data['sourcesLabels'] = [$clientsLang['facebook']  . $facebookPercentage . '%', $clientsLang['form']  . $formPercentage . '%'];

        // Gender
        $genders = getResponse($statisticsData, 'gender');
        $totalVisits = array_sum(array_column($genders, 'total_users'));

        $malePercentage = computePercentage($genders, 'gender', 'male', 'total_users', $totalVisits);
        $femalePercentage = computePercentage($genders, 'gender', 'female', 'total_users', $totalVisits);
        $othersPercentage = computePercentage($genders, 'gender', '', 'total_users', $totalVisits) + computePercentage($genders, 'gender', 'other', 'total_users', $totalVisits);
        $template_data['genderData'] = [$malePercentage, $femalePercentage, $othersPercentage];
        $template_data['genderLabels'] = [$clientsLang['male'] . $malePercentage . '%', $clientsLang['female'] . $femalePercentage . '%', $clientsLang['others'] . $othersPercentage . '%'];


        // Accommodated
        $accommodated = getResponse($statisticsData, 'accommodated');
        $totalAccommodated = array_sum(array_column($genders, 'total_users'));
        $accommodatedPercentage = computePercentage($accommodated, 'accommodated', 'True', 'total_users', $totalAccommodated);
        $nonAccommodatedPercentage = computePercentage($accommodated, 'accommodated', 'False', 'total_users', $totalAccommodated);
        $template_data['accommodatedData'] = [$accommodatedPercentage, $nonAccommodatedPercentage];
        $template_data['accommodatedLabels'] = [$clientsLang['accommodated'] . $accommodatedPercentage . '%', $clientsLang['non accommodated'] . $nonAccommodatedPercentage . '%'];

        // Subscribed
        $subscribed = getResponse($statisticsData, 'subscribed');
        $totalSubscribed = array_sum(array_column($subscribed, 'total_users'));
        $subscribedPercentage = computePercentage($subscribed, 'unsubscribed', 'False', 'total_users', $totalSubscribed);
        $unsubscribedPercentage = computePercentage($subscribed, 'unsubscribed', 'True', 'total_users', $totalSubscribed);
        $template_data['subscribedData'] = [$subscribedPercentage, $unsubscribedPercentage];
        $template_data['subscribedLabels'] = [$clientsLang['subscribed'] . $subscribedPercentage . '%', $clientsLang['unsubscribed'] . $unsubscribedPercentage . '%'];

        // Country
        $countries = getResponse($statisticsData, 'country');
        $unknownUserCountries = array_first($countries, function ($key, $value) {
            return $value->country === 'Unknown';
        });

        $unknownUserCountries = !empty($unknownUserCountries) ? $unknownUserCountries->total_users : 0;
        $countryVisits = array_sum(array_column($countries, 'total_users')) - $unknownUserCountries;
        $template_data['countryData'] = [];
        $template_data['countryLabels'] = [];

        $countryIndex = 0;
        foreach ($countries as $country) {
            if ($countryIndex > 5) {
                break;
            }

            if($country->country != 'Unknown'){
                array_push($template_data['countryData'], round(data_get($country, 'total_users', 0) * 100 / $countryVisits, 2));
                array_push($template_data['countryLabels'], $country->country);
            }

            $countryIndex ++;
        }

        // Devices
        $devices = getResponse($statisticsData, 'devices');
        $genericDevices = array_first($devices, function ($key, $value) {
            return $value->device_brand === 'Generic';
        });

        $genericDevices = !empty($genericDevices) ? $genericDevices->total_devices : 0;
        $devicesConnections = array_sum(array_column($devices, 'total_devices')) - $genericDevices;

        $template_data['devicesData'] = [];
        $template_data['devicesLabels'] = [];

        $deviceIndex = 0;
        foreach ($devices as $device) {
            if ($deviceIndex > 6) {
                break;
            }

            if($device->device_brand != 'Generic'){
                array_push($template_data['devicesData'], round(data_get($device, 'total_devices', 0) * 100 / $devicesConnections, 2));
                array_push($template_data['devicesLabels'], $device->device_brand);
            }

            $deviceIndex ++;
        }

        // Generation
        $generations = getResponse($statisticsData, 'generations');
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
        $template_data['generationLabels'] = array_keys($template_sub_data);
        $template_data['generationData'] = array_values($template_sub_data);

        // Get CLICKS statistics

        $template_data['socialMediaUsers'] = getResponse($statisticsData, 'socialMediaConnections')[0]->total_access ?? 0;

        // Get total friends from Facebook
        $template_data['averageFacebookFriends'] = getResponse($statisticsData, 'socialMediaFriends')->total_friends ?? 0;

        // Get total shares from facebook
        $template_data['socialMediaPublications'] = getResponse($statisticsData, 'publications')->total_publications ?? 0;
        $template_data['averageSocialMediaShares'] = getResponse($statisticsData, 'avgPublications')->avg_publications ?? 0;


        //REPUTATION statistics
        
        function getGenerationStats($generations, $search) {
            return data_get(array_first($generations, function ($key, $value) use ($search) {
                return $value->generation === $search;
            }), 'avg', 0);
        }

        // Score timeline
        $scoreTimeline = getResponse($statisticsData, 'scoreTimeline');
        $template_data['averageScores'] = array_pluck($scoreTimeline, 'score_avg');
        $template_data['dates'] = array_pluck($scoreTimeline, 'date');
        array_walk($template_data['dates'], 'formatDate');
        $template_data['averageScoresSteps'] = createStepsForRates($template_data['averageScores']);

        // Surveys Fulfilled
        $reputation = getResponse($statisticsData, 'reputation');
        $template_data['surveysSent'] = data_get($reputation, 'sent');
        $template_data['surveysFulfilled'] = round(data_get($reputation, 'done') * 100 / (data_get($reputation, 'sent') ? data_get($reputation, 'sent') : 1), 2);
        $template_data['surveysUnfulfilled'] = 100 - $template_data['surveysFulfilled'];

        // Avg Score
        $template_data['averageScore'] = data_get($reputation, 'avg');

        // Average Response Time
        if (data_get($reputation, 'avg_timelapse_response')) {
            $template_data['averageTimelapseResponse'] = $reputation->avg_timelapse_response . 'h';

            $days = floor($reputation->avg_timelapse_response/24);
            $hours = round(($reputation->avg_timelapse_response/24 - $days) * 24);

            $template_data['parsedAverageTimelapseResponse'] = $days.' días y ' . $hours . ' horas';

        } else {
            $template_data['averageTimelapseResponse'] = empty($reputation->avg_timelapse_response) ? '-' : $reputation->avg_timelapse_response;
            $template_data['parsedAverageTimelapseResponse'] = '';
        }

        // Answers By Score
        $answersByScore = getResponse($statisticsData, 'answersByScore');
        $answersScore = [];

        for ($i = 0; $i <= 10; $i++){
            array_push($answersScore ,computePercentage($answersByScore, 'score', $i, 'total', data_get($reputation, 'done')));
        }

        $template_data['answersByScore'] = $answersScore;

        // Answers By Gender
        $answersByGender = getResponse($statisticsData, 'answersByGender');
        $template_data['gendersAvg'] = array_pluck($answersByGender, 'avg');
        $gendersLabels = array_pluck($answersByGender, 'gender');

        $template_data['gendersLabels'] = array_map(function($gender) { 
            $gender = empty($gender) ? 'others' : $gender;
            return $gender; 
        }, $gendersLabels);

        // Answers By Country
        $answersByCountry = getResponse($statisticsData, 'answersByCountry');
        $template_data['countries'] = array_pluck(array_slice($answersByCountry, 0, 5), 'country');
        $template_data['answersByCountries'] = array_pluck(array_slice($answersByCountry, 0, 5), 'avg');

        // Answers By Generation
        $answersByGeneration = getResponse($statisticsData, 'answersByGeneration');
        $generations = [
            'Mature' => getGenerationStats($answersByGeneration, 'mature'),
            'Baby boomers' => getGenerationStats($answersByGeneration, 'baby boomer'),
            'Gen X' => getGenerationStats($answersByGeneration, 'generation x'),
            'Millenials' => getGenerationStats($answersByGeneration, 'millenial'),
            'Gen Z' => getGenerationStats($answersByGeneration, 'generation z'),
        ];
        asort($generations);
        $generations = array_reverse($generations);
        $template_data['satisfactionGenerationLabels'] = array_keys($generations);
        $template_data['satisfactionGenerationData'] = array_values($generations);

        // Answers by Category
        $categories = getResponse($statisticsData, 'answersByCategories');

        $categoryData =  array_unique(array_map(function ($val) use($categories) {
            // Group categories by name
            $categoriesGrouped = array_filter($categories, function ($category) use ($val) {
                return $val->category == $category->category && !is_null($val->avg);
            });

            $totalCountByCategory = computeCount($categoriesGrouped, 'count');
            $categoryAvg = computeAvg($categoriesGrouped, $totalCountByCategory);
            
            return $totalCountByCategory
                ? ["category" => $val->category, "avg" => $categoryAvg]
                : null;
        }, $categories), SORT_REGULAR);

        $categoryData = filterNullValues($categoryData, 'avg');

        $template_data['categories'] = filterNullValues($categories, 'avg');
        $template_data['categoryLabels'] = array_pluck($categoryData, 'category');
        $template_data['categoriesData'] = array_pluck($categoryData, 'avg');


        usort($categories, 'sortByID');
        $template_data['categoriesOrdered'] = $categories;

        // Answers by Question
        $template_data['questions'] = getResponse($statisticsData, 'answersByQuestions');

        $multiresponseAnswers = getResponse($statisticsData, 'multiresponseAnswers');

        $multiresponseQuestions = [];
        foreach ($multiresponseAnswers as $answer) {
            $responses = array_filter($multiresponseAnswers, function ($multiresponseAnswer) use ($answer) {
                return $multiresponseAnswer->id ==  $answer->id;
            });

            $multiresponseQuestions[] = [
                "id"                => $answer->id,
                "question"          => $answer->question,
                "category_id"       => $answer->category_id,
                "responses"         => $responses
            ];
        }

        $template_data['multiresponseQuestions'] = array_unique($multiresponseQuestions, SORT_REGULAR);

        // ENGAGEMENT statistics
        $template_data['sentBackground'] = 'rgba(0,0,0,0.5)';
        $template_data['opensBackground'] = 'rgba(163,230,53,0.5)';
        $template_data['clicksBackground'] = 'rgba(56,247,234,0.5)';

        $template_data['sentBorder'] = 'rgba(0,0,0,1)';
        $template_data['opensBorder'] = 'rgba(163,230,53,1)';
        $template_data['clicksBorder'] = 'rgba(56, 247, 234, 1)';

        $emails = getResponse($statisticsData, 'emails', true);
        $interactions = getResponse($statisticsData, 'interactions', true);

        $emailsDates = array_pluck($emails, 'date');
        $interactionDates = array_pluck($interactions, 'date');

        $template_data['satisfactionInteraction'] = getEmailInfo($emails, $interactions, 'satisfaction');
        $template_data['reviewInteraction'] = getEmailInfo($emails, $interactions, 'review');
        $template_data['birthdayInteraction'] = getEmailInfo($emails, $interactions, 'birthday');
        $template_data['offerInteraction'] = getEmailInfo($emails, $interactions, 'stay_offer');
        $template_data['warningInteraction'] = getEmailInfo($emails, $interactions, 'satisfaction_warning');
        $template_data['birthdayWarningInteraction'] = getEmailInfo($emails, $interactions, 'birthday_warning');
        $template_data['loyalWarningInteraction'] = getEmailInfo($emails, $interactions, 'loyalty_warning');
        
        //Loyalty statistics
        $loyalUsersInTime = getResponse($statisticsData, 'loyalty-timeline');
        $template_data['recurrentDays'] = array_pluck($loyalUsersInTime, 'date'); 
        $template_data['recurrentClientsTimeline'] = array_pluck($loyalUsersInTime, 'count');
        $template_data['recurrentClientsSteps'] = !empty($template_data['recurrentClientsTimeline']) ? getDataSteps(max($template_data['recurrentClientsTimeline'])) : 0;

        $loyalInfo = getResponse($statisticsData, 'loyalty-info');
        $template_data['recurrentAvg'] = data_get($loyalInfo, 'recurrent_avg'); 
        $template_data['recurrentClients'] = data_get($loyalInfo, 'recurrent_clients');

        array_walk($template_data['recurrentDays'], 'formatDate');

        $date = new DateTime($_SESSION['rangeStart']);
        $finishDate = new DateTime($_SESSION['rangeEnd']);

        $template_data['origin_date_title'] = $date->format('d/m/Y');
        $template_data['finish_date_title'] = $finishDate->format('d/m/Y');

        session_unset();

        $html = $view->render('views::reports/clientReport', $template_data);
        $response->getBody()->write($html);
        return $response;
//    }

};

function sortByID($object1, $object2) { 
    return $object1->id - $object2->id;
}