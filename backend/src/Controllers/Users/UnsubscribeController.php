<?php

namespace App\Controllers\Users;

use ApiGatewayConnection;
use App\Models\HotelGuid;
use App\Models\UserHotel;
use Psr\Container\ContainerInterface;
use App\Models\User;
use App\Models\UserGuid;
use Slim\Routing\RouteContext;
use Exception;

require_once __DIR__ . '/../../../lib/pushtech_api.php';
require_once __DIR__ . '/../../Services/Connections/ApiGatewayConnection.php';

/**
 * @property mixed view
 * @property mixed router
 * @property mixed flash
 * @property mixed table
 * @property object user
 * @property object hotel
 * @property ApiGatewayConnection gateway
 */
class UnsubscribeController
{

    protected $container;
    protected static $gateway;
    protected $client;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->view = $this->container->get('view');
        $this->flash = $this->container->get('flash');
        static::$gateway = new ApiGatewayConnection();
    }

    public function __invoke($request, $response, $args)
    {
        $router = RouteContext::fromRequest($request)->getRouteParser();

        $this->view->addData([
            'title' => 'Hotelinking'
        ]);
        $errors = $request->getAttribute('errors');
        if ($errors) {
            foreach ($errors as $msg) {
                $this->flash->addMessage('errors', $msg);
            }
        }

        $user_guid = $request->getAttribute('user_guid');
        $hotel_guid = $request->getAttribute('hotel_guid');

        $this->getUser($user_guid, $response);
        $this->getHotel($hotel_guid, $response);

        $brandId = $this->hotel->brand->id;
        $userId = $this->user->id;

        $subscriptions = $this->getUserSubscriptions($brandId, $userId);
        if ($this->user) {
            $html = $this->view->render('users::unsubscribe', [
                'user' => $this->user,
                'notifications_subscribed' => $subscriptions['notifications_subscribed'],
                'commercial_profile_subscribed' => $subscriptions['commercial_profile_subscribed']
            ]);
            $response->getBody()->write($html);
            return $response;
        } else {
            return $response->withHeader('Location', $router->urlFor('500'))->withStatus(302);
        }
    }

    private function getUser($guid, $response)
    {
        $user_guid = UserGuid::where('guid', '=', $guid)->first();
        if (!$user_guid) {
            $user = User::where('email', $guid)->first();
            if (!$user) {
                $this->flash->addMessage('errors', ['User does not exist']);
                $this->user = false;
                return false;
            }
            $this->user = $user;
        } else {
            $this->user = $user_guid->user;
        }
        return $this->user;
    }

    private function getHotel($guid, $response)
    {
        $hotel_guid = HotelGuid::where('guid', '=', $guid)->first();

        if (!$hotel_guid) {
            //            $this->flash->addMessage('errors',['Hotel does not exist']);
            // return $this->view->render('errors::500');
            $this->hotel = false;
            return false;
            // return $response->withRedirect($this->router->pathFor('500'));
        } else {
            $this->hotel = $hotel_guid->hotel;
            return $hotel_guid->hotel;
        }
    }

    private function getUserHotel($hotel_id, $user_id)
    {
        $user_hotel = UserHotel::where('id_usuario', '=', $user_id)->where('id_hotel', $hotel_id)->first();

        if (!$user_hotel) {
            $this->flash->addMessage('errors', ['UserHotel does not exist']);
            // return $this->view->render('errors::500');
            $this->userHotel = false;
            return false;
            // return $response->withRedirect($this->router->pathFor('500'));
        } else {
            $this->userHotel = $user_hotel;
            return $user_hotel;
        }
    }

    private function getUserSubscriptions($brandId, $userId)
    {
        $endpoint = HOTELINKING_ENDPOINT . 'brands/' . $brandId . '/users/' . $userId . '/subscriptions';
        $gateway =  new ApiGatewayConnection();
        $response = $gateway->sendRequest([], $endpoint, 'GET');
        $subscriptions = json_decode($response, true);
         
        return $subscriptions;
    }


    public function unsubscribe($request, $response, $args)
    {
        global $log;
        $router = RouteContext::fromRequest($request)->getRouteParser();

        //get post
        if ($request->getMethod() === 'POST') {
            $errors = $request->getAttribute('errors');
            $user_guid = $request->getAttribute('user_guid');
            $hotel_guid = $request->getAttribute('hotel_guid');

            //if errors
            if ($errors) {
                foreach ($errors as $msg) {
                    $this->flash->addMessage('errors', $msg);
                }
                return $response->withHeader('Location', $router->urlFor('user_unsubscribe'))->withStatus(302);
            } else {
                // DO LOGIN
                $body = $request->getParsedBody();
                if ($hotel_guid) {
                    $params = ['user_guid' => $user_guid, 'hotel_guid' => $hotel_guid];
                    $redirect_to = 'user_unsubscribe';
                } else {
                    $params = ['user_guid' => $user_guid];
                    $redirect_to = 'old_user_unsubscribe';
                }

                $unsubscribeNotifications = isset($body['unsubscribe_notifications']) ? true : false;
                $unsubscribeCommercialProfile = isset($body['unsubscribe_commercial_profile']) ? true : false;

                if ($unsubscribeNotifications || $unsubscribeCommercialProfile) {
                    $user = $this->getUser($user_guid, $response);
                    $hotel = $this->getHotel($hotel_guid, $response);

                    if ($hotel) {
                        if ($user) {

                            $brand = $hotel->brand;
                            // Send data to api
                            $this->unsubscribeUser($brand->id, $user->id, $unsubscribeNotifications, $unsubscribeCommercialProfile);
                        }
                    } else {
                        //no hotel guid in url -> old case, unsubscribe from all hotels.
                        $user_hotels = UserHotel::where('id_usuario', '=', $user->id)->get();
                        foreach ($user_hotels as $user_hotel) {
                            try {
                                $brand_id = $user_hotel->hotel->brand->id;
                                $user = $user_hotel->user;
                                // Send data to api
                                $this->unsubscribeUser($brand_id, $user->id);
                            } catch (Exception $e) {
                                $log->error('Error emiting UserUnsubscribeEvent', ['user_hotel' => json_encode($user_hotel), 'error' => ['message' => $e->getMessage(), 'file' => $e->getFile(), 'line' => $e->getLine()]]);
                            }
                        }
                    }
                    return $response->withHeader('Location', $router->urlFor($redirect_to, $params))->withStatus(302);
                } else {
                    return $response->withHeader('Location', $router->urlFor($redirect_to, $params))->withStatus(302);
                }
            }
        }
    }

    /**
     * Send data to api to perform unsubscription
     * 
     * @param Integer $brand_id Brand id
     * @param User $user User object
     * @param String $user_guid User guid
     * 
     * @return null
     */
    public static function unsubscribeUser($brandId, $userId, $unsubscribeNotifications = false, $unsubscribeCommercialProfile = false)
    {
        global $log;

        $data = [
            'unsubscribe_notifications' => $unsubscribeNotifications,
            'unsubscribe_commercial_profile' => $unsubscribeCommercialProfile
        ];
       
        $gateway =  new ApiGatewayConnection();
        $gateway->sendRequest($data,  HOTELINKING_ENDPOINT . 'brands/' . $brandId . '/users/' . $userId . '/unsubscribe', 'PUT');
        $log->info('user unsubscribed', ['user_id' => $userId, 'brand_id' => $brandId]);
    }
}
