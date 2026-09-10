var canPub;
function comprobarGuardable(fun){
	$.ajax({ url: "/lib/webservices/hotel-crear-detalle-oferta.php",
		data: 'guardable=check',
		type: 'POST',
		async: false,
		success: function(output) {
			fun(output);
		}
	});
}
function comprobarPublicable(){
	$.ajax({ url: "/lib/webservices/hotel-crear-detalle-oferta.php",
		data: 'publicable=check',
		type: 'POST',
		async: false,
		success: function(output) {
			canPub=output;
		}
	});
}
$(document).ready(function(){

	$('.costAndQuotaBtn').click(function(){
		$('.costAndQuotaExplanation').fadeToggle();
	})

	$('#offerCostAndQuota').on('shown.bs.modal', function (e) {
			//tour.goTo(18);
		})

	$('#offerCostAndQuota').on('hidden.bs.modal', function (e) {
			//tour.goTo(21);
		});

	var croppicContainerEyecandyOptions = {
		uploadUrl:'/lib/webservices/img_save_to_file.php',
		cropUrl:'/lib/webservices/img_crop.php',
		outputUrlId:'croppic',
		customUploadButtonId:'imgUploadBtn',
		onAfterImgUpload: 	function(){
			var UploadedImg = $('.cropImgWrapper img');
			if(UploadedImg.naturalWidth < 710 || UploadedImg.naturalheight < 500){
				cropContainerEyecandy.reset();
				$('#imageUploadErrorModal').modal('show');
			}else{
				//tour.goTo(8);
				//checkTop();
			}
			checkTop();
		}
	}
	
	var cropContainerEyecandy = new Croppic('croppic', croppicContainerEyecandyOptions);

	$('.btnCrop').click(function(){
		cropContainerEyecandy.crop();
	})

	$('.btnPublishOffer, .btnSaveOffer').popover({
		trigger : 'hover'
	})

	$('.btnPublishOffer').click(function(e){
		preventDefault(e);
		comprobarPublicable();
		if (canPub != '0'){
			window.location = "<?php echo $urlTree['hotel-publicar-oferta']?>";
		}else{
			$('#cantPublishOfferModal').modal({
				show: true
			})
		}
	});

	$('.btnSaveOffer').click(function(e){
		comprobarGuardable(function(val){
			canSave = val;
		});
		e.preventDefault();
		if (canSave == '0'){
			$('#offerSaveErrorModal').modal({
				show: true
			})
		}else{
			window.location = "<?php echo $urlTree['hotel-crear-detalle-oferta'] ?>/?action=save";
		}
	});

	$('.btnVideotutorial').click(function(e){
		e.preventDefault();
		$('#createOfferVideoModal').modal({
			show: true
		});
	})

	function initToolbarBootstrapBindings() {
		$('.btn-toolbar a[title]').tooltip({container:'body'});
		$('.dropdown-menu input').click(function() {return false;})
		.change(function () {$(this).parent('.dropdown-menu').siblings('.dropdown-toggle').dropdown('toggle');})
		.keydown('esc', function () {this.value='';$(this).change();});
	};

	function showErrorAlert (reason, detail) {
		var msg='';
		if (reason==='unsupported-file-type') { msg = "Unsupported format " +detail; }
		else {
			console.log("error uploading file", reason, detail);
		}
		$('&lt;div class="alert"&gt; &lt;button type="button" class="close" data-dismiss="alert"&gt;&amp;times;&lt;/button&gt;'+
			'&lt;strong&gt;File upload error&lt;/strong&gt; '+msg+' &lt;/div&gt;').prependTo('#alerts');
	};

	initToolbarBootstrapBindings();
	$('#offerDesc').wysiwyg({ toolbarSelector: '[data-role=editor1-toolbar]'});
	$('#offerCond').wysiwyg({ toolbarSelector: '[data-role=editor2-toolbar]'});
	$('.a-divisa').click(function(e){
		e.preventDefault();
		var divisa = $(this).data('value');
		$('.input-divisa').val(divisa);
		$('.divisa-toggle').text(divisa);
		calcularPuntos();
	});

	checkCircles(offerMet);

});