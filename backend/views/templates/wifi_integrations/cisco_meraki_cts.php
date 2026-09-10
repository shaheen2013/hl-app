<div class="table-responsive mt2 relative">
    <table class="table table-striped table-bordered mb0" id="post-goals-div">
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
                <?php echo $hotelGoalsLang['Please make sure Meraki is configured in order to use this option'] ?>
            </td>
        </tr>

    </table>
</div>