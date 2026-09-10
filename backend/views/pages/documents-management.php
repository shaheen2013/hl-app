<?php //Miramos si esta definida la variable de control de index.php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
} ?>

<?php include LANG . $_SESSION['userLang'] . '/documents-management.php' ?>
<div id="wrapper">
    <?php include TEMPLATES . 'hotel-sidebar.php'; ?>
    <div id="page-content-wrapper">

        <div class="top-bar">
            <?php include TEMPLATES . 'hotel-profile-menu.php'; ?>
            <img class="topbarLogo visible-xs" src="<?php echo DIR_IMG . 'topbar-logo-xs.png' ?>" width="77"
                 height="70"
                 alt="top bar logo">
        </div>

        <div class="utility-bar">
            <div class="col-lg-12">
                <h1 class="pull-left"><i class="fa fa-file-text"></i><?php echo $lang['Title'] ?></h1>
                <div class="breadcrumbs pull-right">
                    <ul>
                        <?php include(TEMPLATES . 'breadcrumbs.php'); ?>
                    </ul>
                </div>
            </div>
        </div>

        <div class="mainContent" id="fullContainer">

            <!-- Documents list -->
            <div class="row" style="padding-left: 30px; padding-right: 30px">
                <div class="col-lg-12 mt2">
                    <div class="panel panel-default noPadding">
                        <div class="panel-heading">
                            <?php echo $lang['Documents list'] ?>
                        </div>
                        <ul class="list-group">
                            <?php foreach ($documentList as $document) { ?>
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-lg-10">
                                <a href="<?php echo $urlTree['document-detail'] . '/' . $document['id'] ?>"
                                   title="<?php echo $document['name'] ?> detail">
                                    <?php echo $document['name'] ?>
                                </a>
                                    </div>
                                     <div class="col-lg-2">
                                         <p style="<?php echo $document['active'] ? 'color: green' : 'color: red' ?>">
                                             <?php echo $document['active'] ?
                                                 $lang['Document status active'] :
                                                 $lang['Document status inactive']
                                                ?>
                                         </p>
                                     </div>
                                </div>
                            </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Add new document button -->
            <div class="row" style="padding-left: 30px; padding-right: 30px">
                <div class="col-lg-3 mb2">
                    <a href="<?php echo $urlTree['document-detail']?>"
                       class="btn btn-success btn-lg btn-block">
                        <?php echo $lang['Add new document'] ?>
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>
