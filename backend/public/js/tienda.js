$(window).load(function(){
	var $container = $('#shop-container');
	$container.isotope({
		layoutMode: 'masonry',
		itemSelector : '.shop-item',
		getSortData: {
			cost: '[data-cost] parseInt',
			startDate: '[data-start-date]',
			endDate: '[data-end-date]',
			category: '[data-category] parseInt',
			rating: '[data-rating] parseFloat'
		}
	});
	$('.sortBtn').click(function(e){
		e.preventDefault();
		var sortValue = $(this).attr('data-sort-by');
		if($(this).hasClass('asc')){
			$(this).removeClass('asc').addClass('desc');
		}else{
			$(this).removeClass('desc').addClass('asc');
		}
		if($(this).hasClass('asc')){
			$container.isotope({ sortBy: sortValue, sortAscending: true});
		}else{
			$container.isotope({ sortBy: sortValue, sortAscending: false });
		}
	});
})

$(document).ready(function(){
	$('.fichaOferta, .fichaHotel').hover(function(){
		$(this).children('.overlayer').fadeToggle('fast');
	});

	$('.btn-hollow').hover(function(){
		$(this).tooltip({
			container : 'body',
			placement: 'right'
		});
	})
	$('.asBtn').click(function(e){
		e.preventDefault();
		if($('.mobile-search-section').hasClass('moveAdvancedSearch')){
			$('.mobile-search-section').removeClass('moveAdvancedSearch');
		}
		$('.advancedSearch').toggleClass('moveAdvancedSearch');
		$('.hotel-services-submenu').fadeOut('fast');
	});

	$('.shop-search-link').click(function(e){
		e.preventDefault();
		if($('.advancedSearch').hasClass('moveAdvancedSearch')){
			$('.advancedSearch').removeClass('moveAdvancedSearch');
		}
		$('html, body').animate({ scrollTop: 0 }, 'fast');
		$('.mobile-search-section').toggleClass('moveAdvancedSearch');
		$('.hotel-services-submenu').fadeOut('fast');
	});

	$('.closeAdvancedSearch').click(function(e){
		e.preventDefault();
		$('.advancedSearch,.mobile-search-section').removeClass('moveAdvancedSearch');
	});

	$('.closeSubCategories').click(function(e){
		e.preventDefault();
		$('.hotel-services-submenu').fadeOut('fast');
	})

	$('.sorting-search-link').click(function(e){
		e.preventDefault();
		if(!$('.mobile-shop-filters').hasClass('movefilters')){
			$('html, body').animate({ scrollTop: 0 }, 'fast');
		}
		$('.mobile-shop-filters').toggleClass('movefilters');
	});


	$( "#hotel" ).autocomplete({
		source: "/lib/webservices/listadohoteles.php",
		minLength: 3,
		select: function(event, ui) {
			$('#hotelId').val(ui.item.value);
			$(this).val(ui.item.label);
			return false;
		}
	});

	$("#hotel").keyup(function(){
		var hotel = $("#hotel").val();
		$('#hotelId').val(hotel);
	});

	$('.rubiesRange').slider({
		range: true,
		min: 0,
		max: 20000,
		values:[1,20000],
		step:10,
		create:function(event, ui){
			$("#rubiesRange").val("Entre 1 y 20000 rubies");
		},
		slide: function (event, ui) {
			$("#rubiesRange").val("Entre " + ui.values[ 0 ] + " y " + ui.values[ 1 ] + " rubies");
			$('#minRubiesRange').val(ui.values[ 0 ]);
			$('#maxRubiesRange').val(ui.values[ 1 ]);
		}
	});

	$('.hotelRange').slider({
		range: true,
		min: 1,
		max: 7,
		values:[1,7],
		step:1,
		create:function(event, ui){
			$("#hotelRange").val("Entre 1 y 7 estrellas");
		},
		slide: function (event, ui) {
			$("#hotelRange").val("Entre " + ui.values[ 0 ] + " y " + ui.values[ 1 ] + " estrellas");
			$('#minHotelRange').val(ui.values[ 0 ]);
			$('#maxHotelRange').val(ui.values[ 1 ]);
		}
	});

	$('.hotelRating').slider({
		range: true,
		min: 1,
		max: 10,
		values:[1,10],
		step:1,
		create:function(event, ui){
			$("#hotelRating").val("Entre 1 y 10 puntos");
		},
		slide: function (event, ui) {
			$("#hotelRating").val("Entre " + ui.values[ 0 ] + " y " + ui.values[ 1 ] + " puntos");
			$('#minHotelRating').val(ui.values[ 0 ]);
			$('#maxHotelRating').val(ui.values[ 1 ]);
		}
	});

	$('.fechaInicio, .fechaFin').datepicker({
		dateFormat: "dd-mm-yy"
	});


});