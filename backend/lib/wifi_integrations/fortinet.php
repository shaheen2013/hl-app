<?php

	//TODO: This integration is not tested with a real team because we do not have any fortinet to do it. It remains to be done when one is available.

	// Set param "MAGIC" from GET or SESSION, MAGIC is a required value to send back to Fortinet.
	$magic = $_SESSION['magic'] = array_get($_GET, 'magic', array_get($_SESSION, 'magic', FALSE));
	
	// Set param "POST" from GET or SESSION, POST is action value URL to form Fortinet's.
	$post = $_SESSION['post'] = array_get($_GET, 'post', array_get($_SESSION, 'post', FALSE));
	
	// Set param "MAC" from GET or SESSION, MAC is the mac address from the user's device.
	$mac = $_SESSION['mac'] = array_get($_GET, 'usermac', array_get($_SESSION, 'mac', FALSE));
	
	// Set param "PASSWORD" and "USERNAME" to dashboard.
    $password = array_get($datosWifiHotel, 'password', FALSE);
    $username = array_get($datosWifiHotel, 'username', FALSE);

?>

		<form id="userAuthorizationForm" class="hotelinking-wifi-login-form" action="<?php echo $post ?>" method="POST" name="form">
			
			<input type="hidden" name="username" value="<?php echo $username ?>">
			<input type="hidden" name="password" value="<?php echo $password ?>">
			<input type="hidden" name="magic" value="<?php echo $magic ?>">

		</form>

		<script type="text/javascript">

			$(document).ready(function () {
				HLevents.subscribe('wifi-redirect', function(obj){
                    //Send log

					$('.hotelinking-wifi-login-form').submit();
				})
		    })

		</script>