<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}


function getHotelLoyaltyConfig($hotel_id){
    $sql = "SELECT stay_time, loyalty_min_visits, loyalty_emails, loyalty_alerts FROM hoteles WHERE id = $hotel_id";
    return lectura($sql);
}

function saveHotelLoyaltyConfig($hotel_id, $loyalty_emails, $loyalty_alert){
    $con = conectar();
    global $log;
    $hotel_id = mysqli_real_escape_string($con, $hotel_id);
    $loyalty_emails = mysqli_real_escape_string($con, $loyalty_emails);
    $loyalty_alert = mysqli_real_escape_string($con, $loyalty_alert);

    $sql = "UPDATE hoteles SET loyalty_emails= '$loyalty_emails', loyalty_alerts=$loyalty_alert WHERE id = $hotel_id ";
    $log->debug($sql);
    return escritura($sql, $con);

}
//function getHotelSubProducts($product_name){
//    $con = conectar();
//    $product_name = mysqli_real_escape_string($con, $product_name);
//
//    $sql = "SELECT name FROM sub_products WHERE product_id = (SELECT id FROM products WHERE producto = '$product_name') ";
//
//    return lecturaArray($sql);
//}
//
//function getSubProducts($hotel_id, $currentProductPage){
//    global $log;
//    $con = conectar();
//    $currentProductPage = mysqli_real_escape_string($con, $currentProductPage);
//
//    $sql = "SELECT sub_products.name, value, active FROM hotel_sub_products
//            left join sub_products on sub_products.id =   hotel_sub_products.sub_product_id
//            WHERE product_id = (SELECT id FROM products WHERE producto = '$currentProductPage') ";
//    $log->debug($sql);
//    return lecturaArray($sql);
//}
//
//function saveSubProduct($hotel_id, $product_name, $sub_product_name, $sub_product_value){
//    global $log;
//    $con = conectar();
//    $hotel_id = mysqli_real_escape_string($con, $hotel_id);
//    $product_name = mysqli_real_escape_string($con, $product_name);
//    $sub_product_name = mysqli_real_escape_string($con, $sub_product_name);
//    $sub_product_value = mysqli_real_escape_string($con, $sub_product_value);
//
//
//    $sql = "INSERT INTO hotel_sub_products (hotel_id,sub_product_id,value) VALUES
//            ($hotel_id,
//            (SELECT id FROM sub_products WHERE name = '$sub_product_name' and product_id = (SELECT id FROM products WHERE producto = '$product_name')),
//            '$sub_product_value')
//            ON DUPLICATE KEY UPDATE value='$sub_product_value'";
//    $log->debug($sql);
//    return escritura($sql, $con);
//
//}




?>