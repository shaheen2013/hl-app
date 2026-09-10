<?php //Miramos si esta definida la variable de control de index.php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
} ?>

<?php include LANG . $_SESSION['userLang'] . '/autocheckin-configuration.php' ?>
<script src="<?php echo DIR_JS ?>autocheckin-configuration.js"></script>
<div id="wrapper">
    <?php include TEMPLATES . 'hotel-sidebar.php'; ?>
    <div id="page-content-wrapper">

        <div class="top-bar">
            <?php include TEMPLATES . 'hotel-profile-menu.php'; ?>
            <img class="topbarLogo visible-xs" src="<?php echo DIR_IMG . 'topbar-logo-xs.png' ?>" width="77" height="70" alt="top bar logo">
        </div>

        <div class="utility-bar">
            <div class="col-lg-12">
                <h1 class="pull-left"><i class="fa fa-file-text" style="padding-right: 10px;"></i><?php echo $lang['Title'] ?></h1>
                <div class="breadcrumbs pull-right">
                    <ul>
                        <?php include(TEMPLATES . 'breadcrumbs.php'); ?>
                    </ul>
                </div>
            </div>
        </div>

        <div class="mainContent" id="fullContainer">

            <div class="col-lg-12 mt2">

                <form name="commentsConfigurationForm" id="commentsConfigurationForm" action="<?php echo $url['dir1'] ?>" method="post">
                    <!-- Comments emails panel -->
                    <div class="panel panel-default noPadding">
                        <div class="panel-heading">
                            <?php echo $lang['Comments header'] ?>
                        </div>
                        <div class="panel-body">
                            <div class="column">
                                <?php if (!$autocheckinCommentsProductActive) { ?>
                                    <div class="alert alert-warning" role="alert">
                                        <?php echo $lang['not active comments'] ?>
                                    </div>
                                <?php } else { ?>
                                    <div class="row form-row">
                                        <p class="col-lg-12"><?php echo $lang['Comments title'] ?></p>
                                        <div class="col-lg-6">
                                            <textarea rows="5" name="reservations_emails" class="form-control" placeholder="<?php echo $lang['Placeholder comments'] ?>"><?php echo $reservationCommentEmailList; ?></textarea>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <!-- End Comments emails panel -->
                    <!-- Documents emails panel -->
                    <div class="panel panel-default noPadding">
                        <div class="panel-heading">
                            <?php echo $lang['Documentation header'] ?>
                        </div>
                        <div class="panel-body">
                            <div class="column">
                                <?php if (!$autocheckinDocumentsProductActive && !$autocheckinIdentityDocumentsProductActive) { ?>
                                    <div class="alert alert-warning" role="alert">
                                        <?php echo $lang['not active documents'] ?>
                                    </div>
                                <?php } else { ?>
                                    <div class="row form-row">
                                        <p class="col-lg-12"><?php echo $lang['Documentation title'] ?></p>
                                        <div class="col-lg-6">
                                            <textarea rows="5" name="documents_emails" class="form-control" placeholder="<?php echo $lang['Placeholder comments'] ?>"><?php echo $documentsEmailList; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="row form-row mt2">
                                        <p class="col-lg-12"><?php echo $lang['Documentation subject'] ?></p>
                                        <div class="col-lg-6">
                                            <input type="text" name="documents_subject" class="form-control" placeholder="Documents {{bookingCode}}" value="<?php echo $documentsSubject ? $documentsSubject : "" ?>">
                                        </div>
                                    </div>
                                    <div class="row form-row mt2">
                                        <ul class="list-group" style="columns: 3; padding: 15px 0;">
                                            <?php foreach ($availableVariablesList as $variable) { ?>
                                                <li class="list-group-item" style="border: none">
                                                    <?php echo $variable['placeholder'] ?>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <!-- End Documents emails panel -->

                    <div class="row">
                        <div class="col-lg-3">
                            <input type="submit" class="btn btn-success btn-lg btn-block" name="hotelConfirmButton" style="margin-bottom: 10px" value="<?php echo $lang['Save'] ?>">
                        </div>
                    </div>
                </form>

            </div>

        </div>
    </div>
</div>