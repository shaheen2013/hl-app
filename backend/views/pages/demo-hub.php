<?php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}

$ssoServiceInstance = new \App\Services\SsoService();
$allRoles = $ssoServiceInstance->getAvailableRoles();
$isMockMode = $ssoServiceInstance->isMock();
$verifyEndpoint = $ssoServiceInstance->getVerifyUrl();
$tokenDisplay = $token ?? ($_SESSION['sso_token'] ?? 'demo-token');
?>

<div class="container-fluid" style="min-height: 85vh; background: #f8fafc; padding: 40px 20px;">
    <div style="max-width: 1000px; margin: 0 auto;">
        
        <!-- Header Banner -->
        <div style="background: linear-gradient(135deg, #1e1b4b 0%, #312e81 60%, #4338ca 100%); border-radius: 16px; padding: 32px 36px; color: #ffffff; margin-bottom: 28px; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);">
            <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;">
                <div>
                    <div style="display: inline-flex; align-items: center; gap: 6px; background: rgba(255, 255, 255, 0.15); padding: 4px 12px; border-radius: 9999px; font-size: 12px; font-weight: 600; margin-bottom: 12px; backdrop-filter: blur(8px);">
                        <i class="fa fa-shield"></i> Hotelinking SSO Demo Hub
                    </div>
                    <h1 style="font-size: 28px; font-weight: 800; margin: 0 0 8px; color: #ffffff; letter-spacing: -0.02em;">
                        Single Sign-On Integration Gateway
                    </h1>
                    <p style="font-size: 14px; color: #c7d2fe; margin: 0; max-width: 620px; line-height: 1.5;">
                        Secure SSO token verification portal. Access any role-wise seeded environment instantly or test token validation against the remote SSO endpoint.
                    </p>
                </div>
                <div style="background: rgba(0, 0, 0, 0.25); border: 1px solid rgba(255, 255, 255, 0.15); border-radius: 12px; padding: 14px 18px; font-size: 12px;">
                    <div style="color: #94a3b8; margin-bottom: 4px;">Verification Status:</div>
                    <div style="color: #10b981; font-weight: 700; font-size: 14px; display: flex; align-items: center; gap: 6px;">
                        <i class="fa fa-check-circle"></i> Token Authorized
                    </div>
                    <div style="color: #cbd5e1; font-size: 11px; margin-top: 4px;">
                        Mode: <span style="color: #fbbf24; font-weight: 600;"><?php echo $isMockMode ? 'MOCK SSO (Local)' : 'LIVE SSO'; ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Available Roles Grid -->
        <div style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; padding: 28px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); margin-bottom: 28px;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; padding-bottom: 14px; border-bottom: 1px solid #f1f5f9;">
                <div>
                    <h2 style="font-size: 18px; font-weight: 700; color: #0f172a; margin: 0 0 4px;">
                        Select a Role-wise User to Log In
                    </h2>
                    <p style="font-size: 13px; color: #64748b; margin: 0;">
                        Click on any card to simulate direct SSO login into the target application module.
                    </p>
                </div>
                <button type="button" onclick="$('#roleSelectModal').modal('show');" class="btn btn-default" style="font-size: 13px; font-weight: 600; border-radius: 8px;">
                    <i class="fa fa-expand"></i> Modal View
                </button>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 18px;">
                <?php foreach ($allRoles as $rKey => $r): ?>
                <div style="border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 20px; background: #ffffff; display: flex; flex-direction: column; justify-content: space-between; transition: all 0.2s ease;" class="hub-role-card">
                    <div>
                        <div style="display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 12px;">
                            <div style="width: 42px; height: 42px; border-radius: 10px; background: <?php echo $r['badge_color']; ?>15; color: <?php echo $r['badge_color']; ?>; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                                <i class="fa <?php echo $r['icon']; ?>"></i>
                            </div>
                            <span style="font-size: 11px; font-weight: 700; padding: 3px 8px; border-radius: 9999px; background: <?php echo $r['badge_color']; ?>18; color: <?php echo $r['badge_color']; ?>; text-transform: uppercase;">
                                <?php echo $r['badge']; ?>
                            </span>
                        </div>
                        <h3 style="font-size: 16px; font-weight: 700; color: #0f172a; margin: 0 0 4px;">
                            <?php echo htmlspecialchars($r['title']); ?>
                        </h3>
                        <div style="font-size: 12px; color: #64748b; margin-bottom: 8px;">
                            <i class="fa fa-envelope-o"></i> <?php echo htmlspecialchars($r['email']); ?>
                        </div>
                        <p style="font-size: 12px; color: #475569; line-height: 1.45; margin: 0 0 16px; min-height: 36px;">
                            <?php echo htmlspecialchars($r['description']); ?>
                        </p>
                    </div>

                    <form method="POST" action="<?php echo BASE_PATH; ?>demo-hub/select-role/" style="margin: 0;">
                        <input type="hidden" name="select_role" value="1">
                        <input type="hidden" name="role" value="<?php echo $rKey; ?>">
                        <input type="hidden" name="token" value="<?php echo htmlspecialchars($tokenDisplay); ?>">
                        <button type="submit" class="btn btn-block" style="background: <?php echo $r['badge_color']; ?>; color: #ffffff; font-weight: 600; font-size: 13px; border-radius: 8px; padding: 9px 16px; border: none; width: 100%; transition: opacity 0.2s ease;">
                            Launch as <?php echo htmlspecialchars($r['title']); ?> &rarr;
                        </button>
                    </form>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Technical Integration Specs Card -->
        <div style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; padding: 24px 28px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);">
            <h3 style="font-size: 15px; font-weight: 700; color: #0f172a; margin: 0 0 12px; display: flex; align-items: center; gap: 8px;">
                <i class="fa fa-code" style="color: #4f46e5;"></i> SSO Integration Configuration
            </h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 14px; font-size: 12px;">
                <div style="background: #f8fafc; padding: 12px; border-radius: 8px; border: 1px solid #e2e8f0;">
                    <div style="color: #64748b; font-weight: 600; margin-bottom: 2px;">SSO Verify URL:</div>
                    <code style="color: #0f172a; word-break: break-all; font-size: 11px;"><?php echo htmlspecialchars($verifyEndpoint); ?></code>
                </div>
                <div style="background: #f8fafc; padding: 12px; border-radius: 8px; border: 1px solid #e2e8f0;">
                    <div style="color: #64748b; font-weight: 600; margin-bottom: 2px;">Callback Route:</div>
                    <code style="color: #0f172a; font-size: 11px;">GET /demo-hub/access/{token}</code>
                </div>
                <div style="background: #f8fafc; padding: 12px; border-radius: 8px; border: 1px solid #e2e8f0;">
                    <div style="color: #64748b; font-weight: 600; margin-bottom: 2px;">MOCK_SSO Flag:</div>
                    <code style="color: <?php echo $isMockMode ? '#10b981' : '#ef4444'; ?>; font-weight: 700; font-size: 11px;">
                        <?php echo $isMockMode ? 'true (Local Dev Active)' : 'false (Remote Active)'; ?>
                    </code>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Render Modal -->
<?php include_once TEMPLATES . 'RoleSelectModal.php'; ?>

<style>
.hub-role-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 24px -6px rgba(0, 0, 0, 0.1);
    border-color: #cbd5e1 !important;
}
.hub-role-card button:hover {
    opacity: 0.9;
}
</style>
