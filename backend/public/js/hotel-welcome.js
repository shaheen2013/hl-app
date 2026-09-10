// JavaScript Document
$('#hotelPassword').on('keyup', function(){ // ----Password
	var selected = $("#hotelPassword").val();
	$.ajax({ url: "/lib/webservices/hotel-welcome.php",
		data: 'pass='+ selected +'',
		type: 'POST',
		success: function(output) {
			if(output==true){
				$('#hotelConfirmButton').prop("disabled", false);
				$('#passInfo').empty();
			}else{
				$('#hotelConfirmButton').prop("disabled", true);
				$('#passInfo').empty();
				$('#passInfo').append("<strong>Password must be 6 to 18 character lenght</strong><br><strong>1 upper case,</strong><strong> 1 lowe case</strong><strong> and 1 number</strong>");
			}
		}
	});
});