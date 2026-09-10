<div class="table-responsive mt2 relative">
    <table class="table table-striped table-bordered mb0" id="post-goals-div">
        <tr class="table-header">
            <td>
                <?php echo $hotelGoalsLang['Access point WIFI url'] ?>
            </td>
            <td class="text-right">
                <?php echo $hotelGoalsLang['Actions'] ?>
            </td>
        </tr>
        <tr>
            <td>
                <div class="row">
                    <div class="col-lg-12">
                        <label><?php echo $hotelGoalsLang['Fill with captive portal'] ?></label>
                        <input type="text" id='selectStay' value="<?php echo $urlWifiStay['url']; ?>" placeholder="<?php echo $hotelGoalsLang['Fill with captive portal'] ?>..." class="form-control">
                    </div>
                </div>
            </td>
            <td>
                <div class="btn-group goal-action-group pull-right" role="group" aria-label="...">
                    <button class="removeGoal btn btn-danger" onclick="removeStay('stay')"><i class="fa fa-times"></i></button>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="row">
                    <div class="col-lg-12">
                        <label><?php echo $hotelGoalsLang['Fill with login form url'] ?></label>
                        <input type="text" id='stay-login-form' value="<?php echo $urlWifiStay['form_url'] ?>" placeholder="<?php echo $hotelGoalsLang['Fill with login form url'] ?>..." class="form-control">
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="row">
                    <div class="col-lg-6">
                        <label><?php echo $hotelGoalsLang['login form Username'] ?></label>
                        <input type="text" id='stay-username' value="<?php echo $urlWifiStay['username'] ?>" placeholder="<?php echo $hotelGoalsLang['login form Username'] ?>.." class="form-control">
                    </div>
                    <div class="col-lg-6">
                        <label><?php echo $hotelGoalsLang['login form Password'] ?></label>
                        <input type="text" id='stay-password' value="<?php echo $urlWifiStay['password'] ?>" placeholder="<?php echo $hotelGoalsLang['login form Password'] ?>..." class="form-control">
                    </div>
                </div>
            </td>
        </tr>
    </table>
</div>