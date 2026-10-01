<?php
$ssoHelper = new \App\Services\SsoService();
$allRolesList = $ssoHelper->getAvailableRoles();

// Determine current role info
$currentRoleKey = 'account_admin';
$currentRoleTitle = 'Account Admin';
$currentRoleEmail = 'admin@hotelinking.com';
$currentRoleBadge = 'Full Access';
$currentRoleColor = '#4f46e5';

if (!empty($_SESSION['demo_user'])) {
    $currentRoleKey = $_SESSION['demo_user']['role_key'] ?? 'account_admin';
    $currentRoleTitle = $_SESSION['demo_user']['role_title'] ?? 'Account Admin';
    $currentRoleEmail = $_SESSION['demo_user']['email'] ?? 'admin@hotelinking.com';
    $currentRoleBadge = $_SESSION['demo_user']['badge'] ?? 'Full Access';
    $currentRoleColor = $_SESSION['demo_user']['badge_color'] ?? '#4f46e5';
} elseif (!empty($_SESSION['staff_logueado'])) {
    $roleId = $_SESSION['staff_role'] ?? 1;
    if ($roleId == 1) {
        $currentRoleKey = 'account_admin';
        $currentRoleTitle = 'Account Admin';
        $currentRoleEmail = 'admin@hotelinking.com';
        $currentRoleColor = '#4f46e5';
    } elseif ($roleId == 2) {
        $currentRoleKey = 'brand_admin';
        $currentRoleTitle = 'Brand Admin';
        $currentRoleEmail = 'brandadmin@hotelinking.com';
        $currentRoleColor = '#0284c7';
    } else {
        $currentRoleKey = 'staff';
        $currentRoleTitle = 'Staff Member';
        $currentRoleEmail = 'staff@hotelinking.com';
        $currentRoleColor = '#d97706';
    }
} elseif (!empty($_SESSION['c_logueado'])) {
    $currentRoleKey = 'chain_admin';
    $currentRoleTitle = 'Chain Admin';
    $currentRoleEmail = 'chain@hotelinking.com';
    $currentRoleColor = '#7c3aed';
} elseif (!empty($_SESSION['h_logueado'])) {
    $currentRoleKey = 'hotel_admin';
    $currentRoleTitle = 'Hotel Owner';
    $currentRoleEmail = 'hotel@hotelinking.com';
    $currentRoleColor = '#059669';
} elseif (!empty($_SESSION['u_logueado'])) {
    $currentRoleKey = 'guest';
    $currentRoleTitle = 'Guest User';
    $currentRoleEmail = 'guest@hotelinking.com';
    $currentRoleColor = '#db2777';
}
?>

