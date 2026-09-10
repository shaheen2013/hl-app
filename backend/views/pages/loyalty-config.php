<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}
include_once LANG .$_SESSION['userLang']. '/loyalty-config.php';
?>

<div id="wrapper">
    <?php include TEMPLATES . 'hotel-sidebar.php'; ?>
    <div id="page-content-wrapper">
        <div class="top-bar">
            <?php include TEMPLATES . 'hotel-profile-menu.php'; ?>
        </div>
        <div class="utility-bar">
            <div class="col-lg-12">
                <h1 class="pull-left"><i class="fa fa-repeat" aria-hidden="true"></i> <?php echo $loyaltyLang['Loyalty config'] ?></h1>
                <div class="breadcrumbs pull-right">
                    <ul>
                        <?php include (TEMPLATES .'breadcrumbs.php'); ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="mainContent" id="fullContainer">
            <div class="col-lg-8 mt2">
                <form action="<?php echo $url['dir1'] ?>" method="post">
                    <div class="col-lg-12">
                        <div class="panel panel-default noPadding">
                            <div class="panel-heading">
                                <?php echo $loyaltyLang['stay_mail_configuration'] ?>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="input-group col-lg-12 mt2">
                                            <input type="text" name="emails" class="form-control" placeholder="" value="<?php echo (isset($hotelLoyalty['loyalty_emails'])) ? $hotelLoyalty['loyalty_emails'] : "" ?>">
                                            <span class="input-group-addon"><label>
                                            <input type="checkbox" name="alerts" aria-label="Checkbox for following text input" <?php echo (isset($hotelLoyalty['loyalty_alerts']) && $hotelLoyalty['loyalty_alerts'] == 1) ? "checked" : "" ?>>  <?php echo $loyaltyLang['Loyalty alert'] ?></label>
                                        </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default noPadding">
                            <div class="panel-heading">
                                <?php echo $loyaltyLang['Loyalty summary config'] ?>
                            </div>
                            <div class="panel-body">
                                <div class="row form-row">
                                    <div class="col-lg-12">
                                        <div class="input-group">
                                            <span class="input-group">
                                                <input type="checkbox" name="summaryActive" aria-label="Checkbox for following text input" <?php echo data_get($loyaltyConfig, 'summaryActive') == 1 ? "checked" : "" ?>>  
                                                <?php echo $loyaltyLang['Active'] ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row form-row">
                                    <div class="col-lg-10">
                                        <?php echo $loyaltyLang['Days to send loyalty summary'] ?>
                                    </div>
                                </div>
                                <div class="row form-row">
                                    <div class="col-lg-3">
                                        <div class="input-group">
                                            <input type="num" name="summarySendDays" class="form-control"
                                                   value="<?php echo data_get($loyaltyConfig, 'summarySendDays', 0) ?>">
                                            
                                            <div class="input-group-addon"><?php echo $loyaltyLang['days'] ?></div>  
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="input-group">
                                            <input type="num" name="summarySendHours" class="form-control"
                                                   value="<?php echo data_get($loyaltyConfig, 'summarySendHours', 0) ?>">
                                            <div class="input-group-addon"><?php echo $loyaltyLang['hours'] ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <button type="submit" class="btn btn-success" name="hotelConfirmButton"><?php echo $loyaltyLang['Save'] ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>