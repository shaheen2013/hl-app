<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<?php include LANG . $_SESSION['userLang'] . '/user-points-log.php' ?>
<?php include TEMPLATES . 'user-top-bar.php' ?>
<div class="container">
	<div class="user-utility-bar mb15">
		<h1 class="pull-left"><strong><i class="rubies rubix3">rubies</i> <?php echo $UserPointsLogLang['Your rewards log'] ?></strong></h1>
		<?php include TEMPLATES . 'user-reward-menu.php' ?>
	</div>
	<div class="clearfix"></div>
			<?php if(!empty($arrayPuntosUsuario)) {?>
			<div class="table-responsive relative">
				<table class="table table-striped">
					<?php foreach ($arrayPuntosUsuario as $log) { ?>
					<tr class="table-row">
						<td>
							<?php
							switch ($log['id_action']) {
								case '1':
								echo '<strong><span class="verde">'.$UserPointsLogLang['Received'].'</span></strong> ' . $log['puntos'] . ' <i class="rubies rubix1 rubiesHL">rubies</i></strong> '.$UserPointsLogLang['from'].' <strong>Hotelinking</strong> '.$UserPointsLogLang['for write your opinion about'].' <a href="'.$log['hotel_action_url'].'" title="'. $log['hotel_action_nombre'] .'">'. $log['hotel_action_nombre'] .'</a>';
								break;
								case '2':
								echo '<strong><span class="verde">'.$UserPointsLogLang['Received'].'</span></strong> ' . $log['puntos'] . ' <i class="rubies rubix1 rubiesHL">rubies</i></strong> '.$UserPointsLogLang['from'].' <strong>Hotelinking</strong> '.$UserPointsLogLang['for share your opinion about'].' <a href="/hotel/'.$log['emisor_san'] . '/' . $log['id_emisor'] . '" title= '. $log['emisor'] .'">'. $log['emisor'] .'</a>';
								break;
								case '3':
								echo '<strong><span class="verde">'.$UserPointsLogLang['Received'].'</span></strong> ' . $log['puntos'] . ' <i class="rubies rubix1 rubiesHL">rubies</i></strong> '.$UserPointsLogLang['from'].' <strong>Hotelinking</strong> '.$UserPointsLogLang['because'].' <a href="'.$log['referral_url'].'" title="'. $log['referral'].'">'. $log['referral'].'</a> '.$UserPointsLogLang['checked-in in'].' <a href="'.$log['url'].'">'. $log['emisor'] .'</a>';
								break;
								case '4':
								echo '<strong><span class="verde">'.$UserPointsLogLang['Received'].'</span></strong></strong> ' . $log['puntos'] . ' <i class="rubies rubix1 rubiesHL">rubies</i></strong> '.$UserPointsLogLang['from'].' <strong>Hotelinking</strong> '.$UserPointsLogLang['because you joined us thanks to'].' <a href="'.$log['referral_url'].'" title="'. $log['referral'].'">'. $log['referral'].'</a>';
								break;								
								case '6':
								echo '<strong><span class="verde">'.$UserPointsLogLang['Received'].'</span></strong> ' . $log['puntos'] . ' <i class="rubies rubix1">rubies</i></strong> '.$UserPointsLogLang['from'].' <a href="'.$log['url'].'" title= '. $log['emisor'] .'">'. $log['emisor'] .'</a> '.$UserPointsLogLang['because you checked-in there'].'';
								break;
								case '7':
								echo '<strong><span class="verde">'.$UserPointsLogLang['Received'].'</span></strong> ' . $log['puntos'] . ' <i class="rubies rubix1">rubies</i></strong> '.$UserPointsLogLang['from'].' <a href="'.$log['url'].'" title= '. $log['emisor'] .'">'. $log['emisor'] .'</a> '.$UserPointsLogLang['because you checked-out there'].'';
								break;
								case '8':
								echo '<strong><span class="verde">'.$UserPointsLogLang['Received'].'</span></strong> ' . $log['puntos'] . ' <i class="rubies rubix1">rubies</i></strong> '.$UserPointsLogLang['from'].' <a href="'.$log['url'].'" title= '. $log['emisor'] .'">'. $log['emisor'] .'</a> '.$UserPointsLogLang['because you been invited to visit the hotel'].'';
								break;
								case '9':
								echo '<strong><span class="naranja">'.$UserPointsLogLang['Gift'].'</span></strong> ' . $log['puntos'] . ' <i class="rubies rubix1">rubies</i></strong> '.$UserPointsLogLang['to'].' <a href="/'.$log['regalador_url'].'" title="'. $log['regalador'].'">'. $log['regalador'].'</a> '.$UserPointsLogLang['to spend in'].' <a href="'.$log['url'].'" title="'. $log['emisor'].'">'. $log['emisor'].'</a>';
								break;
								case '10':
								echo '<strong><span class="naranja">'.$UserPointsLogLang['Spent'].'</span></strong> '. $log['puntos'].' '.($log['adq_ret'] == "adq" ? '<i class="rubies rubix1 rubiesHL">rubies</i>': '<i class="rubies rubix1">rubies</i>' ) .' '.$UserPointsLogLang['in'].' <a href="/oferta/'.$log['oferta_san'].'/' . $log['id_oferta'] .'" title="'.$log['oferta'].'">'.$log['oferta'].'</a> '.$UserPointsLogLang['offer in'].' <a href="'.$log['url'] . '" title= '. $log['emisor'] .'">'. $log['emisor'] .'</a> ';
								break;
								case '11':
								echo '<strong><span class="verde">'.$UserPointsLogLang['Received'].'</span></strong> ' . $log['puntos'] . ' <i class="rubies rubix1">rubies</i></strong> '.$UserPointsLogLang['from your friend'].' <a href="'.$log['regalador_url'].'" title="'. $log['regalador'].'">'. $log['regalador'].'</a> '.$UserPointsLogLang['to spend in'].' <a href="'.$log['url'].'" title="'. $log['emisor'].'">'. $log['emisor'].'</a>';
								break;
								case '12':
								echo '<strong><span class="verde">'.$UserPointsLogLang['Received'].'</span></strong> ' . $log['puntos'] . ' <i class="rubies rubix1">rubies</i></strong> '.$UserPointsLogLang['from'].' <a href="'.$log['url'].'" title= '. $log['emisor'] .'">'. $log['emisor'] .'</a> '.$UserPointsLogLang['as a gift for your loyalty'].'';
								break;
								default:
								break;
							} ?>
							<span class="pull-right"> <?php echo $log['fecha'] ?></span>
						</td>
					</tr>
					<?php } ?>
				</table>
			</div>
			<?php }else{ ?>
					<div class="text-center mt2">
						<img src="<?php echo DIR_IMG . 'big-diamond.png' ?>" alt="diamond" >
						<h2><?php echo $UserPointsLogLang['There are nothing in your rewards log'] ?></h2>
						<h4><?php echo $UserPointsLogLang['Please come back to track your earns and spendings when you have some movement'] ?></h4>
						<h5><?php echo $UserPointsLogLang['Meanswhile maybe you are interested to know'] ?> <a href="<?php echo $urlTree['faq'] ?>" title="How to earn reward points"> <?php echo $UserPointsLogLang['how to earn reward points'] ?></a></h5>
					</div>
			<?php } ?>
</div>
<?php include TEMPLATES . 'give-rewards-friend-modal.php' ?>
<script>
	$(document).ready(function(){
		$('.fichaOferta, .fichaHotel').hover(function(){
			$(this).children('.overlayer').fadeToggle('fast');
		});
		$('.give-rewards').click(function(e){
			e.preventDefault();
			$('#giveRewardsFriendModal').modal('show');
		})
		$('#searchUser').click(function(){
			var email = $("#userEmail").val();
			$.ajax({ url: "/lib/webservices/user-points-ws.php",
				data: 'email='+ email +'',
				type: 'POST',
				success: function(output) {
					$('#result').empty();
					$('#result').append(output);
				}
			});
		})
	})
</script>