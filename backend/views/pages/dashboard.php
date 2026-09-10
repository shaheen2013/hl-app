<?php $this->layout('_layout::layout')?>
<?php $this->start('content') ?>
<div class="grid"
     data-isotope='{ "itemSelector": ".grid-item", "percentPosition" : true, "masonry" : {"columnWidth" : ".grid-sizer"}}'>
    <div class="grid-sizer"></div>

    <!--item-->
    <div class="grid-item">
        <div class="ui card fluid statistics_card">
            <div class="content">
                <div class="header">Total clientes</div>
            </div>
            <div class="content ui center aligned">
                <div class="ui statistic number_statistic">
                    <div class="value">
                        83.789
                    </div>
                    <div class="label">
                        Clientes
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--item-->

    <!--item-->
    <div class="grid-item">
        <div class="ui card fluid statistics_card">
            <div class="content">
                <div class="header">Mujeres vs Hombres</div>
            </div>
            <div class="content">
                <canvas class="doughnut_statistic" id="fvsm"></canvas>
            </div>
        </div>
    </div>
    <!--item-->

</div>

<script>
    $(document).ready(function () {
        // Menu dropdowns
        $('.ui.dropdown')
            .dropdown()
        ;
        // Chart male vs female
        var ctx = document.getElementById("fvsm");
        var data = {
            datasets: [{
                data: [60000, 13789],
                backgroundColor: ['rgba(113,90,225,1)', 'rgba(179,166,225,1)']
            }],
            options: {
                tooltips: {
                    enabled: false
                }
            }
        };
        var options = {
            "cutoutPercentage": 80
        };
        var myDoughnutChart = new Chart(ctx, {
            type: 'doughnut',
            data: data,
            options: options
        });
    });
</script>
<?php $this->end() ?>