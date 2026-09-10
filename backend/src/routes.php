<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy;

include_once APP . 'validators.php';

function includeController($controllerPath, $container) {
    return function (Request $request, Response $response, array $args) use ($controllerPath, $container) {
        // Start output buffering
        ob_start();
        
        // Include the controller file
        include_once $controllerPath;
        
        // Get the output
        $output = ob_get_clean();
        
        // Write the output to the response body
        $response->getBody()->write($output);
        
        // Return the response
        return $response;
    };
}

//route to current login
$app->group('', function (RouteCollectorProxy $group) {
    //AUTH
    $group->get('/', 'auth')->setName('login');
    $group->post('/', 'auth:login')->setName('login');
});

//I put everything under /app just to not have any issues with the actuall way we get urls : dir['1'] in the old app
// all new routes will be under /app until we have them all migrated
$app->group('/app', function (RouteCollectorProxy $group) use ($app, $container, $unsubscribeValidators, $oldUnsubscribeValidators) {

    //LOGOUT
    $group->get('/logout', 'auth:logout')->setName('logout');

    //TOOLS
    $group->group('/tools', function (RouteCollectorProxy $group) use ($app) {
        $group->get('/import', 'import')->setName('tools_import');
    });

    // STATS
    $group->group('/statistics', function (RouteCollectorProxy $group) use ($container) {
        $group->any('/users', includeController(APP . 'Controllers/Statistics/usersController.php', $container))->setName('stats_users');
        $group->any('/clicks', includeController(APP . 'Controllers/Statistics/clicksController.php', $container))->setName('stats_clicks');
        $group->any('/reputation', includeController(APP . 'Controllers/Statistics/reputationController.php', $container))->setName('stats_reputation');
        $group->any('/engagement', includeController(APP . 'Controllers/Statistics/engagementController.php', $container))->setName('stats_engagement');
        $group->any('/staff-engagement', includeController(APP . 'Controllers/Statistics/staffEngagementController.php', $container))->setName('stats_staff_engagement');
        $group->any('/comparison-table', includeController(APP . 'Controllers/Statistics/comparisonTableController.php', $container))->setName('comparison_table');
        $group->any('/loyalty', includeController(APP . 'Controllers/Statistics/loyaltyController.php', $container))->setName('stats_loyalty');
        $group->any('/loyalty/visitors', includeController(APP . 'Controllers/Statistics/loyaltyVisitorsController.php', $container))->setName('loyalty_visitors');
    });

    $group->group('/reports', function (RouteCollectorProxy $group) use ($container) {
        $group->any('/client-report', includeController(APP . 'Controllers/Statistics/clientReportController.php', $container))->setName('client-report');
    });
    // UNSUSCRIBES
    $group->group('/users', function (RouteCollectorProxy $group) use ($app) {
        $group->get('/{user_guid}/{hotel_guid}/unsubscribe', 'unsubscribe')->setName('user_unsubscribe');
        $group->post('/{user_guid}/{hotel_guid}/unsubscribe', 'unsubscribe:unsubscribe')->setName('user_unsubscribe');
    });

    // COOKIES
    $group->any('/{bookingPrefix}/hlSetCookie', includeController(APP . 'Controllers/HLSetCookieController.php', $container))->setName('hlSetCookie');

    $group->any('/{bookingPrefix}/hlGetCookie', function ($request, $response, $args) use ($container) {
        $view = $container->get('view');
        $html = $view->render('views::hlGetCookie');
        $response->getBody()->write($html);
        return $response;
    })->setName('hlGetCookie');

    $group->any('/{bookingPrefix}/test', function ($request, $response, $args) use ($container) {
        $view = $container->get('view');
        $html = $view->render('views::test');
        $response->getBody()->write($html);
        return $response;
    })->setName('test');

    $group->group('/users', function (RouteCollectorProxy $group) {
        $group->get('/{user_guid}/unsubscribe', 'unsubscribe')->setName('old_user_unsubscribe');
        $group->post('/{user_guid}/unsubscribe', 'unsubscribe:unsubscribe')->setName('old_user_unsubscribe');
    });

    // DATABASE
    $group->group('/database', function (RouteCollectorProxy $group) use ($container){
        $group->any('/home', includeController(APP . 'Controllers/Database/homeController.php', $container))->setName('database_home');
    });

    // 404, 500...
    $group->group('/errors', function (RouteCollectorProxy $group) use ($container) {
        $group->get('/404', function ($request, $response, $args) use ($container) {
            $view = $container->get('view');
            $html = $view->render('errors::404');
            $response->getBody()->write($html);
            return $response;
        })->setName('404');

        $group->get('/500', function ($request, $response, $args) use ($container) {
            $view = $container->get('view');
            $html = $view->render('errors::500');
            $response->getBody()->write($html);
            return $response;
        })->setName('500');
    });

});
