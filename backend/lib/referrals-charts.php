<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}
	
	$refZero = array_filter($checkinsReferrals);
	$checkinsReferrals = implode (", ", $checkinsReferrals);
	$lanZero = array_filter($landingConversionMonth);
	$landingConversionMonth = implode (", ", $landingConversionMonth);
 ?>
<script>
	$(function(){
		//overall rating
		$('#checkins-progression-chart').highcharts({
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
					text: 'Check-ins'
				},
			},
			tooltip:{
				backgroundColor: '#000',
				borderColor: '#000',
				borderRadius: 3,
				formatter: function() {
					return 'Check-ins <b>'+ this.y +'</b>';
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
				name: 'Referral check-ins',
				data: [<?php echo $checkinsReferrals ?>]
			}]
		});
		//Landing conversion
		$('#landing-conversion-progression-chart').highcharts({
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
					text: 'Num. conversions'
				},
			},
			tooltip:{
				backgroundColor: '#000',
				borderColor: '#000',
				borderRadius: 3,
				formatter: function() {
					return 'Conversions <b>'+ this.y +'</b>';
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
				name: 'Num. of conversions',
				data: [<?php echo $landingConversionMonth ?>]
			}]
		});
// Reputation by age chart
chart = new Highcharts.Chart({
	chart: {
		renderTo: 'sm-referrals-chart',
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
			name: 'Twitter',
			color: '#65c3df',
			y: <?php echo $referralsBySocialMedia['tw'] ?>
		}, {
			name: 'Facebook',
			color: '#00A7BC',
			y: <?php echo $referralsBySocialMedia['fb'] ?>
		},{
			name: 'Instagram',
			color: '#65c3df',
			y: <?php echo $referralsBySocialMedia['in'] ?>
		},{
			name: 'Linkedin',
			color: '#65c3df',
			y: <?php echo $referralsBySocialMedia['li'] ?>
		}],
		size: '80%',
		innerSize: '70%',
		showInLegend:false,
		dataLabels: {
			enabled: false
		}
	}]
});
	}); //document ready
</script>