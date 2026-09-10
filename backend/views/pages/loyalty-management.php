<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<?php include LANG . $_SESSION['userLang'] . '/loyalty-management.php' ?>
<div id="wrapper">
    <?php include TEMPLATES . 'hotel-sidebar.php'; ?>
    <div id="page-content-wrapper">
        <div class="top-bar">
            <img class="topbarLogo visible-xs" src="<?php echo DIR_IMG . 'topbar-logo-xs.png' ?>" width="77" height="70"
                 alt="top bar logo">
                 <?php include TEMPLATES . 'hotel-suggest.php'; ?>
        </div>
        <div class="utility-bar">
            <div class="col-lg-12">
                <h1 class="pull-left"><i class="fa fa-smile-o"></i> <?php echo $loyaltyLang['Loyalty management'] ?> </h1>
                <div class="breadcrumbs pull-right">
                    <ul>
                        <?php include (TEMPLATES .'breadcrumbs.php'); ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="mainContent" id="fullContainer">
            <div class="row mt2">
                <div class="col-lg-8 col-lg-offset-2">
                    <?php if($arrayDatosCadena){?>                      
                    <div>
                        <div class="form-group">
                            <label><?php echo $loyaltyLang['stay_time_label'] ?></label>
                            <input type='hidden' id="chain_id" name='chain_id' value='<?php echo $chainID?>'/>
                            <input type="number" min="1" max="60" id='chain_stay_time' class="form-control form-with-button" value="<?php echo $arrayDatosCadena['stay_time'] ?>" name="stay_time" required>
                            <div class='btn-group goal-action-group' role='group' aria-label='...'>
                                <button class='saveGoal btn btn-info' onclick='saveChainStayTime()'><i class='fa fa-save'></i></button>
                            </div>
                        </div>
                    </div>
                    <?php }?>
                    <label><?php echo $loyaltyLang['Set goal offers based on number of friends booked'] ?></label>
                    <div class="table-responsive mt2 relative mb0">
                        <table class="table table-striped table-bordered mb0" id="goals-div">
                            <tr class="table-header">
                                <td  style="width:15%;max-width:150px">
                                    <?php echo $loyaltyLang['Goal set'] ?>
                                    <i data-toggle="tooltip" class="hasTooltip fa fa-question-circle" data-placement="top" title="<?php echo $loyaltyLang['goalHelp'] ?>"></i>
                                </td>
                                <?php if (!empty($chainID)) { ?>
                                    <td>
                                        <?php echo $loyaltyLang['establishment'] ?>
                                        <i data-toggle="tooltip" class="hasTooltip fa fa-question-circle" data-placement="top" title="<?php echo $loyaltyLang['establishmentHelp'] ?>"></i>
                                    </td>
                                <?php }?>
                                <td>
                                    <?php echo $loyaltyLang['Assign reward'] ?>
                                </td>
                                <td>
                                    <?php echo $loyaltyLang['reward_type'] ?>
                                </td>
                                <td>
                                    <?php echo $loyaltyLang['Days to expire']?>
                                </td>
                                <td style="width: 10%;min-width: 105px;" class="text-right">
                                    <?php echo $loyaltyLang['Actions'] ?>
                                </td>
                            </tr>

                            <?php foreach ($groupedLoyalty as $visits => $loyalyOffers){ ?>
                                <tr class='table-row' value="<?php echo count($loyalyOffers) ?>">
                                    <th rowspan="<?php echo count($loyalyOffers) ?>">
                                        <?php if ($visits == 0) {
                                            echo $loyaltyLang['default'];
                                        } else { ?>
                                            <input
                                                type='number'
                                                disabled
                                                min='1'
                                                name='num-visits'
                                                class='goal form-control num-visits'
                                                value='<?php echo $visits?>'
                                                placeholder='<?php echo $visits?>'
                                            >
                                            <input type='hidden' value='' >
                                        <?php } ?>
                                    </th>
                                    <?php foreach ($loyalyOffers as $loyaltyOffer){?>
                                        <?php if (!empty($chainID)) { ?>
                                            <td>
                                                <div class='col-lg-12'>
                                                    <div class='row'>
                                                        <select class='brand-select form-control'>
                                                            <option value='<?php echo $_SESSION['loggedParentBrandID'] ?>' ><?php echo $loyaltyLang['all'] ?></option>
                                                            <?php foreach (array_get($brands, 'cadena_hotel') as $brand){?>
                                                                <option
                                                                    value='<?php echo array_get($brand, 'hotel.brandId') ?>'
                                                                    <?php echo data_get($loyaltyOffer, 'brand.id') == array_get($brand, 'hotel.brandId') ?
                                                                        'selected':
                                                                        ''
                                                                    ?>
                                                                >
                                                                    <?php echo data_get($brand, 'hotel.name') ?>
                                                                </option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </td>
                                        <?php } ?>
                                        <td>
                                            <input type='hidden' class='offer_goal_id' name='offer_goal_id' value='<?php echo $loyaltyOffer['id'] ?? ''; ?>'/>
                                            <div class='col-lg-12'>
                                                <div class='row'>
                                                    <select class='goal-select form-control'>
                                                        <option><?php echo $loyaltyLang['not selected'] ?></option>
                                                        <?php foreach ($offers as $offer){?>
                                                            <option
                                                                value='<?php echo $offer['id'] ?>'
                                                                <?php echo array_get($loyaltyOffer, 'offer.id')==$offer['id'] ?
                                                                    'selected' :
                                                                    ''
                                                                ?>
                                                            >
                                                                <?php echo array_get($offer, 'offer_lang.name') ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <select class="goal-offer-type form-control"
                                                    data-type="tipo_oferta">
                                                <option><?php echo $loyaltyLang['not selected'] ?></option>
                                                <option
                                                    <?php echo(array_get($loyaltyOffer, 'type' ) == 'web' ?
                                                        'selected' :
                                                        ''
                                                    ) ?>
                                                    value="web">
                                                    <?php echo $loyaltyLang['gift on website wifi'] ?>
                                                </option>
                                                <option
                                                    <?php echo(array_get($loyaltyOffer, 'type' ) == 'inmediate' ?
                                                        'selected' :
                                                        '')
                                                    ?>
                                                    value="inmediate">
                                                    <?php echo $loyaltyLang['gift during stay wifi'] ?>
                                                </option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type='number' min='1' name='days_to_expire' class='goal form-control days_to_expire' value='<?php echo(array_get($loyaltyOffer, 'days_to_expire' )) ?>' placeholder='<?php echo $loyaltyLang['Days to expire']?>'>
                                        </td>
                                        <td class='text-right'>
                                            <div class='btn-group goal-action-group' role='group' aria-label='...'>
                                                <button class='saveGoal btn btn-info' onclick='saveGoal( this)'><i class='fa fa-save'></i></button>
                                                <button class='removeGoal btn btn-danger' onclick='removeGoal( this)'><i class='fa fa-times'></i></button>
                                            </div>
                                        </td>
                                    </tr>
                            <?php }}?>
                        </table>
                    </div>
                    <button class="addGoal btn btn-lg btn-success mt2" onclick="addBlankLine()"><i
                                class="fa fa-plus"></i>
                        <?php echo $loyaltyLang['Add new goal'] ?>
                    </button>
                    <a target='_blank' href="<?php echo $urlTree["hotel-crear-detalle-oferta"]?>">
                    <button class="addGoal btn pull-right btn-lg btn-success mt2"><i
                                class="fa fa-plus"></i>
                        <?php echo $loyaltyLang['Make a new offer'] ?>
                    </button>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo DIR_JS ?>feedback-errors.js"></script>
<script>

    $(document).ready(function() {
        addBlankLine();
    });

    function addBlankLine() {
        $('#goals-div').append("'<tr class='table-row'>" +
            "<td>" +
                "<input type='hidden' class='offer_goal_id' name='offer_goal_id' value=''/>"+
                "<input type='number' min='1' name='num-visits' class='goal form-control num-visits' value='' placeholder='<?php echo $loyaltyLang['Visits']?>'>" +
                "<input type='hidden' value='' >" +
            "</td>" +
            <?php if (!empty($chainID)) { ?>
                "<td>" +
                    "<div class='col-lg-12'>" +
                        "<div class='row'>" +
                            "<select class='brand-select form-control'>" +
                                "<option value='<?php echo $_SESSION['loggedParentBrandID'] ?>' ><?php echo $loyaltyLang['all'] ?></option>" +
                                <?php foreach (array_get($brands, 'cadena_hotel') as $brand){?>
                                    "<option value='<?php echo array_get($brand, 'hotel.brandId') ?>'> <?php echo data_get($brand, 'hotel.name') ?>" +
                                    "</option>" +
                                <?php } ?>
                            "</select>" +
                        "</div>" +
                    "</div>" +
                "</td>" +
            <?php } ?>
            "<td>" +
                "<div class='col-lg-12'>" +
                    "<div class='row'>" +
                        "<select class='goal-select form-control'>" +
                            "<option><?php echo $loyaltyLang['not selected'] ?></option>"+
                            <?php foreach ($offers as $offer){?>
                                "<option value='<?php echo $offer['id'] ?>'> <?php echo addslashes(array_get($offer, 'offer_lang.name')) ?>"+
                                "</option>"+
                            <?php } ?>
                        "</select>" +
                    "</div>" +
                "</div>" +
            "</td>" +
            "<td>"+
                "<select class='goal-offer-type form-control'"+
                    "data-type='tipo_oferta'>"+
                    "<option><?php echo $loyaltyLang['not selected'] ?></option>"+
                    "<option value='web'><?php echo $loyaltyLang['gift on website wifi'] ?></option>"+
                     "<option value='inmediate'><?php echo $loyaltyLang['gift during stay wifi'] ?></option>"+
                "</select>"+
            "</td>"+
            "<td>" +
                "<input type='number' min='1' name='days_to_expire' class='goal form-control days_to_expire' value='' placeholder='<?php echo $loyaltyLang['Days to expire']?>'>" +
            "</td>" +
            "<td class='text-right'>" +
                "<div class='btn-group goal-action-group' role='group' aria-label='...'>" +
                    "<button class='saveGoal btn btn-info' onclick='saveGoal( this)'><i class='fa fa-save'></i></button>" +
                    "<button class='removeGoal btn btn-danger' onclick='removeGoal( this)'><i class='fa fa-times'></i></button>" +
                "</div>" +
            "</td>" +
        "</tr>'")
    }

    function removeGoal(element, defaultOffer) {
        if(defaultOffer == null ||defaultOffer ==undefined){
            defaultOffer = false
        }

        var parent = $(element).closest('tr');
        var offer_goal_id = $(parent).find('.offer_goal_id').first().val();
        var brand_id = $(parent).find('.brand-select').first().val() ? $(parent).find('.brand-select').first().val() : <?php echo $brandID ?>;

        $.ajax({
            url: "/lib/webservices/loyalty-management-ws.php",
            data: "&action=delete&offerID="+offer_goal_id+"&brandID="+brand_id,
            type: 'POST',
            success: function(output) {
                if(output !== 'error'){
                    if(!defaultOffer){
                        var selector = $(element).closest('tr')[0].classList.length > 0 ?
                            $(element).closest('tr') :
                            $(element).closest('tr').prevAll('.table-row:first');

                        $(element).closest('tr').find('td').remove();
                        selector[0].setAttribute('value', selector[0].getAttribute('value') - 1);

                        if (selector[0].getAttribute('value') == 0) {
                            selector.remove();
                        }


                    }
                    else{
                        $(parent).find('.goal-select').prop('selectedIndex',0);
                        $(parent).find('.goal-offer-type').prop('selectedIndex',0);

                    }

                    showMessage(2007);
                }
                else{
                    showMessage(4065);
                }
            }
        });
    }


    function saveChainStayTime(){
        var chain_id = $('#chain_id').val();
        var chain_stay_time = $('#chain_stay_time').val();
        $.ajax({
            url: "/lib/webservices/loyalty-management-ws.php",
            data: "&action=stay_time_save&&chain_id="+chain_id+"&chain_stay_time="+chain_stay_time,
            type: 'POST',
            success: function(output) {
                if(output !== 'error'){
                    showMessage(2007);
                }
                else{
                    showMessage(4065);
                }
            }
        });
    }

    function saveGoal(element) {
        var parent = $(element).closest('tr');
        var numVisits = $(parent).find('.num-visits').first().val();
        var offer_id = $(parent).find('.goal-select').first().val();
        var offer_goal_id = $(parent).find('.offer_goal_id').first().val();
        var brand_id = $(parent).find('.brand-select').first().val() ? $(parent).find('.brand-select').first().val() : <?php echo $brandID ?>;
        var offer_type = $(parent).find('.goal-offer-type').first().val();
        var days_to_expire = $(parent).find('.days_to_expire').first().val();
        $.ajax({
            url: "/lib/webservices/loyalty-management-ws.php",
            data: "&action=save&brand_id="+brand_id+"&offer_goal_id="+offer_goal_id+"&days_to_expire="+days_to_expire+"&offer_type="+offer_type+"&offer_id="+offer_id+"&n_triggers="+numVisits+"&product=loyalty",
            type: 'POST',
            success: function(output) {
                if(output !== 'error'){
                    $(parent).find('.offer_goal_id').first().val(output);
                    $(parent).find('.num-visits').prop('disabled', true);
                    showMessage(2007);
                }
                else{
                    showMessage(4065);
                }
            }
        });
    }

    function showMessage(messageID) {
        $.ajax({
            url: "/lib/webservices/msgFeedback.php",
            data: "nError=" + messageID + "&lang=<?php echo $_SESSION['userLang'] ?>",
            type: 'POST',
            success: function(output) {
                data = $.parseJSON(output);
                showError(data);
            }
        });
    }
</script>
