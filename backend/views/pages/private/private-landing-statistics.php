<div class="mainContent chartsContainer">
    <div class="col-lg-12">
        <h1 class="mb2 text-center">Estadísticas LANDING para el hotel: <strong><?php echo $hotelStatistics['hotelName'] ?></strong></h1>
        <?php include TEMPLATES . 'private/top-menu.php'?>
        <div class="mainContent mt2" id="homeContainer">
            <div class="column-width"></div>
            <!-- Landing opens -->
            <div class="item">
                <div class="white-module">
                    <h5>Iframe Landing</h5>
                    <div class="hlChart gaugesChart" id="landing-opens">
                    </div>
                    <div class="centered-data v-center">
                        <span class="azul">ABIERTO</span>
                        <h2 class="azul"><?php echo $hotelStatistics['landing_iframe_opens'] ?></h2>
                        <span class="azul">VECES</span>
                    </div>
                </div>
            </div>
            <!-- Landing opens -->
            <!-- facebook cliks -->
            <div class="item">
                <div class="white-module">
                    <h5>Facebook clicks</h5>
                    <div class="centered-data v-center">
                        <h2 class="azul"><?php echo $fcWin ?></h2>
                        <h4 class="verde"><i class="fa fa-arrow-circle-o-up"></i> <?php echo $fcPercent ?>%</h4>
                    </div>
                </div>
            </div>
            <!-- facebook cliks -->
            <!-- facebook success-->
            <div class="item">
                <div class="white-module">
                    <h5>Facebook success rate</h5>
                    <div class="centered-data v-center">
                        <h2 class="azul"><?php echo $fbSuccess ?></h2>
                        <h4 class="verde"><i class="fa fa-arrow-circle-o-up"></i> <?php echo $fbConversions ?>%</h4>
                    </div>
                </div>
            </div>
            <!-- facebook success-->
            <!-- email clicks -->
            <div class="item">
                <div class="white-module">
                    <h5>Email clicks</h5>
                    <div class="centered-data v-center">
                        <h2 class="azul"><?php echo $emailWin ?></h2>
                        <h4 class="verde"><i class="fa fa-arrow-circle-o-up"></i> <?php echo $emailPercent ?>%</h4>
                    </div>
                </div>
            </div>
            <!-- email clicks -->
            <!-- mail success-->
            <div class="item">
                <div class="white-module">
                    <h5>Email success rate</h5>
                    <div class="centered-data v-center">
                        <h2 class="azul"><?php echo $mailSuccess ?></h2>
                        <h4 class="verde"><i class="fa fa-arrow-circle-o-up"></i> <?php echo $mailConversions ?>%</h4>
                    </div>
                </div>
            </div>
            <!-- mail success-->
            <!-- permissions -->
            <div class="item">
                <div class="white-module">
                    <h5>Denied permissions</h5>
                    <div class="centered-data v-center" style="width:80%">
                        <p class="naranja">PUBLIC PROFILE: <strong><?php echo $publicProfile ?></strong></p>
                        <p class="naranja">EMAIL: <strong><?php echo $email ?></strong></p>
                        <p class="naranja">USER FRIENDS: <strong><?php echo $userFriends ?></strong></p>
                        <p class="naranja">PUBLISH ACTIONS: <strong><?php echo $publishActions ?></strong></p>
                    </div>
                </div>
            </div>
            <!-- permissions -->
            <!-- cancels -->
            <div class="item">
                <div class="white-module">
                    <h5>First time Cancels</h5>
                    <div class="centered-data v-center">
                        <h2 class="azul"><?php echo $cancels ?></h2>
                        <h4 class="verde">REINTENTS: <?php echo $reintents ?></h4>
                    </div>
                </div>
            </div>
            <!-- cancels -->
        </div>
    </div>
</div>
<script src="<?php echo DIR_JS . 'packery.js'?>"></script>
<script src="<?php echo DIR_JS . 'jquery-ui-1.9.2.custom.min.js'?>"></script>
<?php include LIB . 'landing-charts-private-statistics.php' ?>
<script src="https://code.highcharts.com/highcharts.js"></script>