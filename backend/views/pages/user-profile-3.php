<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<?php include LANG . $_SESSION['userLang'] . '/user-profile-3.php' ?>
<?php include TEMPLATES . 'user-top-bar.php' ?>
<div class="container">
	<div class="user-utility-bar mb15">
		<?php include TEMPLATES . 'user-profile-menu.php'; ?>
	</div>
</div>
	<div class="container" id="fullContainer">
	<div class="col-lg-10 col-lg-offset-1 text-center">
			<h3><?php echo $UserProfile3Lang['Travel preferences text'] ?></h3>
		</div>
		<div class="clearfix"></div>
		<form role="form" class="mt2 profileForm col-lg-6 col-lg-offset-3" method="post">
			<div class="row mb2">
				<div class="col-lg-5">
					<label for="worldPlaces"><?php echo $UserProfile3Lang['Lugares del mundo'] ?></label>
					<select name="worldPlaces" id="worldPlaces" class="form-control" multiple>
					<?php foreach ($arrayPaises as $value) {
						echo '<option value="' . $value['id'] . '">' . $value['pais'] . '</option>';
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
					<label for="iWantToVisit"><?php echo $UserProfile3Lang['Que quiero visitar'] ?></label>
					<select name="iWantToVisit" id="iWantToVisit" class="form-control" multiple>
					<?php foreach ($arrayPaisesUsuario as $value) {
						echo '<option value="' . $value['id_pais'] . '">' . $value['pais'] . '</option>';
					}
					?>
					</select>
				</div>
			</div>
			<div class="col-lg-12">
				<div class="alert alert-info">
					<div class="pull-left">
						<i class="fa fa-lightbulb-o pr"></i>
					</div>
					<p><?php echo $UserProfile3Lang['Seleccionar varios nota aviso'] ?></a></p>
					<p><?php echo $UserProfile3Lang['Seleccionar varios nota aviso 2'] ?></p>
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="row">
				<div class="col-lg-12">
					<h3><?php echo $UserProfile3Lang['¿Quieres invitar a algún compañero de viajes a hotelinking?'] ?></h3>
					<p><?php echo $UserProfile3Lang['Invitar amigo texto explicacion'] ?></p>
				</div>
				<div class="clearfix"></div>
				<div class="row mt mb">
					<div class="col-lg-12">
						<label for="userEmail"><?php echo $UserProfile3Lang['Correo electrónico'] ?></label>
						<input type="email" class="form-control" name="userEmail" placeholder="<?php echo $UserProfile3Lang['Inserta la cuenta de correo...'] ?>">
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-12 mb">
					<input type="submit" class="btn btn-success btn-lg mt2 pull-left" value="<?php echo $UserProfile3Lang['Save changes'] ?>" name="save-profile-3"/>
				</div>
			</div>
			<?php foreach ($arrayPaisesUsuario as $value) {
						echo '<input class="countryVals" type="hidden" value="' . $value['id_pais'] . '" name="countrylist[]">';
					}
			?>
		</form>
		<div class="clearfix"></div>
	</div>
	</div>
</div>
<script>
	$(function(){
		$(".movePlaces").on("click", function(e){
			var existe = false;
			e.preventDefault();
			$("#worldPlaces option:selected").each(function(){
				var actual = $(this).text();
				$('#iWantToVisit option').each(function(){
					if (actual == $(this).text()) {
						existe = true;
					};
				});
				if (!existe){
					$('.profileForm').append("<input type='hidden' class='countryVals' name='countrylist[]' value=" + $(this).val() + ">");
					$("#iWantToVisit").append("<option value="+$(this).val()+">"+$(this).text()+ "</option>\n");
				};
			});
		});
		$(".removePlaces").on("click", function(e){
			e.preventDefault();
			$("#iWantToVisit option:selected").each(function(){
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