<?php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;};
include LANG . $_SESSION['userLang'].'/hotel-profile-sm.php';
?>
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
                <h1 class="pull-left"><i class="fa fa-facebook"></i> <?php echo $hotelProfileSmLang['Social media details']?></h1>
                <div class="breadcrumbs pull-right">
                    <ul>
                        <?php include (TEMPLATES .'breadcrumbs.php'); ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="mainContent" id="fullContainer">

            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-8 mt2">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <form class="form-inline" method="POST" action="<?php echo $urlTree['hotel-profile-sm'] ?>">
                                    <div class="form-group">
                                        <label for="facebookFP"><?php echo $hotelProfileSmLang['Facebook Fan Page'] ?></label>
                                        <div class="input-group">
                                            <div class="input-group-addon">https://www.facebook.com/</div>
                                            <input type="text" class="form-control" id="facebookFP" name="facebookFP" value="<?php echo $fbFanPage ?>" placeholder="<?php echo $hotelProfileSmLang['your page name'] ?>">
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-success mt2"><?php echo $hotelProfileSmLang['Save facebook page'] ?></button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <h3><?php echo $hotelProfileSmLang['Social media share text'] ?></h3>

                <form class="form-inline" method="POST" action="<?php echo $urlTree['hotel-profile-sm'] ?>">
                    <input type="hidden" name="shareMediaTextLang" value="<?php echo $actualLang ?>" id="actualLang">

                    <div class="row">
                        <div class="col-lg-8 mt2">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <p><?php echo $hotelProfileSmLang['Actual language:'] ?><span id="langSected"><img src="<?php echo BASE_PATH . DIR_IMG . 'flags/' . $idiomasHotel[0]['img'] ?>" alt="<?php echo $idiomasHotel[0]['lang'] ?> flag" class="pl flag-icon"> <strong><?php echo $idiomasHotel[0]['country'] ?></strong></span></p>
                                    <div class="dropdown">
                                        <button class="btn btn-primary dropdown-toggle btn-block" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="true">
                                            <?php echo $hotelProfileSmLang['Edit in other language'] ?>
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1" >
                                            <?php foreach($idiomasHotel as $idioma){ ?>
                                                <li role="presentation" onclick="changeLang('<?php echo $idioma['lang'] ?>')" class='langLi'> <a href="#" class="langLia"><span id="circle-<?php echo $idioma['lang'] ?>"><?php if(!empty($_SESSION[$idioma['lang']]['check']) && $_SESSION[$idioma['lang']]['check']==1) echo'<i class="fa fa-check-circle-o verde lang-ok"></i>' ?></span> <img src="<?php echo BASE_PATH . DIR_IMG . 'flags/' . $idioma['img'] ?>" alt="<?php echo $idioma['lang'] ?> flag" class="pl flag-icon"> <strong><?php echo $idioma['country'] ?></strong></a></li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                    <p class="mt text-center"><small><a href="<?php echo $urlTree['hotel-profile-langs'] ?>"><?php echo $hotelProfileSmLang['Select more languages'] ?></a></small></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-8 mt2">
                            <div class="panel panel-default noPadding">
                                <div class="panel-heading">
                                    <?php echo $hotelProfileSmLang['Pre-stay'] ?>
                                </div>
                                <div class="panel-body">
                                    <textarea id="sharePreText" class="shareMediaText form-control" name="pre" placeholder="Write pre-stay message here..."><?php echo $socialMediaTextShare['pre'] ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-8 mt2">
                            <div class="panel panel-default noPadding">
                                <div class="panel-heading">
                                    <?php echo $hotelProfileSmLang['Stay'] ?>
                                </div>
                                <div class="panel-body">
                                    <textarea id="shareStayText" class="shareMediaText form-control" name="stay" placeholder="Write stay message here..."><?php echo $socialMediaTextShare['stay'] ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-8 mt2">
                            <div class="panel panel-default noPadding">
                                <div class="panel-heading">
                                    <?php echo $hotelProfileSmLang['Post-stay'] ?>
                                </div>
                                <div class="panel-body">
                                    <textarea id="sharePostText" class="shareMediaText form-control" name="post" placeholder="Write post stay message here..."><?php echo $socialMediaTextShare['post'] ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success mt2"><?php echo $hotelProfileSmLang['Save'] ?></button>
                </form>

            </div>


            

        </div>
    </div>
</div>
<script src="<?php echo DIR_JS ?>feedback-errors.js"></script>
<script src="<?php echo DIR_JS ?>hotel-profile-sm.min.js"></script>