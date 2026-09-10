<?php //Miramos si esta definida la variable de control de index.php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}

include LANG . $_SESSION['userLang'] . '/chain-protocol-management.php';
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
                    <i class="fa fa-cog"></i> <?php echo $ProtocolLang['Set protocol'] ?>
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
                        <?php echo $ProtocolLang['Select treatment'] ?>
                    </div>
                    <div class="panel-body">
                        <form role="form" class="mt2" action="/<?php echo $formAction; ?>" method="post">
                            <input type="hidden" name="action" value="update">
                            <ul class="protocolList">
                                <?php
                                    foreach ($protocols as $protocol):
                                        $checked = ($protocol['id'] && $protocol['treatment'] == 'informal') ? 'checked="checked"' : "";
                                        $protocol['id'] = (isset($parentId) && $parentId == $protocol['brand_id']) ? null : $protocol['id'];
                                ?>
                                <li>
                                    <label><?php echo ucfirst($protocol['service']); ?></label>
                                    <label class="switch">
                                        <input id="<?php echo $protocol['service']; ?>" name="services[<?php echo $protocol['service']; ?>][active]" type="checkbox" value="<?php echo $protocol['service']; ?>" <?php echo $checked; ?>>
                                        <span class="treatment-slider round butonText"></span>
                                        <input type="hidden" name="services[<?php echo $protocol['service']; ?>][id]" value="<?php echo $protocol['id']; ?>">
                                    </label>
                                </li>
                                <br/>
                                <?php
                                    endforeach;
                                ?>
                            </ul>
                            <input class="btn btn-success" name="protocol-submit" type="submit" value=" <?php echo $ProtocolLang['Change treatment'] ?>">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
