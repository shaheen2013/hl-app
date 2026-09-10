<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<?php include LANG . $_SESSION['userLang'] . '/referrals.php' ?>
<div class="overlayer"></div>
<div class="ripple">
    <h4><strong>Downloading CSV, please wait</strong></h4>
    <img src="<?php echo DIR_IMG . 'ripple.gif' ?>" alt="loaded spinner" width="100" height="100">
    <p><strong>When downloaded, you can refresh the page</strong></p>
</div>
<div id="wrapper">
	<?php include TEMPLATES . 'hotel-sidebar.php'; ?>
	<div id="page-content-wrapper">
        <div class="top-bar">
            <?php include TEMPLATES . 'referrals-menu.php' ?>
            <img class="topbarLogo visible-xs" src="<?php echo DIR_IMG . 'topbar-logo-xs.png' ?>" width="77" height="70"
                 alt="top bar logo">
        </div>
		<div class="utility-bar">
			<div class="row">
				<div class="col-lg-12">
					<h1 class="pull-left"><i class="fa fa-truck"></i> <?php echo $ReferralsLang['Referrers tracking'] ?> <?php echo '('.array_get($arrayUsuarios ,'0.total_shares', 0).')' ?></h1>
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
				<?php if(!empty($arrayUsuarios)){ ?>
					<form action="<?php echo $url['dir1'] ?>/" class="form-inline pull-left">
						<div class="form-group">
							<input type="text" class="form-control" name="search" id="cuponMainSearch" placeholder="<?php echo $ReferralsLang['Search by user name or code'] ?>">
						</div>
						<button type="submit" class="btn btn-primary">Search</button>
					</form>
<!--					    <a href="--><?php //echo $url['dir1'] ?><!--/?action=downloadcsv" class="pull-right btn btn-primary download-csv"><i class="fa fa-download" aria-hidden="true"></i> --><?php //echo $ReferralsLang['Download CSV'] ?><!--</a>-->
				<div class="clearfix"></div>
				<div class="table-responsive mt relative">
					<table class="table table-striped">
						<tr class="table-header">
							<td>
								<span class="pull-left"><?php echo $ReferralsLang['Referrer'] ?></span> <a href="<?php echo $urlTree['referrals'] ?>/?ord=nombre" title="sort"><i class="fa fa-sort pull-left pl"></i></a>
							</td>
							<td>
								<span class="pull-left">Facebook Friends <span class="hasTooltip naranja" data-toggle="tooltip" data-placement="top" title="Followers and friends in social media networks"><i class="fa fa-question-circle"></i>
								</span></span> <a href="<?php echo $urlTree['referrals'] ?>/?ord=fb_friends" title="sort"><i class="fa fa-sort pull-left pl"></i></a>
							</td>
							<td>
								<span class="pull-left"><?php echo $ReferralsLang['# social media shares'] ?> <span class="hasTooltip naranja" data-toggle="tooltip" data-placement="top" title="Shares maded by your guests in social media networks of their opinions about your hotel"><i class="fa fa-question-circle"></i>
								</span></span><a href="<?php echo $urlTree['referrals'] ?>/?ord=shares" title="sort"><i class="fa fa-sort pull-left pl"></i></a>
							</td>
							<td class="text-center">
								<span><?php echo $ReferralsLang['Actions'] ?></span>
							</td>
						</tr>
						<?php foreach ($arrayUsuarios as $usuario) { ?>
						<tr class="table-row">
							<td>
								<?php if (!empty($usuario['img'])){?>
								<img class="img-circle img-thumbnail" src="<?php echo $usuario['img'] ?>" alt="user avatar" width="50" height="50">
								<?php }else {?>
								<img class="img-circle img-thumbnail" src="<?php echo DIR_IMG;?>avatar.jpg" alt="user avatar" width="50" height="50">
								<?php } ?>
								<span class="pl"><?php echo $usuario['nombre'] ?></span>
							</td>
							<td>
								<?php echo $usuario['fb_friends'] ?>
							</td>
							<td>
								<?php echo $usuario['shares'] ?> <span class="hasTooltip naranja sharesContentPopover" data-toggle="tooltip" title="Pre stay shares: <?php echo $usuario['shares_prestay'] ?>, Stay shares: <?php echo $usuario['shares_stay'] ?>, Post stay shares: <?php echo $usuario['shares_poststay'] ?>"><i class="fa fa-question-circle"></i>
								</span>
							</td>
							<td>
								<div class="text-center">
									<a href="mailto:<?php echo $usuario['email'] ?>" class="btn btn-default hasTooltip btn-sm" data-toggle="tooltip" data-placement="left" title="<?php echo $ReferralsLang['Send a mail to user'] ?>"><i class="fa fa-envelope-o"></i></a>
									<?php //if($usuario['id_facebook'] != '') {?>
									<!--<a href="https://facebook.com/<?php //echo $usuario['id_facebook'] ?>" class="btn btn-facebook hasTooltip" data-toggle="tooltip" data-placement="left" title="Ver el perfil de <?php //echo $usuario['nombre'] ?> en facebook" target="_blank"><i class="fa fa-facebook"></i></a>-->
									<?php //} ?>
								</div>
							</td>
						</tr>
						<?php } ?>
					</table>
					<?php include TEMPLATES . 'paginacion-template.php'; ?>
				</div>
				<?php } else { ?>
					<div class="text-center mt2 container no-data-msg">
						<i class="fa fa-truck grisClaro fa-5x"></i>
						<h2><?php echo $ReferralsLang['There´re no referral activity at this moment'] ?></h2>
						<h4><?php echo $ReferralsLang['By the way, ¿Are your customers sharing his opinion about your hotel?'] ?></h4>
						<a href="<?php echo $urlTree['stay-share'] . '/' .  $guidHotel . '/'?>" class="btn btn-lg btn-success mt2"><?php echo $ReferralsLang['Start inviting guests to your hotel'] ?></a>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
<script>
	$(function (){
		$('.hasPopover').mouseover(function (){
			$(this).popover('show');
		});
		$('.hasPopover').mouseout(function (){
			$(this).popover('hide');
		});	
	});

    $('.download-csv').click(function(){
        $(this).addClass('disabled');
        $('.overlayer,.ripple').show();
    })
</script>