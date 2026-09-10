<?php

namespace App\Controllers\Widget;


use ApiGatewayConnection;
use App\Models\Hotel;
use Interop\Container\ContainerInterface;

require_once __DIR__ . '/../../Services/Connections/ApiGatewayConnection.php';

/**
 * @property mixed view
 * @property mixed router
 * @property mixed flash
 * @property ApiGatewayConnection gateway
 * @property mixed table
 * @property mixed langs
 */
class WidgetEngagementConfiguration extends WidgetController
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

        if (isset($_POST['messageType'])) {
            $this->storeMessages($_POST, $this->widget, $this->langs);
        } else {
            $this->storeWidgetGenericProduct($_POST, $this->widget);
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
            'current_subPage' => 'widget_engagement_configuration',
            'flash'           => $this->flash->getMessages()
        ]);

        $unknownUserMessages = null;
        $knownUserMessages = null;
        $visitUserMessages = null;

        if (array_get($permissions, 'unknown_user_messages')) {
            $unknownUserMessages = json_decode(array_get($permissions, 'unknown_user_messages'), true);
        }
        
        if (array_get($permissions, 'known_user_messages')) {
            $knownUserMessages = json_decode(array_get($permissions, 'known_user_messages'), true);
        }
        
        if (array_get($permissions, 'visit_user_messages')) {
            $visitUserMessages = json_decode(array_get($permissions, 'visit_user_messages'), true);
        }

        return $this->view->render('views::widget/widget-engagement-configuration', [
            'env'                             => WIDGET_ENV,
            'defaultOffer'                    => $defaultOffer,
            'widgetActive'                    => $widgetActive,
            'parentWidgetActive'              => $this->parentWidgetActive,
            'unknownUserMessages'             => $unknownUserMessages ?? [],
            'knownUserMessages'               => $knownUserMessages ?? [], 
            'visitUserMessages'               => $visitUserMessages ?? [],
            'messagesTime'                    => array_get($permissions, 'message_time'),
            'timeBetweenMessages'             => array_get($permissions, 'time_between_messages'),
            'langs'                           => $this->langs,
        ]);

    }

    public function storeMessages($request, $widget, $langs)
    {
        $messages = json_decode(array_get($request, 'actualMessages'), true);

        if (array_get($request, 'messageAction') == "reset") {
            $this->callGateway('PUT', WIDGET_ENDPOINT . 'widgets/' . array_get($widget, 'id') . '/products-config/' . array_get($request, 'messageType') . '/reset', [], true);
        } else {
            if (array_get($request, 'messageAction') == "save") {
                $messageToUpdate = null;
                foreach ($langs as $lang) {
                    foreach($request as $key => $value) {
                        if(preg_match('/_message_'.$lang.'/', $key)) { 
                            $position = $key[0] - 1; 
                            $messageToUpdate[$lang] = $value;
                        }
                    }
                }        
    
                $messages[$position] =  $messageToUpdate;  
            } else {
                $messagePosition = array_first(array_keys($request), function ($key, $value) {
                    return preg_match('/_message_/', $value);
                });
    
                array_splice($messages, $messagePosition[0] - 1, 1);
            }
    
            $engagemenPayload = [
                'widget_id'           => array_get($widget, 'id'),
                'product_config_name' => array_get($request, 'messageType'),
                'value'               => json_encode($messages)
            ];
    
            $this->callGateway('POST', WIDGET_ENDPOINT . 'widgets/products/configs/product-name', $engagemenPayload, true);
        }
    }

}
