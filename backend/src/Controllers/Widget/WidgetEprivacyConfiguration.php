<?php

namespace App\Controllers\Widget;


use ApiGatewayConnection;
use App\Models\Hotel;
use App\Models\Lang;
use Interop\Container\ContainerInterface;
use InvalidArgumentException;

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
class WidgetEprivacyConfiguration extends WidgetController
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

        if (!isset($_POST['benefits_active']) && !isset($_POST['delete_title']) && !isset($_POST['gdpr-privacy-text']) && !isset($_POST['behaviour']) &&
            !isset($_POST['widgetOfferToDelete'])) {
            $this->storeWidgetGenericProduct($_POST, $this->widget);
        }  else if (isset($_POST['gdpr-privacy-text'])) {
            $pages = ['eprivacy_introduction' => ['widget_eprivacy_checkbox_text', 'widget_eprivacy_small_text'], 'eprivacy_page' => ['legal_text']];
            $this->storeEprivacyDynamicText($_POST, $this->brand, $pages, 1, $this->langs);
        }

        $this->widget = $this->getBrandWidget($this->widgetActive);
        return $this->render($this->widget, $this->brand, $this->widgetActive);
    }

    public function render($widget, $brand, $widgetActive)
    {
        $notifyErrors = $widgetActive;
        $brandInfo = [
            'brand_id'    => $brand->id,
            'module_name' => 'widget_eprivacy_small_text',
        ];

        $eprivacyBrandInfo = $this->safeJsonParser($this->callGateway('GET', WIDGET_ENDPOINT . 'hldynamic/eprivacy', $brandInfo,  $notifyErrors), true);
        $eprivacyFilteredBrandInfo = [];
        foreach ($eprivacyBrandInfo as $eprivacyInfo) {
            $eprivacyFilteredBrandInfo[str_replace("}}", "", str_replace("{{", "", array_get($eprivacyInfo, 'name')))] = array_get($eprivacyInfo, 'value');
        }

        $payload = [
            'brand_id'   => $brand->id,
            'parent_id'  => $brand->parent_id,
            'pageName'   => 'eprivacy_introduction',
            'moduleName' => 'widget_eprivacy_small_text',
            'ignoreConfiguration' => true
        ];

        $smallEprivacyText = $this->safeJsonParser($this->callGateway('GET',  WIDGET_ENDPOINT . 'hldynamic', $payload, $notifyErrors ), true);

        $payload = [
            'brand_id'   => $brand->id,
            'parent_id'  => $brand->parent_id,
            'pageName'   => 'eprivacy_introduction',
            'moduleName' => 'widget_eprivacy_checkbox_text',
            'ignoreConfiguration' => true
        ];
        $termsOfUseText = $this->safeJsonParser($this->callGateway('GET', WIDGET_ENDPOINT . 'hldynamic', $payload,  $notifyErrors), true);

        $payload = [
            'brand_id'   => $brand->id,
            'parent_id'  => $brand->parent_id,
            'pageName'   => 'eprivacy_page',
            'moduleName' => 'legal_text',
            'ignoreConfiguration' => true
        ];
        $eprivacyText = $this->safeJsonParser($this->callGateway('GET', WIDGET_ENDPOINT . 'hldynamic', $payload, $notifyErrors), true);

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
            'current_subPage' => 'widget_eprivacy_configuration',
            'flash' => $this->flash->getMessages()
        ]);

        $benefits = null;

        return $this->view->render('views::widget/widget-eprivacy-configuration', [
            'env'                             => WIDGET_ENV,
            'parentWidgetActive'              => $this->parentWidgetActive,
            'defaultOffer'                    => $defaultOffer,
            'widgetActive'                    => $widgetActive,
            'langs'                           => $this->langs,
            'smallEprivacyText'               => $smallEprivacyText,
            'termsOfUseText'                  => $termsOfUseText,
            'eprivacyText'                    => $eprivacyText,
            'eprivacyBrandInfo'               => $eprivacyFilteredBrandInfo,
        ]);

    }

    public function storeEprivacyDynamicText($request, $brand, $pages, $active, $langs)
    {
        $brandInfo = [
            'brand_id'          => $brand->id,
            'company_name'      => array_get($request, 'gdpr-company-name'),
            'company_address'   => array_get($request, 'gdpr-address'),
            'company_nif'       => array_get($request, 'gdpr-cif'),
            'company_email'     => array_get($request, 'gdpr-email'),
            'restricted_portal' => '1',
        ];

        $this->callGateway('POST', WIDGET_ENDPOINT . 'hldynamic/eprivacy', $brandInfo);

        $hlContents = [];
        foreach ($pages as $page => $modules) {
            foreach ($modules as $module) {
                foreach ($langs as $lang) {
                    $hlContents[$page][$module][$lang] = array_get($request, $module . '_' . $lang);
                }
            }
        }

        foreach ($hlContents as $page => $module) {
            foreach ($module as $moduleName => $hlContent) {
                $payload = [
                    'brand_id'      => $brand->id,
                    'pageState'     => 'classic',
                    'configuration' => array_get($request, 'gdpr-privacy-text'),
                    'custom_texts'  => \GuzzleHttp\json_encode($hlContent),
                    'pageName'      => $page,
                    'moduleName'    => $moduleName,
                    'active'        => $active,
                ];
                $this->callGateway('POST',  WIDGET_ENDPOINT . 'hldynamic', $payload);
            }
        }
    }
}
