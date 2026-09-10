<?php //Miramos si esta definida la variable de control de index.php
use GuzzleHttp\Psr7\Query;

if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}

//Contenido solo visible si logueado
include LIB . 'logueado.php';
hotelStaffLanding(); // Si no esta logueado lo manda a la landing

// For front purposes
$currentPage = 'satisfaction-list';
$currentSubPage = 'satisfaction-list';

require_once __DIR__ . '/../src/Services/Connections/ApiGatewayConnection.php';
include_once RUTA_DIR . LIB . 'utils.php';
include_once RUTA_DIR . LIB . 'ordenacion.php';
include LIB . 'obtenerdatosHotel.php';
include LIB . 'pager.php';
include_once MODEL . 'hotel-profile-langsModel.php';

// Campo ORDER BY
$pant = 'stf-lst'; // Pantalla
if (!empty($_GET['ord'])) {
    $order = $_GET['ord'];
    $_SESSION['ord' . $pant] = $_GET['ord'];
    $sort = toggle();
} elseif (!empty($_SESSION['ord' . $pant])) {
    $order = $_SESSION['ord' . $pant];
    $sort = $_SESSION['ascdesc'];
} else {
    // Orden por defecto
    $order = 'fecha_update';
    $sort = 'DESC';
}

$requestUri = parse_url($_SERVER['REQUEST_URI']);
$queryString = Query::parse($requestUri['query'] ?? '');
$pathUrl = rtrim($requestUri['path'], '/') . '/';

unset($queryString['ord']);
$currentPageUrl = $pathUrl . (!empty($queryString) ? '?' . Query::build($queryString) : '');
$symbol = empty($queryString) ? '?' : '&';

$urlSearch = array_get($_GET, 'search'); //Url param
$urlDataSearchStart = array_get($_GET, 'start'); //Url param
$urlDataSearchEnd = array_get($_GET, 'end'); //Url param
$page = array_get($_GET, 'pag', 1);
$itemsPage = array_get($_GET, 'per_page', 10);

$brandID = array_get($_GET, 'chain-hotel') == 'chain' ? $_SESSION['loggedParentBrandID'] : $_SESSION['loggedBrandID'];
$satisfactionParams = ["search" => $urlSearch, "from" => $urlDataSearchStart, "to" => $urlDataSearchEnd, "order" => $order, "sort" => $sort, "page" => $page, "itemsPerPage" => $itemsPage];

$gateway = new ApiGatewayConnection();

if (isset($_POST['update_user_survey'])) {

    $incidentsReviewed = array_get($_POST, 'incidents_reviewed') === "on";
    $userSurveyId = array_get($_POST, 'user_survey_id_hidden');

    try {
        $response = $gateway->sendRequest(['incidents_reviewed' => array_get($_POST, 'incidents_reviewed') === "on"], HOTELINKING_ENDPOINT . 'brands/' . array_get($_SESSION, 'loggedBrandID') . '/surveys/' . $userSurveyId, 'PUT');
    } catch (Exception $e) {
        global $log;
        $log->error("Error updating user survey", [$e]);
    } finally {
        header("Location: satisfaction-list");
        exit();
    }
}

if (array_get($_POST, 'new_comment')) {

    $incident_text = array_get($_POST, 'new_comment');
    $user_satisfaction_id = array_get($_POST, 'user_survey_id_hidden');

    try {
        $response = $gateway->sendRequest(['incident_text' => $incident_text, "hotel_staff_id" => array_get($_SESSION, 'staff_logueado')], HOTELINKING_ENDPOINT . 'satisfactions/' . $user_satisfaction_id . '/incidents', 'POST');
    } catch (Exception $e) {
        global $log;
        $log->error("Error creating new comment incident", [$e]);
    } finally {
        header("Location: satisfaction-list");
        exit();
    }
}

try {
    $survey = json_decode($gateway->sendRequest($satisfactionParams, HOTELINKING_ENDPOINT . 'brands/' . $brandID . '/surveys/satisfactions', 'GET'));
    $questions = json_decode($gateway->sendRequest(null, HOTELINKING_ENDPOINT . 'brands/' . array_get($_SESSION, 'loggedBrandID') . '/survey-questions', 'GET'));
    $satisfactionConfig = json_decode($gateway->sendRequest(null, HOTELINKING_ENDPOINT . "brands/" . array_get($_SESSION, 'loggedBrandID') . "/products/" . getIdByProductName('satisfaction') . "/configuration", 'GET'));
    $sendingDays = getSendingDaysReview(array_get($_SESSION, 'h_logueado', array_get($_SESSION, 'staff_id_hotel')));

    $satisfactions = $survey->data;
    $totalSatisfactions = $survey->pagination->total;
    $averageScore = $survey->pagination->averageScore;
    $pages = $survey->pagination->totalPages;
} catch (Exception $e) {
    global $log;
    $log->error("Error getting Satisfaction List", [$e]);
    $ok =  array(false, '4067');
    $satisfactionConfig = null;
    $averageScore = 0;
    $totalSatisfactions = 0;
    $sendingDays = 0;
}

$numberPages = ceil($totalSatisfactions / $itemsPage);
$currentLang = $_SESSION['userNavLang'] ?? 'en';
$availableLangs = getLangList('content');
$paginate = getPager($numberPages, $page);
