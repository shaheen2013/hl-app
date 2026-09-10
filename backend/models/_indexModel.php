<?php
//cache
include_once __DIR__ . '/../' . LIB . 'cache.php';
// FX para conectar la base de datos
//$con: 0 - BD hotelinking
//      1 - BD hotelinking Read replica
//      2 - BD emails / reviews
function conectar($con = 0)
{
    if ($con == 0) {
        //Conexión default de lectura y escritura (Todos los permisos)
        $link_conn = mysqli_connect(HOST, USER, PASS, DB, DB_PORT) or die("ERROR BD 1" . mysqli_error($con));
    } elseif ($con == 1) {
        //Conexión Read Replica (Lectura + funciones)
        $link_conn = mysqli_connect(HOST_RR, USER_RR, PASS_RR, DB_RR, DB_RR_PORT) or die("ERROR BD_RR" . mysqli_error($con));
    } elseif ($con == 2) {
        //Conexión
        $link_conn = mysqli_connect(HOST_EMAILS, USER_EMAILS, PASS_EMAILS, DB_EMAILS, EMAILS_PORT) or die("ERROR BD_EMAILS" . mysqli_error($con));
    }

    mysqli_set_charset($link_conn, 'utf8');

    return $link_conn;
}

//función para liberar consulta
function liberar($rs)
{
    if ($rs) {
        mysqli_free_result($rs);
    }
}

//función para desconectar la base de datos
function desconectar($link_conn = '')
{
    global $log;

    if (!empty($link_conn)) {
        try {
            mysqli_close($link_conn);
        } catch (Exception $e) {
            $log->error("Error closing connection", [$e]);
        }
    }
}

//Fx para escapar string para un solo dato
//$bd : base de datos
function sqlEscape($dato, $con = '', $close = true, $bd = 1)
{
    empty($con) ? $con = conectar($bd) : $con = $con;
    $dato = mysqli_real_escape_string($con, $dato);
    if ($close) {
        mysqli_close($con);
    }

    return $dato;
}

function escapeArray($array, $con = '', $close = true, $bd = 1)
{
    global $log;
    empty($con) ? $con = conectar($bd) : $con = $con;
    foreach ($array as $key => $value) {
        if (is_array($value)) {
            // error_log('Escape array error for ' . json_encode($value));
            $escapedArray[$key] = $value;
        } else {
            $escapedArray[$key] = mysqli_real_escape_string($con, $value);
        }
    }
    if ($close) {
        mysqli_close($con);
    }

    return $escapedArray;
}

// $sql : query
// $bd : 1 read replica - 0 no read replica...
// $con : conexión. Si nos pasan la conexión no la creamos.
// $close : debemos cerrar la conexión?
function lectura($sql, $con = '', $close = true, $bd = 1)
{

    // For debug logging
    // $mark = new ExecutionTime();
    // $mark->start();
    global $log;

    empty($con) ? $con = conectar($bd) : $con = $con;

    $rs = mysqli_query($con, $sql);

    if ($rs === false) {
        if (ENV !== 'test') {
            //Se ha producido un error. Enviamos info a email de errores
            include_once RUTA_DIR . LIB . 'errores.php';
            $log->error('lectura error', $_SESSION);
            errorBD(__FUNCTION__, $sql, $bd, mysqli_error($con));
            $row = null;
        } else {
            //    echo mysqli_error($con);
            throw new Exception('Sql error ' . mysqli_error($con));
            //    exit;
        }
    } else {
        $row = mysqli_fetch_assoc($rs);
        liberar($rs);
    }

    if ($close) {
        mysqli_close($con);
    }

    return $row;
}

function lecturaArray($sql, $con = '', $close = true, $bd = 1)
{

    // For debug logging
    // $mark = new ExecutionTime();
    // $mark->start();
    // global $log;

    // $log->debug($sql);

    $data = array();

    empty($con) ? $con = conectar($bd) : $con = $con;

    $rs = mysqli_query($con, $sql);

    if ($rs === false) {
        if (ENV !== 'test') {
            //Se ha producido un error. Enviamos info a email de errores
            include_once RUTA_DIR . LIB . 'errores.php';
            errorBD(__FUNCTION__, $sql, $bd, mysqli_error($con));
            $row = null;
        } else {
            echo mysqli_error($con);
            exit;
        }
        ;
    } else {
        while ($row = mysqli_fetch_assoc($rs)) {
            $data[] = $row;
        }
        liberar($rs);
    }

    if ($close) {
        mysqli_close($con);
    }

    return $data;
}

