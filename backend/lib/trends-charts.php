<script>
var colors = [];
  var r = 25;
  var g = 49;
  var b = 56;
  var str="";
  for(var i=0;i<30;i++)
  {
    r+=33;
    g+=33;
    b+=33;
    var color = "#" + ((1 << 24) + (r << 16) + (g << 8) + b).toString(16).slice(1);
    colors.push(color);
  }
// Hotel category trends
chart = new Highcharts.Chart({
	chart: {
		renderTo: 'hotel-category-trends',
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
				return '<b>' + this.point.name + ':</b> ' + this.y + '%';
			},
			crosshairs: [true, true],
			style: {
				color: '#FFFFFF',
			}
		},
	series: [{
		name: '',
		borderWidth: 4,
		data: [
			<?php 
				$i = 0;
				foreach ($arrayEstrellas as $key => $value) {?>
				{	
					name: '<?php echo $key ?> stars',
					color: colors[<?php echo $i ?>],
					y: <?php echo $value ?>
				},
				<?php $i++ ?>
			<?php } ?>
			],
		size: '70%',
		innerSize: '70%',
		showInLegend:false,
		dataLabels: {
			formatter: function () {
				return this.y > 1 ? '<b>' + this.point.name + ':</b> ' + this.y + '%'  : null;
			}
		}
	}]
});

// Hotel price preferences
chart = new Highcharts.Chart({
	chart: {
		renderTo: 'average-price-trends',
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
				return '<b>' + this.point.name + ':</b> ' + this.y + '%';
			},
			crosshairs: [true, true],
			style: {
				color: '#FFFFFF',
			}
		},
	series: [{
		name: '',
		borderWidth: 4,
		data: [
			<?php 
				$i = 0;
				foreach ($arrayPrecio as $key => $value) {?>
				{	
					name: '<?php echo $key ?> $',
					color: colors[<?php echo $i ?>],
					y: <?php echo $value ?>
				},
				<?php $i++ ?>
			<?php } ?>
			],
		size: '70%',
		innerSize: '70%',
		showInLegend:false,
		dataLabels: {
			formatter: function () {
				return this.y > 1 ? '<b>' + this.point.name + ':</b> ' + this.y + '%'  : null;
			}
		}
	}]
});

// Preferred hotels by user
chart = new Highcharts.Chart({
	chart: {
		renderTo: 'preferred-hotels',
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
				return '<b>' + this.point.name + ':</b> ' + this.y + '%';
			},
			crosshairs: [true, true],
			style: {
				color: '#FFFFFF',
			}
		},
	series: [{
		name: '',
		borderWidth: 4,
		data: [
			<?php 
				$i = 0;
				foreach ($arrayTipoHotel as $key => $value) {?>
				{	
					name: '<?php echo $key ?>',
					color: colors[<?php echo $i ?>],
					y: <?php echo $value ?>
				},
				<?php $i++ ?>
			<?php } ?>
			],
		size: '70%',
		innerSize: '70%',
		showInLegend:false,
		dataLabels: {
			formatter: function () {
				return this.y > 1 ? '<b>' + this.point.name + ':</b> ' + this.y + '%'  : null;
			}
		}
	}]
});

// Preferred decoration by user
chart = new Highcharts.Chart({
	chart: {
		renderTo: 'preferred-decoration',
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
				return '<b>' + this.point.name + ':</b> ' + this.y + '%';
			},
			crosshairs: [true, true],
			style: {
				color: '#FFFFFF',
			}
		},
	series: [{
		name: '',
		borderWidth: 4,
		data: [
			<?php 
				$i = 0;
				foreach ($arrayDecoracionHotel as $key => $value) {?>
				{	
					name: '<?php echo $key ?>',
					color: colors[<?php echo $i ?>],
					y: <?php echo $value ?>
				},
				<?php $i++ ?>
			<?php } ?>
			],
		size: '70%',
		innerSize: '70%',
		showInLegend:false,
		dataLabels: {
			formatter: function () {
				return this.y > 1 ? '<b>' + this.point.name + ':</b> ' + this.y + '%'  : null;
			}
		}
	}]
});

