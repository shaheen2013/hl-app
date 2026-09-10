<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}
include_once LANG .$_SESSION['userLang']. '/pixel-config.php';
?>

<div id="wrapper">
    <?php include TEMPLATES . 'hotel-sidebar.php'; ?>
    <div id="page-content-wrapper">
        <div class="top-bar">
            <?php include TEMPLATES . 'hotel-profile-menu.php'; ?>
        </div>
        <div class="utility-bar">
            <div class="col-lg-12">
                <h1 class="pull-left"><i class="fa fa-cube" aria-hidden="true"></i> <?php echo $pixelLang['pixel config'] ?></h1>
                <div class="breadcrumbs pull-right">
                    <ul>
                        <?php include (TEMPLATES .'breadcrumbs.php'); ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="mainContent" id="fullContainer">
            <div class="col-lg-12 mt2 mb2">
                <div class="table-responsive mt2 relative mb0">
                    <table class="table table-striped table-bordered mb0" id="goals-div">
                        <tr class="table-header">
                            <td class="col-md-1">
                                <?php echo $pixelLang['active'] ?>
                            </td>
                            <td class="col-md-3">
                                <?php echo $pixelLang['email'] ?>
                            </td>
                            <td class="col-md-7">
                                <?php echo $pixelLang['url']?>
                            </td>
                            <td class="col-md-1 text-right">
                                <?php echo $pixelLang['actions'] ?>
                            </td>
                        </tr>

                        <?php foreach ($pixelConfig as $config) { ?>
                            <tr class='table-row' >
                                <td class="col-md-1 text-center dt-center">
                                    <input type='checkbox' <?php echo $config->active ? 'checked' : '' ?> >
                                </td>
                                <td class="col-md-3">
                                    <?php echo $config->name; ?>
                                </td>
                                <td class="col-md-7">
                                    <input type="text" class="brandPixelUrl form-control" value="<?php echo $config->url ?>">
                                </td>
                                <td class='col-md-1 text-right'>
                                    <div class='btn-group goal-action-group' role='group' aria-label='...'>
                                        <button class='saveBrandPixel btn btn-info <?php echo  $config->id ? '' : 'disabled'?>' onclick='saveBrandPixel(this, <?php echo $config->email_type_id ?>)'><i class='fa fa-save'></i></button>
                                        <button class='removeBrandPixel btn btn-danger <?php echo  $config->id ? '' : 'disabled'?>' onclick='removeBrandPixel(this, <?php echo $config->id ?>)'><i class='fa fa-times'></i></button>
                                    </div>
                                </td>
                            </tr>
                        <?php }?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo DIR_JS ?>feedback-errors.js"></script>
<script>

    $('.brandPixelUrl').on('input', function (e) {
        var parent = $(this).closest('tr')[0];

        if (this.value) {
            $(parent).find(".saveBrandPixel").removeClass("disabled");
        } else {
            $(parent).find(".saveBrandPixel").addClass("disabled");
        }
    });

    function removeBrandPixel(element, brandPixelId) {
        var parent = $(element).closest('tr')[0];
        var brand_id = <?php echo $brandId ?>;

        $.ajax({
            url: "/lib/webservices/pixel-config-ws.php",
            data: "&action=delete&brand_pixel_id="+brandPixelId+"&brand_id="+brand_id,
            type: 'POST',
            success: function(output) {
                if(output !== 'error'){
                    $(parent).find("input[type=checkbox]").prop('checked', false);
                    $(parent).find("input[type=text]").prop('value', '');
                    $(parent).find(".removeBrandPixel").attr("onclick","removeBrandPixel(this,)");
                    $(parent).find(".removeBrandPixel").addClass("disabled");
                    $(parent).find(".saveBrandPixel").addClass("disabled");

                    showMessage(2007);
                }
                else{
                    showMessage(4065);
                }
            }
        });
    }

    function saveBrandPixel(element, emailTypeId) {
        var parent = $(element).closest('tr')[0];
        var brand_id = <?php echo $brandId ?>;
        var url = $(parent).find(".brandPixelUrl")[0].value;
        var active = $(parent).find("input[type=checkbox]").is(":checked");

        $.ajax({
            url: "/lib/webservices/pixel-config-ws.php",
            data: "&action=save&brand_id="+brand_id+"&email_type_id="+emailTypeId+"&url="+url+"&active="+active,
            type: 'POST',
            success: function(output) {
                if(output !== 'error') {
                    $(parent).find(".removeBrandPixel").attr("onclick","removeBrandPixel(this," + JSON.parse(output).id + ")");
                    $(parent).find(".removeBrandPixel").removeClass("disabled");
                    
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