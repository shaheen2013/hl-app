<?php

namespace App\Services;

use GuzzleHttp\Client;
use Exception;

class SsoService
{
    private $verifyUrl;
    private $mockSso;

    public function __construct()
    {
        if (defined('HR_SSO_VERIFY_URL')) {
            $this->verifyUrl = HR_SSO_VERIFY_URL;
        } else {
            $this->verifyUrl = getenv('HR_SSO_VERIFY_URL') ?: 'https://hr.mediusware.xyz/api/demo/validate/';
        }

        if (defined('MOCK_SSO')) {
            $this->mockSso = (bool) MOCK_SSO;
        } else {
            $envMock = getenv('MOCK_SSO');
            $this->mockSso = $envMock !== false ? filter_var($envMock, FILTER_VALIDATE_BOOLEAN) : true;
        }
    }

    public function isMock(): bool
    {
        return $this->mockSso;
    }

    public function getVerifyUrl(): string
    {
        return $this->verifyUrl;
    }

    /**
     * Verify the SSO access token via remote endpoint or mock bypass.
     *
     * @param string $token
     * @return array
     */
    public function verifyToken(string $token): array
    {
        $token = trim($token);
        if (empty($token)) {
            return [
                'success' => false,
                'status'  => 'error',
                'message' => 'Token is required'
            ];
        }

        // Mock verification for local development
        if ($this->mockSso) {
            return [
                'success' => true,
                'status'  => 'success',
                'message' => 'Token verified successfully (Mock Mode)',
                'data'    => [
                    'user' => [
                        'email' => 'admin@hotelinking.com',
                        'role'  => 'admin'
                    ]
                ]
            ];
        }

        // Remote verification via HR SSO Endpoint
        try {
            $client = new Client([
                'timeout' => 10,
                'verify'  => false,
            ]);

            $response = $client->post($this->verifyUrl, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Accept'        => 'application/json',
                    'Content-Type'  => 'application/json',
                ],
                'json' => [
                    'token'        => $token,
                    'access_token' => $token,
                ]
            ]);

            $statusCode = $response->getStatusCode();
            $body = (string) $response->getBody();
            $data = json_decode($body, true);

            if ($statusCode >= 200 && $statusCode < 300 && !empty($data['success'])) {
                return [
                    'success' => true,
                    'status'  => 'success',
                    'message' => $data['message'] ?? 'Token verified successfully',
                    'data'    => $data['data'] ?? [
                        'user' => [
                            'email' => 'admin@hotelinking.com',
                            'role'  => 'admin'
                        ]
                    ]
                ];
            }

