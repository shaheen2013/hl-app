<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<?php include LANG . $_SESSION['userLang'] . '/invitar-usuarios-2.php' ?>
<div id="wrapper">
	<?php include TEMPLATES . 'hotel-sidebar.php'; ?>
	<div id="page-content-wrapper">
        <div class="top-bar">
            <?php include TEMPLATES . 'invite-users-menu.php'; ?>
            <img class="topbarLogo visible-xs" src="<?php echo DIR_IMG . 'topbar-logo-xs.png' ?>" width="77" height="70"
                 alt="top bar logo">
        </div>
		<div class="utility-bar">
			<div class="col-lg-12">
				<div class="row">
					<h1 class="pull-left"><i class="fa fa-envelope-o"></i> <?php echo($ready ? $InvitarUsuario2Lang['Send invite new guests'] : $InvitarUsuario2Lang['invite new guests']) ?></h1>
					<div class="breadcrumbs pull-right">
						<ul>
							<?php include (TEMPLATES .'breadcrumbs.php'); ?>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<div class="mainContent" id="fullContainer">
			<div class="col-lg-12 mt">
				<?php if(!$ready){
					echo '	<div class="alert alert-info"><div class="pull-left"><i class="fa fa-lightbulb-o pr"></i></div><p>'.$InvitarUsuario2Lang['Warning test'].'</p></div>';
				}else{ ?>
				<div class="alert alert-danger text-center" id="alertInvalidEmails"><?php echo $InvitarUsuario2Lang['We detected that you have'] ?> <strong id="nInvalidEmails"><?php echo $emailNoValidos ?></strong> <?php echo $InvitarUsuario2Lang['invalid emails'] ?><br/><?php echo '<a class="btn btn-success btn-sm mt" href="'.$urlTree['invitar-usuarios-2'].'/'.$id_lista.'/?email=0">'.$InvitarUsuario2Lang['take a look'].'</a> <a class="btn btn-danger btn-sm mt ml" href="'.$urlTree['invitar-usuarios-2'].'/'.$id_lista.'/?del=all">'.$InvitarUsuario2Lang['Delete invalid emails'].'</a>'; ?></div>
				<div id="inviteFilters" class="mb4 dnone">
					<div class="row mb2">
						<div class="col-md-6 mt">
							<p><?php echo $InvitarUsuario2Lang['Filter by not invited to:'] ?></p>
							<div class="btn-group" role="group" aria-label="...">
								<?php if($_SESSION['permisos']['RF'] == '1'){ //SI TIENE LY ?>
								<button type="button" class="btn btn-default btn-filter" data-filter="noRf"><?php echo $InvitarUsuario2Lang['Referral'] ?></button>
								<?php } ?>
								<?php if($_SESSION['permisos']['LY'] == '1'){ //SI TIENE LY ?>
								<button type="button" class="btn btn-default btn-filter" data-filter="noLy"><?php echo $InvitarUsuario2Lang['Loyalty'] ?></button>
								<?php } ?>
								<button type="button" class="btn btn-default btn-filter" data-filter="noMail"><?php echo $InvitarUsuario2Lang['Not invited'] ?></button>
							</div>
						</div>
						<div class="col-md-6 mt">
							<p><?php echo $InvitarUsuario2Lang['Filter by language:'] ?></p>
							<select name="lang" class="form-control langFilter">
								<option value="0">...</option>
								<?php foreach($langs as $key => $value){?>
								<option data-filter="<?php echo $key ?>" value="<?php echo $key ?>" <?php if(!empty($filtros['lang']) && $filtros['lang']==$key ){ echo 'selected='; } ?>><?php echo $value ?></option>
								<?php }?>
							</select>
						</div>
					</div>
                    <?php if($_SESSION['permisos']['LY'] == '1'){ //SI TIENE LY ?>
					<div class="row mb2">
						<div class="col-lg-12">
							<div class="col-lg-12 mt2">
									<span><?php echo $InvitarUsuario2Lang['Filter by Points:'] ?></span>
									<input class="noBorder mb noBg" type="text" id="slidePointsRange" disabled value="" name="userPointsRange">
									<div class="slider slidePointsRange pl"></div>
							</div>
							<div class="col-lg-12 mt2">
									<span><?php echo $InvitarUsuario2Lang['Filter by Nights:'] ?></span>
									<input class="noBorder mb noBg" type="text" id="slideNightsRange" disabled value="" name="userNightsRange">
									<div class="slider slideNightsRange pl"></div>
							</div>
							<div class="col-lg-12 mt2">
									<span><?php echo $InvitarUsuario2Lang['Filter by total spent:'] ?></span>
									<input class="noBorder mb noBg" type="text" id="slideSpentRange" disabled value="" name="userSpentRange">
									<div class="slider slideSpentRange pl"></div>
							</div>
						</div>
					</div>
                    <?php }?>
					<button class="btn btn-primary applyFilterBtn mt2"><?php echo $InvitarUsuario2Lang['Apply filters'] ?></button>
					<button class="btn btn-default hideFiltersBtn mt2"><?php echo $InvitarUsuario2Lang['Hide filters'] ?></button>
				</div>
				<?php } ?>
				<button class="btn btn-default showFiltersBtn pull-right"><?php echo $InvitarUsuario2Lang['Show filters'] ?></button>
				<form action="<?php echo $urlTree['invitar-usuarios-result'] ?>" method="POST" id="sendInviteListForm" name="sendInviteListForm">
					<?php if($ready){
						echo '<button type="submit" value="Preparar envio de invitaciones" class="btn btn-success pull-left hotelConfirmButton" data-toggle="modal" data-target="#ready-to-send-invites-modal">'.$InvitarUsuario2Lang['Preparing delivery of'].' <span class="nValidEmails">'.$_SESSION['n'].'</span> '.$InvitarUsuario2Lang['invites'].'</button>';
						echo(isset($_GET['email']) ? '<a href="'.$urlTree['invitar-usuarios-2'].'/'.$id_lista.'" class="btn btn-primary pull-right">'.$InvitarUsuario2Lang['Back to full list'].'</a>' : '');
					} ?>
					<?php if(!$ready){
						echo '<a href="#" class="btn btn-primary pull-left ml saveListBtn saveList" title="save draft">'.$InvitarUsuario2Lang['Save this list'].'</a>';
					} ?>
					<div class="clearfix"></div>
					<div class="table-responsive mt relative">
						<table class="table table-striped" id="usersListIU2">
							<tr class="table-header">
								<td>
									<select name="field-1" id="invite-field-1" class="form-control invite-field" <?php echo($ready ? 'disabled' : '') ?>>
										<?php if ($ready){
											echo '<option value="5">'.$InvitarUsuario2Lang['Guest name'].'</option>';
										}else{
											echo '<option value="6">'.$InvitarUsuario2Lang['Select an option..'].'</option> <option value="5">'.$InvitarUsuario2Lang['Guest name'].'</option> <option value="0">'.$InvitarUsuario2Lang['Guest email'].'</option> <option value="2">'.$InvitarUsuario2Lang['Language/Nationality'].'</option>';
											if($_SESSION['permisos']['LY'] == '1'){
												echo '<option value="1">'.$InvitarUsuario2Lang['Reward points given'].'</option><option value="3">'.$InvitarUsuario2Lang['Guest spend total'].'</option><option value="4">'.$InvitarUsuario2Lang['Guest total nights'].'</option>';
											}
										}?>
									</select>
								</td>
								<td>
									<select name="field-2" id="invite-field-2" class="form-control invite-field" disabled>
										<?php echo($ready ? '<option value="1">Email</option>' : '')?>
									</select>
								</td>
								<td>
									<select name="field-3" id="invite-field-3" class="form-control invite-field" disabled>
										<?php echo($ready ? '<option value="2">Language</option>' : '')?>
									</select>
								</td>

								<?php if($_SESSION['permisos']['LY'] == '1'){ //SI TIENE LY ?>
								<td>
									<select name="field-4" id="invite-field-4" class="form-control invite-field" disabled>
										<?php echo($ready ? '<option value="3">Points</option>' : '')?>
									</select>
								</td>
								<td>
									<select name="field-5" id="invite-field-5" class="form-control invite-field" disabled>
										<?php echo($ready ? '<option value="4">Total hotel spent</option>' : '')?>
									</select>
								</td>
								<td>
									<select name="field-6" id="invite-field-6" class="form-control invite-field" disabled>
										<?php echo($ready ? '<option value="5">Total nights</option>' : '')?>
									</select>
								</td>
								<?php } ?>

								<td>
									<?php echo $InvitarUsuario2Lang['Actions'] ?>
								</td>
								<td>
									<?php echo $InvitarUsuario2Lang['Send?'] ?>
								</td>
							</tr>
							<?php foreach ($arrayPaginado as $value): ?>
								<?php $rowCount += 1; ?>
								<tr class="table-row">
									<td>
										<input type="text" class="form-control" data-type="nombre-<?php echo $value['7'];?>" name="<?php echo 'nombre-'. $rowCount; ?>" value="<?php echo $value[0]; ?>" <?php echo($ready ? '' : 'disabled') ?>>
									</td>
									<td>
										<?php if ($ready){ ?>
										<input type="email" class="form-control <?php echo($value[6] !== '1' ? 'has-error' : ''); ?>" data-type="email-<?php echo $value['7'];?>" id="email-<?php echo $value['7'];?>" name="<?php echo 'email'. $rowCount; ?>" value="<?php echo $value[1]; ?>">
										<?php }else{ ?>
										<input type="email" class="form-control"  data-type="email-<?php echo $value['6'];?>" id="email-<?php echo $value['7'];?>" name="<?php echo 'email'. $rowCount; ?>" value="<?php echo $value[1]; ?>" disabled>
										<?php } ?>
									</td>	
									<td>
										<input type="text" class="form-control" data-type="idioma-<?php echo $value['7'];?>" name="<?php echo 'idioma-'. $rowCount; ?>" value="<?php echo $value[2]; ?>" <?php echo($ready ? '' : 'disabled') ?>>
									</td>

									<?php if($_SESSION['permisos']['LY'] == '1'){ //SI TIENE LY ?>
									<td>
										<?php if ($ready){ ?>
										<input type="text" class="form-control <?php echo($value[3] == 0 ? 'has-warning' : '') ?>" data-type="puntos-<?php echo $value['7'];?>" id="puntos-<?php echo $value['7'];?>" name="<?php echo 'puntos'. $rowCount; ?>" value="<?php echo $value[3]; ?>">
										<?php }else{ ?>
										<input type="text" class="form-control" data-type="puntos-<?php echo $value['7'];?>" name="<?php echo 'puntos'. $rowCount; ?>" value="<?php echo $value[3]; ?>" disabled>
										<?php } ?>
									</td>
									<td>
										<?php if ($ready){ ?>
										<input type="text" class="form-control <?php echo($value[4] == 0 ? 'has-warning' : '') ?>" data-type="total_spent-<?php echo $value['7'];?>" id="total_spent-<?php echo $value['7'];?>" name="<?php echo 'totalSpent'. $rowCount; ?>" value="<?php echo $value[4]; ?>">
										<?php }else{ ?>
										<input type="text" class="form-control" data-type="total_spent-<?php echo $value['7'];?>" name="<?php echo 'totalSpent'. $rowCount; ?>" value="<?php echo $value[4]; ?>" disabled>
										<?php } ?>
									</td>
									<td>
										<?php if ($ready){ ?>
										<input type="text" class="form-control <?php echo($value[5] == 0 ? 'has-warning' : '') ?>" data-type="total_noches-<?php echo $value['7'];?>" id="total_noches-<?php echo $value['7'];?>" name="<?php echo 'totalNights'. $rowCount; ?>" value="<?php echo $value[5]; ?>">
										<?php }else{ ?>
										<input type="text" class="form-control" data-type="total_noches-<?php echo $value['7'];?>" name="<?php echo 'totalNights'. $rowCount; ?>" value="<?php echo $value[5]; ?>" disabled>
										<?php } ?>
									</td>
									<?php } ?>

									<td>
										<a href="<?php echo $urlTree['invitar-usuarios-2'] . '/' . $id_lista . '/?del=' . $value['7']  ?>" class="btn btn-warning"><i class="fa fa-times"></i></a>
									</td>
									<td>
										<input type="checkbox" class="markedToSend" name="marcado-<?php echo $value[7] ?>" checked='checked' value="0" data-type="marcado-<?php echo $value[7] ?>" id="marcado-<?php echo $value[7] ?>">
									</td>
								</tr>
							<?php endforeach ?>
							<input type="hidden" name="n" value="<?php echo $_SESSION['n']; ?>">
							<input type="hidden" name="idList" id="idList" value="<?php echo $id_lista ?>">
							<input type="hidden" id="wsSec" value="<?php echo $wsSec ?>">
							<input type="hidden" id="hotelId" value="<?php echo $_SESSION['h_logueado'] ?>">
							<input type="hidden" name="type" id="submitType" value="none">
							<input type="hidden" id="prod" name="prod" value="<?php echo $prod ?>">
							<input type="hidden" id="rowCount" name="rowCount" value="<?php echo $rowCount ?>">
						</table>
						<?php if($ready){
							echo '<button type="submit" value="Preparar envio de invitaciones" class="btn btn-success pull-left hotelConfirmButton" data-toggle="modal" data-target="#ready-to-send-invites-modal">'.$InvitarUsuario2Lang['Preparing delivery of'].' <span class="nValidEmails">'.$_SESSION['n'].'</span> '.$InvitarUsuario2Lang['invites'].'</button>';
						} ?>
						<?php if(!$ready){
							echo '<a href="#" class="btn btn-primary pull-left ml saveListBtn saveList" title="save draft">Save this list</a>';
						}?>
						<?php include TEMPLATES . 'paginacion-template-alldirs.php'; ?>
					</div>
					<input type="hidden" id="archivo" name="archivo" value="<?php echo $nombre_archivo ?>">
				</form>
			</div>
			<div class="col-lg-12">
				<button class="addGoal btn btn-lg btn-default mt2" onclick="addNewLine()"><i class="fa fa-plus"></i> <?php echo $InvitarUsuario2Lang['Add guest'] ?></button>
			</div>
		</div>
	</div>
