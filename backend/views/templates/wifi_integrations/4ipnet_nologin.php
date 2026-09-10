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
                    <div class="col-lg-12">
                        <label><?php echo $hotelGoalsLang['Fill with captive portal'] ?></label>
                        <input type="text" id='stay-login-form' value="<?php echo $urlWifiStay['form_url'] ?>"
                               placeholder="<?php echo $hotelGoalsLang['Fill with captive portal'] ?>..."
                               class="form-control">
                    </div>
                </div>
            </td>
        </tr>
    </table>
</div>