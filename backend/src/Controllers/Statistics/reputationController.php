<?php

use App\Models\Hotel;

if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}

include LANG . $_SESSION['userLang'] . '/statistics/reputation.php' ;
include_once MODEL .'clients-profileModel.php';
include_once RUTA_DIR . LIB . 'dashboards_helpers.php';
include_once RUTA_DIR . LIB . 'dashboards_search_session_management.php';
include_once APP . 'Services/Connections/ApiGatewayConnection.php';
include_once RUTA_DIR . LIB . 'shared_dashboards_models.php';

$view = $container->get('view');
$view->addData([
    'page_title' => $lang['stats reputation'],
    'page_icon' => 'heartbeat',
    'current_page' => 'statistics',
    'current_subPage' => 'reputation_dashboard'
]);

//Assign statistics to template variables
$template_data = [];

// Check if satisfaction survey product is not active
if (!hotelHasProduct('satisfaction')) {
    $template_data['satisfactionSurveyProductActive'] = false;
    $html = $view->render('views::statistics/reputation', $template_data);
    $response->getBody()->write($html);
    return $response;
}

if (isset($_GET['lapse'])) {
    $_SESSION['lapse'] = $_GET['lapse'];
}

if (!isset($_SESSION['lapse'])) {
    $_SESSION['lapse'] = 'month';
}

$gateway = new ApiGatewayConnection();
$brandId = !empty($_SESSION['chainSearch']) ? $_SESSION['loggedParentBrandID'] : $_SESSION['loggedBrandID'];
$hotel_id = array_get($_SESSION, 'h_logueado', array_get($_SESSION, 'staff_id_hotel'));

$from = getDateRangeStart();
$to = getDateRangeEnd();

$requests = [
    'scoreTimeline' => [
        'endpoint' => STATISTICS_ENDPOINT . 'brands/'. $brandId .'/survey/score-timeline',
        'method' => 'GET',
        'payload' => ['by' => $_SESSION['lapse'], 'from' => $from, 'to' => $to],
    ],
    'reputation' => [
        'endpoint' => STATISTICS_ENDPOINT . 'brands/'. $brandId .'/survey/reputation',
        'method' => 'GET',
        'payload' => ['from' => $from, 'to' => $to],
    ],
    'answersByScore' => [
        'endpoint' => STATISTICS_ENDPOINT . 'brands/'. $brandId .'/survey/answers-by-score',
        'method' => 'GET',
        'payload' => ['from' => $from, 'to' => $to],
    ],
    'answersByGender' => [
        'endpoint' => STATISTICS_ENDPOINT . 'brands/'. $brandId .'/survey/answers-by-gender',
        'method' => 'GET',
        'payload' => ['from' => $from, 'to' => $to],
    ],
    'answersByCountry' => [
        'endpoint' => STATISTICS_ENDPOINT . 'brands/'. $brandId .'/survey/answers-by-country',
        'method' => 'GET',
        'payload' => ['from' => $from, 'to' => $to],
    ],
    'answersByGeneration' => [
        'endpoint' => STATISTICS_ENDPOINT . 'brands/'. $brandId .'/survey/answers-by-generation',
        'method' => 'GET',
        'payload' => ['from' => $from, 'to' => $to],
    ],
    'answersByCategories' => [
        'endpoint' => STATISTICS_ENDPOINT . 'brands/'. $brandId .'/survey/answers-by-category',
        'method' => 'GET',
        'payload' => ['from' => $from, 'to' => $to, 'lang' => $_SESSION['userLang']],
    ],
    'answersByQuestions' => [
        'endpoint' => STATISTICS_ENDPOINT . 'brands/'. $brandId .'/survey/answers-by-question',
        'method' => 'GET',
        'payload' => ['from' => $from, 'to' => $to, 'lang' => $_SESSION['userLang']],
    ],
    'multiresponseAnswers' => [
        'endpoint' => STATISTICS_ENDPOINT . 'brands/'. $brandId .'/survey/multiresponse-answers',
        'method' => 'GET',
        'payload' => ['from' => $from, 'to' => $to, 'lang' => $_SESSION['userLang']],
    ],
];

$promises = $gateway->sendAsyncRequests($requests);
$reputationStatistics = $gateway->awaitAsyncRequests($promises);

// Score timeline
$scoreTimeline = getResponse($reputationStatistics, 'scoreTimeline');
$template_data['averageScores'] = array_pluck($scoreTimeline, 'score_avg');
$template_data['dates'] = array_pluck($scoreTimeline, 'date');
array_walk($template_data['dates'], 'formatDate');
$template_data['averageScoresSteps'] = createStepsForRates($template_data['averageScores']);

$template_data['reputation_score_limit'] = null;
if (!$_SESSION['chainSearch']) {
    $cutOffScore = getSatisfactionPuntMin($hotel_id);
    $template_data['reputation_score_limit'] = [];
    foreach ($template_data['dates'] as $date){
        $template_data['reputation_score_limit'] [] = $cutOffScore;
    }
}