</div>
<form name="filtersForm" class="filtersForm" action="<?php echo $urlTree['invitar-usuarios-2'].'/'.$url['dir2'].'/' ?>" method="post">
	<input type="hidden" name="points2" id="points2" value="10000">
	<input type="hidden" name="points1" id="points1" value="0">
	<input type="hidden" name="nights2" id="nights2" value="365">
	<input type="hidden" name="nights1" id="nights1" value="0">
	<input type="hidden" name="spent2" id="spent2" value="1000000">
	<input type="hidden" name="spent1" id="spent1" value="0">
    <input type="hidden" name="lang" value="<?php echo (!empty($_POST['lang'])?$_POST['lang']:'')?>">
	<input type="hidden" name="formFilters" value="true">
</form>
<?php include TEMPLATES. 'save-guest-list-modal.php' ?>
<?php include VIEWS . '/templates/ready-to-send-invites-modal.php'; ?>
<script src="<?php echo DIR_JS ?>feedback-errors.js"></script>
<script type="text/javascript" src="<?php echo DIR_JS?>invitar-usuario-2.js"></script>
<script>
	$(document).ready(function(){
		$('.saveListBtn').addClass("dnone");
		$('.sendInviteBtn').button();
		$('.saveListBtn').click(function(e){
			e.preventDefault();
			$('#saveGuestListModal').modal('show');
		})
//init hidden fields
$('.invite-field').change(function(){
	var fieldValue = $(this).val();
	var fieldName = $(this).attr('name');
	$('#saveListModalForm').find('input[name='+fieldName+']').val(fieldValue);
})
setTimeout(
	function()
	{
		$('#sendInviteListForm .has-warning').first().focus();
	}, 1000);

$("#alertInvalidEmails").hide();

$('.slidePointsRange').slider({
	range: true,
	min: 0,
	max: 10000,
	values:[0,10000],
	step:10,
	create: function(event,ui){
		if($("#points2").val() == 0){
			$(this).css('border', '1px solid #ffba00');
			$(this).children('.ui-slider-range').css('background', '#ffba00');
			$(this).children('.ui-slider-handle').css('background', '#ffba00');
			$(this).children('.ui-slider-handle').css('border', '1px solid #ffba00');
		}else{
			$(this).css('border', '1px solid #ceeef7');
			$(this).children('.ui-slider-range').css('background', '#c1e4ef');
			$(this).children('.ui-slider-handle').css('background', '#65c3df');
			$(this).children('.ui-slider-handle').css('border', '1px solid #65c3df');
		}
	},
	slide: function (event, ui) {
		$("#slidePointsRange").val( 'From ' + ui.values[ 0 ] + " to " + ' ' + ui.values[ 1 ] + ' points' );
		$('#points1').val(ui.values[ 0 ]);
		$('#points2').val(ui.values[ 1 ]);
		if($("#points2").val() == 0){
			$(this).css('border', '1px solid #ffba00');
			$(this).children('.ui-slider-range').css('background', '#ffba00');
			$(this).children('.ui-slider-handle').css('background', '#ffba00');
			$(this).children('.ui-slider-handle').css('border', '1px solid #ffba00');
		}else{
			$(this).css('border', '1px solid #ceeef7');
			$(this).children('.ui-slider-range').css('background', '#c1e4ef');
			$(this).children('.ui-slider-handle').css('background', '#65c3df');
			$(this).children('.ui-slider-handle').css('border', '1px solid #65c3df');
		}

	}
});

$('.slideNightsRange').slider({
	range: true,
	min: 0,
	max: 365,
	values:[0,365],
	step:1,
	create: function(event,ui){
		if($("#nights2").val() == 0){
			$(this).css('border', '1px solid #ffba00');
			$(this).children('.ui-slider-range').css('background', '#ffba00');
			$(this).children('.ui-slider-handle').css('background', '#ffba00');
			$(this).children('.ui-slider-handle').css('border', '1px solid #ffba00');
		}else{
			$(this).css('border', '1px solid #ceeef7');
			$(this).children('.ui-slider-range').css('background', '#c1e4ef');
			$(this).children('.ui-slider-handle').css('background', '#65c3df');
			$(this).children('.ui-slider-handle').css('border', '1px solid #65c3df');
		}
	},
	slide: function (event, ui) {
		$("#slideNightsRange").val( 'From ' + ui.values[ 0 ] + " to " + ' ' + ui.values[ 1 ] + ' nights' );
		$('#nights1').val(ui.values[ 0 ]);
		$('#nights2').val(ui.values[ 1 ]);
		if($("#nights2").val() == 0){
			$(this).css('border', '1px solid #ffba00');
			$(this).children('.ui-slider-range').css('background', '#ffba00');
			$(this).children('.ui-slider-handle').css('background', '#ffba00');
			$(this).children('.ui-slider-handle').css('border', '1px solid #ffba00');
		}else{
			$(this).css('border', '1px solid #ceeef7');
			$(this).children('.ui-slider-range').css('background', '#c1e4ef');
			$(this).children('.ui-slider-handle').css('background', '#65c3df');
			$(this).children('.ui-slider-handle').css('border', '1px solid #65c3df');
		}

	}
});

$('.slideSpentRange').slider({
	range: true,
	min: 0,
	max: 1000000,
	values:[0,1000000],
	step:10,
	create: function(event,ui){
		if($("#spent2").val() == 0){
			$(this).css('border', '1px solid #ffba00');
			$(this).children('.ui-slider-range').css('background', '#ffba00');
			$(this).children('.ui-slider-handle').css('background', '#ffba00');
			$(this).children('.ui-slider-handle').css('border', '1px solid #ffba00');
		}else{
			$(this).css('border', '1px solid #ceeef7');
			$(this).children('.ui-slider-range').css('background', '#c1e4ef');
			$(this).children('.ui-slider-handle').css('background', '#65c3df');
			$(this).children('.ui-slider-handle').css('border', '1px solid #65c3df');
		}
	},
	slide: function (event, ui) {
		$("#slideSpentRange").val( 'From ' + ui.values[ 0 ] + " to " + ' ' + ui.values[ 1 ] + ' spent' );
		$('#spent1').val(ui.values[ 0 ]);
		$('#spent2').val(ui.values[ 1 ]);
		if($("#spent2").val() == 0){
			$(this).css('border', '1px solid #ffba00');
			$(this).children('.ui-slider-range').css('background', '#ffba00');
			$(this).children('.ui-slider-handle').css('background', '#ffba00');
			$(this).children('.ui-slider-handle').css('border', '1px solid #ffba00');
		}else{
			$(this).css('border', '1px solid #ceeef7');
			$(this).children('.ui-slider-range').css('background', '#c1e4ef');
			$(this).children('.ui-slider-handle').css('background', '#65c3df');
			$(this).children('.ui-slider-handle').css('border', '1px solid #65c3df');
		}

	}
});

});
</script>