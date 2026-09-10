<?php

namespace App\Controllers\Widget;


use ApiGatewayConnection;
use App\Models\Hotel;
use App\Models\Lang;
use Interop\Container\ContainerInterface;
use Respect\Validation\Rules\Date;

require_once __DIR__ . '/../../../lib/pushtech_api.php';
require_once __DIR__ . '/../../Services/Connections/ApiGatewayConnection.php';
include_once RUTA_DIR . LIB . 'dashboards_search_session_management.php';

/**
 * @property mixed view
 * @property mixed router
 * @property mixed flash
 * @property ApiGatewayConnection gateway
 * @property mixed table
 * @property mixed getParams
 */
class WidgetClientsController extends WidgetController
{

    protected $container;

    public function __construct(ContainerInterface $container)
    {
        global $log; $log->debug("Constructing");
        $this->container = $container;
        $this->view = $this->container->get('view');
        $this->router = $this->container->get('router');
        $this->flash = $this->container->get('flash');
        $this->table = $this->container->get('db')->table('users');
        $this->gateway = new ApiGatewayConnection();
        $this->getParams = [];
        global $log; $log->debug("Constructing 2");

        parent::__construct($container, new ApiGatewayConnection());

        global $log; $log->debug("Constructing 3");

    }

    public function __invoke($request, $response, $args)
    {
        return $this->render();
    }

    public function filter() {
        $resultsPerPage = array_get($_POST, 'resultsList', 10);
        return $this->render(array_get($_POST, 'page', 1), $resultsPerPage);
    }

    public function render($page=1, $resultsPerPage = 10)
    {
        if ($_SESSION['rangeStart']) {
            $this->getParams['from'] = date_format(date_create($_SESSION['rangeStart']), 'Ymd');
        }
        
        if ($_SESSION['rangeEnd']) {
            $this->getParams['to'] = date_format(date_create($_SESSION['rangeEnd']), 'Ymd');
        }

        $hotel = Hotel::where('id', '=', array_get($_SESSION, 'h_logueado'))->first();
        $brand = $hotel->brand;
        $widget = $this->getBrandWidget($this->widgetActive);
        $widgetId = array_get($widget, 'id');

        try {
            $clients = $this->safeJsonParser($this->gateway->sendRequest($this->getParams, WIDGET_ENDPOINT . 'stats/widget/'.$widgetId.'/clients', 'GET'), true);
        } catch (\Exception $e) {
            $clients = null;
        }
        
        $this->view->addData([
            'page_title'      => 'Clientes y Reservas',
            'page_icon'       => 'bed icon',
            'current_page'    => 'widget',
            'current_subPage' => 'widget_clients',
        ]);

        return $this->view->render('views::widget/widget-clients', [
            'clients'  => $clients ? array_slice($clients, max($page - 1, 0)*$resultsPerPage, $resultsPerPage) : null,
            'numberPages' => $clients ? intval(count($clients)/$resultsPerPage) : null,
            'page' => $page,
            'resultsPerPage' => $resultsPerPage
        ]);
    }
}
