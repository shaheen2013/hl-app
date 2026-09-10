<?php
//Hotelinking mandatory libraries and methods
include_once 'librerias.php';
include_once APP . 'Services/Connections/ApiGatewayConnection.php';


$hotel_id = array_get($_POST, 'id_hotel');
$search_chain = array_get($_POST, 'search_chain');
$hideUnsubscribed = array_get($_POST, 'hide_unsubscribed');
$phone_active = array_get($_POST, 'phone_active');
$hotel_id = array_get($_POST, 'id_hotel');
$commercial_profile = array_get($_POST, 'commercial_profile');
$gateway = new ApiGatewayConnection();

if (!isset($_POST['export-type']) && (array_get($_POST, 'testing') == 1 || (isset($_POST['id_hotel']) && $_POST['id_hotel'] === array_get($_SESSION, 'h_logueado', array_get($_SESSION, 'staff_id_hotel'))))) {
    header('Content-Type: application/json');
    /*
        We send the filter parameters. 
        Only subscribers will be retrieved if selected from the front dropdown or configured from the private 
        to hide the unsubscribed.
    */
    $brandId = $search_chain ? $_SESSION['loggedParentBrandID'] : $_SESSION['loggedBrandID'];
    $clientParams = [
        "hosted" => !isset($_POST['hosted']) || $_POST['hosted'] == 'true' ? 1 : 0,
        "subscribed" => !isset($_POST['subscribed']) || $_POST['subscribed'] == 'true' || $hideUnsubscribed ? 1 : 0,
        "from" => isset($_POST['from']) ? $_POST['from'] : null, 
        "to" => isset($_POST['to']) ? $_POST['to'] : null,
        "search_text" => array_get($_POST, 'search.value') != "" && !array_get($_POST, 'export_all') ? 
            addslashes(array_get($_POST, 'search.value')) : 
            null,
        "search_type" => isset($_POST['search_type']) ? $_POST['search_type'] : null,
        "page"  => $_POST['start']/$_POST['length'] + 1,
        "per_page" => $_POST['length'],
        "sort_field" => array_get($_POST, 'columns.' . array_get($_POST, 'order.0.column') . '.data'),
        "sort_order" => array_get($_POST,'order.0.dir'),
        "search_by"  => array_get($_POST,'search_by'),
    ];

    $clients = json_decode($gateway->sendRequest($clientParams, HOTELINKING_ENDPOINT . 'brands/' . $brandId . '/clients', 'GET'));
    $rows = $array = json_decode(json_encode($clients->data), true);
    $total = $clients->meta->total;

    //return the object that dataTables expects
    $result = array(
        "draw" => intval(array_get($_POST, 'draw')),
        "recordsTotal" => intval($total),
        "recordsFiltered" => intval($total),
        'data' => $rows,
    );

    echo json_encode($result);
} else {
    global $log;
    $emailList = array_get($_POST, 'emails');

    $brandId = $search_chain ? (int) $_SESSION['loggedParentBrandID'] : (int) $_SESSION['loggedBrandID'];

    if ($brandId) {
        if ($_POST['export-type'] == 'export-query')
        {
            $clientParams = [
                "hosted"            => !isset($_POST['hosted']) || $_POST['hosted'] == 'true' ? true : false,
                "subscribed"        => !isset($_POST['subscribed']) || $_POST['subscribed'] == 'true'|| $hideUnsubscribed ? 1 : 0,
                "from"              => isset($_POST['from']) ? $_POST['from'] : null, 
                "to"                => isset($_POST['to']) ? $_POST['to'] : null,
                "search_text"       => array_get($_POST, 'search.value') != "" && !array_get($_POST, 'export_all') ? addslashes(array_get($_POST, 'search.value')) : null,
                "sort_field"        => array_get($_POST, 'columns.' . array_get($_POST, 'order.0.column') . '.data'),
                "sort_order"        => array_get($_POST,'order.0.dir'),
                "search_by"         => array_get($_POST,'search_by') 
            ];
        } 
    
        $clientParams['emails'] = array_values(
            array_filter(
                array_map(
                    'trim', 
                    explode(",", $emailList)
                ),
                'strlen'
            )
        );
        
        $clientParams['phone_active'] = $phone_active ? true : false;
        $clientParams['commercial_profile'] = $commercial_profile ? true : false;

        // Emiting event "generate_export_csv"
        try {
            $gateway->sendRequest($clientParams, HOTELINKING_ENDPOINT . 'brands/' . $brandId . '/clients/report', 'POST');
            http_response_code(200);
        } catch (\Exception $e) { 
            $log->error("Client export error", ["Exception" => $e, "brand_id" => $brandId, "ClientParams" =>  $clientParams]);
            http_response_code(400);
        }
    } else {
        http_response_code(400);
    }
    
}