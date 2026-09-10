$(document).ready(function(){
	$('.validation-form').bootstrapValidator({
		message: 'This value is not valid',
		feedbackIcons: {
			valid: 'fa fa-check',
			invalid: 'fa fa-exclamation',
			validating: 'fa fa-refresh'
		},
		live: 'enabled',
		fields:{
			emailReserva:{
				validators:{
					emailAddress: {
						message: 'The value is not a valid email address'
					}
				}
			},
			hotelWebsite:{
				validators:{
					noEmpty: {
						message: 'Add an hotel website here'
					}
				}
			},
			telefonoReservas:{
				validators:{
					noEmpty: {
						message: 'The phone field is not valid'
					}
				}
			}
		}
	});
	$('.validation-form').bootstrapValidator('validate');
});