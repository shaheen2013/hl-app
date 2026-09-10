<?php
$view = $container->get('view');

if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}

$view->addData([
    'page_title' => 'Actividad en tiempo real',
    'page_icon' => 'tv',
    'current_page' => 'database',
    'current_subPage' => 'home'
]);

$template_data['hotel_id'] = array_get($_SESSION,'h_logueado', array_get($_SESSION,'staff_id_hotel'));
//return $this->view->render('views::database/home', $template_data);
$html = $view->render('views::database/home', $template_data);
$response->getBody()->write($html);
return $response;
