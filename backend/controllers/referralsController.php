<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

//descarga el CSV
use Goodby\CSV\Export\Standard\ExporterConfig;
use Goodby\CSV\Export\Standard\Exporter;

//Contenido solo visible si logueado
include RUTA_DIR . LIB.'logueado.php';
hotelStaffLanding ();// Si no esta logueado lo manda a la landing

include_once RUTA_DIR . LIB.'paginacion2.php';
include_once RUTA_DIR . LIB.'ordenacion.php';
include_once RUTA_DIR . LIB.'obtenerdatosHotel.php';


// For front purposes
$currentPage = 'clients-management';
$currentSubPage = 'referrers-management';

// Campo ORDER BY
$pant='rfr'; // Pantalla
if(!empty($_GET['ord'])){
	$order = $_GET['ord'];
	$_SESSION['ord'.$pant] = $_GET['ord'];
	$sort = toggle();
}else if(!empty($_SESSION['ord'.$pant])){
	$order = $_SESSION['ord'.$pant];
	$sort = $_SESSION['ascdesc'];
}else{
	// Orden por defecto
	$order = 'total_spent';
	$sort = 'DESC';
}

$itemsPage = 10;
if (!empty($_GET['search'])){
	$busqueda = mysqli_real_escape_string(conectar(), $_GET['search']);
	//$arrayUsuarios = obtenerUsuarios($_SESSION['h_logueado'], $busqueda);
	$arrayUsuarios = obtenerUsuarios($_SESSION['h_logueado'], $busqueda,$order, $sort, $itemsPage, $pagina); 
}else{
	//$arrayUsuarios = obtenerUsuarios($_SESSION['h_logueado'], '');
	$arrayUsuarios = obtenerUsuarios($_SESSION['h_logueado'], '',$order, $sort, $itemsPage, $pagina);
}

$nombre_hotel_san = string_sanitize(obtenerNombreHotel($_SESSION['h_logueado']));
$monedaHotel = obtenerMonedaHotel($_SESSION['h_logueado']);

//Urls para share de hotelDesk & user
$guidHotel = obtenerGUIDHotel($_SESSION['h_logueado']);

// Datamatch activated verification
$datamatch_activated = checkDatamatchActivated($_SESSION['h_logueado']);

// El gestor de la cadena acaba de cambiar de hotel. Viene de 'chain-management' y solo tien RF
if(!empty($_GET['change'])){
	if($change='ok'){
		$ok = array(true, '2018');
	}
}

//Descarga CSV
if (!empty($_GET['action'])){
    $action = $_GET['action'];
    if ($action === 'downloadcsv'){
        $downloading = true;
        $columnHeaders = array(
            'pms_id',
            'name',
            'email',
            'card_id',
            'fb_friends',
            'locale',
            'birthday',
            'gender',
            'preStay_shares',
            'stay_shares',
            'referrer_name',
            'first_checkin',
            'last_checkin',
            'room number'
        );

        //Config exporter
        $config = new ExporterConfig();
		$config
			->setDelimiter("\t") // Customize delimiter. Default value is comma(,)
			->setEnclosure("")  // Customize enclosure. Default value is double quotation(")// Customize escape character. Default value is backslash(\)
			->setFromCharset('UTF-8') // Customize source encoding. Default value is null.
			->setColumnHeaders($columnHeaders)
		;

        //Get all info
        $export = exportarUsuarios($_SESSION['h_logueado']);

        //Export
        $exporter = new Exporter($config);
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=hotelinking_users.csv');
        $exporter->export('php://output', $export);
        exit;
    }
}
?>