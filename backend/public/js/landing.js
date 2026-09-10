var canvas = document.getElementById('background-canvas');
var lines = document.getElementById('line-canvas');
var line2 = document.getElementById('line-canvas');
var line3 = document.getElementById('line-canvas');

$(function(){
	new Lazyload();

	$('.big-data-img').addClass('big-data-img-load');

	$('.first-input-mail').focus();

//show hidden CTA
$(document).scroll(function () {
	var y = $(this).scrollTop();
	if (y > 800) {
		$('.btn-fixed-holder').removeClass('btn-fixed-holder-hidden');
	} else {
		$('.btn-fixed-holder').addClass('btn-fixed-holder-hidden');
	}
});



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

//features
$('.features-btn').click(function(e){
	e.preventDefault();
	$('html,body').animate({
		scrollTop: $('.loyalty-section').offset().top
	}, 600).delay( 800 ).$('.first-input-mail').focus();;
});

//features
$('.btn-blue').click(function(e){
	e.preventDefault();
	$('html,body').animate({
		scrollTop: $('.top-section').offset().top
	}, 600);
});

$('.tools-list a').click(function(e){
	e.preventDefault();
	var section = '.' + $(this).data('section');
	$('html,body').animate({
		scrollTop: $(section).offset().top -70
	}, 1200);
});

//benefits
$('.benefits-btn').click(function(e){
	e.preventDefault();
	$('html,body').animate({
		scrollTop: $('.blue-section').offset().top
	}, 600);
});
});
if (canvas.getContext){
	var ctx = canvas.getContext('2d');
	ctx.beginPath();
	ctx.moveTo(0,340);
	ctx.lineTo(155,285);
	ctx.lineTo(315,169);
	ctx.lineTo(496,211);
	ctx.lineTo(718,255);
	ctx.lineTo(936,166);
	ctx.lineTo(1190,220);
	ctx.lineTo(1354,269);
	ctx.lineTo(1572,173);
	ctx.lineTo(1734,283);
	ctx.lineTo(1920,340);
	ctx.lineTo(1920,400);
	ctx.lineTo(0,400);
	ctx.closePath();
	ctx.lineCap = "round";
	ctx.fillStyle="#FFF";
	ctx.fill();

	var line = lines.getContext('2d');
	line.beginPath();
	line.moveTo(171,286);
	line.lineTo(358,310); 
	line.moveTo(328,171);
	line.lineTo(358,310);
	line.moveTo(510,210);
	line.lineTo(358,310);
	line.moveTo(510,210);
	line.lineTo(479,373);
	line.moveTo(358,310);
	line.lineTo(479,373);
	line.lineTo(718,255);
	line.lineTo(995,272);
	line.moveTo(718,255);
	line.lineTo(1026,360);
	line.lineTo(951,171);
	line.lineCap = "round";
	line.strokeStyle = '#d8d8d8';
	line.stroke();

	var line2 = line2.getContext('2d');
	line2.beginPath();
	line2.moveTo(1736,275);
	line2.lineTo(1376,270);
	line2.lineTo(1026,360);
	line2.lineTo(1206,219);
	line2.lineTo(994,271);
	line2.lineTo(951,171);
	line2.moveTo(1026,360);
	line2.lineTo(479,371);
	line2.lineCap = "round";
	line2.strokeStyle = '#d8d8d8';
	line2.stroke();

	var line3 = line3.getContext('2d');
	line3.beginPath();
	line3.moveTo(508,210);
	line3.lineTo(624,136);
	line3.lineTo(714,252);
	line3.moveTo(624,136);
	line3.lineTo(644,44);
	line3.moveTo(327,170);
	line3.lineTo(300,78);
	line3.moveTo(171,286);
	line3.lineTo(196,109);
	line3.moveTo(950,160);
	line3.lineTo(889,82);
	line3.moveTo(960,160);
	line3.lineTo(960,64);
	line3.moveTo(960,160);
	line3.lineTo(993,125);
	line3.moveTo(1215,222);
	line3.lineTo(1220,122);
	line3.moveTo(1204,222);
	line3.lineTo(1431,136);
	line3.lineTo(1567,172);
	line3.moveTo(1431,136);
	line3.lineTo(1320,43);
	line3.lineCap = "round";
	line3.strokeStyle = '#FFFFFF';
	line3.stroke();
}