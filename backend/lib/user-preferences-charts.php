<script>
$(function () {
    $('#categories-chart').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: ''
        },
        xAxis: {
            type: 'category',
            labels: {
                rotation: -45,
                style: {
                    fontSize: '14px',
                    fontFamily: 'Verdana, sans-serif'
                }
            }
        },
        yAxis: {
        	allowDecimals: false,
            min: 0,
            title: {
                text: 'Redeemed categories'
            }
        },
 			credits: {
				enabled: false
			},
        legend: {
            enabled: false
        },
        series: [{
            name: 'Redeemed',
            data: [
		<?php foreach ($ofertasCompradas as $oferta) {
			foreach ($oferta as $key => $value) {
				echo '["'.$key.'", '.$value.'],';
			}

		} ?>
            ],
            dataLabels: {
                enabled: true,
                rotation: -90,
                color: '#FFFFFF',
                align: 'right',
                x: 4,
                y: 10,
                style: {
                    fontSize: '14px',
                    fontFamily: 'Verdana, sans-serif',
                    textShadow: '0 0 3px black'
                }
            }
        }]
    });

$('#wishlists-chart').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: ''
        },
        xAxis: {
            type: 'category',
            labels: {
                rotation: -45,
                style: {
                    fontSize: '14px',
                    fontFamily: 'Verdana, sans-serif'
                }
            }
        },
        yAxis: {
        	allowDecimals: false,
            min: 0,
            title: {
                text: 'Redeemed categories'
            }
        },
 			credits: {
				enabled: false
			},
        legend: {
            enabled: false
        },
        series: [{
            name: 'Redeemed',
            data: [
		<?php foreach ($ofertasWishlisted as $oferta) {
			foreach ($oferta as $key => $value) {
				echo '["'.$key.'", '.$value.'],';
			}

		} ?>
            ],
            dataLabels: {
                enabled: true,
                rotation: -90,
                color: '#FFFFFF',
                align: 'right',
                x: 4,
                y: 10,
                style: {
                    fontSize: '14px',
                    fontFamily: 'Verdana, sans-serif',
                    textShadow: '0 0 3px black'
                }
            }
        }]
    });

});
</script>