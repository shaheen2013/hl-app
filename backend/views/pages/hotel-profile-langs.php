<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<?php include LANG . $_SESSION['userLang'].'/hotel-profile-langs.php' ?>
<div id="wrapper">
	<?php include TEMPLATES . 'hotel-sidebar.php'; ?>
	<div id="page-content-wrapper">
        <div class="top-bar">
            <?php include TEMPLATES . 'hotel-profile-menu.php'; ?>
            <img class="topbarLogo visible-xs" src="<?php echo DIR_IMG . 'topbar-logo-xs.png' ?>" width="77" height="70"
                 alt="top bar logo">
        </div>
		<div class="utility-bar mb15">
			<div class="col-lg-12">
				<h1 class="pull-left"><i class="fa fa-flag-o"></i> <?php echo $hotelProfileLang['Activate languages'] ?></h1>
				<div class="breadcrumbs pull-right">
					<ul>
						<?php include (TEMPLATES .'breadcrumbs.php'); ?>
					</ul>
				</div>
			</div>
		</div>
		<div class="col-lg-8">
			<div class="panel panel-default noPadding">
				<div class="panel-heading">
					<?php echo $hotelProfileLang['Select your application language'] ?>
				</div>
				<div class="panel-body">
					<form method="POST" name="yourLangSelection" class="mt2">
						<ul class="flagList">
							<?php foreach ($systemLangList as $lang) { ?>
							<li>
								<div class="radio">
									<label>
										<input type="radio" name="lang" value="<?php echo $lang['id'] ?>"<?php if($langHotel==$lang['lang'])echo 'checked' ?>> <span class="pl">  <img src="<?php echo BASE_PATH . DIR_IMG . 'flags/' . $lang['img'] ?>" alt="<?php echo $lang['country'] ?> flag"> <?php echo $lang['country'] ?></span>
									</label>
								</div>
							</li>
							<?php } ?>
						</ul>
						<input class="btn btn-success" name="yourLangSelection" type="submit" value="<?php echo $hotelProfileLang['Select your language'] ?>">
					</form>
				</div>
			</div>
			<div class="panel panel-default noPadding">
				<div class="panel-heading">
					<?php echo $hotelProfileLang['Select the languages you wish to use in your <strong>reward offers</strong> and <strong>landing page</strong>'] ?>
				</div>
				<div class="panel-body">
					<form method="POST" name="langSelection" class="mt2">
						<ul class="flagList">
							<?php foreach ($contentLangList as $lang) { ?>
							<li>
								<div class="checkbox">
									<label>
										<input type="checkbox" name="<?php echo $lang['country'] ?>" value="<?php echo $lang['id'] ?>"<?php if(!empty($langsHotel[$lang['id']]))echo 'checked' ?>> <span class="pl">  <img src="<?php echo BASE_PATH . DIR_IMG . 'flags/' . $lang['img'] ?>" alt="<?php echo $lang['country'] ?> flag"> <?php echo $lang['country'] ?></span>
									</label>
								</div>
							</li>
							<?php } ?>
						</ul>
						<input class="btn btn-success" name="langSelection" type="submit" value="<?php echo $hotelProfileLang['Activate languages buton'] ?>">
					</form>
				</div>
			</div>
		</div>
	</div>
</div>