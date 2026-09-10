<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

?>
<script>
    // Overall Reputation Chart
    chart = new Highcharts.Chart({
        chart: {
            renderTo: 'referrals-chart',
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
            name: 'referrals',
            borderWidth: 4,
            data: [{
                name: 'identificados',
                color: '#3B5998',
                y: <?php echo $kpisReferrals['referrals_no_anonimos'] ?>
            }, {
                name: 'no identificados',
                color: '#efefef',
                y: <?php echo $kpisReferrals['referrals_anonimos'] ?>
            }],
            size: '90%',
            innerSize: '90%',
            showInLegend:false,
            dataLabels: {
                enabled: false
            }
        }]
    });

<?php if($satisfaction_permissions == 1) { ?>
    //Overall satisfaction chart
    chart = new Highcharts.Chart({
        chart: {
            renderTo: 'reviews-chart',
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
            name: '%',
            borderWidth: 4,
            data: [{
                name: 'Done',
                color: '#45b7af',
                y: <?php echo $total_reviews_done ?>
            }, {
                name: 'Not done',
                color: '#efefef',
                y: <?php echo $total_reviews_not_done ?>
            }],
            size: '90%',
            innerSize: '90%',
            showInLegend:false,
            dataLabels: {
                enabled: false
            }
        }]
    });
 <?php } ?>

    // Overall Reputation Chart
    chart = new Highcharts.Chart({
        chart: {
            renderTo: 'users-chart',
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
            name: 'referrals',
            borderWidth: 4,
            data: [{
                name: 'facebook',
                color: '#3B5998',
                y: <?php echo $kpisReferrals['usuarios_fb'] ?>
            }, {
                name: 'email',
                color: '#efefef',
                y: <?php echo $kpisReferrals['usuarios_email'] ?>
            }],
            size: '90%',
            innerSize: '90%',
            showInLegend:false,
            dataLabels: {
                enabled: false
            }
        }]
    });
</script>
