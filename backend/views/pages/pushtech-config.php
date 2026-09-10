<?php //Miramos si esta definida la variable de control de index.php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
} ?>
<?php include LANG . $_SESSION['userLang'] . '/pushtech-config.php' ?>
<div id="wrapper">
    <?php include TEMPLATES . 'hotel-sidebar.php'; ?>
    <div id="page-content-wrapper">
        <div class="top-bar">
            <?php include TEMPLATES . 'hotel-profile-menu.php'; ?>
        </div>
        <div class="utility-bar mb15">
            <div class="col-lg-12">
                <div class="row">
                    <h1 class="pull-left"><img src="../../public/img/pushtech_logo.png" alt="pushtech"></h1>
                    <div class="breadcrumbs pull-right">
                        <ul>
                            <?php include(TEMPLATES . 'breadcrumbs.php'); ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <?php if (!empty($offers)) : ?>
            <?php if (!empty($campaigns)) : ?>
                <div class="col-lg-12">
                    <?php if ((!empty($_SESSION['c_logueado']) && empty($chain_website)) || empty($hotel_website) || empty($booking_engine)) : ?>
                        <div class="alert alert-danger" role="alert">
                            <h4><?php echo $pushtech_lang['Please fix those issues before sending campaigns'] ?></h4>
                            <ul>
                                <?php if (!empty($_SESSION['c_logueado'])) : ?>
                                    <?php echo(empty($chain_website) ? '<li>' . $pushtech_lang['Chain website is empty'] . '</li>' : '') ?>
                                <?php endif; ?>
                                <?php echo(empty($hotel_website) ? '<li>' . $pushtech_lang['Hotel website is empty'] . '</li>' : '') ?>
                                <?php echo(empty($booking_engine) || $booking_engine == '0' ? '<li>' . $pushtech_lang['No booking engine selected for this hotel'] . '</li>' : '') ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    <div class="panel panel-default mt20">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <h4><?php echo $pushtech_lang['Map'] ?>
                                        <strong>Pushtech</strong> <?php echo $pushtech_lang['campaigns with'] ?>
                                        <strong>Hotelinking</strong> <?php echo $pushtech_lang['offers'] ?>
                                    </h4>
                                    <!--mapping-->
                                    <?php foreach ($mappings as $mapping): ?>
                                        <form method="POST">
                                            <div class="row pushtech_mapping">
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <select name="pushtech_campaign" class="form-control">
                                                            <option value><?php echo $pushtech_lang['Select Pushtech campaign...'] ?></option>
                                                            <?php foreach ($campaigns as $campaign => $detail) : ?>
                                                                <option value="<?php echo $detail['id'] ?>" <?php echo($detail['id'] == $mapping['id_campaign'] ? 'selected' : '') ?>><?php echo $detail['name'] ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-1 text-center hidden-xs">
                                                    <i class="fa fa-arrows-h fa-3x" aria-hidden="true"></i>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <select name="hotelinking_offer" class="form-control">
                                                            <option value><?php echo $pushtech_lang['Select Hotelinking offer...'] ?></option>
                                                            <?php foreach ($offers as $offer) : ?>
                                                                <option value="<?php echo $offer['id_oferta'] ?>" <?php echo($offer['id_oferta'] == $mapping['id_offer'] ? 'selected' : '') ?>><?php echo $offer['nombre'] ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="form-group">
                                                        <input type="number" class="form-control" name="days_valid"
                                                               placeholder="Valid days"
                                                               value="<?php echo(!empty($mapping['days_valid']) ? $mapping['days_valid'] : '') ?>">
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="btn-group pull-right" role="group" aria-label="...">
                                                        <button class="btn btn-primary save-mapping-button"
                                                                type="submit" name="action" value="updateCampaign"><i
                                                                    class="fa fa-refresh" aria-hidden="true"></i>
                                                        </button>
                                                        <button class="btn btn-warning delete-mapping-button"
                                                                type="submit" name="action" value="deleteMapping"><i
                                                                    class="fa fa-times" aria-hidden="true"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="hidden" name="id_mapping" value="<?php echo $mapping['id'] ?>">
                                        </form>
                                    <?php endforeach; ?>
                                    <!--end mapping-->
                                    <!-- new mapping-->
                                    <div class="row pushtech_mapping">
                                        <form method="POST">
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <select name="pushtech_campaign" class="form-control">
                                                        <option value><?php echo $pushtech_lang['Select Pushtech campaign...'] ?></option>
                                                        <?php foreach ($campaigns as $campaign => $detail) : ?>
                                                            <option value="<?php echo $detail['id'] ?>"><?php echo $detail['name'] ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-1 text-center hidden-xs">
                                                <i class="fa fa-arrows-h fa-3x" aria-hidden="true"></i>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <select name="hotelinking_offer" class="form-control">
                                                        <option value><?php echo $pushtech_lang['Select Hotelinking offer...'] ?></option>
                                                        <?php foreach ($offers as $offer) : ?>
                                                            <option value="<?php echo $offer['id_oferta'] ?>"><?php echo $offer['nombre'] ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="form-group">
                                                    <input type="number" class="form-control" name="days_valid"
                                                           placeholder="Valid days">
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="btn-group pull-right" role="group" aria-label="...">
                                                    <button class="btn btn-success save-mapping-button" type="submit"
                                                            name="action"
                                                            value="updateCampaign"><?php echo $pushtech_lang['Create new mapping'] ?>
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <!-- end new mapping-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="col-lg-12">
                    <div class="alert alert-danger" role="alert">
                        <?php echo $pushtech_lang['Create campaigns on Pushtech tool first'] ?>
                    </div>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="col-lg-12">
                <div class="alert alert-danger" role="alert">
                    <?php echo $pushtech_lang['First you need to create offers to match with Pushtech campaigns'] ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="<?php echo DIR_JS ?>feedback-errors.js"></script>
