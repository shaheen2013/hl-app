<?php
require_once RUTA_DIR . LIB . 'apiGateway.php';
require_once RUTA_DIR . LIB . 'utils.php';

class DeviceBlacklist
{
    const BLACK_LIST_FOUND_MESSAGE = 'BLACKLIST DEVICE FOUND';
    const PRODUCT = 'ip_binding';
    const CACHE_PREFIX = 'DEVICES_HOTEL_';
    const CACHE_TTL = 30; // In days
    const EVENT_NAME = 'device_rejected';
    const EVENT_SOURCE = 'Device';
    const DEVICE_END_POINT = 'device/blacklist';

    private $log;
    private $hotelUUID;
    private $remoteIp;
    private $userMac;
    private $langMessage;

    public function __construct(array $urlSegments, Monolog\Logger $log)
    {
        $this->setHotelUUID($urlSegments);
        $this->setMessage();
        $this->setUserMac();
        $this->remoteIp = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $this->log = $log;
    }

    public function stopIfBlacklisted()
    {
        $deviceFound = $this->findDevice();
        if ($deviceFound['found']) {
            $this->log->warning(self::BLACK_LIST_FOUND_MESSAGE, $deviceFound);
            $this->sendEvent($deviceFound);

            header('HTTP/1.0 403 Forbidden');
            echo $this->langMessage;
            exit();
        }
    }

    public function findDevice(): array
    {
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? "";
        $devices = $this->getDevices() ?? [];

        foreach ($devices as $device) {
            if (stripos($userAgent, $device) !== false) {
                $deviceInfo = [
                    'found'   => true,
                    'device'  => $device,
                    'message' => $this->langMessage,
                    'info'    => [
                        'hotel_uuid' => $this->hotelUUID,
                        'mac'        => $this->userMac,
                        'user_agent' => $userAgent,
                        'ip'         => $this->remoteIp
                    ]
                ];

                return $deviceInfo;
            }
        }

        return [
            'found'   => false,
            'device'  => null,
            'message' => "",
            'info'    => []
        ];
    }

    public function getDevices(): array
    {
        if ($cache = getFromCache(self::CACHE_PREFIX)) {
            return $cache->get();
        }

        $gateway = new ApiGatewayConnection();
        $response = $this->safeJsonParser($gateway->sendRequest(null, HOTELINKING_ENDPOINT . self::DEVICE_END_POINT, 'GET'));
        $devices = array_column($response, 'device');

        setToCache(self::CACHE_PREFIX, $devices, self::CACHE_TTL * 86400);

        return $devices;
    }

    public function isValidUuid($uuid)
    {
        if (!is_string($uuid) || (preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/', $uuid) !== 1)) {
            return false;
        }

        return true;
    }

    private function sendEvent(array $device)
    {
        $payload = [
            'hotel'  => [
                'uuid' => $this->hotelUUID
            ],
            'device' => [
                'name'       => $device['device'],
                'mac'        => $device['info']['mac'],
                'user_agent' => $device['info']['user_agent'],
                'ip'         => $device['info']['ip']
            ]
        ];

        emitEvent(self::EVENT_SOURCE, self::EVENT_NAME, $payload);
    }

    private function setMessage()
    {
        $treatment = 'formal';
        include_once LANG . $_SESSION['userLang'] . '/stay-wifi-redirect.php';
        $this->langMessage = $stayWifiRedirect['device_blacklisted'];
    }

    private function setHotelUUID(array $urlSegments)
    {
        $uuid = $urlSegments['dir2'] ?? null;
        $this->hotelUUID = $this->isValidUuid($uuid) ? $uuid : "";
    }

    private function setUserMac()
    {
        $userMac = $_SESSION['mac'] ?? $_REQUEST['mac'] ?? "";
        $this->userMac = macFormatter($userMac);
    }

    private function safeJsonParser($object, $assoc = true)
    {
        if ($object) {
            return \GuzzleHttp\json_decode($object, $assoc);
        }

        return [];
    }
}
