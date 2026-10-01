<?php

namespace App\Middlewares;

use App\Services\SsoService;

class VerifySsoToken
{
    private $ssoService;

    public function __construct()
    {
        $this->ssoService = new SsoService();
    }

    /**
     * Intercept request and validate SSO token.
     *
     * @param string|null $token
     * @return array
     */
    public function handle(?string $token): array
    {
        if (empty($token)) {
            return [
                'authorized' => false,
                'error'      => 'Missing access token in request URL',
                'redirect'   => BASE_PATH . 'login/?error=missing_token'
            ];
        }

        $result = $this->ssoService->verifyToken($token);

        if (!$result['success']) {
            return [
                'authorized' => false,
                'error'      => $result['message'] ?? 'Invalid or expired SSO token',
                'redirect'   => BASE_PATH . 'login/?error=invalid_token'
            ];
        }

        // Token is valid
        $_SESSION['sso_verified'] = true;
        $_SESSION['sso_token'] = $token;
        $_SESSION['sso_user'] = $result['data']['user'] ?? null;

        return [
            'authorized' => true,
            'data'       => $result['data'] ?? [],
            'roles'      => $this->ssoService->getAvailableRoles()
        ];
    }
}