// Surveys Fulfilled
$reputation = getResponse($reputationStatistics, 'reputation');
$template_data['surveysSent'] = $reputation->sent;
$template_data['surveysFulfilled'] = round($reputation->done * 100 / ($reputation->sent ? $reputation->sent : 1), 2);
$template_data['surveysUnfulfilled'] = 100 - $template_data['surveysFulfilled'];

// Avg Score
$template_data['averageScore'] = $reputation->avg;

// Average Response Time
if ($reputation->avg_timelapse_response) {
    $template_data['averageTimelapseResponse'] = $reputation->avg_timelapse_response . 'h';

    $days = floor($reputation->avg_timelapse_response/24);
    $hours = round(($reputation->avg_timelapse_response/24 - $days) * 24);

    $template_data['parsedAverageTimelapseResponse'] = $days.' ' .$lang['days'] . ' ' . $lang['and'] . ' ' . $hours . ' ' . $lang['hours'];

} else {
    $template_data['averageTimelapseResponse'] = empty($reputation->avg_timelapse_response) ? '-' : $reputation->avg_timelapse_response;
    $template_data['parsedAverageTimelapseResponse'] = '';
}

// Answers By Score
$answersByScore = getResponse($reputationStatistics, 'answersByScore');
$answersScore = [];
array_push($answersScore, computePercentage($answersByScore, 'score', 0, 'total', $reputation->done));
array_push($answersScore, computePercentage($answersByScore, 'score', 1, 'total', $reputation->done));
array_push($answersScore, computePercentage($answersByScore, 'score', 2, 'total', $reputation->done));
array_push($answersScore, computePercentage($answersByScore, 'score', 3, 'total', $reputation->done));
array_push($answersScore, computePercentage($answersByScore, 'score', 4, 'total', $reputation->done));
array_push($answersScore, computePercentage($answersByScore, 'score', 5, 'total', $reputation->done));
array_push($answersScore, computePercentage($answersByScore, 'score', 6, 'total', $reputation->done));
array_push($answersScore, computePercentage($answersByScore, 'score', 7, 'total', $reputation->done));
array_push($answersScore, computePercentage($answersByScore, 'score', 8, 'total', $reputation->done));
array_push($answersScore, computePercentage($answersByScore, 'score', 9, 'total', $reputation->done));
array_push($answersScore, computePercentage($answersByScore, 'score', 10, 'total', $reputation->done));
$template_data['answersByScore'] = $answersScore;

// Answers By Gender
$answersByGender = getResponse($reputationStatistics, 'answersByGender');
$template_data['gendersAvg'] = array_pluck($answersByGender, 'avg');
$gendersLabels = array_pluck($answersByGender, 'gender');
$template_data['gendersLabels'] = array_map(function($gender) use ($lang) { 
    $gender = empty($gender) || $gender == 'undefine' ? 'others' : $gender;

    return $lang[$gender]; 
}, $gendersLabels);

// Answers By Country
$answersByCountry = getResponse($reputationStatistics, 'answersByCountry');
$template_data['countries'] = array_pluck(array_slice($answersByCountry, 0, 5), 'country');
$template_data['answersByCountries'] = array_pluck(array_slice($answersByCountry, 0, 5), 'avg');

// Answers By Generation
$answersByGeneration = getResponse($reputationStatistics, 'answersByGeneration');
$generations = [
    'Mature' => getGenerationStats($answersByGeneration, 'mature'),
    'Baby boomers' => getGenerationStats($answersByGeneration, 'baby boomer'),
    'Gen X' => getGenerationStats($answersByGeneration, 'generation x'),
    'Millenials' => getGenerationStats($answersByGeneration, 'millenial'),
    'Gen Z' => getGenerationStats($answersByGeneration, 'generation z'),
];
asort($generations);
$generations = array_reverse($generations);
$template_data['generationLabels'] = array_keys($generations);
$template_data['generationData'] = array_values($generations);

$categories = getResponse($reputationStatistics, 'answersByCategories');

// Answers by Category
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
$template_data['questions'] = getResponse($reputationStatistics, 'answersByQuestions');

$multiresponseAnswers = getResponse($reputationStatistics, 'multiresponseAnswers');

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
$template_data['distinctBrands'] = array_unique(
    array_merge(
        array_values(array_unique(array_column($template_data['questions'], 'hotel_name'))),
        array_values(array_unique(array_column($multiresponseAnswers, 'hotel_name')))
    )
);

$template_data['satisfactionSurveyProductActive'] = true;
$html = $view->render('views::statistics/reputation', $template_data);
$response->getBody()->write($html);
return $response;

function getGenerationStats($generations, $search) {
    return data_get(array_first($generations, function ($key, $value) use ($search) {
        return $value->generation === $search;
    }), 'avg', 0);
}

function sortByID($object1, $object2) { 
    return $object1->id - $object2->id;
}