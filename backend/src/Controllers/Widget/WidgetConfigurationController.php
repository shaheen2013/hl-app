<?php

namespace App\Controllers\Widget;


use ApiGatewayConnection;
use App\Models\Brand;
use App\Models\Product;
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
class WidgetConfigurationController extends WidgetController
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
        if(!empty($this->widget) && !empty($_POST)){
            //By default, add success message
            $this->flash->addMessageNow('success', [$this->successMessage]);
        }
    
        if (
            !isset($_POST['benefits_active']) &&
            !isset($_POST['delete_title']) &&
            !isset($_POST['gdpr-privacy-text']) &&
            !isset($_POST['behaviour']) &&
            !isset($_POST['widgetOfferToDelete']) &&
            !isset($_POST['actualMessages']) &&
            !isset($_POST['chainWidget']) &&
            !isset($_POST['resetAssistantJob']) &&
            !isset($_POST['hide_promocode']) 
        ) {
            $this->storeWidgetGenericProduct($_POST, $this->widget);
        } else if (isset($_POST['behaviour'])) {
            $this->storeOffer($_POST, $this->widget, $this->brand->id);
        } else if (isset($_POST['widgetOfferToDelete'])) {
            $this->callGateway('DELETE', WIDGET_ENDPOINT . 'widgets/offers/' . $_POST['widgetOfferToDelete']);
        } else if (isset($_POST['resetAssistantJob'])) {
            $this->callGateway('PUT', WIDGET_ENDPOINT . 'widgets/' . array_get($this->widget, 'id') . '/products-config/assistant_job/reset', [], true);
        } else if (isset($_POST['hide_promocode'])) {
            $this->activeHidePromocode($_POST, $this->widget);
        } else {
            $widgetPayload = $_POST['chainWidget'] == "on" ?
                ['brand_id' => $this->brand->id] : 
                ['brand_id' => $this->brand->parent_id];
            $widgetByChain = $_POST['chainWidget'] == "on" ?
                ['value' => 0] : 
                ['value' => 1];
            $this->widgetActive = $_POST['chainWidget'] == "on" ?
                1 :
                0;

            $widgetProduct = Product::where('producto', 'widget')->first();

            $this->callGateway('PUT', HOTELINKING_ENDPOINT . "brands/" . $this->brand->id . "/products/" . $widgetProduct->id . "/configuration", $widgetByChain);
            $this->callGateway('PUT', WIDGET_ENDPOINT . 'widgets/' . array_get($this->widget, 'id'), $widgetPayload);
        }

        if(isset($_POST['assistant_job'])){
            $this->storeJobs($_POST, $this->widget);
        }

        $this->widget = $this->getBrandWidget($this->widgetActive);
        return $this->render($this->widget, $this->brand, $this->widgetActive);
    }

    public function render($widget, $brand, $widgetActive)
    {

        $notifyErrors = $widgetActive;
        $hlOffers = $this->safeJsonParser($this->callGateway('GET', HOTELINKING_ENDPOINT . "offers/brand/" . $brand->id . "/" . array_get($_SESSION, 'userLang'), null, $notifyErrors), true);

        $events = $this->safeJsonParser($this->callGateway('GET', WIDGET_ENDPOINT . 'events', '', $notifyErrors), true);

        $showWidget = array_get($widget, 'active');

        $brandParentWidget = false;
        if (array_get($widget, 'brand_id') == $brand->parent_id) {
            $brandParentWidget = true;
        }

        $permissions = [];

        $widgetOffers = [];

        if (!empty($widget)) {
            $widgetOffers = $this->safeJsonParser($this->callGateway('GET', WIDGET_ENDPOINT . "offers/widget/" . array_get($widget, 'id'), null, $notifyErrors), true);
        }

        $defaultOffer = [];

        if ($widgetOffers) {
            $defaultOffer = array_filter($widgetOffers, function ($item) {
                if (array_get($item, 'number_triggers') == 1 && array_get($item, 'widgets_event.event.name') == 'visit') {
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
            'current_subPage' => 'widget_configuration',
            'flash'           => $this->flash->getMessages()
        ]);

        $benefits = null;

        if (array_get($permissions, 'benefits_list')) {
            $benefits = json_decode(array_get($permissions, 'benefits_list'), true);
        }

        return $this->view->render('views::widget/widget-configuration', [
            'env'                     => WIDGET_ENV,
            'offers'                  => $hlOffers,
            'widgetOffers'            => $widgetOffers,
            'defaultOffer'            => $defaultOffer,
            'widgetActive'            => $widgetActive,
            'showWidget'              => $showWidget,
            'langs'                   => $this->langs,
            'parentWidgetActive'      => $this->parentWidgetActive,
            'chainWidget'             => $brandParentWidget,
            'widget_background_color' => array_get($permissions, 'widget_background_color'),
            'widget_title_color'      => array_get($permissions, 'widget_title_color'),
            'widget_text_color'       => array_get($permissions, 'widget_text_color'),
            'text_button_color'       => array_get($permissions, 'text_button_color'),
            'button_color'            => array_get($permissions, 'button_color'),
            'link_colors'             => array_get($permissions, 'link_colors'),
            'widgetCode'              => array_get($widget, 'code'),
            'background_img'          => array_get($permissions, 'assistant_img'),
            'assistant_name'          => array_get($permissions, 'assistant_name'),
            'events'                  => $events,
            'exclusive_favorite'      => array_get($permissions, 'exclusive_favorite'),
            'min_satisfaction_date'   => array_get($permissions, 'min_satisfaction_date'),
            'min_satisfaction_score'  => array_get($permissions, 'min_satisfaction_score'),
            'satisfaction_lapse'      => array_get($permissions, 'satisfaction_lapse'),
            'satisfaction_active'     => array_get($permissions, 'satisfaction_active'),
            'only_satisfaction'       => array_get($permissions, 'only_satisfaction'),
            'hide_promocode'          => array_get($permissions, 'hide_promocode'),
            'hide_promocode_param'    => array_get($permissions, 'hide_promocode_param'),
            'assistantJob'            => json_decode(array_get($permissions, 'assistant_job'), true),
            'builderUrl'              => WIDGET_BUILDER_URL,
        ]);

    }

    public function storeOffer($request, $widget, $brand_id)
    {
        $offerInfo = explode("_", array_get($request, 'offers'));
        $payload = [
            'widget_id'       => array_get($widget, 'id'),
            'event_id'        => array_get($request, 'behaviour'),
            'offer_id'        => array_get($offerInfo, 0),
            'number_triggers' => array_get($request, 'behaviour-qty'),
            'promocode'       => array_get($offerInfo, 1),
        ];
        $this->safeJsonParser($this->callGateway('POST', WIDGET_ENDPOINT . 'store-offer-and-event', $payload, true), true);
        $offersHL = $this->safeJsonParser($this->callGateway('GET', HOTELINKING_ENDPOINT . "offers/" . array_get($offerInfo, 0), [], true), true);

        if (array_get($offersHL, 'offer_lang')) {
            foreach (array_get($offersHL, 'offer_lang') as $offersHLLang) {
                $payload = [
                    'lang'        => array_get($offersHLLang, 'lang'),
                    'name'        => array_get($offersHLLang, 'name'),
                    'description' => array_get($offersHLLang, 'description'),
                    'offer_id'    => array_get($offerInfo, 0),
                ];
                $this->callGateway('POST', WIDGET_ENDPOINT . 'offers/' . array_get($offerInfo, 0) . '/lang', $payload);
            }
        }
    }
    
    public function storeJobs($request, $widget)
    {
        if (!isset($request['resetAssistantJob'])) {
            $assistantJobLangs = [];
            foreach ($this->langs as $lang) {
                foreach($request as $key => $value) {
                    if(preg_match('/message_'.$lang.'/', $key)) { 
                        $assistantJobLangs[$lang] = $value;
                    }
                }
            }        
    
            $engagementPayload = [
                'widget_id'           => array_get($widget, 'id'),
                'product_config_name' => 'assistant_job',
                'value'               => json_encode($assistantJobLangs)
            ];
    
            $this->callGateway('POST', WIDGET_ENDPOINT . 'widgets/products/configs/product-name', $engagementPayload, true);
        }
    }
    
    public function activeHidePromocode($request, $widget)
    {
        include LANG . $_SESSION['userLang'] . '/statistics/widgetConfiguration.php' ;

        $hidePromocodeParam = array_first(array_get($widget, 'products_configs'), function($key, $value)
        {
            return array_get($value, 'label') == 'hide_promocode_param';
        });

        if (!array_get($request, 'hide_promocode') && !array_get($hidePromocodeParam, 'pivot.value')) {
            $this->flash->addMessageNow('errors', [$lang['hidePromocodesError']]);
        } else {
            $this->storeWidgetGenericProduct($_POST, $widget);
        }
    }
}
