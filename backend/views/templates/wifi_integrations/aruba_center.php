<div class="table-responsive mt2 relative">
    <table class="table table-striped table-bordered mb0" id="post-goals-div">
        <tr class="table-header">
            <td>
                Aruba integration data
            </td>
        </tr>
        <tr>
            <td>
                <div class="row mt2">
                    <div class="col-lg-12">
                        <label><?php echo array_get($hotelGoalsLang, 'Fill with captive portal', ''); ?></label>
                        <input type="text" id='selectStay' value="<?php echo array_get($urlWifiStay, 'url', ''); ?>" placeholder="<?php echo array_get($hotelGoalsLang, 'Fill with captive portal', ''); ?>..." class="form-control">
                    </div>
                </div>
            </td>
        </tr>
    </table>
</div>