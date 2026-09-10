<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

	$campaignProgressionActual = implode (", ", $totalCampaignsByMonth[0]);
	$campaignProgressionLast = implode (", ", $totalCampaignsByMonth[1]);
	$retentionByMonth = implode (", ", $retentionVSAdquisitionByMonth['ret']);
	$adquisitionByMonth = implode (", ", $retentionVSAdquisitionByMonth['adq']);
 ?>
<script>
	$(document).ready(function(){
//Campaigns by month
$('#campaign-progression-chart').highcharts({
	chart: {
		type: 'areaspline'
	},
	credits: false,
	xAxis: {
		categories: [
		'Jan',
		'Feb',
		'Mar',
		'Apr',
		'May',
		'Jun',
		'Jul',
		'Ago',
		'Sep',
		'Oct',
		'Nov',
		'Dec'
		]
	},
	yAxis: {
		title: {
			text: 'Campaigns'
		},
	},
	tooltip:{
		backgroundColor: '#000',
		borderColor: '#000',
		borderRadius: 3,
		formatter: function() {
			return 'Campaings <b>'+ this.y +'</b>';
		},
		crosshairs: [true, true],
		style: {
			color: '#FFFFFF',
		}
	},
	credits: {
		enabled: false
	},
	plotOptions: {
		areaspline: {
			fillOpacity: 0.3,
		},
		areaspline: {
			fillOpacity: 0.3,
		}
	},
	series: [{
		name: 'Campaigns',
		data: [<?php echo $campaignProgressionActual ?>],
		color:'#65c3df',
		name:<?php echo $anoActualAnterior[0] ?>
	},
	{
		name: 'Last Year',
		data: [<?php echo $campaignProgressionLast ?>],
		color:'#00363D',
		name:<?php echo $anoActualAnterior[1] ?>
	}]
});

//Campaigns Ret Vs Adq by month
$('#campaign-retVsAdq-chart').highcharts({
	chart: {
		type: 'areaspline'
	},
	credits: false,
	xAxis: {
		categories: [
		'Jan',
		'Feb',
		'Mar',
		'Apr',
		'May',
		'Jun',
		'Jul',
		'Ago',
		'Sep',
		'Oct',
		'Nov',
		'Dec'
		]
	},
	yAxis: {
		title: {
			text: 'Rating'
		},
	},
	tooltip:{
		backgroundColor: '#000',
		borderColor: '#000',
		borderRadius: 3,
		formatter: function() {
			return 'Campaings <b>'+ this.y +'</b>';
		},
		crosshairs: [true, true],
		style: {
			color: '#FFFFFF',
		}
	},
	credits: {
		enabled: false
	},
	plotOptions: {
		areaspline: {
			fillOpacity: 0.3,
		},
		areaspline: {
			fillOpacity: 0.3,
		}
	},
	series: [{
		name: 'Retention',
		data: [<?php echo $retentionByMonth ?>],
		color:'#ff6b6b'

	},
	{
		name: 'Adquisition',
		data: [<?php echo $adquisitionByMonth ?>],
		color:'#65c3df'
	}]
});

// Retention vs Adquisition gauge
chart = new Highcharts.Chart({
	chart: {
		renderTo: 'repVsAdq-chart',
		type: 'pie',
	},
	credits: false,
	plotOptions: {
		pie: {
			shadow: false,
		}
	},
	tooltip:{
		backgroundColor: '#000',
		borderColor: '#000',
		borderRadius: 3,
		formatter: function() {
			return  this.point.name +' <b>'+ this.y +' %</b>';
		},
		style : {
			color: '#FFFFFF'
		}
	},
	series: [{
		name: '',
		borderWidth: 4,
		data: [{
			name: 'Rentention',
			color: '#ff6b6b',
			y: <?php echo $retVSAdq["ret"]?>
		}, {
			name: 'Adquisition',
			color: '#65c3df',
			y: <?php echo $retVSAdq["adq"]?>
		}],
		size: '90%',
		innerSize: '90%',
		showInLegend:false,
		dataLabels: {
			enabled: false
		}
	}]
});
//Campaigns by category
$('#campaigns-category-chart').highcharts({
	chart: {
		type: 'column'
	},
	credits: false,
	title: {
		text: ''
	},
	subtitle: {
		text: ''
	},
	xAxis: {
		type: 'category',
		labels: {
			rotation: -45,
			style: {
				fontSize: '13px',
				fontFamily: 'Verdana, sans-serif'
			}
		}
	},
	yAxis: {
		min: 0,
		title: {
			text: 'Check In categories'
		}
	},
	legend: {
		enabled: false
	},
	tooltip: {
		backgroundColor: '#000',
		borderColor: '#000',
		borderRadius: 3,
		style : {
			color: '#FFFFFF'
		},
		pointFormat: 'Redeemed: <b>{point.y} times</b>',
	},
	series: [{
		name: 'Campaigns',
		data: [
		<?php foreach ($campaignsByCategory as $key => $value) {
			echo '["'.$key.'", '.$value.'],';
		} ?>
		],
		color:'#65c3df',
		dataLabels: {
			enabled: false,
			rotation: -90,
			color: '#000000',
			align: 'right',
			x: 4,
			y: 10,
			style: {
				fontSize: '13px',
				fontFamily: 'Verdana, sans-serif'
			}
		}
	}]
});
//Campaigns by generation
        $('#campaigns-generation-chart').highcharts({
            chart: {
                type: 'column'
            },
            credits: false,
            title: {
                text: 'Stacked column chart'
            },
            xAxis: {
                categories: [
                <?php foreach ($mostRedeemedByGeneration as $key => $value) {
                	echo '"'.$key.'",';
                } ?>
                ],
                		labels: {
			rotation: -45,
			style: {
				fontSize: '13px',
				fontFamily: 'Verdana, sans-serif'
			}
		}
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Total Redeemed campaigns by category'
                },
                stackLabels: {
                    enabled: true,
                    style: {
                        fontWeight: 'bold',
                        color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                    }
                }
            },
            legend: {
                align: 'center',
                verticalAlign: 'bottom',
                floating: false,
                backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
                shadow: false
            },
            tooltip: {
            			backgroundColor: '#000',
		borderColor: '#000',
		borderRadius: 3,
		style : {
			color: '#FFFFFF'
		},
                formatter: function() {
                    return '<b>'+ this.x +'</b><br/>'+
                        this.series.name +': '+ this.y;
                }
            },
            plotOptions: {
                column: {
                    stacking: 'normal',
                    dataLabels: {
                        enabled: true,
                        color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
                        style: {
                            textShadow: '0 0 3px black, 0 0 3px black'
                        }
                    }
                }
            },
            series: [{
                name: 'Gen Z (18-23)',
                data: [
                	<?php foreach ($mostRedeemedByGeneration as $key => $value) {
                		echo $value['18-23'] . ',';
                	} ?>
                ],
                color:'#65c3df'
            }, {
                name: 'Gen Y (24-36)',
                data: [
                	<?php foreach ($mostRedeemedByGeneration as $key => $value) {
                		echo $value['24-36'] . ',';
                	} ?>
                ],
                color:'#00A7BC'
            }, {
                name: 'Gen X (37-48)',
                data: [
                	<?php foreach ($mostRedeemedByGeneration as $key => $value) {
                		echo $value['37-48'] . ',';
                	} ?>
                ],
                color:'#006E7C'
            }, {
                name: 'Baby boomers (49-67)',
                data: [
                	<?php foreach ($mostRedeemedByGeneration as $key => $value) {
                		echo $value['49-67'] . ',';
                	} ?>
                ],
                color:'#00363D'
            }, {
                name: 'Seniors (+68)',
                data: [
                	<?php foreach ($mostRedeemedByGeneration as $key => $value) {
                		echo $value['+68'] . ',';
                	} ?>
                ],
                color:'#000000'
            }]
        });
}); //document ready
</script>