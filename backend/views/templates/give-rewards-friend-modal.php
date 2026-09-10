<?php include LANG . $_SESSION['userLang'] . '/give-rewards-friend-modal.php' ?>
<div class="modal fade bs-modal-lg" id="giveRewardsFriendModal">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title"><?php echo $GiveRewardFriendModalLang['Give reward points to a friend'] ?></h4>
			</div>
			<div class="modal-body">
				<p><?php echo $GiveRewardFriendModalLang['We need some information to give your reward points.'] ?></p>
				<form action="<?php echo $url['dir1'] ?>" method="post">
					<label for="userEmail" class="mt2"><?php echo $GiveRewardFriendModalLang['Wich hotel rewards do you want to give?'] ?></label>
					<select name="hotelRewards" id="hotelRewards" class="form-control">
						<?php foreach ($arraySelectPuntos as $hotel) {
							echo '<option value="'.$hotel['id'].'">'.$hotel['nombre'].' - '.$hotel['puntos'].' '.$GiveRewardFriendModalLang['rewards points'].'</option>';
						} ?>

					</select>
					<label for="rewardPoints" class="mt2"><?php echo $GiveRewardFriendModalLang['How much rewards do you want to give?'] ?></label>
					<div class="input-group">
						<span class="input-group-addon"><i class="rubies rubix2">rubies</i></span>
						<input type="number" class="form-control" id="rewardPoints" name="rewardPoints" placeholder="0">
					</div>
					<label for="userEmail" class="mt2"><?php echo $GiveRewardFriendModalLang['Write the user email that you want to give rewards'] ?></label>
					<input type="email" class="form-control" id="userEmail" name="userEmail"  placeholder="<?php echo $GiveRewardFriendModalLang['User email...'] ?>">
					<input id="searchUser" class="btn btn-primary mt2" value="<?php echo $GiveRewardFriendModalLang['Search User'] ?>">
                    <div class="text-center mt2" id="result">
                    <!--<h3 class="naranja"><strong>User doesn´t exist</strong></h3>
                        <p>This email doesn´t exist in Hotelinking, we will send an invite to this user along with the points you want to give.</p>
                        <p>Confirm that you want to give points to this user.</p>
                        <button class="btn btn-success mt btn-lg"><strong>Confirm send reward points</strong></button>-->
                    </div>
                </form>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $GiveRewardFriendModalLang['Cerrar'] ?></button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->