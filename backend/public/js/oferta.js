$(document).ready(function(){
	$('.hotelDetailsToggle').click(function(e){
		e.preventDefault();
		var icon = $(this).children('.fa')
		if(icon.hasClass('fa-chevron-down')){
			icon.removeClass('fa-chevron-down').addClass('fa-chevron-up');
			$('.hotelLoc').slideToggle('fast');
		}else{
			icon.removeClass('fa-chevron-up').addClass('fa-chevron-down');
			$('.hotelLoc').slideToggle('fast');
		}
	});
	$('.offerCostModule').popover({
		trigger: 'hover',
		placement: 'left'
	})

});