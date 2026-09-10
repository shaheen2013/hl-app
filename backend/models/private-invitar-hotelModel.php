<?php //Miramos si esta definida la variable de control de index.php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}

if (empty($_SESSION['private'])) {
    header('Location: /');
}
include LIB . '/fecha.php';

function updateAffilired($name, $id, $id_hotel, $guid)
{   
    // Remove spaces between words and empty Strings before update because hotel can have 
    // many affilired_hotel separated with commma.
    $name = implode(',', array_filter(array_map('trim', explode(',', $name)), "strlen"));

    $id = mysqli_real_escape_string(conectar(), $id);
    $name = mysqli_real_escape_string(conectar(), $name);
    $id_hotel = mysqli_real_escape_string(conectar(), $id_hotel);
    $guid = mysqli_real_escape_string(conectar(), $guid);
    $sql = "UPDATE hotel_guid SET affilired_hotel='" . $name . "', affilired_id='" . $id . "'
	WHERE id_hotel = '$id_hotel'";

    //Delete cache
    deleteCacheByTag("hotel_affilired_{$guid}");
    deleteCacheByTag("hotel_{$id_hotel}_be_code");
    
    mysqli_query(conectar(), $sql);
}

function insertarInvitacionHotel($email, $token, $quota, $hotelName)
{
    $fecha = dateTimeHoy();
    $sql = "INSERT INTO invitaciones_hotel (email, fecha, token, quota, hotelName)
	VALUES ('" . $email . "', '" . $fecha . "', '" . $token . "', '" . $quota . "', '" . $hotelName . "')";
    mysqli_query(conectar(), $sql);
}

function emailExiste($email)
{
    $email = mysqli_real_escape_string(conectar(), $email);
    $sql = "SELECT
	IFNULL((SELECT id FROM hoteles WHERE email='" . $email . "'),0) AS id_hotel,
	IFNULL((SELECT id FROM cadena WHERE email='" . $email . "'),0) AS id_cadena ";
    $rs = mysqli_query(conectar(), $sql);
    $row = mysqli_fetch_assoc($rs);
    liberar($rs);
    if ($row['id_hotel'] == '0' && $row['id_cadena'] == '0') {
        return false;
    } else {
        return true;
    }
}

function borrarInvitacionAntigua($email)
{
    $sql = "DELETE FROM invitaciones_hotel WHERE email='" . $email . "' ";
    mysqli_query(conectar(), $sql);
}

function actualizarInvitacion($email, $quota, $hotelName)
{
    $sql = "UPDATE invitaciones_hotel SET quota='" . $quota . "', hotelName='" . $hotelName . "'
	WHERE email='" . $email . "' ";
    mysqli_query(conectar(), $sql);
}

function recuperarTokenUrl($email)
{
    $email = mysqli_real_escape_string(conectar(), $email);
    $sql = "SELECT token FROM invitaciones_hotel WHERE email = '" . $email . "'";
    $rs = mysqli_query(conectar(), $sql);
    $row = mysqli_fetch_assoc($rs);
    liberar($rs);
    if (!$row['token']) {
        return false;
    } else {
        return $row['token'];
    }
}

//get all hotels to show in private
//HACK: right now we get pushtech api key/secret as key/secret
//this will not scale if we have +1 API keys
//TODO: need to refactor the way we store hotel products in DB in a normalized way
//TODO: remove permisos_hotels table and create a hotel_products, hotel_products_external_apis table to do normalization with existing external_apis table
//TODO: similar to (cadena, hoteles, and cadena_hotel at the moment)
function getHotels($searchInput = '')
{
    $con = conectar(1);

        $sql = "SELECT
            hoteles.email,
            hoteles.hotelName,
            hoteles.iframe_style,
            hoteles.bypass_active,
            hotel_guid.id_hotel,
            hotel_guid.guid,
            hotel_guid.affilired_hotel,
            hotel_guid.affilired_id,
            products.producto,
            products.id as product_id,
            brand_product.active,
            brands.id as brand_id,
            brands.parent_id as account_id,
            activated
            FROM hoteles
            LEFT JOIN hotel_guid ON hoteles.id = hotel_guid.id_hotel
            LEFT JOIN brands ON hoteles.id = brands.hotel_id
            LEFT JOIN brand_product ON brands.id = brand_product.brand_id 
            LEFT JOIN products ON products.id = brand_product.product_id";


            if ($searchInput) {
                // Prevent sql injection
                $searchInput = mysqli_real_escape_string($con, $searchInput);
                // Add to query the searchInput
                $sql = $sql . "\nWHERE brands.parent_id = '" . $searchInput . "'";
                $sql = $sql . "\n OR brands.id = '" . $searchInput . "'";
                $sql = $sql . "\n OR hoteles.hotelName like '%" . $searchInput . "%'";
                $sql = $sql . "\n OR hoteles.email like '%" . $searchInput . "%'";
            }

    $row = lecturaArray($sql, $con);

    return $row;
}

function changeHotelTemplate($id, $template)
{
    $id = mysqli_real_escape_string(conectar(), $id);
    $template = mysqli_real_escape_string(conectar(), $template);
    $sql = "UPDATE hoteles SET iframe_style='$template' WHERE id='$id'";
    mysqli_query(conectar(), $sql);

    //Delete cache
    deleteCacheByTag('hotel_profile_' . $id);

    return true;
}

