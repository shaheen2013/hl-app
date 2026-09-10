<?php //Miramos si esta definida la variable de control de index.php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}

//Contenido solo visible si logueado
include LIB . 'logueado.php';
hotelStaffLanding();// Si no esta logueado lo manda a la landing
include LIB . 'isIndependent.php';
include LIB . 'cadenaPantalla.php';
include LIB . 'protocolManagement.php';

$currentPage = 'chain-management';
$currentSubPage = 'chain-protocol-management';
$brandId = $_SESSION['chain']['brand_id'] ?? null;
$formAction = $urlTree['chain-protocol-management'];

updateProtocol($brandId, $formAction);

$protocols = getBrandProtocols($brandId);