function startTransaction($con = '', $bd = 0)
{
    empty($con) ? $con = conectar($bd) : $con = $con;
    mysqli_query($con, "SET AUTOCOMMIT=0");
    mysqli_query($con, "START TRANSACTION");
}

function commitTransaction($con = '', $bd = 0)
{
    empty($con) ? $con = conectar($bd) : $con = $con;
    mysqli_query($con, "COMMIT");
}

function rollbackTransaction($con = '', $bd = 0)
{
    empty($con) ? $con = conectar($bd) : $con = $con;
    $rs = mysqli_query($con, "ROLLBACK");
}

function escritura($sql, $con = '', $close = true, $bd = 0)
{

    // For debug logging
    // $mark = new ExecutionTime();
    // $mark->start();

    empty($con) ? $con = conectar($bd) : $con = $con;

    $rs = mysqli_query($con, $sql);

    if ($rs === false) {
        // //Se ha producido un error. Enviamos info a email de errores
        // include_once RUTA_DIR . LIB . 'errores.php';
        // errorBD(__FUNCTION__, $sql, $bd, mysqli_error($con));
        // // throw new ErrorException('Could not write to database');

        if (ENV !== 'test') {
            //Se ha producido un error. Enviamos info a email de errores
            include_once RUTA_DIR . LIB . 'errores.php';
            errorBD(__FUNCTION__, $sql, $bd, mysqli_error($con));
        // $row = NULL;
        } else {
            // TODO: mysqli_error($con);
            global $log;
            $log->error("SQL error", [
                "query" => $sql,
                "error" => mysqli_error($con)
            ]);
            throw new Exception('Sql error ' . mysqli_error($con));

            // return mysqli_error($con);
            //            global $log;
            //            $log->addError(__FUNCTION__, array('query' => $sql, 'error' => mysqli_error($con)));
            // $mark->end();
            // return false;
        };
    }
    $id = mysqli_insert_id($con);

    if ($close) {
        mysqli_close($con);
    }

    return $id;
}

function hotelDeCadena($id_hotel)
{
    //Get from cache
    // $cache = getFromCache('hotelDeCadena_' . $id_hotel);

    // if (!$cache) {
    $con = conectar(1);
    $id_hotel = mysqli_real_escape_string($con, $id_hotel);
    $sql = "SELECT COUNT(id) AS n FROM cadena_hotel WHERE id_hotel='" . $id_hotel . "' ";
    $row = lectura($sql, $con);
    // if ($row){
    //     setToCache('hotelDeCadena_' . $id_hotel, $row, 31536000);
    // }
    // desconectar($con);
    // } else {
    //     $row = $cache->get();
    // }

    if ($row['n'] == 0) {
        return false;
    } else {
        return true;
    }
}

function hotelIdCadena($id_hotel)
{
    //Get from cache
    $cache = null;//getFromCache('hotelIdCadena_' . $id_hotel);

    if (!$cache) {
        $con = conectar(1);
        $id_hotel = mysqli_real_escape_string($con, $id_hotel ?? "");
        $sql = "SELECT id_cadena FROM cadena_hotel WHERE id_hotel='" . $id_hotel . "' ";
        $row = lectura($sql, $con, true);

        // if ($row) {
        //     setToCache('hotelIdCadena_' . $id_hotel, $row, 31536000);
        // }
    } else {
        $row = $cache->get();
    }

    return !empty($row) ? $row['id_cadena'] : null;
}

function getChainHotelIDsForHotelID($id_hotel)
{
    //Get from cache
    $cache = getFromCache('chainHotelsForHotel_' . $id_hotel);
    if (!$cache) {
        $id_chain = hotelIdCadena($id_hotel);
        $con = conectar(1);
        $id_hotel = mysqli_real_escape_string($con, $id_hotel);

        $sql = "SELECT id_hotel FROM cadena_hotel WHERE id_cadena ='$id_chain'";
        $row = lecturaArray($sql, $con);

        if ($row) {
            setToCache('chainHotelsForHotel_' . $id_hotel, $row, 31536000);
        }
    } else {
        $row = $cache->get();
    }

    return $row;
}

