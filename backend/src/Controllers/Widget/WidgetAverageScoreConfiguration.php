<?php

namespace App\Controllers\Widget;


use ApiGatewayConnection;
use Carbon\Carbon;
use Interop\Container\ContainerInterface;

require_once __DIR__ . '/../../../lib/pushtech_api.php';
require_once __DIR__ . '/../../Services/Connections/ApiGatewayConnection.php';

/**
 * @property mixed view
 * @property mixed router
 * @property mixed flash
 * @property ApiGatewayConnection gateway
 * @property mixed table
 * @property mixed langs
 */
class WidgetAverageScoreConfiguration extends WidgetController
{

    protected $container;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container, new ApiGatewayConnection());

    }

    public function __invoke($request, $response, $args)
    {
        return $this->render($this->widget, $this->brand, $this->widgetActive);
    }

    public function saveConfig()
    {
        //By default, add success message
        $this->flash->addMessageNow('success', [$this->successMessage]);
        
        if (!isset($_POST['min_average_score_date'])) {
            $this->storeWidgetGenericProduct($_POST, $this->widget);
        } else if (isset($_POST['min_average_score_date'])) {
            $this->storeAverageScoreProduct($_POST, 'min_average_score_date', $this->widget);
        } 
      
        $this->widget = $this->getBrandWidget($this->widgetActive);

        return $this->render($this->widget, $this->brand, $this->widgetActive);
    }

    public function render($widget, $brand, $widgetActive)
    {
        $notifyErrors = $widgetActive;

        $hlOffers = $this->safeJsonParser($this->callGateway('GET', HOTELINKING_ENDPOINT . "offers/brand/" . $brand->id . "/" . array_get($_SESSION, 'userLang'), null, $notifyErrors), true);

        $permissions = [];

        $widgetOffers = $this->safeJsonParser($this->callGateway('GET', WIDGET_ENDPOINT . "offers/widget/" . array_get($widget, 'id')), true);
        $defaultOffer = [];

        if($widgetOffers){
            $defaultOffer =  array_filter($widgetOffers, function ($item) {
                if (array_get($item, 'number_triggers')== 1 && array_get($item, 'widgets_event.event.name')== 'visit') {
                    return true;
                }
                return false;
            });
        }
        if (array_get($widget, 'products_configs')) {
            foreach (array_get($widget, 'products_configs') as $product) {
                if (array_get($product, 'label')) {
                    $permissions[array_get($product, 'label')] = array_get($product, 'pivot.value');
                }
            }
        }

        $this->view->addData([
            'page_title'      => 'Configuración del widget',
            'page_icon'       => 'window restore outline icon',
            'current_page'    => 'widget',
            'current_subPage' => 'widget_average_score_configuration',
            'flash' => $this->flash->getMessages()
        ]);

        return $this->view->render('views::widget/widget-average-score-configuration', [
            'defaultOffer'            => $defaultOffer,
            'widgetActive'            => $widgetActive,
            'parentWidgetActive'      => $this->parentWidgetActive,
            'min_average_score_date'  => array_get($permissions, 'min_average_score_date'),
            'average_score_active'    => array_get($permissions, 'average_score_active'),
            'bigDaysValue'            => $this->getBigDaysValue() 
        ]);

    }

    public function storeAverageScoreProduct($post, $product_config_name, $widget)
    {
        $payload = [
            "widget_id" => array_get($widget, 'id'),
            "product_config_name" => $product_config_name,
            "value" => $post[$product_config_name]
        ];

        $this->callGateway('POST', WIDGET_ENDPOINT . 'widgets/products/configs/product-name',  $payload);

    }

    private function getBigDaysValue()
    {
        $unixDate = Carbon::create(1970, 1, 1);
        $currentDate = Carbon::create(2020, 1, 1);

        $daysDiff = -1 * abs($currentDate->diffInDays($unixDate));

        return $daysDiff;
    }

}
