<?php
if (!isset($availableRoles) || empty($availableRoles)) {
    $ssoServiceHelper = new \App\Services\SsoService();
    $availableRoles = $ssoServiceHelper->getAvailableRoles();
}
$ssoTokenValue = $verifiedToken ?? ($_SESSION['sso_token'] ?? 'demo-token');
?>

<!-- Role Selection Modal -->
<div id="roleSelectModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="roleModalTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document" style="max-width: 900px; margin: 40px auto;">
        <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); overflow: hidden; background: #ffffff;">
            
            <!-- Modal Header -->
            <div class="modal-header" style="background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #4338ca 100%); color: #ffffff; padding: 24px 30px; border-bottom: none; position: relative;">
                <div style="display: flex; align-items: center; justify-content: space-between; width: 100%;">
                    <div style="display: flex; align-items: center; gap: 14px;">
                        <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(255, 255, 255, 0.15); display: flex; align-items: center; justify-content: center; backdrop-filter: blur(8px);">
                            <i class="fa fa-key" style="font-size: 20px; color: #a5b4fc;"></i>
                        </div>
                        <div>
                            <h3 id="roleModalTitle" style="margin: 0; font-size: 22px; font-weight: 700; color: #ffffff; letter-spacing: -0.02em;">
                                HR SSO Authentication Verified
                            </h3>
                            <p style="margin: 4px 0 0; font-size: 13px; color: #c7d2fe; opacity: 0.9;">
                                Token verified successfully. Select your target role profile to proceed to the dashboard.
                            </p>
                        </div>
                    </div>
                    <?php if (!empty($_SESSION['h_logueado']) || !empty($_SESSION['staff_logueado']) || !empty($_SESSION['c_logueado'])): ?>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #ffffff; opacity: 0.8; font-size: 26px; text-shadow: none;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="modal-body" style="padding: 28px 30px; background: #f8fafc;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
                    <div style="font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b;">
                        Available Demo Roles (<?php echo count($availableRoles); ?>)
                    </div>
                    <div style="font-size: 12px; color: #10b981; font-weight: 600; display: flex; align-items: center; gap: 6px;">
                        <span style="width: 8px; height: 8px; border-radius: 50%; background: #10b981; display: inline-block;"></span>
                        SSO Session Ready
                    </div>
                </div>

                <!-- Roles Grid -->
                <div class="roles-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px;">
                    <?php foreach ($availableRoles as $key => $role): ?>
                    <div class="role-card" data-role="<?php echo $key; ?>" 
                         style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 18px; cursor: pointer; transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); display: flex; flex-direction: column; justify-content: space-between; position: relative;">
                        
                        <div>
                            <!-- Header of Card -->
                            <div style="display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 12px;">
                                <div style="width: 40px; height: 40px; border-radius: 10px; background: <?php echo $role['badge_color']; ?>15; color: <?php echo $role['badge_color']; ?>; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                                    <i class="fa <?php echo $role['icon']; ?>"></i>
                                </div>
                                <span style="font-size: 11px; font-weight: 700; padding: 3px 8px; border-radius: 9999px; background: <?php echo $role['badge_color']; ?>18; color: <?php echo $role['badge_color']; ?>; text-transform: uppercase; letter-spacing: 0.04em;">
                                    <?php echo $role['badge']; ?>
                                </span>
                            </div>

                            <!-- Role Title & Info -->
                            <h4 style="margin: 0 0 4px; font-size: 16px; font-weight: 700; color: #0f172a;">
                                <?php echo htmlspecialchars($role['title']); ?>
                            </h4>
                            <div style="font-size: 12px; color: #64748b; margin-bottom: 8px; display: flex; align-items: center; gap: 4px;">
                                <i class="fa fa-envelope-o" style="font-size: 11px;"></i>
                                <?php echo htmlspecialchars($role['email']); ?>
                            </div>
                            <p style="font-size: 12px; color: #475569; margin: 0 0 14px; line-height: 1.45; min-height: 36px;">
                                <?php echo htmlspecialchars($role['description']); ?>
                            </p>
                        </div>

                        <!-- Action Button -->
                        <form method="POST" action="<?php echo BASE_PATH; ?>demo-hub/select-role/" style="margin: 0;">
                            <input type="hidden" name="select_role" value="1">
                            <input type="hidden" name="role" value="<?php echo $key; ?>">
                            <input type="hidden" name="token" value="<?php echo htmlspecialchars($ssoTokenValue); ?>">
                            <button type="submit" class="btn btn-block select-role-btn" 
                                    style="background: #f1f5f9; color: #0f172a; font-weight: 600; font-size: 13px; border-radius: 8px; padding: 8px 14px; border: 1px solid #e2e8f0; transition: all 0.2s ease; width: 100%;">
                                Login as <?php echo htmlspecialchars($role['title']); ?> &rarr;
                            </button>
                        </form>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Footer details -->
                <div style="margin-top: 22px; padding-top: 16px; border-top: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between; font-size: 12px; color: #94a3b8;">
                    <div>
                        <i class="fa fa-info-circle"></i> Password for all demo accounts: <code style="background: #e2e8f0; color: #334155; padding: 2px 6px; border-radius: 4px;">secret</code>
                    </div>
                    <div>
                        Hotelinking SSO Gateway v2.0
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
.role-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08), 0 8px 10px -6px rgba(0, 0, 0, 0.04);
    border-color: #cbd5e1 !important;
}
.role-card:hover .select-role-btn {
    background: #4f46e5 !important;
    color: #ffffff !important;
    border-color: #4f46e5 !important;
}
</style>

<script>
$(document).ready(function() {
    <?php if (!empty($showRoleModal)): ?>
    $('#roleSelectModal').modal('show');
    <?php endif; ?>
});
</script>
