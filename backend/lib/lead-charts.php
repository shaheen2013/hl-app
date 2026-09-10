<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

	$leadZero = array_filter($leadsByMonth['landing']);
	$leadsByMonthLanding= implode (", ", $leadsByMonth['landing']);
	$leadsByMonthHotel= implode (", ", $leadsByMonth['hotel']);
	$leadsByMonthOffer= implode (", ", $leadsByMonth['offer']);
	$totZero = array_filter($leadsByMonth['total']);
	$leadsByMonthTotal= implode (", ", $leadsByMonth['total']);
 ?>
<script>
	$(function(){
//leads
$('#leads-progression-chart').highcharts({
	chart: {
		type: 'areaspline'
	},
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
			text: 'Visits'
		},
	},
	tooltip:{
		backgroundColor: '#000',
		borderColor: '#000',
		borderRadius: 3,
		formatter: function() {
			return 'Visits <b>'+ this.y +'</b>';
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
			fillOpacity: 0.3
		}
	},
	series: [{
		name: 'Total visits',
		data: [<?php echo $leadsByMonthTotal ?>],
		color:'#65c3df'
	},{
		name: 'Landing Visits',
		data: [<?php echo $leadsByMonthLanding ?>],
		color:'#00A7BC'
	},{
		name: 'Hotel page visits',
		data: [<?php echo $leadsByMonthHotel ?>],
		color:'#006E7C'
	},{
		name: 'Offer page visits',
		data: [<?php echo $leadsByMonthOffer ?>],
		color:'#00363D'
	}]
});
//conversion funnel
$('#funnel-chart').highcharts({
	chart: {
		type: 'funnel',
		marginRight: 200
	},
	tooltip:{
		backgroundColor: '#000',
		borderColor: '#000',
		borderRadius: 3,
		formatter: function() {
			return this.point.name + ': <b>'+ this.point.y +'</b>';
		},
		style: {
			color: '#FFFFFF',
		}
	},
	title: {
		text: 'User to guest conversion funnel',
		x: -50
	},
	plotOptions: {
		series: {
			dataLabels: {
				enabled: true,
				format: '<b>{point.name}</b> ({point.y:,.0f})',
				color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black',
				softConnector: false
			},
			neckWidth: '15%',
			neckHeight: '30%'

//-- Other available options
// height: pixels or percent
// width: pixels or percent
}
},
legend: {
	enabled: false
},
	series: [{
		name:'Conversion',
		data: [{
			name:'Hotel landing unique visitors',
			y:<?php echo $arrayFunnel['visitors'] ?>,
			color:'#65c3df'
		},{
			name:'New referrals in your database',
			y:<?php echo $arrayFunnel['referralDB'] ?>,
			color:'#00A7BC'
		},{
			name:'Checked in your hotel',
			y:<?php echo $arrayFunnel['referralChkin'] ?>,
			color:'#006E7C'
		}]
	}]
});
});
</script>