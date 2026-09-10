<?php

namespace App\Controllers\Widget;


use ApiGatewayConnection;
use App\Models\Hotel;
use App\Models\Lang;
use Interop\Container\ContainerInterface;
use InvalidArgumentException;
use Respect\Validation\Rules\Date;

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
class WidgetNotificationsConfiguration extends WidgetController
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

        if (!isset($_POST['benefits_active']) && !isset($_POST['delete_title']) && !isset($_POST['gdpr-privacy-text']) && !isset($_POST['behaviour']) &&
            !isset($_POST['widgetOfferToDelete'])) {
            $this->storeWidgetGenericProduct($_POST, $this->widget);
        }
        
        $this->widget = $this->getBrandWidget($this->widgetActive);
        return $this->render($this->widget, $this->widgetActive);
    }

    public function render($widget, $widgetActive)
    {
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
            'current_subPage' => 'widget_notifications_configuration',
            'flash' => $this->flash->getMessages()
        ]);

        return $this->view->render('views::widget/widget-notifications-configuration', [
            'env'                             => WIDGET_ENV,
            'defaultOffer'                    => $defaultOffer,
            'widgetActive'                    => $widgetActive,
            'parentWidgetActive'              => $this->parentWidgetActive,
            'days_selected'                   => array_get($permissions, 'days_antelation'),
            'widget_booking_email_active'     => array_get($permissions, 'widget_booking_email_active'),
            'notification_active'             => array_get($permissions, 'notification_active'),
            'langs'                           => $this->langs,
            'remarketing_notification_active' => array_get($permissions, 'remarketing_notification_active'),
            'remarketing_number_emails'       => array_get($permissions, 'remarketing_number_emails'),
            'remarketing_days_subsequent'     => array_get($permissions, 'remarketing_days_subsequent'),
            'promocode_notification_active'   => array_get($permissions, 'promocode_notification_active'),
            'promocode_days_subsequent'       => array_get($permissions, 'promocode_days_subsequent'),
            'promocode_number_emails'         => array_get($permissions, 'promocode_number_emails'),
            'promocodeFirstLapse'             => array_get($permissions, 'promocode_first_lapse'),
            'remarketingFirstLapse'           => array_get($permissions, 'remarketing_first_lapse'),
        ]);

    }
}
