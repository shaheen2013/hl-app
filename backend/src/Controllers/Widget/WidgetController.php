<?php
/**
 * Created by PhpStorm.
 * User: hl
 * Date: 15/05/2019
 * Time: 11:01
 */

namespace App\Controllers\Widget;



use ApiGatewayConnection;
use App\Models\Hotel;
use App\Models\Lang;
use Interop\Container\ContainerInterface;
use InvalidArgumentException;

require_once __DIR__ . '/../../Services/Connections/ApiGatewayConnection.php';

/**
 * @property ApiGatewayConnection gateway
 * @property  Object flash
 * @property array langs
 * @property  string successMessage
 * @property  string errorMessage
 * @property  object container
 * @property  object view
 * @property  object router
 * @property  object table
 * @property array|mixed widget
 * @property Hotel hotel
 * @property mixed brand
 * @property bool widgetActive
 * @property array|mixed|null widgetProductActive
 * @property bool parentWidgetActive
 */
class WidgetController
{
    public function __construct(ContainerInterface $container, $connection)
    {
        require RUTA_DIR . LANG . $_SESSION['userLang'] . '/feedback.php';

        $this->container = $container;
        $this->view = $this->container->get('view');
        $this->router = $this->container->get('router');
        $this->table = $this->container->get('db')->table('users');
        $this->gateway = $connection;
        $this->flash = $this->container->get('flash');
        $this->langs = $this->getLangs();
        $this->successMessage = $msg2007;
        $this->errorMessage = $msg4065;
        $this->hotel = Hotel::where('id', '=', array_get($_SESSION, 'h_logueado'))->first();
        $this->brand = $this->hotel->brand;
        $this->widgetActive = $this->getChildActive();
        $this->parentWidgetActive = false;
        $this->widget = $this->getBrandWidget($this->widgetActive);
    }

    protected function getLangs()
    {
        $langs = Lang::get();
        $languages = [];
        foreach ($langs as $lang) {
            $languages[] = $lang->name;
        }


        return $languages;
    }

    protected function getBrandWidget($brandWidget){
        if ($brandWidget){
            $widget = $this->safeJsonParser($this->callGateway('GET', WIDGET_ENDPOINT . 'dynamic/widgets/brand_id', ['value' => $this->brand->id], false), true);
        }
        else {
            $widget = $this->safeJsonParser($this->callGateway('GET',  WIDGET_ENDPOINT . 'dynamic/widgets/brand_id', ['value' => $this->brand->parent_id], false), true);
            if($widget) {
                $this->parentWidgetActive = true;
            }
        }
        return $widget;
    }
    
    protected function getChildActive() {
        $widgetByChainConfig = $this->safeJsonParser($this->callGateway('GET', HOTELINKING_ENDPOINT . 'brands/' . $this->brand->id . '/products/14/configuration'), true);
        return empty($widgetByChainConfig) || !array_get($widgetByChainConfig, 'value', false);
    }

    protected function safeJsonParser($object, $assoc=false){
        if($object){
            try{
                return json_decode($object, $assoc);
            }catch (InvalidArgumentException $e){
                global $log;
                $log->warning('wrong answer from api', ["Exception" => $e,"object" => $object]);
                return [];
            }
        }
        return [];
    }

    protected function callGateway($method, $url, $payload=[], $notifyError=false) {
        $response = null;
        try {
            $response = $this->gateway->sendRequest($payload, $url, $method);
        } catch (\Exception $e) {
            if ($notifyError) {
                $this->flash->addMessageNow('errors', [$this->errorMessage]);
            }
        }

        return $response;
    }

    protected function storeWidgetGenericProduct($request, $widget)
    {         
        if (!empty($widget)) {
            foreach ($request as $product_config => $value) {
                
                if ($product_config == 'notification_active' || 
                    $product_config == 'remarketing_notification_active' || 
                    $product_config == 'promocode_notification_active' || 
                    $product_config == 'widgetActive' || 
                    $product_config == 'satisfaction_active' ||
                    $product_config == 'average_score_active' ||
                    $product_config == 'widget_booking_email_active' ||
                    $product_config == 'hide_promocode'
                    ) {

                    $value = $value == 'on' ? 'off' : 'on';

                }

                $payload = [
                    'widget_id'           => array_get($widget, 'id'),
                    'product_config_name' => $product_config,
                    'value'               => $value
                ];

                $this->callGateway('POST', WIDGET_ENDPOINT . 'widgets/products/configs/product-name', $payload, true );
            }
        } 
    }
}