function getHotelIDsForChainID($id_chain)
{
    //Get from cache
    $cache = getFromCache('hotelIDsForChainID_' . $id_chain);

    if (!$cache) {
        $con = conectar(1);
        $id_chain = mysqli_real_escape_string($con, $id_chain);
        $sql = "SELECT id_hotel FROM cadena_hotel WHERE id_cadena = $id_chain";
        $row = lecturaArray($sql, $con);
        if ($row) {
            $hotel_ids = array_pluck($row, 'id_hotel');
        }

        setToCache('hotelIDsForChainID_' . $id_chain, $hotel_ids, 31536000);
    } else {
        $hotel_ids = $cache->get();
    }

    return $hotel_ids;
}

function getHotelID()
{
    $hotel_id = data_get($_SESSION, 'hotel.id', null);
    if ($hotel_id) {
        return $hotel_id;
    } else {
        // TODO: logout session back to login page ?
    }
}

function isChain()
{
    return isset($_SESSION['c_logueado']);
}

function chainID()
{
    return data_get($_SESSION, 'c_logueado', null);
}

function hotelHasProduct($product)
{
    return (isset($_SESSION['permisos'][$product]) && $_SESSION['permisos'][$product] === '1') ? true : false;
}

function array_insert(&$array, $position, $insert_array)
{
    $first_array = array_splice($array, 0, $position);
    $array = array_merge($first_array, $insert_array, $array);
}

function imageSize($size, $image_url)
{
    if (!empty($image_url)) {
        return str_replace(['original', 'large', 'medium', 'small'], $size, $image_url);
    } else {
        return null;
    }
}

// DATAMATCH
function isIntegrationEnabled()
{
    return defined('INTEGRATIONS_ENABLE') ? INTEGRATIONS_ENABLE : false;
}

function checkDatamatchActivated($hotel_id)
{
    // Check if Datamatch is permited for this enviroment and if hotel_id is not null
    if (isIntegrationEnabled() && !is_null($hotel_id)) {
        include_once RUTA_DIR . LIB . 'obtenerdatosHotel.php';

        $brand = getHotelBrand($hotel_id);
        $cacheName = "Datamatch_activated_" . $brand['id'];
        $cache = getFromCache($cacheName);
        
        if (!$cache) {
            $con = conectar(1);
            $sql = "SELECT active
                    FROM brand_product
                    INNER JOIN products ON products.id = brand_product.product_id
                    WHERE brand_id = " . $brand['id'] . " AND products.producto = 'datamatch'";
            $row = lectura($sql, $con);

            if ($row) {
                setToCache($cacheName, $row, 31536000);
            }
        } else {
            $row = $cache->get();
        }

        // Check if is permited
        $datamatch_active = array_get($row, 'active', null);
        return $datamatch_active ? ($datamatch_active == 1 ? true : false) : false;
    }
    return false;
}

//function that returns the fields of the hotel_rooms table for the hotel
//field list are room / guest / premium
function getBrandAccessCodes($brandID, $accessType = "room")
{
    $cacheName = 'brand_access' . $brandID . '_' . $accessType;
    $cache = getFromCache($cacheName);

    if (!$cache) {
        require_once __DIR__ . '/../src/Services/Connections/ApiGatewayConnection.php';

        try {
            $gateway = new ApiGatewayConnection();
            $brandAccess = json_decode(
                $gateway->sendRequest(
                    ["type" => $accessType], 
                    HOTELINKING_ENDPOINT . 'brands/' . $brandID . '/access', 'GET'
                )
            );

            setToCache($cacheName, $brandAccess, 31536000);
        } catch (Exception $e) {
            global $log;
            $log->error("Error getting brand acces list", ["brand" => $brandID, "accessType" => $accessType]);
            
            return [];
        }
    } else {
        $brandAccess = $cache->get();
    }

    return $brandAccess->codes ?? [];
}
