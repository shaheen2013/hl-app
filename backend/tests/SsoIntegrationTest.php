<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Services\SsoService;
use App\Middlewares\VerifySsoToken;

class SsoIntegrationTest extends TestCase
{
    private $ssoService;
    private $middleware;

    protected function setUp(): void
    {
        parent::setUp();
        if (!defined('BASE_PATH')) {
            define('BASE_PATH', 'http://localhost:9005/');
        }
        if (!defined('SECURE_BASE_PATH')) {
            define('SECURE_BASE_PATH', 'https://localhost:9005/');
        }
        if (!defined('MOCK_SSO')) {
            define('MOCK_SSO', true);
        }
        if (!defined('HR_SSO_VERIFY_URL')) {
            define('HR_SSO_VERIFY_URL', 'https://hr.mediusware.xyz/api/demo/validate/');
        }

        $this->ssoService = new SsoService();
        $this->middleware = new VerifySsoToken();
    }

    /**
     * Test missing token scenario returns error and redirect.
     */
    public function testMissingTokenFailsValidation()
    {
        $result = $this->ssoService->verifyToken('');
        $this->assertFalse($result['success']);
        $this->assertEquals('error', $result['status']);

        $middlewareResult = $this->middleware->handle(null);
        $this->assertFalse($middlewareResult['authorized']);
        $this->assertStringContainsString('missing_token', $middlewareResult['redirect']);
    }

    /**
     * Test mock token verification returns success and default admin user data.
     */
    public function testMockTokenVerificationSucceeds()
    {
        $result = $this->ssoService->verifyToken('valid-demo-mock-token');
        $this->assertTrue($result['success']);
        $this->assertEquals('success', $result['status']);
        $this->assertNotEmpty($result['data']['user']['email']);
    }

    /**
     * Test middleware validates token and returns all available demo roles.
     */
    public function testMiddlewareReturnsAvailableRolesOnSuccess()
    {
        $middlewareResult = $this->middleware->handle('test-token-12345');
        $this->assertTrue($middlewareResult['authorized']);
        $this->assertArrayHasKey('account_admin', $middlewareResult['roles']);
        $this->assertArrayHasKey('brand_admin', $middlewareResult['roles']);
        $this->assertArrayHasKey('hotel_admin', $middlewareResult['roles']);
        $this->assertArrayHasKey('chain_admin', $middlewareResult['roles']);
        $this->assertArrayHasKey('staff', $middlewareResult['roles']);
        $this->assertArrayHasKey('guest', $middlewareResult['roles']);
    }

    /**
     * Test role list contains comprehensive metadata for modal and switcher.
     */
    public function testAvailableRolesMetadata()
    {
        $roles = $this->ssoService->getAvailableRoles();
        $this->assertCount(6, $roles);

        foreach ($roles as $key => $role) {
            $this->assertArrayHasKey('title', $role);
            $this->assertArrayHasKey('email', $role);
            $this->assertArrayHasKey('icon', $role);
            $this->assertArrayHasKey('badge_color', $role);
            $this->assertArrayHasKey('description', $role);
        }
    }
}
