<?php
//Email que se envia cuando se consigue el primer referral para ganar los 12K guest slots
$asunto2nd = 	$segundoEmail['asunto'];
$cuerpo2nd = '<h1 style="text-align:center">'.$segundoEmail['h1'].'</h1>
			<h4 style="text-align:center">'.$segundoEmail['Your second referral just landed'].'</h4>
			<p style="text-align:center">'.$segundoEmail['keep sharing'].'</p>
			<p style="text-align:center"><strong>'.$yourCode.'</strong></p>
			<h4 style="text-align:center">'.$segundoEmail['more referrals'].'</h4>
			<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
			  <tr>
			    <td style="text-align:center"><img src="http://www.hotelinking.com/public/img/email/images/2stinvite.gif" alt="1st invite" /></td>
			  </tr>
			</table>
			<br><br><br>
			<small style="text-align:center !important; font-size:9px">No more notifications from landing?, click here: <a href="'.$UnsubUrl.'">Unsuscribe</a></small>';
 ?>