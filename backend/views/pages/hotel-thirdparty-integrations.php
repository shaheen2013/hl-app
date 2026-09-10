<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>
<div id="wrapper">
    <?php include TEMPLATES . 'hotel-sidebar.php'; ?>
    <div id="page-content-wrapper">
        <div class="top-bar">
            <?php include TEMPLATES . 'hotel-profile-menu.php'; ?>
            <img class="topbarLogo visible-xs" src="<?php echo DIR_IMG . 'topbar-logo-xs.png' ?>" width="77" height="70"
                 alt="top bar logo">
        </div>
        <div class="utility-bar">
            <div class="col-lg-12">
                <h1 class="pull-left"><i class="fa fa-cogs" aria-hidden="true"></i> Hotel integrations</h1>
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
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label for="pushtech-token">Pushtech Account id</label>
                                        <input type="text" class="form-control" id="pushtech-token" name="pustechToken" value="<?php echo $pushtech['token'] ?>" placeholder="Insert pushtech Token here...">
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="pushtech-token">Pushtech Account secret</label>
                                        <input type="text" class="form-control" id="pushtech-secret" name="pustechSecret" value="<?php echo $pushtech['secret'] ?>" placeholder="Insert pustech Secret here...">
                                    </div>
                                </div>
                                <div class="row mt2">
                                    <div class="col-lg-12">
                                        <button type="submit" class="btn btn-success">Save Pushtech data</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>