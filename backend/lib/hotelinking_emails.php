<?php
// Librería que contiene todos los métodos de HL para la plataforma de emails (HLE)

//Cuando creamos un hotel nuevo también lo insertamos en la BD de emails
//TO_DELETE (not used with refactor email platform)
function HLEcrearHotel($id_hotel, $name, $email, $logo)
{
    $rutaLogo = SECURE_BASE_PATH . DIR_IMG_FICHA_HOTEL . $id_hotel . '/logo/med_' . $logo;
    $sql = "INSERT INTO hotels (id_hotelinking, name, email, logo) VALUES ('" . $id_hotel . "', '" . $name . "', '" . $email . "', '" . $rutaLogo . "')";
    escritura($sql, '', true, 2);
}

function actualizarDatosHotelPlataformaEmails($id_hotel, $key, $value)
{
    if ($key == 'logo')
        $value = SECURE_BASE_PATH . DIR_IMG_FICHA_HOTEL . $id_hotel . '/logo/med_' . $value;

    $sql = "UPDATE hotels SET $key='" . $value . "' WHERE id_hotelinking=$id_hotel ";
    escritura($sql, '', true, 2);
}

//FX para obtener el id de hotel de emails a partir del email de HL del hotel
function obtenerIdEmailsIdHotelHL($id_hotel)
{
    //Get from cache
    $cacheName = 'obtenerIdEmailsIdHotelHL_' . $id_hotel;
    $cache = getFromCache($cacheName);

    if (!$cache) {
        $sql = "SELECT hotels.id AS hotel_id FROM hotels WHERE hotels.id_hotelinking=$id_hotel ";
        $con = conectar(2);
        $row = lectura($sql, $con);

        if ($row) {
            setToCache($cacheName, $row, 31536000);
        }
    } else {
        $row = $cache->get();
    }
    return ($row['hotel_id']);
}

function obtenerProductosHotel($brandId)
{
    $con = conectar();
    $sql = "SELECT * FROM brand_product 
        LEFT JOIN products ON brand_product.product_id = products.id 
        WHERE brand_id=$brandId AND active=1";

    $brandPermissions = lecturaArray($sql, $con, true);
    return ($brandPermissions);
}

function getBrandProductActive($brandProducts, $productName)
{
    return array_first($brandProducts, function ($key, $brandProduct) use ($productName){
        return $brandProduct['producto'] == $productName;
    });
    
}
