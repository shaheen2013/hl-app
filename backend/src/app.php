<?php
namespace App;

use Slim\Factory\AppFactory;

use DI\Container;
use League\Plates\Engine;
use Slim\Middleware\RoutingMiddleware;
use Slim\Views\PlatesExtension;
use Slim\Flash\Messages;



$settings = require_once APP . 'settings.php';

// Create Slim app
$container = new Container();
AppFactory::setContainer($container);

$container->set('flash', function () {
    return new Messages();
});

$container->set('settings', function () use ($settings){
    return $settings;
});

$app = AppFactory::create();

$container->set('view', function () use ($app) {
    $plates = new Engine(APP . 'templates/');

    $plates->addFolder('common', __DIR__ . '/templates/common');
    $plates->addFolder('_layout', __DIR__ . '/templates/_layout');
    $plates->addFolder('views', __DIR__ . '/templates/views');
    $plates->addFolder('statistics', __DIR__ . '/templates/partials/statistics');
    $plates->addFolder('partials', __DIR__ . '/templates/partials');
    $plates->addFolder('tools', __DIR__ . '/templates/views/tools');
    $plates->addFolder('errors', __DIR__ . '/templates/views/errors');
    $plates->addFolder('auth', __DIR__ . '/templates/views/auth');
    $plates->addFolder('users', __DIR__ . '/templates/views/users');

    $plates->registerFunction('asset', function ($path) {
        return '/' . ltrim($path, '/');
    });

    $plates->registerFunction('baseUrl', function ($path) {
        return '/' . ltrim($path, '/');
    });

    $plates->registerFunction('pathFor', function ($name, $data = [], $queryParams = []) use ($app) {
        return $app->getRouteCollector()->getRouteParser()->urlFor($name, $data, $queryParams);
    });

    // Add uriFull function that was previously provided by blat/slim-plates
    $plates->registerFunction('uriFull', function () {
        // Get the current URL with query string
        return $_SERVER['REQUEST_URI'];
    });

    return $plates;
});


//instanciate the app container and attach services
//require_once APP.'container.php';
$app->add(new PlatesExtension($app));
$capsule = new \Illuminate\Database\Capsule\Manager();
$capsule->addConnection([
    'driver' => 'mysql',
    'host' => HOST,
    'database' => DB,
    'username' => USER,
    'password' => PASS,
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => '',
]);
$capsule->setAsGlobal();
$capsule->bootEloquent();


//add middlwares
require_once APP.'middlewares.php';
$app->addRoutingMiddleware();

/**
 * Add Error Handling Middleware
 *
 * @param bool $displayErrorDetails -> Should be set to false in production
 * @param bool $logErrors -> Parameter is passed to the default ErrorHandler
 * @param bool $logErrorDetails -> Display error details in error log
 * which can be replaced by a callable of your choice.
 */
$displayErros = ENV === 'production' ? false : true;
$app->addErrorMiddleware($displayErros, true, true);

//instanciate controllers mappings
$controllers = require APP .'controllers.php';
foreach ($controllers as $key => $class) {
    $container->set($key, function ($c) use ($class) {
        return new $class($c);
    });
}

$view = $container->get('view');

//finally add routes
require_once APP.'routes.php';

// Run app
$app->run();

?>