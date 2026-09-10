<?php //Miramos si esta definida la variable de control de index.php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
} ?>

<?php include LANG . $_SESSION['userLang'] . '/clients-reports-management.php' ?>
<div id="wrapper">
    <?php include TEMPLATES . 'hotel-sidebar.php'; ?>
    <div id="page-content-wrapper">
        <div class="top-bar">
            <?php include TEMPLATES . 'hotel-profile-menu.php'; ?>
            <img class="topbarLogo visible-xs" src="<?php echo DIR_IMG . 'topbar-logo-xs.png' ?>" width="77" height="70" alt="top bar logo">
        </div>
        <div class="utility-bar">
            <div class="col-lg-12">
                <h1 class="pull-left"><i class="fa fa-line-chart"></i><?php echo $lang['Title'] ?></h1>
                <div class="breadcrumbs pull-right">
                    <ul>
                        <?php include(TEMPLATES . 'breadcrumbs.php'); ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="mainContent" id="fullContainer">
            <div class="col-lg-12 mt2">
                <div class="col-lg-12">

                    <!-- Reports management -->
                    <div>
                        <h3><?php echo $lang['reports management'] ?></h3>

                        <div class="table-responsive mt2 relative mb0">
                            <table class="table table-striped table-bordered mb0" id="goals-div">
                                <tr class="table-header">
                                    <td style="width:13%;max-width:140px">
                                        <i data-toggle="tooltip" class="hasTooltip fa fa-question-circle" data-placement="top" title="" data-original-title="<?php echo $lang['frequency tooltip'] ?>"></i> <?php echo $lang['frequency'] ?>
                                    </td>
                                    <td>
                                        <i data-toggle="tooltip" class="hasTooltip fa fa-question-circle" data-placement="top" title="" data-original-title="<?php echo $lang['report_type tooltip'] ?>"></i>
                                        <?php echo $lang['report_type'] ?>
                                    </td>
                                    <td>
                                        <i data-toggle="tooltip" class="hasTooltip fa fa-question-circle" data-placement="top" title="" data-original-title='<?php echo $lang['emails tooltip'] ?>'></i>
                                        <?php echo $lang['emails'] ?>
                                    </td>
                                    <td style="width: 10%;min-width: 105px;" class="text-right">
                                        <?php echo $lang['actions'] ?>
                                    </td>
                                </tr>
                                <?php if ($hotelAutomaticReports) {
                                    foreach ($hotelAutomaticReports as $hotelAutomaticReport) {
                                ?>
                                        <tr>
                                            <td>
                                                <div class='col-lg-12'>
                                                    <div>
                                                        <input type='number' min="1" name='frequency' class='goal form-control frequency' value='<?php echo array_get($hotelAutomaticReport, 'frequency') ?>' placeholder='<?php echo $lang['days'] ?>'>
                                                        <input type="hidden" name="automatic_report_id" class="report_id" value="<?php echo array_get($hotelAutomaticReport, 'id') ?>">
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div>

                                                    <select class='goal-select form-control report_type' name="report_type">
                                                        <option <?php echo array_get($hotelAutomaticReport, 'report_type') == 'accumulated' ? 'selected' : '' ?> value="accumulated"><?php echo $lang['accumulated'] ?></option>
                                                        <option <?php echo array_get($hotelAutomaticReport, 'report_type') == 'segment' ? 'selected' : '' ?> value='segment'><?php echo $lang['segment'] ?></option>
                                                    </select>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <input type='text' name='report_emails' class='goal form-control emails' value='<?php echo array_get($hotelAutomaticReport, 'emails') ?>' placeholder='emails'>
                                                </div>

                                            </td>
                                            <td class='text-right'>
                                                <div class='btn-group goal-action-group' role='group' aria-label='...'>
                                                    <button class='saveGoal btn btn-info' onclick='saveGoal( this)'><i class='fa fa-save'></i></button>
                                                    <button class='removeGoal btn btn-danger' onclick='removeGoal( this)'><i class='fa fa-times'></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                <?php }
                                }
                                ?>
                                <tr>
                                    <td>
                                        <div class='col-lg-12'>
                                            <div>
                                                <input type='number' min="1" name='frequency' class='goal form-control frequency' value='' placeholder='<?php echo $lang['days'] ?>'>
                                                <input type="hidden" class="report_id" name="automatic_report_id" value="">
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>

                                            <select class='goal-select form-control report_type' name="report_type">
                                                <option value="accumulated"><?php echo $lang['accumulated'] ?></option>
                                                <option value='segment'><?php echo $lang['segment'] ?></option>


                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <input type='text' name='report_emails' class='goal form-control emails' value='' placeholder='emails'>
                                        </div>

                                    </td>
                                    <td class='text-right'>
                                        <div class='btn-group goal-action-group' role='group' aria-label='...'>
                                            <button class='saveGoal btn btn-info' onclick='saveGoal( this)'><i class='fa fa-save'></i></button>
                                            <button class='removeGoal btn btn-danger' onclick='removeGoal( this)'><i class='fa fa-times'></i></button>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <button class="addGoal btn btn-lg btn-success mt2" onclick="addBlankLine()"><i class="fa fa-plus"></i>
                            <?php echo $lang['Add new report'] ?>
                        </button>
                    </div>


                    <hr>

                    <!-- Portal pro validations -->
                    <?php if ($portalProActivated) { ?>
                        <div>
                            <h3><?php echo $lang['portal pro validations'] ?></h3>

                            <div class="table-responsive mt2 relative mb0">
                                <table class="table table-striped table-bordered mb0" id="goals-div">
                                    <tr class="table-header">
                                        <td style="width:13%;max-width:140px">
                                            <i data-toggle="tooltip" class="hasTooltip fa fa-question-circle" data-placement="top" title="" data-original-title="<?php echo $lang['frequency tooltip'] ?>"></i> <?php echo $lang['frequency'] ?>
                                        </td>
                                        <td>
                                            <i data-toggle="tooltip" class="hasTooltip fa fa-question-circle" data-placement="top" title="" data-original-title='<?php echo $lang['emails tooltip'] ?>'></i>
                                            <?php echo $lang['emails'] ?>
                                        </td>
                                        <td style="width: 10%;min-width: 105px;" class="text-right">
                                            <?php echo $lang['actions'] ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class='col-lg-12'>
                                                <div>
                                                    <input type='number' min="1" name='frequency' class='goal form-control portalProFrequency' value='<?php echo $hotelPortalProReports['config']['interval'] ?? "" ?>' placeholder='<?php echo $lang['days'] ?>'>
                                                    <input type="hidden" class="newPortalProReport" name="newPortalProReport" value="<?php echo $newPortalProReport ?>">
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <input type='text' name='report_emails' class='goal form-control portalProEmails' value='<?php echo $hotelPortalProReports['config']['email_subscriptions'] ?? "" ?>' placeholder='emails'>
                                            </div>

                                        </td>
                                        <td class='text-right'>
                                            <div class='btn-group goal-action-group' role='group' aria-label='...'>
                                                <button class='saveGoal btn btn-info' onclick='savePortalProReport(this)'><i class='fa fa-save'></i></button>
                                                <button class='removeGoal btn btn-danger' onclick='removePortalProReport( this)'><i class='fa fa-times'></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>

    </div>
</div>
<script src="<?php echo DIR_JS ?>feedback-errors.js"></script>
<script>
    function addBlankLine() {
        $('#goals-div').append("<tr>" +
            "<td>" +
            "<div class='col-lg-12'>" +
            "<div>" +
            "<input type='number' min=\"1\" name='frequency'" +
            "class='goal form-control frequency' value=''" +
            "placeholder='<?php echo $lang['frequency'] ?>'>" +
            " <input type='hidden' class='report_id' name='automatic_report_id' value=''>" +
            "</div>" +
            "</div>" +
            "<input type='hidden' value=''>" +
            "</td>" +
            "<td>" +
            "<div>" +
            "<select class='goal-select form-control report_type' name='report_type'>" +
            "<option value='accumulated'><?php echo $lang['accumulated'] ?></option>" +
            "<option value='segment'><?php echo $lang['segment'] ?></option>" +
            "</select>" +
            "</div>" +
            "</td>" +
            "<td>" +
            "<div>" +
            "<input type='text' name='report_emails'" +
            "class='goal form-control emails' value=''" +
            "placeholder='emails'>" +
            "</div>" +
            "</td>" +
            "<td class='text-right'>" +
            "<div class='btn-group goal-action-group' role='group' aria-label='...'>" +
            "<button class='saveGoal btn btn-info' onclick='saveGoal( this)'><i class='fa fa-save'></i></button>" +
            "<button class='removeGoal btn btn-danger' onclick='removeGoal( this)'><i class='fa fa-times'></i></button>" +
            "</div>" +
            "</td>" +
            "</tr>")
    }

    function removeGoal(element) {
        var parent = $(element).closest('tr');
        var automatic_report_id = $(parent).find('.report_id').first().val();
        if (automatic_report_id) {
            $.ajax({
                url: "/lib/webservices/clients-reports-management-ws.php",
                data: "&action=delete&automatic_report_id=" + automatic_report_id,
                type: 'POST',
                success: function(output) {
                    if (output !== 'error') {
                        $(element).closest('tr').remove();
                        $.ajax({
                            url: "/lib/webservices/msgFeedback.php",
                            data: "nError=2007&lang=<?php echo $_SESSION['userLang'] ?>",
                            type: 'POST',
                            success: function(output) {
                                data = $.parseJSON(output);
                                showError(data);
                            }
                        });
                    } else {
                        alert('error')
                    }
                }
            });
        } else {
            $(element).closest('tr').remove();
        }
    }

    function saveGoal(element) {
        var parent = $(element).closest('tr');
        var automatic_report_id = $(parent).find('.report_id').first().val();
        var emails = $(parent).find('.emails').first().val();
        var report_type = $(parent).find('.report_type').first().val();
        var frequency = $(parent).find('.frequency').first().val();
        $.ajax({
            url: "/lib/webservices/clients-reports-management-ws.php",
            data: "&action=save&hotel_id=<?php echo $hotelId ?>&automatic_report_id=" + automatic_report_id + "&emails=" + emails + "&chain_id=&report_type=" + report_type + "&frequency=" + frequency,
            type: 'POST',
            success: function(output) {
                if (output !== 'error') {
                    $(parent).find('.report_id').first().val(output);
                    $.ajax({
                        url: "/lib/webservices/msgFeedback.php",
                        data: "nError=2007&lang=<?php echo $_SESSION['userLang'] ?>",
                        type: 'POST',
                        success: function(output) {
                            data = $.parseJSON(output);
                            showError(data);
                        }
                    });
                } else {
                    showInsertDataError();
                }
            },
            error: function() {
                showInsertDataError();
            }
        });
    }

    function showInsertDataError() {
        $.ajax({
            url: "/lib/webservices/msgFeedback.php",
            data: "nError=4065&lang=<?php echo $_SESSION['userLang'] ?>",
            type: 'POST',
            success: function(output) {
                data = $.parseJSON(output);
                showError(data);
            }
        });
    }

    <?php if ($portalProActivated): ?>
        function savePortalProReport(element) {
            var parent = $(element).closest('tr');
            var emails = $('.portalProEmails').val();
            var frequency = $('.portalProFrequency').val();
            var brandId = <?php echo $brandId ?>;
            var chainId = "<?php echo $chainId ?>";
            var productId = <?php echo $portalProProductId ?>;
            var newReport = $(parent).find('.newPortalProReport').first().val();
            var method = newReport == 'true' ? "POST" : "PUT";


            $.ajax({
                url: "/lib/webservices/clients-reports-management-ws.php",
                data: "&action=portal_pro_save&method=" + method + "&brandId=" + brandId + "&emails=" + emails + "&interval=" + frequency + "&chainId=" + chainId + "&productId=" + productId,
                type: 'POST',
                success: function(output) {
                    if (output !== 'error') {
                        // Set to false because resource is created/updated
                        $(parent).find('.newPortalProReport').first().val('false');
                        $.ajax({
                            url: "/lib/webservices/msgFeedback.php",
                            data: "nError=2007&lang=<?php echo $_SESSION['userLang'] ?>",
                            type: 'POST',
                            success: function(output) {
                                data = $.parseJSON(output);
                                showError(data);
                            }
                        });
                    } else {
                        showInsertDataError();
                    }
                },
                error: function() {
                    showInsertDataError();
                }
            });

        }

        function removePortalProReport(element) {
            var brandId = <?php echo $brandId ?>;
            var productId = <?php echo $portalProProductId ?>;
            $.ajax({
                url: "/lib/webservices/clients-reports-management-ws.php",
                data: "&action=portal_pro_delete&brandId=" + brandId + "&productId=" + productId,
                type: 'POST',
                success: function(output) {
                    if (output !== 'error') {
                        // Clear all inputs
                        $('.portalProEmails').val("")
                        $('.portalProFrequency').val("")
                        // Set to false because resource is created/updated
                        $(parent).find('.newPortalProReport').first().val('false');
                        $.ajax({
                            url: "/lib/webservices/msgFeedback.php",
                            data: "nError=2007&lang=<?php echo $_SESSION['userLang'] ?>",
                            type: 'POST',
                            success: function(output) {
                                data = $.parseJSON(output);
                                showError(data);
                            }
                        });
                    } else {
                        showInsertDataError();
                    }
                },
                error: function() {
                    showInsertDataError();
                }
            });
        }
    <?php endif; ?>
</script>