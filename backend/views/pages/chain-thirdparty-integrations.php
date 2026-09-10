<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>
<div id="wrapper">
    <?php include TEMPLATES . 'hotel-sidebar.php'; ?>
    <div id="page-content-wrapper">
        <div class="top-bar">
            <?php include TEMPLATES . 'chain-management-menu.php'; ?>
            <img class="topbarLogo visible-xs" src="<?php echo DIR_IMG . 'topbar-logo-xs.png' ?>" width="77" height="70"
                 alt="top bar logo">
        </div>
        <div class="utility-bar">
            <div class="col-lg-12">
                <h1 class="pull-left"><i class="fa fa-cogs" aria-hidden="true"></i> Chain integrations</h1>
                <div class="breadcrumbs pull-right">
                    <ul>
                        <?php include (TEMPLATES .'breadcrumbs.php'); ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-8 mt2">
            <div id="fullContainer">
                <section class="pushtech_integration">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <img src="<?php echo DIR_IMG . 'pushtech_logo.png'?>" alt="pushtech logo">
                            <form class="form-inline mt2" method="POST">
                                <div class="form-group">
                                    <input type="text" class="form-control" id="pushtech-token" name="pustechToken" value="<?php echo $pushtech['token'] ?>" placeholder="Insert pushtech Token here...">
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" id="pushtech-secret" name="pustechSecret" value="<?php echo $pushtech['secret'] ?>" placeholder="Insert pustech Secret here...">
                                </div>
                                <button type="submit" class="btn btn-success">Save</button>
                                <button type="reset" class="btn btn-warning">Reset</button>
                            </form>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>