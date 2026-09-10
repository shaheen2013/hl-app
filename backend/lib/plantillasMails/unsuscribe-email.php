<?php
//Email que se envia cuando se consigue el primer referral para ganar los 12K guest slots
$asuntoUn = 	$unsubEmail['asunto'];
$cuerpoUn = '<h1 style="text-align:center">'.$unsubEmail['h1'].'</h1>
			<p style="text-align:center">'.$unsubEmail['unsubMsg'].'</p>
			<p style="text-align:center"><a href="'.$_SESSION['unsubUrl'].'" title="unsuscribe">'.$_SESSION['unsubUrl'].'</a></p>';
 ?>