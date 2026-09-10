<?php include LANG . $_SESSION['userLang'] . '/create-new-hotel-modal.php' ?>
<div class="modal fade bs-modal-lg" id="createNewHotelModal">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title"><?php echo $lang['Please fill the basic info for your new hotel'] ?></h4>
			</div>
			<div class="modal-body">
				<p><?php echo $lang['In order to add an hotel to your chain you need to fill the fields below (at least the (*) mandatory fields)'] ?></p>
				<form role="form" class="mt2 validation-form" method="post" enctype="multipart/form-data" >
					<div class="row">
						<div class="col-lg-8 col-md-6">
							<div class="form-group">
								<label for="hotelName"><?php echo $lang['Nombre de tu hotel'] ?></label>
								<input type="text" class="form-control" id="hotelName" name="hotelName"  placeholder="<?php echo $lang['El nombre de tu hotel es...'] ?>" required >
							</div>
						</div>
						<div class="col-lg-4 col-md-4">
							<div class="form-group">
								<label for="hotelCity"><?php echo $lang['Ciudad'] ?></label>
								<input class="form-control" name="hotelCity" id="hotelCity" required >
                                <input id="place_name" type="hidden" name="place_name" value=""/>
                                <input id="place_country" type="hidden" name="place_country" value=""/>
                                <input id="place_adm_area" type="hidden" name="place_adm_area" value=""/>
                                <input id="lat" type="hidden" name="lat" value=""/>
                                <input id="lng" type="hidden" name="lng" value=""/>
                                <input id="place_id" type="hidden" name="place_id" value=""/>
                                <input id="country_name" type="hidden" name="country_name" value=""/>
							</div>
						</div>
						<div class="col-lg-12 mt2">
							<div class="form-group">
								<label for="hotelStreet"><?php echo $lang['Address'] ?></label>
								<input type="text" class="form-control" id="hotelStreet" name="hotelStreet" placeholder="<?php echo $lang['Your hotel address goes here'] ?>" required>
							</div>
						</div>
					</div>
					<div class="row mt2">
						<div class="col-lg-12">
							<div class="form-group">
								<label for="hotelWebsite"><?php echo $lang['Página web'] ?></label>
								<input type="text" class="form-control" id="hotelWebsite" name="hotelWebsite" placeholder="<?php echo $lang['La página web de tu hotel aqui'] ?>" required >
							</div>
						</div>
					</div>
                    <div class="row mt2">
						<div class="col-lg-12">
							<div class="form-group">
								<label for="hotelWebsite"><?php echo $lang['Sube el logo de tu hotel'] ?></label>
								<input type="file" class="form-control sendFile" name="logoHotel" id="logoHotel" required>
							</div>
						</div>
					</div>

					<div class="clearfix"></div>
					<div class="col-lg-5 mb">
						<input type="submit" value="<?php echo $lang['Create new hotel button'] ?>" class="btn btn-success btn-lg mt2 btn-block" name="hotelConfirmButton">
					</div>
					<?php if(!empty($arrayDatosHotel['email'])) {?>
					<input type="hidden" name="emailAnterior" value="<?php echo $arrayDatosHotel['email']; ?>" />
					<?php } ?>
					<?php if(!empty($arrayDatosHotel['verificado'])) {?>
					<input type="hidden" name="verificado" value="<?php echo $arrayDatosHotel['verificado']; ?>" />
					<?php } ?>
				</form>
				<div class="clearfix"></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $lang['Cerrar'] ?></button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
