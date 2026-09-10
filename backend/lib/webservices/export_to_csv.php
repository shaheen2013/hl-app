<?php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

include_once 'librerias.php';// Librerias básicas

function exportarUsuarios($id_hotel){
    $id_hotel = mysqli_real_escape_string(conectar(1), $id_hotel);
    $sql = "SELECT DISTINCT users.nombre, users.email, fb_friends,  
            user_facebook.locale, user_facebook.birthday, user_facebook.gender AS fb_gender, 
            (SELECT COUNT(user_shares.id) 
            FROM user_shares WHERE id_usuario=users.id AND id_hotel=$id_hotel AND id_tipo_share='2') AS shares_prestay, 
            (SELECT COUNT(user_shares.id) 
            FROM user_shares WHERE id_usuario=users.id AND id_hotel=$id_hotel AND id_tipo_share='3') AS shares_stay 
            FROM user_hotels
            LEFT JOIN users ON users.id=user_hotels.id_usuario 
            LEFT JOIN user_shares ON user_shares.id_usuario=users.id  
            LEFT JOIN user_facebook ON users.id = user_facebook.id_usuario,
            WHERE user_hotels.id_hotel=$id_hotel";
    //$rs = mysqli_query (conectar(1), $sql) or die (mysqli_error(conectar(1)));
    
    $rows = lecturaArray($sql);
    /*$rows = array();
    while($r = mysqli_fetch_assoc($rs)) {
        $rows[] = $r;
    }*/
    return $rows;
}

//descarga el CSV
use Goodby\CSV\Export\Standard\ExporterConfig;
use Goodby\CSV\Export\Standard\Exporter;

//Config exporter
$config = new ExporterConfig();
$config
    ->setDelimiter("\t") // Customize delimiter. Default value is comma(,)
    ->setEnclosure("'")  // Customize enclosure. Default value is double quotation(")
    ->setEscape("\\")    // Customize escape character. Default value is backslash(\)
    ->setToCharset('SJIS-win') // Customize file encoding. Default value is null, no converting.
    ->setFromCharset('UTF-8') // Customize source encoding. Default value is null.
;

//Get all info
$export = exportarUsuarios($_SESSION['h_logueado']);

//Export
$exporter = new Exporter($config);

header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=hotelinking_usersxs.csv");
// Disable caching
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies
$exporter->export('php://output', $export);
