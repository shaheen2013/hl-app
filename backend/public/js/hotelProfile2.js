$(document).ready(function($) {
	checkNumChars('#hotelDescription');
	checkNumChars('#hotelConditions');

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

	function checkNumChars(id){
		var size = $(id).text().length;
		if(size < 250){
			$(id).removeClass('has-success').addClass('has-errors');
			$(id).next('.textAreaCount').addClass('textAreaCountError').removeClass('textAreaCountSuccess');
			$(id).next('.textAreaCount').html('<i class="fa fa-exclamation"></i> ' + size + ' of min 250 characters');
		}else{
			$(id).removeClass('has-errors').addClass('has-success');
			$(id).next('.textAreaCount').removeClass('textAreaCountError').addClass('textAreaCountSuccess');
			$(id).next('.textAreaCount').html('<i class="fa fa-check"></i> ' + size + ' of min 250 characters');
		}
	}

	initToolbarBootstrapBindings();

	$('#hotelDescription').wysiwyg({ toolbarSelector: '[data-role=editor1-toolbar]'});
	$('#hotelConditions').wysiwyg({ toolbarSelector: '[data-role=editor2-toolbar]'});

	$('.modal-link').click(function(e){
		e.preventDefault();
		var modal = $ (this).attr('data-target');
		$(modal).modal();
	});

	$('#hotelDescription').bind("keyup", function() {
		if(($('#hotelDescription').text()) == "") {
			$('#hotelDescription').empty();
		}else{
			var descContent = $('#hotelDescription').html();
			$('#hotelDescriptionText').html(descContent);
			checkNumChars('#hotelDescription');
		}
	});
	$('#hotelConditions').bind("keyup", function() {
		if(($('#hotelConditions').text()) == "") {
			$('#hotelConditions').empty();
		}else{
			var descContent = $('#hotelConditions').html();
			$('#hotelConditionsText').html(descContent);
			checkNumChars('#hotelConditions');
		}
	});

	$('.validation-form').bootstrapValidator({
		message: 'This value is not valid',
		feedbackIcons: {
			valid: 'fa fa-check',
			invalid: 'fa fa-exclamation',
			validating: 'fa fa-refresh'
		},
		live: 'enabled',
		fields:{
			hotelType:{
				validators:{
					noEmpty:{
						message: 'The hotel type can´t be empty'
					},
					regexp: {
						regexp: /^((?!Sel).)*$/,
						message: 'The hotel type can´t be empty'
					}
				}
			},
			hotelDecoration:{
				validators:{
					noEmpty:{
						message: 'The hotel decoration can´t be empty'
					},
					regexp: {
						regexp: /^((?!Sel).)*$/,
						message: 'The hotel decoration can´t be empty'
					}
				}
			},
			'roomType[]': {
				validators: {
					notEmpty: {
						message: 'Please specify at least one room type'
					}
				}
			},
			'roomExtra[]': {
				validators: {
					notEmpty: {
						message: 'Please specify at least one room extra'
					}
				}
			},
			'hotelServices[]': {
				validators: {
					notEmpty: {
						message: 'Please specify at least one hotel service'
					}
				}
			},
			"fotosDelHotel[]": {
				validators: {
					noEmpty:{
						message: 'Please Upload images of your hotel'
					}
				}
			}
		}
	});
$('.validation-form').bootstrapValidator('validate');

});