<?php

// Fetch dependency injection container of slim app
// use Projek\Slim\Plates;
// use Projek\Slim\PlatesExtension;
use Slim\Views\Plates;
use Slim\Views\PlatesExtension;

$container = $app->getContainer();

// Register provider
$container['flash'] = function () {
    return new \Slim\Flash\Messages();
};

// override the view service with plates instead of twig
$container['view'] = function ($c) {

    //settings for plates (similar as what we have in index.php for native plates)
    $settings = [
        'directory' => APP . 'templates/',
        'assetPath' => RUTA_DIR
    ];

    // Instantiate and add Slim specific extension for plates, don't ask :P 
    $view = new Plates($settings);
    $view->setResponse($c->get('response'));
    $view->loadExtension(new PlatesExtension($c['router'], $c['request']->getUri()));

    //add folder definitions for plates
    $view->addFolder('common', __DIR__ . '/templates/common');
    $view->addFolder('_layout', __DIR__ . '/templates/_layout');
    $view->addFolder('views', __DIR__ . '/templates/views');
    $view->addFolder('statistics', __DIR__ . '/templates/partials/statistics');
    $view->addFolder('partials', __DIR__ . '/templates/partials');
    $view->addFolder('tools', __DIR__ . '/templates/views/tools');
    $view->addFolder('errors', __DIR__ . '/templates/views/errors');
    $view->addFolder('auth', __DIR__ . '/templates/views/auth');
    $view->addFolder('users', __DIR__ . '/templates/views/users');
    return $view;
};

$container['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        $response->withStatus(404);
        return $c['view']->render('errors::404');
    };
};

// // connect to db with Illuminate larvel
// $capsule = new \Illuminate\Database\Capsule\Manager();
// $capsule->addConnection($container['settings']['db']);
// $capsule->setAsGlobal();
// $capsule->bootEloquent();
// /// END connect to db

// // to accsess the $capsule with our container from our controllers
// $container['db'] = function($container) use ($capsule){
//     return $capsule;
// };

?>