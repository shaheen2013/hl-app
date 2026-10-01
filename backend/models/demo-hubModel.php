<?php

if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}

// Model for Demo Hub SSO Integration
function getDemoHubRolesList()
{
    $ssoService = new \App\Services\SsoService();
    return $ssoService->getAvailableRoles();
}
