$(function(){

	new Lazyload();
	//button scroll down
	$('.btn-down').click(function(){
	   $('html,body').animate({
	        scrollTop: $('.loyalty-section').offset().top
	    }, 600);
	});

	//button scroll up
	$('.btn-up').click(function(){
	   $('html,body').animate({
	        scrollTop: $('.top-section').offset().top
	    }, 600);
	});

	$('.hamburguer-icon').click(function(e){
		e.preventDefault();
		$('.mobile-submenu').toggleClass('mobile-submenu-anim');
		$('.close-mobile-menu').toggleClass('close-mobile-menu-anim');
	});

	$('.close-mobile-menu').click(function(e){
		e.preventDefault();
		$('.mobile-submenu').toggleClass('mobile-submenu-anim');
		$('.close-mobile-menu').toggleClass('close-mobile-menu-anim');
	});

	//features
	$('.features-btn').click(function(e){
		e.preventDefault();
	   $('html,body').animate({
	        scrollTop: $('.loyalty-section').offset().top
	    }, 600);
	});

	//benefits
	$('.benefits-btn').click(function(e){
		e.preventDefault();
	   $('html,body').animate({
	        scrollTop: $('.blue-section').offset().top
	    }, 600);
	});

	$('.tools-list a').click(function(e){
		e.preventDefault();
		var section = '.' + $(this).data('section');
		$('html,body').animate({
			scrollTop: $(section).offset().top -70
		}, 1200);
	});

});