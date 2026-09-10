<div class="table-responsive mt2 relative">
    <table class="table table-striped table-bordered mb0" id="post-goals-div">
        <tr class="table-header">
            <td>
                <?php echo $hotelGoalsLang['Access point WIFI url'] ?>
            </td>
        </tr>
        <tr>
            <td>
                <div class="row">
                    <div class="col-lg-6">
                        <label><?php echo $hotelGoalsLang['login form Username'] ?></label>
                        <input type="text" id='stay-username' value="<?php echo $urlWifiStay['username'] ?>"
                               placeholder="<?php echo $hotelGoalsLang['login form Username'] ?>.."
                               class="form-control">
                    </div>
                    <div class="col-lg-6">
                        <label><?php echo $hotelGoalsLang['login form Password'] ?></label>
                        <input type="text" id='stay-password' value="<?php echo $urlWifiStay['password'] ?>"
                               placeholder="<?php echo $hotelGoalsLang['login form Password'] ?>..."
                               class="form-control">
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <input type="checkbox" id='stay-guest-enabled' class="form-check-input"  <?php echo($urlWifiStay['guest_enabled'] ? 'checked' : '')?>
                               >
                        <label for="stay-guest-enabled"><?php echo $hotelGoalsLang['guest_enabled'] ?></label>
                    </div>
                </div>
            </td>
        </tr>
    </table>
</div>