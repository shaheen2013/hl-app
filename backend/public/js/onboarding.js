$(document).ready(function(){
	//Actualiza la barra de progreso
	var onboardingProgress = $('.data-progress').data('progress');
	$('.progress-bar').css('width', onboardingProgress + '%').html(onboardingProgress + '%');


	$('.first-video-btn').click(function(){
		$('.ob-modal').css('opacity', 0);
		$('.first-video-video').fadeIn();
		$('.btn-onboarding-hidden-1').fadeIn();
		$(this).fadeOut();
		$('.ob-first_video h2').html('Ready to go');
		$('.ob-first_video p').html('You are ready to go to the next step');
		//actualiza la base de datos
		var step = $(this).data('step');
		var id = $(this).data('id');
		onboardingUpdateStep(step, id);
	});


	$('.second-video-btn').click(function(){
		$('.ob-modal').css('opacity', 0);
		$('.second-video-video').fadeIn();
		$('.btn-onboarding-hidden-3').fadeIn();
		$('.ob-second_video h2').html('Ready to go');
		$('.ob-second_video p').html('You are ready to go to the next step');
		$(this).fadeOut();
		//actualiza la base de datos
		var step = $(this).data('step');
		var id = $(this).data('id');
		onboardingUpdateStep(step, id);
	});


	$('.third-video-btn').click(function(){
		$('.ob-modal').css('opacity', 0);
		$('.third-video-video').fadeIn();
		$('.btn-onboarding-hidden-4').fadeIn();
		$('.ob-invite_send h2').html('Ready to go');
		$('.ob-invite_send p').html('You are ready to go to the next step');
		$(this).fadeOut();
		//actualiza la base de datos
		var step = $(this).data('step');
		var id = $(this).data('id');
		onboardingUpdateStep(step, id);
	});

	$('.hotel-profile-btn').click(function(){
		$('.ob-modal').css('opacity', 0);
		$('.hotel-profile-video').fadeIn();
	});

	$('.hotel-booking-btn').click(function(){
		$('.ob-modal').css('opacity', 0);
		$('.hotel-booking-video').fadeIn();
	});

	$('.hotel-landing-btn').click(function(){
		$('.ob-modal').css('opacity', 0);
		$('.hotel-landing-video').fadeIn();
	});	

	$('.basic-info-btn').click(function(){
		$('.ob-modal').css('opacity', 0);
		$('.basic-info-video').fadeIn();
	});	

	$('.launch-btn').click(function(){
		//actualiza la base de datos
		var step = $(this).data('step');
		var id = $(this).data('id');
		onboardingUpdateStep(step, id);
		$(this).fadeOut();
		$('.btn-go-dashboard').fadeIn().delay(1000);
		$('.ob-invite_send h2').html('');
		$('.ob-invite_send p').html('');
	});

	$('.close-first-video').click(function(){
		$('.first-video-video').fadeOut();
		$('.ob-modal').css('opacity', 1);
	});

	$('.close-second-video').click(function(){
		$('.second-video-video').fadeOut();
		$('.ob-modal').css('opacity', 1);
	});

	$('.close-third-video').click(function(){
		$('.third-video-video').fadeOut();
		$('.ob-modal').css('opacity', 1);
	});

	$('.close-hotel-profile-video').click(function(){
		$('.hotel-profile-video').fadeOut();
		$('.ob-modal').css('opacity', 1);
	});

	$('.close-hotel-booking-video').click(function(){
		$('.hotel-booking-video').fadeOut();
		$('.ob-modal').css('opacity', 1);
	});

	$('.close-hotel-landing-video').click(function(){
		$('.hotel-landing-video').fadeOut();
		$('.ob-modal').css('opacity', 1);
	});

	$('.close-basic-info-video').click(function(){
		$('.basic-info-video').fadeOut();
		$('.ob-modal').css('opacity', 1);
	});

	//Cierra el onboarding de basic_info para que se pueda rellenar el formulario
	$('.fill-basic-info-btn').click(function(){
		$('.ob-overlayer').fadeOut();
	});

	$('.fill-detail-info-btn').click(function(){
		$('.ob-overlayer').fadeOut();
		tour.start();
	});

	//Función para actualizar los pasos del onboarding
	function onboardingUpdateStep(step, id){
		$.ajax({
			url : "/lib/webservices/onboardingModel.php",
			data : "step=" + step + "&id=" + id,
			type : "POST"
		})
	}
})
