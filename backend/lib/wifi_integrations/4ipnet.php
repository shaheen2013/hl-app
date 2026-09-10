<?php

	if(!empty($_GET) && !empty($_GET['loginurl'])){

		$loginurl = $_SESSION['loginurl'] = (!empty($_GET['loginurl']) ? urldecode($_GET['loginurl']) : null);
		$_SESSION['mac'] = (!empty($_GET['umac']) ? urldecode($_GET['umac']) : null);

	} else {

		$loginurl = $_SESSION['loginurl'] ?? null;

	}

?>

		<form id="userAuthorizationForm" class="hotelinking-wifi-login-form" action="<?php echo $loginurl; ?>" method="POST" name="form">
			
			<input type="hidden" name="myusername" size="25" value="<?php echo $datosWifiHotel['username'] ?>">
			<input type="hidden" name="mypassword" size="25" value="<?php echo $datosWifiHotel['password'] ?>">

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