<?php

// Set param "MAGIC" from GET or SESSION, MAGIC is a required value to send back to Fortinet.
$magic = $_SESSION['magic'] = array_get($_GET, 'magic', array_get($_SESSION, 'magic', false));

// Set param "POST" from GET or SESSION, POST is action value URL to form Fortinet's.
$post = $_SESSION['post'] = array_get($_GET, 'post', array_get($_SESSION, 'post', false));

// Set param "MAC" from GET or SESSION, MAC is the mac address from the user's device.
$mac = $_SESSION['mac'] = array_get($_GET, 'usermac', array_get($_SESSION, 'mac', false));

// Set param "PASSWORD" from array value, PASS contain password login is a required value to send back to Fortinet.
$pass = $datosWifiHotel['password'];

// Get the roomNumber for session after the wifi-redirect POST
$roomNumber = strtolower(array_get($_SESSION, 'roomNumber', ''));

if ($url['dir1'] === $urlTree['stay-wifi-redirect'] && array_get($_SESSION,'triggered_hotspot')) {
	$log->info('Protur post', [
		'username' => $roomNumber,
		'password' => $pass,
		'magic' => $magic
	]); 
}


?>

		<form id="userAuthorizationForm" class="hotelinking-wifi-login-form" action="<?php echo $post ?>" method="POST" name="form">

			<input type="hidden" name="username"value="<?php echo $roomNumber ?>">
			<input type="hidden" name="password" value="<?php echo $pass ?>">
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