<?php //Miramos si esta definida la variable de control de index.php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}

include LANG . $_SESSION['userLang'] . '/login-configuration.php';
?>
<div id="wrapper">
    <?php include TEMPLATES . 'hotel-sidebar.php'; ?>
    <div id="page-content-wrapper">
        <div class="top-bar">
            <?php
                $template = $currentPage == 'hotel-management' ? 'hotel-profile-menu.php' : 'chain-management-menu.php';
                include TEMPLATES . $template;
            ?>
            <img class="topbarLogo visible-xs" src="<?php echo DIR_IMG . 'topbar-logo-xs.png' ?>" width="77" height="70" alt="top bar logo">
        </div>
        <div class="utility-bar mb15">
            <div class="col-lg-12">
                <h1 class="pull-left">
                    <i class="fa fa-cog"></i> <?php echo $loginTypeLang['Set allowed access type'] ?>
                </h1>
                <div class="breadcrumbs pull-right">
                    <ul>
                        <?php include(TEMPLATES . 'breadcrumbs.php'); ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="mainContent" id="fullContainer">
            <div class="col-lg-8">
                <div class="panel panel-default noPadding">
                    <div class="panel-heading">
                        <?php echo $loginTypeLang['Select access type'] ?>
                    </div>
                    <div class="panel-body">
                        <ul class="list-unstyled">
                            <?php
                                foreach (data_get($accessTypes, "data", []) as $accessType):
                                    if ($accessType->name !== "Google"):
                                        $checked = ($accessType->id && $accessType->active) ? 'checked="checked"' : "";
                            ?>
                                <form id="accessTypeForm" role="form" class="mt2" action="/<?php echo $urlTree['login-configuration']; ?>" method="post">
                                    <li>
                                        <label class="col-lg-2"><?php echo ucfirst($loginTypeLang[$accessType->name] ?? $accessType->name); ?></label>
                                        <label class="switch">
                                            <input id="emailSwitch-<?php echo $accessType->id?>" name="emailSwitch-<?php echo $accessType->id?>" type="checkbox" <?php echo $checked?>>
                                            <span id="slider-<?php echo $accessType->id?>" class="treatment-slider round"></span>
                                            <input type="hidden" name="emailSwitch-<?php echo $accessType->id?>" value="disabled">
                                            <input type="hidden" name="accessTypeId">
                                            <input type="hidden" name="active">
                                        </label>
                                    </li>
                                    <br/>
                                </form>
                            <?php 
                                    endif;
                                endforeach;
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    setTextAndColorCheckbox();
    
    $("[id^=emailSwitch-]").change(function(e){
        setTextAndColorCheckbox();
        $(this).closest("[id^=accessTypeForm]").find("[name='accessTypeId']").val(event.target.id.split('-')[1]);
        $(this).closest("[id^=accessTypeForm]").find("[name='active']").val($(this).is(':checked'));
        
        $(this).closest("[id^=accessTypeForm]").submit();
    });

    function setTextAndColorCheckbox() {
        <?php foreach ($accessTypes->data as $accessType): ?>
            var text = "<?php echo $loginTypeLang['OFF'] ?>";
            var color = "#f3565d"
        
            if ($('#emailSwitch-<?php echo $accessType->id?>').is(":checked")) {
                text = "<?php echo $loginTypeLang['ON'] ?>";
                color = "#65c3df";
            }

            $("#slider-<?php echo $accessType->id?>").text(text);
            $("#slider-<?php echo $accessType->id?>").css("background-color", color);
        <?php endforeach; ?>
    }
</script>