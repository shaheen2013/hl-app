<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}?>

<script>
    // rating progession chart
    $(document).ready(function(){

        var container = document.querySelector('#homeContainer');
        var pckry = new Packery( container, {
            // options
            itemSelector: '.item',
            "columnWidth" : ".column-width",
            "rowHeight" : 305
        });

        // Pre stay shares
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'landing-opens',
                type: 'pie',
            },
            credits: false,
            plotOptions: {
                pie: {
                    shadow: false,
                }
            },
            tooltip:{
                enabled : true
            },
            series: [{
                name: 'ABS NUM',
                borderWidth: 4,
                data: [{
                    name: 'Facebook',
                    color: '#3b5998',
                    y: <?php echo $facebookConversions ?>
                }, {
                    name: 'Not Shared',
                    color: '#efefef',
                    y: <?php echo $noConversions ?>
                }, {
                    name: 'email',
                    color: '#ea3328',
                    y: <?php echo $emailConversions ?>
                }],
                size: '90%',
                innerSize: '90%',
                showInLegend:false,
                dataLabels: {
                    enabled: false
                }
            }]
        });

    });// end document ready
</script>