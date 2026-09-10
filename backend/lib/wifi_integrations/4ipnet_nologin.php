<!-- This integration does not authenticate the user, it only redirects the user to a URL -->
<?php
	if(!empty($_GET)){
		$_SESSION['mac'] = (!empty($_GET['umac']) ? urldecode($_GET['umac']) : null);
	}
?>
<!-- Redirect the user to the second portal/link -->
<form id="userAuthorizationForm" class="hotelinking-wifi-login-form" action="<?php echo $datosWifiHotel['form_url'] ?>" method="GET" name="form">
	
</form>

<script type="text/javascript">

	$(document).ready(function () {
		//must have a button with class send-login-button in the template
		// $('.send-login-button').click(function(){
			HLevents.subscribe('wifi-redirect', function(obj){
				//Send log
				$('.hotelinking-wifi-login-form').submit();
			})
		// })
	})

</script>