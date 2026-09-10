<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Routing\RouteContext;
use Slim\Exception\HttpNotFoundException;

// TODO: refactor this to have a list of middlewares as classes
// TODO: and then instantiate them or generally or per route/group basis in the routes file
// TODO: this will make the visibility/portability much better

//SESSION Middleware
$app->add(function (Request $request, RequestHandler $handler) use ($container, $app) {
    $routeContext = RouteContext::fromRequest($request);
    $routeParser = $routeContext->getRouteParser();
    $route = $routeContext->getRoute();

    //if no hotel in session
    if (!array_has($_SESSION, 'staff_id_hotel') && (!array_has($_SESSION, 'hotel.id') || !array_has($_SESSION, 'h_logueado'))) {
        //list of accessible routes regardless login
        $alwaysAccessRoutes = ['login', 'old_user_unsubscribe', 'user_unsubscribe', 'client-report', 'hlSetCookie', 'hlGetCookie', 'test', 'logout'];
        $current_route = $route->getName();
        global $log;
        $log->info($current_route, [in_array($current_route, $alwaysAccessRoutes), $alwaysAccessRoutes]);
        if (!in_array($current_route, $alwaysAccessRoutes)) {
            $response = $app->getResponseFactory()->createResponse();
            return $response->withHeader('Location', $routeParser->urlFor('login'))->withStatus(302);
        }
    } else {
        if ($_POST && array_has($_POST, 'relogin_hotel_id')) {
            include_once LIB.'loguearHotel.php';
            include_once LIB . 'cookieLogin.php';

            $chain_id = array_get($_SESSION,'c_logueado');
            $staff_id = array_get($_SESSION,'staff_logueado');
            $relogin_hotel_id = array_get($_POST,'relogin_hotel_id');

            $new_chain_id  = hotelIdCadena($relogin_hotel_id);

            if ($chain_id && $new_chain_id == $chain_id) {
                loguearHotel($relogin_hotel_id, 1);
            } else if ($staff_id) {
                loguearStaff($staff_id, $relogin_hotel_id);
            }

            borrarCookieLogin();
            header('Location: '.array_get($_POST, 'url'));
        }

        $container->get('view')->addData([
            'title' => 'Hotelinking - ' . array_get($_SESSION, 'hotel.name'),
            'hotel_name' => array_get($_SESSION, 'hotel.name'),
            'hotel_logo' => array_get($_SESSION, 'hotel.logo'),
            'url' => 'http://localhost',
        ]);

        //if hotel in session redirect to stats_users
        if ($route->getName() == 'login') {
            $response = $app->getResponseFactory()->createResponse();
            return $response->withHeader('Location', SECURE_BASE_PATH . array_get($_SESSION, 'defaultPage', 'hotel-edit-profile-details') . '/')->withStatus(302);
        }
    }

    return $handler->handle($request);
});

$app->add(function (Request $request, RequestHandler $handler) use ($container) {
    $routeContext = RouteContext::fromRequest($request);
    $route = $routeContext->getRoute();

    if (empty($route)) {
        throw new HttpNotFoundException($request);
    }

    $routeName = $route->getName();
    $groups = $route->getGroups();
    $arguments = $route->getArguments();

    $activeGroups = array_map(function ($group) {
        return $group->getPattern();
    }, $groups);

    $container->get('view')->addData([
        'route' => [
            'name' => $routeName,
            'groups' => $activeGroups,
            'arguments' => $arguments,
        ],
    ]);

    // Register isActiveRoute function for plates
    $container->get('view')->registerFunction('isActiveRoute', function ($string) use ($routeName) {
        return $routeName === $string;
    });

    // Register isActiveGroup function for plates
    $container->get('view')->registerFunction('isActiveGroup', function ($string) use ($activeGroups) {
        return in_array($string, $activeGroups);
    });

    return $handler->handle($request);
});

//Flash Middleware
$app->add(function (Request $request, RequestHandler $handler) use ($container) {

    if (array_has($_SESSION, 'flashMessage')) {
        $container->get('flash')->addMessageNow(array_get($_SESSION, 'flashMessage.status'), array_get($_SESSION, 'flashMessage.message'));
        unset($_SESSION['flashMessage']);
    }

    $container->get('view')->addData([
        "flash" => $container->get('flash')->getMessages(),
    ]);

    return $handler->handle($request);
});