<?php //Miramos si esta definida la variable de control de index.php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
} ?>



<?php include LANG . $_SESSION['userLang'] . '/hotel-goals.php' ?>
<div id="wrapper">
    <?php include TEMPLATES . 'hotel-sidebar.php'; ?>
    <div id="page-content-wrapper">
        <div class="top-bar">
            <?php include TEMPLATES . 'hotel-gestion-ofertas-menu.php'; ?>
            <img class="topbarLogo visible-xs" src="<?php echo DIR_IMG . 'topbar-logo-xs.png' ?>" width="77" height="70" alt="top bar logo">
        </div>
        <div class="utility-bar">
            <div class="col-lg-12">
                <h1 class="pull-left"><i class="fa fa-trophy"></i> <?php echo $hotelGoalsLang['Goals Setup'] ?></h1>
                <div class="breadcrumbs pull-right">
                    <ul>
                        <?php include(TEMPLATES . 'breadcrumbs.php'); ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="mainContent" id="fullContainer">
            <div class="col-lg-12">
                <div class="col-lg-12">
                    <h3><?php echo $hotelGoalsLang['Activar Pop ups!'] ?></h3>
                    <div class="row text-center">

                        <div class="col-sm-3">
                            <p><strong><?php echo $hotelGoalsLang['Pop up pre estancia'] ?></strong></p>
                            <button class="activateIframe btn <?php echo ($showIframe == 'enabled' ? 'btn-success' : 'btn-default') ?>" data-id="<?php echo $_SESSION['h_logueado'] ?>" data-iframe="<?php echo $showIframe ?>" data-type="pre-stay"><i class="fa fa-clone"></i> <?php echo ($showIframe == 'enabled' ? $hotelGoalsLang['Disable Iframe'] : $hotelGoalsLang['Enable Iframe']) ?>
                            </button>
                        </div>

                        <div class="col-sm-3">
                            <p><strong><?php echo $hotelGoalsLang['Pop up landing'] ?></strong></p>
                            <button class="activateLandingIframe btn <?php echo ($showLandingIframe == 'enabled' ? 'btn-success' : 'btn-default') ?>" data-id="<?php echo $_SESSION['h_logueado'] ?>" data-iframe="<?php echo $showLandingIframe ?>" data-type="landing"><i class="fa fa-clone"></i> <?php echo ($showLandingIframe == 'enabled' ? $hotelGoalsLang['Disable Iframe'] : $hotelGoalsLang['Enable Iframe']) ?>
                            </button>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-12">
                            <h3><?php echo $hotelGoalsLang['Select WiFi Integration'] ?></h3>
                            <form id="wifiIntegrationForm" name="wifi" method="post" class="form-inline">
                                <select id="select-wifi-providers" class="form-control" name="wifiProvider">
                                    <option value="0"><?php echo $hotelGoalsLang['None'] ?></option>
                                    <?php foreach ($wifiProviders as $wifi) {
                                    ?>
                                        <option <?php if ($wifi['id'] == array_get($urlWifiStay, 'wifi_id')) {
                                                ?> selected <?php
                                                        } ?> value="<?php echo $wifi['id'] ?>"><?php echo $wifi['name'] ?></option>
                                    <?php
                                    } ?>
                                </select>

                                <div id="wifi-form">
                                    <?php if(!empty($wifiForm)) { ?>
                                        <?php include_once TEMPLATES . 'wifi_integrations/' . $wifiForm . '.php' ?> 
                                    <?php } ?>
                                </div>

                                <button type="submit" class="btn btn-success mt2"><?php echo $hotelGoalsLang['Save wifi'] ?></button>
                            </form>
                        </div>
                    </div>

                    <?php if ($hotelHasRoomRequire || $portalProActivated) { ?>
                        <div>
                            <h3><?php echo $hotelGoalsLang['Room List'] ?></h3>
                            <form name="room_list" method="post">
                                <div class="form-group">
                                    <label for="room_list_text_area"><?php echo $hotelGoalsLang['Room List textarea'] ?></label>
                                    <textarea class="form-control" name="room[textarea]" id="room_list_text_area" rows="3"><?php echo trim(implode(",", $roomAccess)) ?></textarea>
                                </div>
                                <input type="hidden" name="brandAccesType" value="room">
                                <button type="submit" class="btn btn-success"><?php echo $hotelGoalsLang['Save Room List'] ?></button>
                            </form>
                        </div>
                        <hr>
                        <div>
                            <h3><?php echo $hotelGoalsLang['Access codes title'] ?></h3>
                            <form name="other_access_codes" method="post">
                                <div class="form-group">
                                    <label for="other_access_codes_text_area"><?php echo $hotelGoalsLang['Access codes summary']; ?></label>
                                    <textarea class="form-control" name="guest[textarea]" id="other_access_codes_text_area" rows="3"><?php echo trim(implode(',', $guestAccess)) ?></textarea>
                                </div>
                                <input type="hidden" name="brandAccesType" value="guest">
                                <button type="submit" class="btn btn-success"><?php echo $hotelGoalsLang['Access codes button']; ?></button>
                            </form>
                        </div>
                        <?php if (array_get($portalProConfig, 'premium_code')) { ?>
                            <hr>
                            <div>
                                <h3><?php echo $hotelGoalsLang['Premium codes title'] ?></h3>
                                <form name="premium_access_codes" method="post">
                                    <div class="form-group">
                                        <label for="premium_access_codes_text_area"><?php echo $hotelGoalsLang['Premium codes textarea']; ?></label>
                                        <textarea class="form-control" name="premium[textarea]" id="premium_access_codes_text_area" rows="3" <?php echo $premiumEditable ? '' : 'disabled' ?>><?php echo trim(implode(',', $premiumAccess)) ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <?php if (array_get($_SESSION, 'c_logueado')) { ?>
                                            <input type="checkbox" name="premiumCodesByChain" aria-label="Checkbox for following text input" <?php echo (isset($premiumByParent) && $premiumByParent) ? "checked" : "" ?>>
                                            <?php echo $hotelGoalsLang['Premium codes checkbox'] ?>
                                        <?php } ?>
                                    </div>
                                    <?php if ($premiumEditable) { ?>
                                        <input type="hidden" name="brandAccesType" value="premium">
                                        <button type="submit" class="btn btn-success"><?php echo $hotelGoalsLang['Premium codes button']; ?></button>
                                    <?php } ?>
                                </form>
                            </div>
                        <?php } ?>

                    <?php } ?>
                    <hr>
                    <h3><?php echo $hotelGoalsLang['Set Goal offer for sharing at Pre stay state'] ?></h3>
                    <div class="table-responsive mt2 relative">
                        <table class="table table-striped table-bordered mb0" id="post-goals-div">
                            <tr class="table-header">
                                <td>
                                    <?php echo $hotelGoalsLang['Select goal offer'] ?>
                                </td>
                                <td class="text-right">
                                    <?php echo $hotelGoalsLang['Actions'] ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width:65%">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <select id="select-prestay" class="form-control" data-type="prestay">
                                                <option value="0">...</option>
                                                <?php foreach ($offersAvailable as $offer) {
                                                ?>
                                                    <option <?php if ($offer['id'] == $ofertasStay['prestay']) {
                                                            ?> selected <?php
                                                                    } ?> value="<?php echo $offer['id'] ?>">
                                                        <?php echo $offer['nombre'] ?>
                                                    </option>
                                                <?php
                                                } ?>
                                            </select>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group goal-action-group pull-right" role="group" aria-label="actions">
                                        <a class="btn btn-primary" id="newOffer-x" href="referral-goals/?newStay=prestay"><?php echo $hotelGoalsLang['Create new'] ?></a>
                                        <button class="removeGoal btn btn-danger" onclick="removeOfferByType('prestay')"><i class="fa fa-times"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>


                    <?php if ($_SESSION['permisos']['wifi_offers'] == 1) {
                    ?>
                        <hr>

                        <h3><?php echo $hotelGoalsLang['Stay Incentive WiFi title']; ?></h3>
                        <form autocomplete="off" method="post" id="brand-offer-wifi" action="">
                            <div class="table-responsive mt relative">
                                <table id="offer-wifi-body" class="table table-striped">
                                    <tr class="table-header">
                                        <td>
                                            <span><?php echo $hotelGoalsLang['default offer']; ?></span>
                                        </td>
                                        <td>
                                            <span><?php echo $hotelGoalsLang['apply']; ?></span>
                                        </td>
                                        <td>
                                            <span><?php echo $hotelGoalsLang['Select goal offer']; ?></span>
                                        </td>
                                        <td>
                                            <span><?php echo $hotelGoalsLang['Conditions wifi']; ?></span>
                                        </td>
                                        <td>
                                            <span><?php echo $hotelGoalsLang['Type of gift']; ?></span>
                                        </td>
                                        <td>
                                            <span><?php echo $hotelGoalsLang['valid_from']; ?></span>
                                        </td>
                                        <td>
                                            <span><?php echo $hotelGoalsLang['valid_to']; ?></span>
                                        </td>
                                        <td>
                                            <span><?php echo $hotelGoalsLang['Days gift']; ?></span>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <?php
                                    $firstRow = true;
                                    $index = 0;
                                    foreach ($brandOffersWifi as $offerWifi) :
                                        $validFrom = empty($offerWifi['valid_from']) ?
                                            null :
                                            date_create_from_format('Y-m-d', $offerWifi['valid_from'])->format('d/m/Y');

                                        $validTo = empty($offerWifi['valid_to']) ?
                                            null :
                                            date_create_from_format('Y-m-d', $offerWifi['valid_to'])->format('d/m/Y');
                                    ?>
                                        <tr class="table-row <?php echo $firstRow ? 'first-row' : ""; ?> row-offer-wifi" id='wifi-offer-id-<?php echo $index; ?>'>
                                            <td>
                                                <input type='hidden' value='<?php echo array_get($offerWifi, 'is_default') ? 1 : 0; ?>' name='default[]' class="defaultHidden">
                                                <input type="checkbox" class="default" style="margin-left: 29px;" <?php echo array_get($offerWifi, 'is_default') ? 'checked="checked"' : ""; ?>>
                                            </td>
                                            <td style="width:<?php echo $_SESSION['userLang'] == "en" ? "15" : "10" ?>%">
                                                <label><?php echo $hotelGoalsLang['accommodated']; ?></label>
                                                <input type='hidden' value='<?php echo array_get($offerWifi, 'accommodated') ? 1 : 0; ?>' name='accommodated[]' class="accommodatedHidden">
                                                <input style="float:right" type="checkbox" class="accommodated" <?php echo array_get($offerWifi, 'accommodated') ? 'checked="checked"' : ""; ?>>
                                                <br>
                                                <label><?php echo $hotelGoalsLang['not accommodated']; ?></label>
                                                <input type='hidden' value='<?php echo array_get($offerWifi, 'non_accommodated') ? 1 : 0; ?>' name='non_accommodated[]' class="nonAccommodatedHidden">
                                                <input style="float:right" type="checkbox" class="non_accommodated" <?php echo array_get($offerWifi, 'non_accommodated') ? 'checked="checked"' : ""; ?>>
                                            </td>
                                            <td>
                                                <input type="hidden" name="id[]" value="<?php echo $offerWifi['id']; ?>" class="offer-wifi-id">
                                                <input type="hidden" name="index[]" value="<?php echo $index; ?>" class="offer-wifi-index">
                                                <select class="form-control select-wifi" data-type="wifi" name="select_offer[]">
                                                    <option><?php echo $hotelGoalsLang['not selected'] ?></option>
                                                    <?php
                                                    foreach ($offersAvailable as $offer) :
                                                        $selected = ($offer['id'] == array_get($offerWifi, 'offer_id')) ? 'selected="selected"' : "";
                                                    ?>
                                                        <option <?php echo $selected; ?> value="<?php echo $offer['id']; ?>">
                                                            <?php echo $offer['nombre']; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-control select-condition" data-type="wifi" name="select_condition[]">
                                                    <option><?php echo $hotelGoalsLang['not selected'] ?></option>
                                                    <?php
                                                    foreach ($defaultOfferConditions as $condition) :
                                                        $selected = ($condition == array_get($offerWifi, 'condition')) ? 'selected="selected"' : "";
                                                    ?>
                                                        <option <?php echo $selected; ?> value="<?php echo $condition; ?>">
                                                            <?php echo $condition; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-control select-type" data-type="wifi" name="select_type[]">
                                                    <option><?php echo $hotelGoalsLang['not selected'] ?></option>
                                                    <?php
                                                    foreach ($defaultOfferTypes as $type) :
                                                        $selected = ($type == array_get($offerWifi, 'offer_type')) ? 'selected="selected"' : "";
                                                    ?>
                                                        <option <?php echo $selected; ?> value="<?php echo $type; ?>">
                                                            <?php echo $type; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </td>
                                            <td class="offer-wifi-dates">
                                                <input type="text" name="valid_from[]" value="<?php echo $validFrom; ?>" class="offer-wifi-date form-control">
                                                <span><?php echo $hotelGoalsLang['bad_date']; ?></span>
                                            </td>
                                            <td class="offer-wifi-dates">
                                                <input type="text" name="valid_to[]" value="<?php echo $validTo; ?>" class="offer-wifi-date form-control">
                                                <span><?php echo $hotelGoalsLang['bad_date']; ?></span>
                                            </td>
                                            <td>
                                                <div class="input-group">
                                                    <input type="number" name="period[]" value="<?php echo array_get($offerWifi, 'period'); ?>" class="offer-wifi-period form-control">
                                                    <div class="input-group-addon"><?php echo $hotelGoalsLang['days']; ?></div>
                                                </div>
                                            </td>
                                            <td>
                                                <button style="width:100%" type="button" class="remove-offer-wifi btn btn-danger col-sm-1" data-id="<?php echo $offerWifi['id']; ?>">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php
                                        $firstRow = false;
                                        $index++;
                                    endforeach;
                                    ?>
                                </table>

                            </div>
                            <div class="pull-left mt0 pagination">
                                <button id="add-offer-wifi" class="btn btn-success btn-xs">
                                    <?php echo $hotelGoalsLang['add_offer']; ?>
                                </button>
                            </div>

                            <div class="clearfix"></div>
                            <button type="submit" class="btn btn-success save-wifi-offer" name="offer_wifi_action" value="insert"><?php echo $hotelGoalsLang['save_changes'] ?></button>

                        </form>
                    <?php
                    } ?>
                    <hr>
                    <h3><?php echo $hotelGoalsLang['Set Goal offer for sharing at Post stay state'] ?></h3>
                    <div class="table-responsive mt2 relative">
                        <table class="table table-striped table-bordered mb0" id="post-goals-div">
                            <tr class="table-header">
                                <td>
                                    <?php echo $hotelGoalsLang['Select goal offer'] ?>
                                </td>
                                <td class="text-right">
                                    <?php echo $hotelGoalsLang['Actions'] ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width:65%">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <select id='select-poststay' class="form-control">
                                                <option value="0">...</option>
                                                <?php foreach ($offersAvailable as $offer) {
                                                ?>
                                                    <option <?php if ($offer['id'] == $ofertasStay['poststay']) {
                                                            ?> selected <?php
                                                                    } ?> value="<?php echo $offer['id'] ?>">
                                                        <?php echo $offer['nombre'] ?>
                                                    </option>
                                                <?php
                                                } ?>
                                            </select>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group goal-action-group pull-right" role="group" aria-label="...">
                                        <a class="btn btn-primary" id="newOffer-x" href="referral-goals/?newStay=poststay">
                                            <?php echo $hotelGoalsLang['Create new'] ?>
                                        </a>
                                        <button class="removeGoal btn btn-danger" onclick="removeOfferByType('poststay')"><i class="fa fa-times"></i></button>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <hr />
                    <h3><?php echo $hotelGoalsLang['Set goal offers based on number of friends booked'] ?></h3>
                    <div class="table-responsive mt2 relative mb0">
                        <table class="table table-striped table-bordered mb0" id="goals-div">
                            <tr class="table-header">
                                <td>
                                    <?php echo $hotelGoalsLang['Goal set'] ?>
                                </td>
                                <td>
                                    <?php echo $hotelGoalsLang['Assign reward'] ?>
                                </td>
                                <td class="text-right">
                                    <?php echo $hotelGoalsLang['Actions'] ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <button class="addGoal btn btn-lg btn-success mt2" onclick="addBlankLine()"><i class="fa fa-plus"></i>
                        <?php echo $hotelGoalsLang['Add new goal'] ?>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete offer wifi modal -->
    <div class="modal fade" tabindex="-1" role="dialog" id="delete-offer-wifi">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title"><?php echo $hotelGoalsLang['delete_offer_wifi_title']; ?></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <p><?php echo $hotelGoalsLang['delete_offer_wifi_text']; ?></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input name="delete_query" type="hidden" value="delete_query">
                    <button type="button" value="delete-offer-wifi" class="btn btn-primary" data-dismiss="modal"><?php echo $hotelGoalsLang['delete_offer_wifi_title']; ?></button>
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $hotelGoalsLang['cancel']; ?></button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- End delete offer wifi modal -->

    <!-- Change default offer modal -->
    <div class="modal fade" tabindex="-1" role="dialog" id="change-default-modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title"><?php echo $hotelGoalsLang['change_default_offer_title']; ?></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <p><?php echo $hotelGoalsLang['change_default_offer_text']; ?></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input id="default-selected" type="hidden" value="">
                    <button id="delte-default-offer-button" type="button" value="delete-default-offer-wifi" class="btn btn-primary"><?php echo $hotelGoalsLang['continue']; ?></button>
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $hotelGoalsLang['cancel']; ?></button>
                </div>
            </div>
        </div>
    </div>
    <!-- End delete offer wifi modal -->

    <input type="hidden" id="userLang" value="<?php echo $_SESSION['userLang']; ?>">
    <script src="<?php echo DIR_JS ?>feedback-errors.js"></script>
    <script src="<?php echo DIR_JS ?>referral-goals.js?v=2"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.17.0/dist/jquery.validate.min.js"></script>
    <script>
        $(document).ready(function() {
            mostrarGoals();

            $('.activateIframe, .activateLandingIframe').click(function() {
                var button = $(this);
                var id = $(this).data('id');
                var iframeState = $(this).attr('data-iframe');
                var type = $(this).attr('data-type');

                console.log('button activated: id ' + id + ' actualState: ' + iframeState);

                var newIframeState = 'enabled';

                if (iframeState == 'enabled') {
                    newIframeState = 'disabled';
                }
                console.log('change button state to: ' + newIframeState);

                $.ajax({
                    url: "/lib/webservices/referral-goals-ws.php",
                    data: 'hid=' + id + '&iframeState=' + newIframeState + '&type=' + type,
                    type: 'POST',
                    success: function(output) {
                        var json = JSON.parse(output);
                        button.removeClass('btn-success btn-primary');
                        if (json.iframeState == 'enabled') {
                            button.attr('data-iframe', 'enabled');
                            button.addClass('btn-success');
                            button.html('<i class="fa fa-clone"></i> <?php echo $hotelGoalsLang['Disable Iframe'] ?>')
                        }
                        if (json.iframeState == 'disabled') {
                            button.attr('data-iframe', 'disabled');
                            button.addClass('btn-default');
                            button.html('<i class="fa fa-clone"></i> <?php echo $hotelGoalsLang['Enable Iframe'] ?>')
                        }
                        showError(json.code);
                    }
                });
            });
        });
    </script>