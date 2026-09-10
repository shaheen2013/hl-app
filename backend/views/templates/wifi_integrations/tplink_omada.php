<div class="table-responsive mt2 relative">
    <table class="table table-striped table-bordered mb0" id="post-goals-div">
        <tr class="table-header">
            <td>
                Tp-Link integration data
            </td>
        </tr>
        <tr>
            <td>
                <div class="row">
                    <div class="col-lg-12">
                        <label>Server IP / URL</label>
                        <input type="text" id='stay-login-form' value="<?php echo $urlWifiStay['form_url'] ?>"
                               placeholder="Insert Tp-Link server IP..."
                               class="form-control">
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="row">
                    <div class="col-lg-3">
                        <label>Tp-Link Admin username</label>
                        <input type="text" id='stay-username' value="<?php echo $urlWifiStay['username'] ?>"
                               placeholder="Insert Tp-Link Admin username..."
                               class="form-control">
                    </div>
                    <div class="col-lg-3">
                        <label>Tp-Link Admin password</label>
                        <input type="password" id='stay-password' value="<?php echo $urlWifiStay['password'] ?>"
                               placeholder="Insert Tp-Link Admin password..."
                               class="form-control">
                    </div>
                    <div class="col-lg-3">
                        <label>Tp-Link authorize time in days</label>
                        <input type="text" id='stay-time' value="<?php echo $urlWifiStay['unifi_time'] ?>"
                               placeholder="Insert authorization time in days..."
                               class="form-control">
                    </div>
                </div>
                <div class="row mt2">
                    <div class="col-lg-12">
                        <label><?php echo $hotelGoalsLang['Fill with captive portal'] ?></label>
                        <input type="text" id='selectStay' value="<?php echo $urlWifiStay['url']; ?>" placeholder="<?php echo $hotelGoalsLang['Fill with captive portal'] ?>..." class="form-control">
                    </div>
                </div>
            </td>
        </tr>
    </table>
</div>