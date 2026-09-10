<?php

namespace App\Controllers\Widget;


use ApiGatewayConnection;
use App\Models\Hotel;
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
class WidgetAdvantagesConfiguration extends WidgetController
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container, new ApiGatewayConnection());

    }

    public function __invoke($request, $response, $args)
    {
        return $this->render($this->widget, $this->widgetActive);
    }

    public function saveConfig()
    {
        //By default, add success message
        $this->flash->addMessageNow('success', [$this->successMessage]);

        if (isset($_POST['benefits_active']) || isset($_POST['title_en']) || isset($_POST['delete_title'])) {
            $this->storeBenefitsProduct($_POST, $this->widget, $this->langs);
        }
        $this->widget = $this->getBrandWidget($this->widgetActive);
        return $this->render($this->widget, $this->widgetActive);
    }

    public function render($widget, $widgetActive)
    {


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

        $permissions = [];
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
            'current_subPage' => 'widget_advantages_configuration',
            'flash' => $this->flash->getMessages()
        ]);

        $benefits = null;

        if (array_get($permissions, 'benefits_list')) {
            $benefits = json_decode(array_get($permissions, 'benefits_list'), true);
        }

        return $this->view->render('views::widget/widget-advantages-configuration', [
            'env'                             => WIDGET_ENV,
            'defaultOffer'                    => $defaultOffer,
            'widgetActive'                    => $widgetActive,
            'parentWidgetActive'              => $this->parentWidgetActive,
            'benefits_list'                   => array_get($benefits, 'benefits_list', []),
            'benefits_active'                 => array_get($permissions, 'benefits_active'),
            'json_benefits_list'              => array_get($permissions, 'benefits_list'),
            'langs'                           => $this->langs,
        ]);

    }

    public function storeBenefitsProduct($request, $widget, $langs)
    {
               
        $benefitsList = (array)array_get(json_decode(array_get($request, 'json_benefits_list'), true), 'benefits_list');

        if (isset($request['benefits_active'])) {
            
            $payload = [
                'widget_id'           => array_get($widget, 'id'),
                'product_config_name' => 'benefits_active',
                'value'               => $request['benefits_active'] == 'on' ? '' : 'on'
            ];
            $this->callGateway('POST', WIDGET_ENDPOINT . 'widgets/products/configs/product-name',  $payload, true);

        } else if (array_get($request, 'title_en')) {
           
            foreach ($langs as $lang) {
                $newBenefit[$lang] = [
                    'title'       => array_get($request, 'title_' . $lang),
                    'description' => array_get($request, 'description_' . $lang),
                    'guaranteed'  => array_get($request, 'guaranteed'),
                ];
            }

            array_push($benefitsList, $newBenefit);
            $benefitsList = ["benefits_list" => $benefitsList];

            $payloadBenefitList = [
                'widget_id'           => array_get($widget, 'id'),
                'product_config_name' => 'benefits_list',
                'value'               => json_encode($benefitsList)
            ];
            
            $this->callGateway('POST', WIDGET_ENDPOINT . 'widgets/products/configs/product-name', $payloadBenefitList, true);

        } else {

            array_filter($benefitsList, function($var, $key) use (&$benefitsList, $request) {
                if (array_get($var, 'en.title') == array_get($request, 'delete_title')) {

                    unset($benefitsList[$key]);
                };
            }, ARRAY_FILTER_USE_BOTH);

            $benefitsList = ["benefits_list" => $benefitsList];
            $payloadBenefitList = [
                'widget_id'           => array_get($widget, 'id'),
                'product_config_name' => 'benefits_list',
                'value'               => json_encode($benefitsList)
            ];
            
            $this->callGateway('POST', WIDGET_ENDPOINT . 'widgets/products/configs/product-name', $payloadBenefitList, true);
        }



    }

}
