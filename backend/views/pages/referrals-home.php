<?php
//Miramos si esta definida la variable de control de index.php
if (!defined('INDEXCONTROLVAL')) {
    echo 'No direct access allowed.';
    exit;
}

include LANG . $_SESSION['userLang'].'/referrals-home.php';
?>

<div id="wrapper">
    <?php include TEMPLATES . 'hotel-sidebar.php'; ?>
    <div id="page-content-wrapper">

        <div class="top-bar">
            <?php include TEMPLATES . 'referrals-menu.php' ?>
            <img class="topbarLogo visible-xs" src="<?php echo DIR_IMG . 'topbar-logo-xs.png' ?>" width="77" height="70"
                 alt="top bar logo">
        </div>

        <div class="utility-bar">
            <div class="col-lg-12">
                <h1 class="pull-left"><i class="fa fa-tachometer" aria-hidden="true"></i> <?php echo $referralsHomeLang['Referrals Dashboard'] ?></h1>
                <div class="breadcrumbs pull-right">
                    <ul>
                        <?php include(TEMPLATES . 'breadcrumbs.php'); ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="mainContent mt">
            <div class="pl pr">
                <div class="panel panel-default panel-no-padding">
                    <div class="panel-heading"><?php echo $referralsHomeLang['Select range dates'] ?> <?php echo (!empty($_SESSION['c_logueado']) ? $referralsHomeLang['and search type'] : '') ?></div>
                    <div class="panel-body">
                        <form class="form-inline referrals-filters" method="GET" action="<?php echo SECURE_BASE_PATH . $urlTree['referrals-home'] . DS ?>">

                            <div class="form-group">
                                <input type="text" class="form-control range-picker" id="range" value="<?php echo $_SESSION['startDate'] . ' - ' . $_SESSION['endDate'] ?>">
                            </div>
                            <input type="hidden" name="type" value="<?php echo $_SESSION['tipo'] ?>" id="input-type">
                            <input type="hidden" name="startDate" id="startDate" value="<?php echo $_SESSION['startDate'] ?>">
                            <input type="hidden" name="endDate" id="endDate" value="<?php echo $_SESSION['endDate'] ?>">
                            <?php if(!empty($_SESSION['c_logueado'])) {?>
                                <div class="btn-group">
                                    <button class="btn btn-default btn-filter <?php echo $_SESSION['tipo'] == 'h' ? 'active' : '' ?>" data-type="h">Hotel</button>
                                    <button class="btn btn-default btn-filter <?php echo $_SESSION['tipo'] == 'c' ? 'active' : '' ?>" data-type="c"><?php echo $referralsHomeLang['Cadena'] ?></button>
                                </div>
                            <?php } ?>

                            <button type="submit" class="btn btn-primary"><?php echo $referralsHomeLang['Change filters'] ?></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="mainContent chartsContainer">
            <div class="mainContent" id="homeContainer">
                <div class="column-width"></div>

                <!--item-->
                <div class="item">
                    <div class="white-module">
                        <!-- <div class="no-data-module"></div> -->
                        <h5><?php echo $referralsHomeLang['Total Users Database'] ?></h5>
                        <div class="hlChart gaugesChart" id="users-chart"></div>
                        <?php if ($total_users == 0) { ?>
                            <div class="no-data-module"></div>
                        <?php } else { ?>
                            <div class="text-center-chart">
                                <h2 class="azul"><?php echo $total_users ?></h2>
                                <small><?php echo $referralsHomeLang['Total users'] ?></small>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <!--item-->
                <div class="item">
                    <div class="white-module data-module numberChart">
                        <h5><?php echo $referralsHomeLang['PPC Value'] ?></h5>
                        <div class="text-center-chart">
                            <i class="fa fa-money fa-3x grisClaro" aria-hidden="true"></i>
                            <h2 class="chartNumberSmall"><?php echo $ppcValue ?> $</h2>
                        </div>
                    </div>
                </div>

                <!--item-->
                <div class="item">
                    <div class="white-module data-module numberChart">
                        <h5><?php echo $referralsHomeLang['Facebook impressions value'] ?></h5>
                        <div class="text-center-chart">
                            <i class="fa fa-money fa-3x grisClaro" aria-hidden="true"></i>
                            <h2 class="chartNumberSmall"><?php echo $total_sm_friends_value ?> $</h2>
                        </div>
                    </div>
                </div>

                <!--item-->
                <div class="item">
                    <div class="white-module data-module numberChart">
                        <h5><?php echo $referralsHomeLang['Total Shares'] ?></h5>
                        <div class="text-center-chart">
                            <i class="fa fa-share-alt fa-3x grisClaro" aria-hidden="true"></i>
                            <h2 class="chartNumberSmall"><?php echo $total_shares ?></h2>
                        </div>
                    </div>
                </div>

                <!--item-->
                <div class="item">
                    <div class="white-module data-module numberChart">
                        <h5><?php echo $referralsHomeLang['Total Facebook connections'] ?></h5>
                        <div class="text-center-chart">
                            <i class="fa fa-users fa-3x grisClaro" aria-hidden="true"></i>
                            <h2 class="chartNumberSmall"><?php echo $total_sm_friends ?></h2>
                        </div>
                    </div>
                </div>

                <!--item-->
                <div class="item">
                    <div class="white-module data-module numberChart">
                        <h5><?php echo $referralsHomeLang['Total Facebook impressions'] ?></h5>
                        <div class="text-center-chart">
                            <i class="fa fa-desktop fa-3x grisClaro" aria-hidden="true"></i>
                            <h2 class="chartNumberSmall"><?php echo $total_impressions ?></h2>
                        </div>
                    </div>
                </div>

                <!--item-->
                <div class="item">
                    <div class="white-module data-module numberChart">
                        <h5><?php echo $referralsHomeLang['Unique clicks'] ?></h5>
                        <div class="text-center-chart">
                            <i class="fa fa-mouse-pointer fa-3x grisClaro" aria-hidden="true"></i>
                            <h2 class="chartNumberSmall"><?php echo $clicks_unicos ?></h2>
                        </div>
                    </div>
                </div>

                <!--item-->
                <div class="item">
                    <div class="white-module data-module numberChart">
                        <h5><?php echo $referralsHomeLang['Total referrers'] ?></h5>
                        <div class="text-center-chart">
                            <i class="fa fa-bullhorn fa-3x grisClaro" aria-hidden="true"></i>
                            <h2 class="chartNumberSmall"><?php echo $total_referrers ?></h2>
                        </div>
                    </div>
                </div>

                <!--item-->
                <div class="item">
                    <div class="white-module data-module numberChart">
                        <h5><?php echo $referralsHomeLang['Sign ups'] ?></h5>
                        <div class="text-center-chart">
                            <i class="fa fa-user-plus fa-3x grisClaro" aria-hidden="true"></i>
                            <h2 class="chartNumberSmall"><?php echo $sign_ups ?></h2>
                        </div>
                    </div>
                </div>

                <!--item-->
                <div class="item">
                    <div class="white-module">
                        <!-- <div class="no-data-module"></div> -->
                        <h5><?php echo $referralsHomeLang['Total referrals'] ?></h5>
                        <div class="hlChart gaugesChart" id="referrals-chart"></div>
                        <?php if ($kpisReferrals['total_referrals'] == 0) { ?>
                            <div class="no-data-module"></div>
                        <?php } else { ?>
                            <div class="text-center-chart">
                                <h2 class="azul"><?php echo $total_referrals ?></h2>
                                <small><?php echo $referralsHomeLang['Total referrals'] ?></small>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <!--item-->
                <div class="item">
                    <div class="white-module data-module numberChart">
                        <h5><?php echo $referralsHomeLang['Total reviews sent'] ?></h5>
                        <?php if($satisfaction_permissions == 1) { ?>

                            <!-- <div class="no-data-module"></div> -->
                            <div class="text-center-chart">
                                <i class="fa fa-envelope-o fa-3x grisClaro" aria-hidden="true"></i>
                                <h2 class="chartNumberSmall"><?php echo $total_reviews ?></h2>
                            </div>
                        <?php } else { ?>
                            <div class="text-center-chart">
                                <i class="fa fa-exclamation fa-3x grisClaro" aria-hidden="true"></i>
                                <br>
                                <small><?php echo $referralsHomeLang['You need to adquire this module<br>please contact our'] ?> <a href="mailto:helpdesk@hotelinking.com"><?php echo $referralsHomeLang['sales department'] ?></a></small>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <!--item-->
                <div class="item">
                    <div class="white-module">
                        <!-- <div class="no-data-module"></div> -->
                        <h5><?php echo $referralsHomeLang['Reviews results'] ?></h5>
                        <?php if($satisfaction_permissions == 1) { ?>
                            <div class="hlChart gaugesChart" id="reviews-chart"></div>
                            <div class="text-center-chart">
                                <h2 class="azul"><?php echo $media_satisfaction ?></h2>
                                <small><?php echo $referralsHomeLang['Satisfaction median'] ?></small>
                            </div>
                        <?php } else { ?>
                            <div class="text-center-chart">
                                <i class="fa fa-exclamation fa-3x grisClaro" aria-hidden="true"></i>
                                <br>
                                <small><?php echo $referralsHomeLang['You need to adquire this module<br>please contact our'] ?> <a href="mailto:helpdesk@hotelinking.com"><?php echo $referralsHomeLang['sales department'] ?></a></small>
                            </div>
                        <?php } ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script src="<?php echo DIR_JS . 'packery.js' ?>"></script>
<script src="<?php echo DIR_JS . 'jquery-ui-1.9.2.custom.min.js' ?>"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<?php include LIB . 'referrals-home-charts.php' ?>
<script>
    $(document).ready(function () {
        var container = document.querySelector('#homeContainer');
        var pckry = new Packery(container, {
            // options
            itemSelector: '.item',
            "columnWidth": ".column-width",
            "rowHeight": 305
        });

        $('.range-picker').daterangepicker({
            autoApply: true,
            maxDate: moment(),
            locale: {
                format: 'YYYY-MM-DD'
            },
            ranges : {
                '<?php echo $referralsHomeLang['Today']?>': [moment(), moment()],
                '<?php echo $referralsHomeLang['Yesterday']?>': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                '<?php echo $referralsHomeLang['Last 7 Days']?>': [moment().subtract(6, 'days'), moment()],
                '<?php echo $referralsHomeLang['Last 30 Days']?>': [moment().subtract(29, 'days'), moment()],
                '<?php echo $referralsHomeLang['This Month']?>': [moment().startOf('month'), moment().endOf('month')],
                '<?php echo $referralsHomeLang['Last Month']?>': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        });

        $('.range-picker').on('apply.daterangepicker', function(ev, picker) {
            $('#startDate').val(picker.startDate.format('YYYY-MM-DD'));
            $('#endDate').val(picker.endDate.format('YYYY-MM-DD'));
        });

        $('.btn-filter').click(function(e){
            e.preventDefault();
            $('.btn-filter').removeClass('active');
            $(this).addClass('active');
            $('#input-type').val($(this).data("type"));
        });
    });
</script>