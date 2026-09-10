<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

$ratingProgression = implode (", ", $RatingProgression);
$surveysProgression = implode (", ", $surveysProgression);
?>
<script>
	$(function(){
	//overall rating
	$('#rating-progression-chart').highcharts({
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
			max: 10,
			title: {
				text: 'Rating'
			},
		},
		tooltip:{
			backgroundColor: '#000',
			borderColor: '#000',
			borderRadius: 3,
			formatter: function() {
				return 'Rating <b>'+ this.y +'</b>';
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
				color:'#65c3df'
			}
		},
		series: [{
			name: 'Rating',
			data: [<?php echo $ratingProgression ?>]
		}]
	});

	//number of surveys per month
	$('#survey-progression-chart').highcharts({
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
				text: 'Number of surveys'
			},
		},
		tooltip:{
			backgroundColor: '#000',
			borderColor: '#000',
			borderRadius: 3,
			formatter: function() {
				return 'Surveys <b>'+ this.y +'</b>';
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
				color:'#00363D'
			}
		},
		series: [{
			name: 'Number of surveys',
			data: [<?php echo $surveysProgression ?>]
		}]
	});

	// Checkouts vs surveys
	chart = new Highcharts.Chart({
		chart: {
			renderTo: 'checkout-survey-chart',
			type: 'pie'
		},
		credits: false,
		plotOptions: {
			pie: {
				shadow: false,
			}
		},
		tooltip:{
			enabled : false
		},
		series: [{
			name: '',
			borderWidth: 4,
			data: [{
				name: 'Surveys',
				color: '#65c3df',
				y: <?php echo $surveysVSCheckouts[0] ?>
			}, {
				name: '',
				color: '#efefef',
				y: <?php echo $surveysVSCheckouts[1] ?>
			}],
			size: '90%',
			innerSize: '90%',
			showInLegend:false,
			dataLabels: {
				enabled: false
			}
		}]
	});

// Reputation by age chart
chart = new Highcharts.Chart({
	chart: {
		renderTo: 'age-reputation-chart',
		type: 'pie',
	},
	credits: false,
	plotOptions: {
		pie: {
			shadow: false,
		}
	},
	tooltip:{
		enabled : false
	},
	series: [{
		name: '',
		borderWidth: 4,
		data: [{
			name: 'Gen Z',
			color: '#c7b299',
			y: <?php echo $reputationByAge[1]; ?>
		}, {
			name: ' Gen Y',
			color: '#65c3df',
			y: <?php echo $reputationByAge[3]; ?>
		},{
			name: 'Gen X',
			color: '#45b7af',
			y: <?php echo $reputationByAge[2]; ?>
		}, {
			name: 'teenagers',
			color: '#ff6b6b',
			y: <?php echo $reputationByAge[0]; ?>
		}],
		size: '90%',
		innerSize: '90%',
		showInLegend:false,
		dataLabels: {
			enabled: false
		}
	}]
});
});//end of document ready
</script>