            return [
                'success' => false,
                'status'  => 'error',
                'message' => $data['message'] ?? 'Invalid or expired token'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'status'  => 'error',
                'message' => 'Verification service temporarily unavailable: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Available roles for the SSO Role Selection Modal and Top Bar Switcher.
     *
     * @return array
     */
    public function getAvailableRoles(): array
    {
        return [
            'account_admin' => [
                'key'         => 'account_admin',
                'title'       => 'Account Admin',
                'category'    => 'Staff Role (Level 1)',
                'email'       => 'admin@hotelinking.com',
                'name'        => 'Mediusware Account Admin',
                'badge'       => 'Full Access',
                'badge_color' => '#4f46e5',
                'icon'        => 'fa-shield',
                'description' => 'Complete administrative access over account settings, hotels, campaigns, and team members.',
                'target'      => 'Hotel & Staff Portal',
            ],
            'brand_admin' => [
                'key'         => 'brand_admin',
                'title'       => 'Brand Admin',
                'category'    => 'Staff Role (Level 2)',
                'email'       => 'brandadmin@hotelinking.com',
                'name'        => 'Mediusware Brand Admin',
                'badge'       => 'Brand Level',
                'badge_color' => '#0284c7',
                'icon'        => 'fa-building',
                'description' => 'Manages brand-wide properties, campaigns, loyalty programs, and guest reviews.',
                'target'      => 'Hotel & Brand Portal',
            ],
            'staff' => [
                'key'         => 'staff',
                'title'       => 'Staff Member',
                'category'    => 'Staff Role (Level 3)',
                'email'       => 'staff@hotelinking.com',
                'name'        => 'Mediusware Staff Member',
                'badge'       => 'Operational',
                'badge_color' => '#d97706',
                'icon'        => 'fa-users',
                'description' => 'Handles day-to-day reception tasks, guest survey incidents, and voucher redemptions.',
                'target'      => 'Staff Operational Portal',
            ],
            'hotel_admin' => [
                'key'         => 'hotel_admin',
                'title'       => 'Hotel Owner / Property Admin',
                'category'    => 'Hotel Account',
                'email'       => 'hotel@hotelinking.com',
                'name'        => 'Mediusware Grand Resort Palma',
                'badge'       => 'Property Owner',
                'badge_color' => '#059669',
                'icon'        => 'fa-hotel',
                'description' => 'Direct access to resort configuration, Wi-Fi captive portal settings, and bookings.',
                'target'      => 'Hotel Management Portal',
            ],
            'chain_admin' => [
                'key'         => 'chain_admin',
                'title'       => 'Chain Owner / Executive',
                'category'    => 'Chain Account',
                'email'       => 'chain@hotelinking.com',
                'name'        => 'Grand Medius Hotels & Resorts',
                'badge'       => 'Multi-Property',
                'badge_color' => '#7c3aed',
                'icon'        => 'fa-globe',
                'description' => 'Manages the overall hotel chain group, cross-property marketing, and group analytics.',
                'target'      => 'Chain Management Portal',
            ],
            'guest' => [
                'key'         => 'guest',
                'title'       => 'Guest / Loyalty Customer',
                'category'    => 'End User',
                'email'       => 'guest@hotelinking.com',
                'name'        => 'John Doe',
                'badge'       => 'Loyalty VIP',
                'badge_color' => '#db2777',
                'icon'        => 'fa-gift',
                'description' => 'Hotel guest loyalty portal for rewards points, redeemable vouchers, and survey feedback.',
                'target'      => 'Guest Rewards Portal',
            ],
        ];
    }

    /**
     * Authenticate session as the selected role's default user.
     *
     * @param string $roleKey
     * @return array
     */
    public function loginAsRole(string $roleKey): array
    {
        include_once RUTA_DIR . LIB . 'loguearHotel.php';
        include_once RUTA_DIR . LIB . 'cookieLogin.php';

        $roles = $this->getAvailableRoles();
        if (!isset($roles[$roleKey])) {
            $roleKey = 'account_admin';
        }
        $roleInfo = $roles[$roleKey];

        // Clear existing session state
        unset($_SESSION['h_logueado']);
        unset($_SESSION['u_logueado']);
        unset($_SESSION['c_logueado']);
        unset($_SESSION['staff_logueado']);

        $defaultPage = 'hotel-home';

        switch ($roleKey) {
            case 'account_admin':
                $res = loguearStaff(1, 1);
                $defaultPage = !empty($res['defaultPage']) ? $res['defaultPage'] : 'hotel-home';
                break;
            case 'brand_admin':
                $res = loguearStaff(2, 1);
                $defaultPage = !empty($res['defaultPage']) ? $res['defaultPage'] : 'hotel-home';
                break;
            case 'staff':
                $res = loguearStaff(3, 1);
                $defaultPage = !empty($res['defaultPage']) ? $res['defaultPage'] : 'hotel-home';
                break;
            case 'hotel_admin':
                $res = loguearHotel(1);
                $defaultPage = !empty($res['defaultPage']) ? $res['defaultPage'] : 'hotel-home';
                break;
            case 'chain_admin':
                $res = loguearCadena(1);
                $defaultPage = !empty($res['defaultPage']) ? $res['defaultPage'] : 'chain-management';
                break;
            case 'guest':
                $_SESSION['u_logueado'] = 1;
                $_SESSION['userName'] = 'John Doe';
                $_SESSION['userEmail'] = 'guest@hotelinking.com';
                $_SESSION['puntos'] = 750;
                $defaultPage = 'user-profile';
                break;
        }

        // Store demo session info for top bar and switchers
        $_SESSION['demo_user'] = [
            'role_key'    => $roleKey,
            'role_title'  => $roleInfo['title'],
            'email'       => $roleInfo['email'],
            'name'        => $roleInfo['name'],
            'badge'       => $roleInfo['badge'],
            'badge_color' => $roleInfo['badge_color'],
            'logged_at'   => date('Y-m-d H:i:s'),
        ];
        $_SESSION['sso_authenticated'] = true;

        borrarCookieLogin();

        return [
            'success'     => true,
            'role'        => $roleKey,
            'role_info'   => $roleInfo,
            'defaultPage' => $defaultPage,
            'redirectUrl' => BASE_PATH . $defaultPage . '/'
        ];
    }
}
