<?php include LANG . $_SESSION['userLang'] . '/review-urls.php' ?>
<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>
<div id="wrapper">
	<?php include TEMPLATES . 'hotel-sidebar.php'; ?>
	<div id="page-content-wrapper">
        <div class="top-bar">
            <?php include TEMPLATES . 'hotel-profile-menu.php'; ?>
            <img class="topbarLogo visible-xs" src="<?php echo DIR_IMG . 'topbar-logo-xs.png' ?>" width="77" height="70"
                 alt="top bar logo">
        </div>
		<div class="utility-bar">
			<div class="col-lg-12">
				<h1 class="pull-left"><i class="fa fa-envelope-o"></i> <?php echo $reviewUrlsLang['Reviews automation'] ?></h1>
				<div class="breadcrumbs pull-right">
					<ul>
						<?php include (TEMPLATES .'breadcrumbs.php'); ?>
					</ul>
				</div>
			</div>
		</div>
		<div class="mainContent" id="fullContainer">
			<!-- Time to send panel -->
			<div class="col-lg-10 mt2">
				<form action="<?php echo $url['dir1'] ?>" method="post">
					<div class="col-lg-12">
						<div class="panel panel-default noPadding customizedSatisfactionElement">
							<div class="panel-heading">
								<?php echo $reviewUrlsLang['Días que han de pasar'] ?>
							</div>
							<div class="panel-body">
								<div class="row form-row">
									<div class="col-lg-12">
										<div class="input-group">
											<input onclick="showReviewConfiguration()"
													id="sendAfterWifiConnection" type="radio"
													name="reviewSendType"
													aria-label="Checkbox for following text input" value="after_wifi" <?php echo array_get($reviewConfig, 'send_type') === 'after_wifi' ? "checked" : "" ?>>
											<label style="margin-left: 1rem"
													for="sendAfterWifiConnection"><?php echo $reviewUrlsLang['after_wifi'] ?></label>
										</div>
										<div class="input-group">
											<input onclick="showReviewConfiguration()"
													id="sendAfterSatisfaction" type="radio"
													name="reviewSendType"
													aria-label="Checkbox for following text input" value="after_satisfaction" <?php echo array_get($reviewConfig, 'send_type') === 'after_satisfaction' ? "checked" : "" ?>>
											<label style="margin-left: 1rem"
													for="sendAfterSatisfaction"><?php echo $reviewUrlsLang['after_satisfaction'] ?></label>
										</div>
										<?php if ($portalProProductActive) { ?>
											<div class="input-group">
												<input onclick="showReviewConfiguration()"
													id="sendAfterCheckout" type="radio"
													name="reviewSendType"
													aria-label="Checkbox for following text input" value="after_check_out" <?php echo array_get($reviewConfig, 'send_type') === 'after_check_out' ? "checked" : "" ?>>
												<label style="margin-left: 1rem"
													for="sendAfterCheckout"><?php echo $reviewUrlsLang['after_check_out'] ?></label>
											</div>
										<?php } ?>
									</div>
								</div>
								<div class="row form-row" id="sendDates">
									<div class="col-lg-3">
										<div class="input-group">
											<input type="text" name="diasEnvioReview" id="inputDias" class="form-control" value="<?php echo $reviewConfig['send_after_days']?>">
											<div class="input-group-addon"><?php echo $reviewUrlsLang['días'] ?></div>
										</div>
									</div>
								</div>
								<div class="row form-row" id="ignoreSatisfaction">
									<div class="col-lg-12">
										<div class="input-group">
											<input type="checkbox" name="ignoreRating" <?php echo (isset($reviewConfig['ignore_rating']) && $reviewConfig['ignore_rating'] == 1) ? "checked" : "" ?> >
											<?php echo $reviewUrlsLang['ignoreRating'] ?></span>
										</div>	
									</div>
								</div>
								<?php if (!$portalProProductActive) { ?>
									<div class="alert alert-warning mt2" role="alert">
										<div class="row">
											<div class="form-group col-md-6">
												<input type="radio" aria-label="Checkbox for following text input" disabled>
												<label style="margin-left: 1rem"><?php echo $reviewUrlsLang['after_check_out'] ?></label>
											</div>
										</div>
										<?php echo $reviewUrlsLang['no portal pro activated message'] ?>
									</div>
								<?php } ?>
								<input type="submit" value="Guardar los cambios" class="btn btn-success mt" name="hotelConfirmButton">
							</div>
						</div>
					</div>
				</form>
			</div>
			<!-- End time to send panel -->

			<div class="col-lg-10 mt2">
				<form action="" method="post">
					<div class="col-lg-12">
						<div class="panel panel-default noPadding">
							<div class="panel-heading">
                                <select id="tripgoogle" name="tripgoogle" class="btn btn-default hasTooltip btn-sm" style="height: 30px;">
                                    <option value="url-tripadvisor">
                                        <?php echo $reviewUrlsLang['Url de tripadvisor']; ?>
                                    </option>
                                    <option value="url-google" <?php echo !empty($urlsReviews['google']) ? 'selected="selected"' : ""; ?>>
                                        <?php echo $reviewUrlsLang['Url de google']; ?>
                                    </option>
                                </select>
    							</div>
							<div class="panel-body url-tripadvisor" <?php echo !empty($urlsReviews['google']) ? 'style="display: none;"' : ""; ?>>
								<div class="input-group">
									<span class="input-group-addon">www.tripadvisor.com</span>
									<input type="text" name="tripadvisor" class="form-control" id="tripadvisor" placeholder="/restOfUrl.../..." value="<?php echo $urlsReviews['tripadvisor'] ?>">
								</div>
							</div>
                            <div class="panel-body url-google" <?php echo empty($urlsReviews['google']) ? 'style="display: none;"' : ""; ?>>
								<div class="input-group">
									<span class="input-group-addon">www.google.com</span>
									<input type="text" name="google" class="form-control" id="google" placeholder="/restOfUrl.../..." value="<?php echo $urlsReviews['google'] ?>">
								</div>
							</div>
						</div>
					</div>

					<div class="col-lg-12">
						<div class="panel panel-default noPadding">
							<div class="panel-heading">
								<?php echo $reviewUrlsLang['Url de HolidayCheck'] ?>
							</div>
							<div class="panel-body">
								<div class="input-group">
									<span class="input-group-addon">www.holidaycheck.de</span>
									<input type="text" name="holidaycheck" class="form-control" id="holidaycheck" placeholder="/restOfUrl.../..." value="<?php echo $urlsReviews['holidaycheck'] ?>">
								</div>
							</div>
						</div>
					</div>

					<div class="col-lg-12">
						<div class="panel panel-default noPadding">
							<div class="panel-heading">
								<?php echo $reviewUrlsLang['Url de Yelp'] ?>
							</div>
							<div class="panel-body">
								<div class="input-group">
									<span class="input-group-addon">www.yelp.com</span>
									<input type="text" name="yelp" class="form-control" id="yelp" placeholder="/restOfUrl.../..." value="<?php echo $urlsReviews['yelp'] ?>">
								</div>
							</div>
						</div>
					</div>

					<div class="col-lg-12">
						<div class="panel panel-default noPadding">
							<div class="panel-heading">
								<?php echo $reviewUrlsLang['Url de TopHotels'] ?>
							</div>
							<div class="panel-body">
								<div class="input-group">
									<span class="input-group-addon">tophotels.ru</span>
									<input type="text" name="tophotels" class="form-control" id="tophotels" placeholder="/restOfUrl.../..." value="<?php echo $urlsReviews['tophotels'] ?>">
								</div>
							</div>
						</div>
					</div>

					<div class="col-lg-12">
						<div class="panel panel-default noPadding">
							<div class="panel-heading">
								<?php echo $reviewUrlsLang['Url de Zoover'] ?>
							</div>
							<div class="panel-body">
								<div class="input-group">
									<span class="input-group-addon">www.zoover.com</span>
									<input type="text" name="zoover" class="form-control" id="zoover" placeholder="/restOfUrl.../..." value="<?php echo $urlsReviews['zoover'] ?>">
								</div>
							</div>
						</div>
					</div>

					<?php foreach($idiomas as $idioma){ ?>
					<div class="col-lg-12">
						<div class="panel panel-default noPadding">
							<div class="panel-heading">
								<?php echo $reviewUrlsLang['Url Personalizada en'] ?> <?php echo $idioma['country'] ?>
							</div>
							<div class="panel-body">
								<input type="text" name="custom_<?php echo $idioma['lang'] ?>" class="form-control" id="urlPersonalizada" placeholder="Url..." value="<?php echo $urlsReviews['custom_'.$idioma['lang']] ?>">
							</div>
						</div>
					</div>
					<?php } ?>
					<div class="col-lg-12">
						<input value="Guardar" class="btn btn-success" name="save" type="submit">
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<script>
    $(document).ready(function() {
        $('#tripgoogle').change(function() {
            var element = 'div.' + this.value;

            $('div.url-tripadvisor').hide();
            $('div.url-google').hide();

            $(element).show();
        });
    });
	
	showReviewConfiguration();
	function showReviewConfiguration() {
			var reviewSendType = $("[name='reviewSendType']:checked").val();
			if (reviewSendType == "after_check_out") {
				$("#ignoreSatisfaction").show();
			} else {
				$("#ignoreSatisfaction").hide();
			}
		}
</script>
