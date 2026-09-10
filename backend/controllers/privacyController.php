<?php if (!defined('INDEXCONTROLVAL')) : exit; endif;

include_once MODEL . 'dynamic-contentModel.php';

$privacy_text = '';
if (array_get($_SESSION, 'hotel.id')) {
    $privacy_text = array_get(getBrandCustomContent(array_get($_SESSION, 'hotel.id'), array_get($_SESSION, 'chain.id'), 'legal_text', $_SESSION['brand_is_not_hotel'] ? 'not_hotel_legal_text' : 'legal_text'), $_SESSION['userLang']);
} else {
    header('Location: /' . $urlTree['404']);
}
