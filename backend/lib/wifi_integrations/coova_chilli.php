<?php
    //No direct access allowed
    if (!defined('INDEXCONTROLVAL')) {
        echo 'No direct access allowed.';
        exit;
    }

    $username = $datosWifiHotel['username'];
    $password = $datosWifiHotel['password'];
    $uamsecret = null;
    $userpassword = "pap";

    // If those parameters exists, store them in session
    $_SESSION['challenge'] = array_get($_GET, 'challenge', array_get($_POST, 'challenge', array_get($_SESSION, 'challenge', '')));
    $_SESSION['mac'] = array_get($_SESSION, 'mac', macFormatter(array_get($_GET, 'mac', macFormatter(array_get($_POST, 'mac', '')))));
    $_SESSION['uamip'] = array_get($_SESSION, 'uamip', array_get($_GET, 'uamip', array_get($_POST, 'uamip', '')));
    $_SESSION['uamport'] = array_get($_SESSION, 'uamport', array_get($_GET, 'uamport', array_get($_POST, 'uamport', '')));
    $_SESSION['userurl'] = $datosWifiHotel['url'] ?? array_get($_GET, 'userurl', array_get($_POST, 'userurl', array_get($_SESSION, 'userurl', "")));
    $_SESSION['res'] = array_get($_GET, 'res', array_get($_POST, 'res', array_get($_SESSION, 'res', '')));
    $_SESSION['loginUrl'] = "http://".$_SESSION['uamip'].":".$_SESSION['uamport']."/login";

	// Hash the password so the RADIUS server undertstands the login
    $hexchal = pack ("H32", $_SESSION['challenge']);
    if ($uamsecret) {
        $newchal = pack ("H*", md5($hexchal . $uamsecret));
    } else {
        $newchal = $hexchal;
    }
    $newpwd = pack("a32", $password);

    $response = md5("\0" . $password . $newchal);
    $pappassword = implode ("", unpack("H32", ($newpwd ^ $newchal)) ? unpack("H32", ($newpwd ^ $newchal)) : []);


    $log->debug('Coova Chilli session info', [
        'session' => $_SESSION,
        '$_GET' => $_GET,
        '$_POST' => $_POST,
    ]);
?>

    <form class="hotelinking-wifi-login-form" action="<?php echo $_SESSION['loginUrl']; ?>" method="GET">
		<?php if (isset($uamsecret) && isset($userpassword) && $userpassword == "pap" ) { ?>
			<input type="hidden" name="username" value="<?php echo $username;?>">
			<input type="hidden" name="password" value="<?php echo $pappassword;?>">
		<?php } else { ?>
			<input type="hidden" name="username" value="<?php echo $username;?>">
			<input type="hidden" name="password" value="<?php echo $password;?>">
			<input type="hidden" name="response" value="<?php echo $response;?>">
			<input type="hidden" name="userurl" value="<?php echo $_SESSION['userurl'];?>">
		<?php } ?>
    </form>

	<?php if($_SESSION['res'] == "success" || $_SESSION['res'] == "already" || $_SESSION['res'] == "logoff") { ?>
		<script>
			$(document).ready(function () {
					window.location.replace("<?php echo $_SESSION['userurl'];?>");
			})
		</script>
	<?php } else {?>
		<script type="text/javascript">
			$(document).ready(function () {
				//Must have a button with class hotelinking-wifi-login-button in the template
				HLevents.subscribe('wifi-redirect', function(obj){
					//Send log
					$('.hotelinking-wifi-login-form').submit();
				})
			})
		</script>
	<?php }?>