//FX para inicializar producto hotel. En caso de haber sido actualizado anteriormente no se modifican los datos existentes
//    $producto: review, satisfaction
//    $id_hotel : id del hotel
//    $diasEnvio: dias para el enío del revire / satisfaction (según $producto)
//    $puntMin : puntuación mínima (solo para satisfaction)
function inicializarProductoHotelDefault($producto, $id_hotel, $diasEnvio, $puntMin = '')
{
    $con = conectar();
    $diasEnvio = mysqli_real_escape_string($con, $diasEnvio);
    $puntMin = mysqli_real_escape_string($con, $puntMin);

    $sql = "INSERT IGNORE hotel_" . $producto . " (id_hotel, diasEnvio ";
    if ($producto == 'satisfaction') {
        $sql .= ", puntMin) VALUES ($id_hotel, '" . $diasEnvio . "', '" . $puntMin . "') ";
    } else if ($producto == 'review') {
        $sql .= ") VALUES ($id_hotel, '" . $diasEnvio . "') ";
    }
    escritura($sql, $con);
}

// FX para actualizar los productos contratados por el hotel
// Review:
//        0 no tiene review
//        1 tiene view contratdo
//        2 tiene review contratado pero con encuesta de satisfacción previa
function actualizarProductoHotel($id_hotel, $brand_id, $product, $on)
{
    $con = conectar();
    $id_hotel = mysqli_real_escape_string($con, $id_hotel);
    $product = mysqli_real_escape_string($con, $product);
    $on = mysqli_real_escape_string($con, $on);

    if ($product == 'pushtech' || $product == 'datamatch') {
        if ($product == 'datamatch') {
            deleteCacheByKey("Datamatch_activated_$brand_id");
        } else if ($product == 'pushtech' && isIntegrationEnabled()) {
            changeActiveStatusPushtechCredentials($brand_id, $on);
        }
        desconectar($con);
    }
    //Delete cache
    $tags = array('hotel_review_' . $id_hotel, 'hotel_satisfaction_' . $id_hotel, 'hotel_wifi_offers_' . $id_hotel, 'pushtech_' . $id_hotel);
    deleteCacheByTags($tags);

    //delete key used in stay-share
    deleteCacheByKey("getBrandProducts" . $brand_id);
    deleteCacheByKey('hotel_wifi_offers_' . $id_hotel);
}

function setBypassActive($id_hotel, $bypass_status)
{
    $sql = "UPDATE hoteles SET bypass_active=$bypass_status WHERE id = $id_hotel";
    escritura($sql);
    $tag = 'hotel_wifi_offers_' . $id_hotel;
    deleteCacheByTag($tag);
    return true;
}

#### NEW PRODUCTS

//get all products
function getProducts()
{
    $con = conectar(1);
    $sql = "SELECT * from products";
    return lecturaArray($sql, $con);
}

function configureNewProduct($hotels_id, $product_name, $active)
{
    if ($active == 1 && $product_name == 'loyalty') {
        configureLoyaltyProduct($hotels_id);
    }
    if ($product_name == 'not_hotel') {
        configureNotHotelForBrandProduct($hotels_id, $active);
    }
}

function configureLoyaltyProduct($hotels_id)
{
    $con = conectar(1);
    $sql = "SELECT loyalty_emails FROM hoteles  WHERE id=$hotels_id ";
    $loyaltyEmail = lectura($sql, $con);

    if ($loyaltyEmail['loyalty_emails'] == null || $loyaltyEmail['loyalty_emails'] == "") {
        $sql = "UPDATE hoteles SET loyalty_emails=(SELECT warning_email FROM hotel_satisfaction WHERE id_hotel= $hotels_id)WHERE id=$hotels_id ";
        escritura($sql);
    }
}

function configureNotHotelForBrandProduct($hotel_id, $active)
{
    include MODEL . 'dynamic-contentModel.php';
    if ($active) {
        setBrandCustomContent($hotel_id, null, 'first_eprivacy_page', 'restrictive_not_hotel', 1, 'default');
        setBrandCustomContent($hotel_id, null, 'second_eprivacy_page', 'restrictive_not_hotel', 1, 'default');
        setBrandCustomContent($hotel_id, null, 'legal_text', 'restrictive_not_hotel', 1, 'default');
        setBrandCaptivePortalType($hotel_id, 1);
    } else {
        setBrandCustomContent($hotel_id, null, 'first_eprivacy_page', 'classic', 1, 'default');
        setBrandCustomContent($hotel_id, null, 'second_eprivacy_page', 'classic', 1, 'default');
        setBrandCustomContent($hotel_id, null, 'legal_text', 'classic', 1, 'default');
        setBrandCaptivePortalType($hotel_id, 0);
    }
}

function setBrandCaptivePortalType($hotel_id, $restricted_portal)
{
    global $log;
    $sql = "INSERT INTO brand_eprivacy (hotel_id, restricted_portal) VALUES ($hotel_id,restricted_portal=$restricted_portal) ON DUPLICATE KEY UPDATE restricted_portal = '$restricted_portal'";
    $log->debug($sql);
    escritura($sql);
}

// ==================== INTEGRATIONS ================
include LIB . 'hotelinking_integrations.php';
include LIB . 'portal_pro.php';
