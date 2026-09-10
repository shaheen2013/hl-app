<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

	//rating progression numbers
	$ratingProgression = implode (", ", $RatingProgression);
	//new customer progression
	$customerProgression = implode (", ", $newGuestsProgression);
	$guestsCountries = implode (",", $guestsCountries);
 ?>
<script>
// rating progession chart
$(document).ready(function(){
	$('#rating-progression-chart').highcharts({
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
//New customer progression
$('#new-customers-chart').highcharts({
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
			text: 'Guests number'
		}
	},
	tooltip:{
		backgroundColor: '#000',
		borderColor: '#000',
		borderRadius: 3,
		formatter: function() {
			return 'Guests <b>'+ this.y +'</b>';
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
		name: 'Guests',
		data: [<?php echo $customerProgression ?>]
	}]
});

// Overall Reputation Chart
chart = new Highcharts.Chart({
	chart: {
		renderTo: 'overall-reputation-chart',
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
			name: 'Ranking',
			color: '#65c3df',
			y: <?php echo $overallReputation[0] ?>
		}, {
			name: '',
			color: '#efefef',
			y: <?php echo $overallReputationInv ?>
		}],
		size: '90%',
		innerSize: '90%',
		showInLegend:false,
		dataLabels: {
			enabled: false
		}
	}]
});

// Reputation by gender chart
chart = new Highcharts.Chart({
	chart: {
		renderTo: 'gender-reputation-chart',
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
			name: 'Ranking',
			color: '#65c3df',
			y: <?php echo $reputationByGender['h'] ?>
		}, {
			name: '',
			color: '#006E7C',
			y: <?php echo $reputationByGender['m'] ?>
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

//googlemaps

function initialize() {
	var mapOptions = {
		zoom: 2,
		center: new google.maps.LatLng('39.5677527','2.6560095'),
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};
var map = new google.maps.Map(document.getElementById('googleMap'),mapOptions);

var image = new google.maps.MarkerImage('<?php echo DIR_IMG ?>mapMarker.png',
	new google.maps.Size(30, 30)
	);
var addresses = [<?php echo $guestsCountries ?>];
for (var x = 0; x < addresses.length; x++) {
	$.getJSON('https://maps.googleapis.com/maps/api/geocode/json?address='+addresses[x]+'&sensor=false', null, function (data) {
		var p = data.results[0].geometry.location;
		var markerpos = new google.maps.LatLng(p.lat, p.lng);
		new google.maps.Marker({
			position: markerpos,
			map: map,
			title: addresses[x],
			icon: image,
			optimized: false
		});

	});
}
}
google.maps.event.addDomListener(window, 'load', initialize);
});// end document ready
</script>