<?php //Miramos si esta definida la variable de control de index.php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
} ?>

<?php include LANG . $_SESSION['userLang'] . '/referrers.php' ?>
<div id="wrapper">
    <?php include TEMPLATES . 'hotel-sidebar.php'; ?>
    <div id="page-content-wrapper">
        <div class="top-bar">
            <?php include TEMPLATES . 'referrals-menu.php' ?>
            <img class="topbarLogo visible-xs" src="<?php echo DIR_IMG . 'topbar-logo-xs.png' ?>" width="77" height="70" alt="top bar logo">
        </div>
        <div class="utility-bar">
            <div class="col-lg-12">
                <h1 class="pull-left"><i class="fa fa-truck"></i> <?php echo $referrersLang['Referrers list'] ?> <?php echo "($totalUsuarios)" ?></h1>
                <div class="breadcrumbs pull-right">
                    <ul>
                        <?php include(TEMPLATES . 'breadcrumbs.php'); ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="mainContent" id="fullContainer">
            <div class="col-lg-12 mt">
                <?php if (!empty($arrayUsuarios)) { ?>
                    <form action="<?php echo $urlActual ?>">
                        <div class="input-group mb2">
                            <span class="input-group-addon"><i class="fa fa-search"></i></span>
                            <input type="text" class="form-control input-lg" name="search" id="cuponMainSearch"
                                   placeholder="<?php echo $referrersLang['Search by user name or code'] ?>">
                        </div>
                    </form>
                    <div class="clearfix"></div>
                    <div class="table-responsive mt relative">
                        <table class="table table-striped">
                            <tr class="table-header">
                                <td>
                                    <span><?php echo $referrersLang['Referral´s name'] ?></span> <a
                                            href="<?php echo $urlActual ?>?ord=name" title="sort"><i
                                                class="fa fa-sort pl"></i></a>
                                </td>
                                <td>
                                    <i class="fa fa-twitter"></i> <a href="<?php echo $urlActual ?>?ord=tw_followers"
                                                                     title="sort"><i class="fa fa-sort pl"></i></a>
                                </td>
                                <td>
                                    <i class="fa fa-facebook"></i> <a href="<?php echo $urlActual ?>?ord=fb_friends"
                                                                      title="sort"><i class="fa fa-sort pl"></i></a>
                                </td>

                                <td>
                                    <span><?php echo $referrersLang['First Visit Date'] ?></span> <a
                                            href="<?php echo $urlActual ?>?ord=created_at" title="sort"><i
                                                class="fa fa-sort pl"></i></a>
                                </td>
                                <td>
                                    <span><?php echo $referrersLang['Booking Date'] ?></span> <a
                                            href="<?php echo $urlActual ?>?ord=redeemed_at" title="sort"><i
                                                class="fa fa-sort pl"></i></a>
                                </td>

                                <td>
                                    <span><?php echo $referrersLang['Total spent'] ?></span> <a
                                            href="<?php echo $urlActual ?>?ord=total_spent" title="sort"><i
                                                class="fa fa-sort pl"></i></a>
                                </td>
                                <td>
                                    <span><?php echo $referrersLang['Status'] ?></span> <a
                                            href="<?php echo $urlActual ?>?ord=status" title="sort"><i
                                                class="fa fa-sort pl"></i></a>
                                </td>
                                <td>
                                    <span><?php echo $referrersLang['Transaction Code'] ?></span>
                                </td>
                                <td>
                                    <span><?php echo $referrersLang['Destination Hotel'] ?></span>
                                </td>
                                <td>
                                    <span class="pull-right"><?php echo $referrersLang['Actions'] ?></span>
                                </td>
                            </tr>
                            <?php foreach ($arrayUsuarios as $usuario) { ?>
                                <tr class="table-row">
                                    <td>
                                        <?php if (!empty($usuario['img'])) { ?>
                                            <img class="img-circle img-thumbnail" src="<?php echo $usuario['img'] ?>"
                                                 alt="user avatar" width="50" height="50">
                                        <?php } else { ?>
                                            <img class="img-circle img-thumbnail" src="<?php echo DIR_IMG; ?>avatar.jpg"
                                                 alt="user avatar" width="50" height="50">
                                        <?php } ?>
                                        <span class="pl"><?php echo $usuario['name'] ?></span>
                                    </td>
                                    <td>
                                        <?php echo $usuario['tw_followers'] ?>
                                    </td>
                                    <td>
                                        <?php echo $usuario['fb_friends'] ?>
                                    </td>
                                    <td>
                                        <?php echo $usuario['created_at'] ?>
                                    </td>
                                    <td>
                                        <?php echo $usuario['redeemed_at'] ?>
                                    </td>
                                    <td>
                                        <?php echo $usuario['total_spent'] . ' ' . $monedaHotel ?>
                                    </td>
                                    <td>
                                        <?php echo $usuario['status'] ?>
                                    </td>
                                    <td>
                                        <?php echo $usuario['transaction'] ?>
                                    </td>
                                    <td>
                                        <?php echo $usuario['hotelName'] ?>
                                    </td>
                                    <td>
                                        <?php if(array_get($usuario,'id') != null){?>
                                        <div class="btn-group-vertical pull-right">
                                            <a href="mailto:<?php echo $usuario['completeEmail'] ?>"
                                               class="btn btn-default hasTooltip btn-sm" data-toggle="tooltip"
                                               data-placement="left"
                                               title="<?php echo $referrersLang['Send a mail to user'] ?>"><i
                                                        class="fa fa-envelope-o"></i></a>
                                            <a href="<?php echo $urlTree['referrals-details'] . '/' . $usuario['id'] ?>"
                                               class="btn btn-default hasTooltip" data-toggle="tooltip"
                                               data-placement="left"
                                               title="<?php echo $referrersLang['See details about this referral'] ?>"><i
                                                        class="fa fa-eye"></i></a>
                                        </div>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </table>
                        <?php include TEMPLATES . 'paginacion-template-alldirs.php'; ?>
                    </div>
                <?php } else { ?>
                    <div class="text-center mt2 container no-data-msg">
                        <i class="fa fa-truck grisClaro fa-5x"></i>
                        <h2><?php echo $referrersLang['There´re no referral activity at this moment'] ?></h2>
                        <h4><?php echo $referrersLang['By the way, ¿Are your customers sharing his opinion about your hotel?'] ?></h4>
                        <a href="<?php echo $urlTree['invitar-usuarios'] ?>/?alert=1"
                           class="btn btn-lg btn-success mt2"><?php echo $referrersLang['Start inviting guests to your hotel'] ?></a>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>