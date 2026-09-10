<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<?php include LANG . $_SESSION['userLang'] . '/user-profile-4.php' ?>
<?php include TEMPLATES . 'user-top-bar.php' ?>
<div class="container">
	<div class="user-utility-bar mb15">
		<?php include TEMPLATES . 'user-profile-menu.php'; ?>
	</div>
</div>
<div class="container" id="fullContainer">
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="col-lg-10 col-lg-offset-1 mb2 text-center">
				<h3><?php echo $UserProfile4Lang['Select wich notifications you want to receive from hotelinking and his partners'] ?></h3>
			</div>
			<form method="POST" class="profileForm mt2">
				<div class="col-lg-8 col-lg-offset-2">
					<div class="col-lg-5">
						<label for="offerPreferences"><?php echo $UserProfile4Lang['Tipos de ofertas'] ?></label>
						<select name="offerPreferences[]" id="offerPreferences" class="form-control" multiple>
							<?php foreach ($arrayTiposOferta as $value) {
								echo '<option value="' . $value['id'] . '">' . $value['nombre'] . '</option>';
							}
							?>
						</select>
					</div>
					<div class="col-lg-2 text-center">
						<label></label>
						<div class="row mt">
							<button class="btn btn-primary movePlaces"><i class="fa fa-angle-double-right"></i></button>
						</div>
						<div class="row">
							<button class="btn btn-warning mt removePlaces"><i class="fa fa-trash-o"></i></button>
						</div>
					</div>
					<div class="col-lg-5">
						<label for="offerPreferencesSelected"><?php echo $UserProfile4Lang['Tipos de oferta preferida'] ?></label>
						<select name="offerPreferencesSelected[]" id="offerPreferencesSelected" class="form-control" multiple>
							<?php foreach ($arrayOfertasUsuario as $value) {
								echo '<option value="' . $value['id_oferta'] . '">' . $value['nombre'] . '</option>';
							}
							?>
						</select>
					</div>
				</div>
                <div class="row">
                	<div class="col-lg-8 col-lg-offset-2 mt2">
                    	<div class="checkbox">
                        	<label>
                            	<input type="checkbox" value="1" name="fromHotelinking" <?php if($arrayNotificaciones['notif_hotelinking'] == 1){echo 'checked=checked';} ?>\> <?php echo $UserProfile4Lang['Recibe notificaciones importantes de hotelinking'] ?>
                            </label>
                        </div>
                    </div>
                </div>
				<div class="clearfix"></div>
				<div class="row">
					<div class="col-lg-8 col-lg-offset-2">
						<input type="submit" name="save-user-profile-4" class="btn btn-success btn-lg mt2 pull-left" value="<?php echo $UserProfile4Lang['Confirma estos datos'] ?>"/>
					</div>
				</div>
				<?php foreach ($arrayOfertasUsuario as $value) {
					echo '<input class="countryVals" type="hidden" value="' . $value['id_oferta'] . '" name="offerPreferencesH[]">';
				}
				?>
			</form>
		</div>
	</div>
</div>
</div>

<script>
	$(function(){
		$(".movePlaces").on("click", function(e){
			var existe = false;
			e.preventDefault();
			$("#offerPreferences option:selected").each(function(){
				var actual = $(this).text();
				$('#offerPreferencesSelected option').each(function(){
					if (actual == $(this).text()) {
						existe = true;
					};
				});
				if (!existe){
					$('.profileForm').append("<input type='hidden' class='countryVals' name='offerPreferencesH[]' value=" + $(this).val() + ">");
					$("#offerPreferencesSelected").append("<option value="+$(this).val()+">"+$(this).text()+ "</option>\n");
				};
			});
		});
		$(".removePlaces").on("click", function(e){
			e.preventDefault();
			$("#offerPreferencesSelected option:selected").each(function(){
				$(this).remove();
				var value =$(this).val();
				$('.countryVals').each(function(){
					if($(this).val() == value){
						$(this).remove();
					}
				});
			});
		});
	});
</script>