<!-- Demo SSO Sticky Top Bar -->
<div id="demo-sso-topbar" style="position: sticky; top: 0; left: 0; right: 0; z-index: 999999; background: #0f172a; color: #f8fafc; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.4); border-bottom: 2px solid <?php echo $currentRoleColor; ?>;">
    <div style="max-width: 1400px; margin: 0 auto; padding: 6px 16px; display: flex; align-items: center; justify-content: space-between; gap: 12px; font-size: 13px;">
        
        <!-- Left: Branding & Status -->
        <div style="display: flex; align-items: center; gap: 10px;">
            <a href="<?php echo BASE_PATH; ?>demo-hub/access/demo-token/" title="SSO Demo Hub" style="display: flex; align-items: center; gap: 8px; text-decoration: none; color: #ffffff;">
                <span style="display: inline-flex; width: 22px; height: 22px; border-radius: 6px; background: linear-gradient(135deg, #4f46e5, #06b6d4); align-items: center; justify-content: center; font-size: 11px; font-weight: 800; color: #ffffff;">
                    HL
                </span>
                <strong style="font-size: 13px; font-weight: 700; letter-spacing: -0.01em; color: #f8fafc;">SSO Demo Hub</strong>
            </a>
            <span style="display: inline-flex; align-items: center; gap: 5px; font-size: 11px; color: #10b981; background: rgba(16, 185, 129, 0.12); padding: 2px 7px; border-radius: 9999px; font-weight: 600;">
                <span style="width: 6px; height: 6px; border-radius: 50%; background: #10b981; display: inline-block; animation: sso-pulse 2s infinite;"></span>
                Active
            </span>
        </div>

        <!-- Center: Current Active User Profile -->
        <div style="display: flex; align-items: center; gap: 10px;">
            <span style="color: #94a3b8; font-size: 12px;">Logged in as:</span>
            <span style="display: inline-flex; align-items: center; gap: 6px; background: rgba(255, 255, 255, 0.08); padding: 3px 10px; border-radius: 6px; border: 1px solid rgba(255, 255, 255, 0.12);">
                <span style="width: 8px; height: 8px; border-radius: 50%; background: <?php echo $currentRoleColor; ?>;"></span>
                <strong style="color: #ffffff; font-weight: 600;"><?php echo htmlspecialchars($currentRoleTitle); ?></strong>
                <span style="color: #cbd5e1; font-size: 11px; font-family: monospace;">(<?php echo htmlspecialchars($currentRoleEmail); ?>)</span>
            </span>
        </div>

        <!-- Right: Role Switcher Dropdown & Actions -->
        <div style="display: flex; align-items: center; gap: 10px;">
            <!-- Dropdown Container -->
            <div class="sso-dropdown" style="position: relative; display: inline-block;">
                <button id="ssoRoleDropdownBtn" type="button" 
                        style="background: linear-gradient(135deg, #4f46e5, #4338ca); color: #ffffff; border: none; padding: 5px 12px; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 6px; box-shadow: 0 2px 6px rgba(79, 70, 229, 0.3); transition: all 0.2s ease;">
                    <i class="fa fa-users"></i>
                    <span>Switch User</span>
                    <i class="fa fa-chevron-down" style="font-size: 10px; opacity: 0.8;"></i>
                </button>

                <!-- Dropdown Menu -->
                <div id="ssoRoleDropdownMenu" style="display: none; position: absolute; right: 0; top: 100%; margin-top: 6px; width: 290px; background: #1e293b; border: 1px solid #334155; border-radius: 10px; box-shadow: 0 15px 30px rgba(0, 0, 0, 0.5); z-index: 1000000; overflow: hidden; padding: 6px 0;">
                    <div style="padding: 6px 14px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #94a3b8; border-bottom: 1px solid #334155;">
                        Select Demo Role User
                    </div>
                    <?php foreach ($allRolesList as $rKey => $rData): ?>
                    <a href="<?php echo BASE_PATH; ?>demo-hub/switch-user/?role=<?php echo $rKey; ?>&return_url=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>"
                       style="display: flex; align-items: center; justify-content: space-between; padding: 9px 14px; text-decoration: none; color: #f1f5f9; transition: background 0.15s ease; border-left: 3px solid <?php echo ($rKey === $currentRoleKey ? $rData['badge_color'] : 'transparent'); ?>; background: <?php echo ($rKey === $currentRoleKey ? 'rgba(255, 255, 255, 0.06)' : 'transparent'); ?>;"
                       class="sso-dropdown-item">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <i class="fa <?php echo $rData['icon']; ?>" style="color: <?php echo $rData['badge_color']; ?>; width: 16px; text-align: center;"></i>
                            <div>
                                <div style="font-weight: 600; font-size: 12px; color: #ffffff;">
                                    <?php echo htmlspecialchars($rData['title']); ?>
                                </div>
                                <div style="font-size: 11px; color: #94a3b8;">
                                    <?php echo htmlspecialchars($rData['email']); ?>
                                </div>
                            </div>
                        </div>
                        <?php if ($rKey === $currentRoleKey): ?>
                            <span style="font-size: 10px; color: #10b981; font-weight: 700;">ACTIVE</span>
                        <?php endif; ?>
                    </a>
                    <?php endforeach; ?>
                    <div style="border-top: 1px solid #334155; margin-top: 4px; padding-top: 4px;">
                        <a href="javascript:void(0)" onclick="$('#roleSelectModal').modal('show'); $('#ssoRoleDropdownMenu').hide();"
                           style="display: flex; align-items: center; gap: 8px; padding: 8px 14px; font-size: 12px; color: #a5b4fc; text-decoration: none;"
                           class="sso-dropdown-item">
                            <i class="fa fa-th-large"></i> Open Full Role Selection Modal
                        </a>
                    </div>
                </div>
            </div>

            <!-- Role Modal Trigger -->
            <button type="button" onclick="$('#roleSelectModal').modal('show');" 
                    style="background: rgba(255, 255, 255, 0.1); color: #e2e8f0; border: 1px solid rgba(255, 255, 255, 0.15); padding: 5px 10px; border-radius: 6px; font-size: 12px; cursor: pointer; transition: all 0.2s ease;">
                <i class="fa fa-th-large"></i> Roles
            </button>

            <!-- Collapse Toggle -->
            <button type="button" id="ssoTopbarToggle" title="Minimize Top Bar"
                    style="background: transparent; border: none; color: #94a3b8; cursor: pointer; padding: 4px 6px; font-size: 13px;">
                <i class="fa fa-chevron-up"></i>
            </button>
        </div>

    </div>
</div>

<!-- Included Role Select Modal -->
<?php include_once TEMPLATES . 'RoleSelectModal.php'; ?>

<style>
@keyframes sso-pulse {
    0% { transform: scale(0.95); opacity: 0.8; }
    50% { transform: scale(1.3); opacity: 1; }
    100% { transform: scale(0.95); opacity: 0.8; }
}
.sso-dropdown-item:hover {
    background: #334155 !important;
}
#demo-sso-topbar a:focus, #demo-sso-topbar button:focus {
    outline: none;
}
</style>

<script>
$(document).ready(function() {
    var $menu = $('#ssoRoleDropdownMenu');
    var $btn = $('#ssoRoleDropdownBtn');

    $btn.on('click', function(e) {
        e.stopPropagation();
        $menu.toggle();
    });

    $(document).on('click', function(e) {
        if (!$(e.target).closest('.sso-dropdown').length) {
            $menu.hide();
        }
    });

    $('#ssoTopbarToggle').on('click', function() {
        var $topbar = $('#demo-sso-topbar > div');
        if ($topbar.is(':visible')) {
            $topbar.slideUp(150);
            $(this).html('<i class="fa fa-chevron-down"></i>');
        } else {
            $topbar.slideDown(150);
            $(this).html('<i class="fa fa-chevron-up"></i>');
        }
    });
});
</script>
