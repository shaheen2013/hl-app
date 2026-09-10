<?php //Miramos si esta definida la variable de control de index.php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}

//Contenido solo visible si logueado
include LIB . 'logueado.php';
hotelStaffLanding();// Si no esta logueado lo manda a la landing
include LIB . 'isIndependent.php';
include LIB . 'protocolManagement.php';

$currentPage = 'hotel-management';
$currentSubPage = 'hotel-protocol-management';
$brandId = $_SESSION['hotel']['brand_id'] ?? null;
$parentId = $_SESSION['hotel']['parent_id'] ?? null;
$formAction = $urlTree['hotel-protocol-management'];

updateProtocol($brandId, $formAction);

$protocols = getBrandProtocols($brandId);
