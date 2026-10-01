<?php

if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}

use App\Middlewares\VerifySsoToken;
use App\Services\SsoService;

$ssoService = new SsoService();
$interceptor = new VerifySsoToken();

$action = $url['dir2'] ?? 'index';
$token = $url['dir3'] ?? ($_GET['token'] ?? null);

// Handle Role Selection / Login submission
if (($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['select_role'])) || $action === 'select-role') {
    $selectedRole = $_REQUEST['role'] ?? 'account_admin';
    $loginResult = $ssoService->loginAsRole($selectedRole);

    if (!empty($_REQUEST['ajax'])) {
        header('Content-Type: application/json');
        echo json_encode($loginResult);
        exit;
    }

    $redirectUrl = $loginResult['redirectUrl'] ?? (BASE_PATH . 'hotel-home/');
    header('Location: ' . $redirectUrl);
    exit;
}

// Handle User Switching from Top Bar
if ($action === 'switch-user') {
    $roleToSwitch = $_REQUEST['role'] ?? 'account_admin';
    $returnUrl = $_REQUEST['return_url'] ?? $_SERVER['HTTP_REFERER'] ?? (BASE_PATH . 'hotel-home/');
    
    $loginResult = $ssoService->loginAsRole($roleToSwitch);

    if (!empty($_REQUEST['ajax'])) {
        header('Content-Type: application/json');
        echo json_encode([
            'success'     => true,
            'role'        => $roleToSwitch,
            'redirectUrl' => $returnUrl
        ]);
        exit;
    }

    header('Location: ' . $returnUrl);
    exit;
}

// Handle SSO Access Callback: /demo-hub/access/{token}
if ($action === 'access') {
    if (empty($token)) {
        header('Location: ' . BASE_PATH . 'hotel-login/?error=' . urlencode('Missing SSO access token'));
        exit;
    }

    // Intercept and verify token
    $verification = $interceptor->handle($token);

    if (!$verification['authorized']) {
        header('Location: ' . BASE_PATH . 'hotel-login/?error=' . urlencode($verification['error']));
        exit;
    }

    // Valid token: prepare available roles for modal display
    $availableRoles = $verification['roles'];
    $ssoUser = $verification['data']['user'] ?? null;
    $showRoleModal = true;
    $verifiedToken = $token;
} else {
    // Default demo hub landing
    $availableRoles = $ssoService->getAvailableRoles();
    $showRoleModal = true;
    $verifiedToken = $_SESSION['sso_token'] ?? 'mock-demo-token';
}
