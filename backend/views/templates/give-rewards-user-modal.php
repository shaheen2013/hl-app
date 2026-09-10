<?php include LANG . $_SESSION['userLang'] . '/give-rewards-user-modal.php' ?>
<div class="modal fade bs-modal-lg" id="give-rewards-user-modal">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title"><?php echo $lang['Give rewards to user'] ?></h4>
			</div>
			<div class="modal-body">
				<form action="<?php echo $urlTree['gestion-usuarios'] ?>" method="POST">
					<label for="givePoints"><?php echo $lang['¿How much points you desire to give?'] ?></label>
					<div class="input-group mt2">
						<span class="input-group-addon"><i class="rubies rubix2">rubies</i></span>
						<input type="number" min="0" class="form-control input-lg" id="givePoints" name="givePoints" placeholder="0">
					</div>
					<input type="hidden" name="userId" id="userId">
					<input type="submit" class="btn btn-primary mt2 btn-lg" value="<?php echo $lang['Give points to user button'] ?>">
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $lang['Cancel'] ?></button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->