// Preferred rooms by user
chart = new Highcharts.Chart({
	chart: {
		renderTo: 'preferred-rooms',
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
				return '<b>' + this.point.name + ':</b> ' + this.y + '%';
			},
			crosshairs: [true, true],
			style: {
				color: '#FFFFFF',
			}
		},
	series: [{
		name: '',
		borderWidth: 4,
		data: [
			<?php 
				$i = 0;
				foreach ($arrayTipoHab as $key => $value) {?>
				{	
					name: '<?php echo $key ?>',
					color: colors[<?php echo $i ?>],
					y: <?php echo $value ?>
				},
				<?php $i++ ?>
			<?php } ?>
			],
		size: '70%',
		innerSize: '70%',
		showInLegend:false,
		dataLabels: {
			formatter: function () {
				return this.y > 1 ? '<b>' + this.point.name + ':</b> ' + this.y + '%'  : null;
			}
		}
	}]
});

// Preferred rooms features
chart = new Highcharts.Chart({
	chart: {
		renderTo: 'preferred-rooms-features',
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
				return '<b>' + this.point.name + ':</b> ' + this.y + '%';
			},
			crosshairs: [true, true],
			style: {
				color: '#FFFFFF',
			}
		},
	series: [{
		name: '',
		borderWidth: 4,
		data: [
			<?php 
				$i = 0;
				foreach ($arrayRoomFeatures as $key => $value) {?>
				{	
					name: '<?php echo $key ?>',
					color: colors[<?php echo $i ?>],
					y: <?php echo $value ?>
				},
				<?php $i++ ?>
			<?php } ?>
			],
		size: '70%',
		innerSize: '70%',
		showInLegend:false,
		dataLabels: {
			formatter: function () {
				return this.y > 1 ? '<b>' + this.point.name + ':</b> ' + this.y + '%'  : null;
			}
		}
	}]
});

// Preferred hotel services
chart = new Highcharts.Chart({
	chart: {
		renderTo: 'preferred-hotel-services',
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
				return '<b>' + this.point.name + ':</b> ' + this.y + '%';
			},
			crosshairs: [true, true],
			style: {
				color: '#FFFFFF',
			}
		},
	series: [{
		name: '',
		borderWidth: 4,
		data: [
			<?php 
				$i = 0;
				foreach ($arrayHotelServices as $key => $value) {?>
				{	
					name: '<?php echo $key ?>',
					color: colors[<?php echo $i ?>],
					y: <?php echo $value ?>
				},
				<?php $i++ ?>
			<?php } ?>
			],
		size: '70%',
		innerSize: '70%',
		showInLegend:false,
		dataLabels: {
			formatter: function () {
				return this.y > 1 ? '<b>' + this.point.name + ':</b> ' + this.y + '%'  : null;
			}
		}
	}]
});

// Preferred offers
chart = new Highcharts.Chart({
	chart: {
		renderTo: 'preferred-offers',
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
				return '<b>' + this.point.name + ':</b> ' + this.y + '%';
			},
			crosshairs: [true, true],
			style: {
				color: '#FFFFFF',
			}
		},
	series: [{
		name: '',
		borderWidth: 4,
		data: [
			<?php 
				$i = 0;
				foreach ($arrayRecibirOfertas as $key => $value) {?>
				{	
					name: '<?php echo $key ?>',
					color: colors[<?php echo $i ?>],
					y: <?php echo $value ?>
				},
				<?php $i++ ?>
			<?php } ?>
			],
		size: '70%',
		innerSize: '70%',
		showInLegend:false,
		dataLabels: {
			formatter: function () {
				return this.y > 1 ? '<b>' + this.point.name + ':</b> ' + this.y + '%'  : null;
			}
		}
	}]
});
</script>