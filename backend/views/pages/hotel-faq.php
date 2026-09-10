<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<div id="wrapper">
	<?php if(!empty($_SESSION['h_logueado'])){
		include TEMPLATES . 'hotel-sidebar.php';
	}else if(!empty($_SESSION['staff_logueado'])){
		include TEMPLATES . 'check-sidebar.php';
	} ?>
    <div class="top-bar">
        <img class="topbarLogo visible-xs visible-sm" src="<?php echo DIR_IMG . 'topbar-logo-xs.png' ?>" width="77"
             height="70" alt="top bar logo">
    </div>
	<div class="container">
			<div class="user-utility-bar">
		<h1 class="pull-left"><i class="fa fa-question"></i> Hotelier frequently asked questions</h1>
	</div>
	</div>

	<div class="container user-faq-container">
		<div class="row">
			<div class="col-lg-12">
				<div class="input-group mb2">
					<span class="input-group-addon"><i class="fa fa-search"></i></span>
					<input type="text" id="tags" class="form-control input-lg mb2" placeholder="Search for a specific topic...">
				</div>
			</div>
		</div>
		<div class="row mt2">
			<div class="col-lg-4">
				<ul class="nav nav-pills nav-stacked">
					<li> <a href="question1" class="active">Is there anything important to do before inviting guests to my loyalty program?</a></li>
					<li><a href="question2">How many reward offers I am able to publish?</a></li>
					<li><a href="question3">What type of reward offers I am able to publish?</a></li>
					<li><a href="question4">What are the difference between red and blue reward points?</a></li>
					<li><a href="question5">How can I decide the costs in rewards for red or blue reward campaigns?</a></li>
					<li><a href="question6">How can I start adding new guests on my loyalty program?</a></li>
				</ul>
			</div>
			<div class="col-lg-8">
				<article class="question" id="question1">
					<h2>Is there anything important to do before inviting guests to my loyalty program?</h2>
					<p>It is key that guests see value on your loyalty platform before you invite them. Once they access

						the platform, the first thing they will see are all your reward offers. Therefore, we recommend 

						you to create as many reward offers as possible, so your guests can see real value and will engage 

						immediately. We recommend you to have at least 10 to 15 reward offers before inviting any guest.
					</p>
				</article>
				<article class="question" id="question2">
					<h2>How many reward offers I am able to publish?</h2>
					<p>There is no limitation on how many reward offers (campaigns) you can publish at the same time. The

						more the better, and make sure you publish tons of them on each category, so your loyalty program 

						will look even more valuable for your guests.</p>
				</article>
					<article class="question" id="question3">
						<h2>What type of reward offers I am able to publish?</h2>
						<p>We have set 4 main categories: free nights, stay discounts, upgrades and check-in services.
							<br>
							Check-in services have its own categories and subcategories, which cover most of the 

							services offered by city hotels and resorts. However, in case you have a service that cannot 

							be adequately classified on any of the categories available, please send us an email to 

							customerservice@hotelinking.com, and we will be pleased to include it along with the existing ones.</p>
					</article>
						<article class="question" id="question4">
							<h2>What are the difference between red and blue reward points?</h2>
							<p>Red reward points are earned by your guests upon check-out. Red reward points are earned based on

								total spend per stay per guest. Hotelinking's system automatically transfers red reward points to your 

								guest balance at an exchange rate of 1 USD – 1 red reward point. It simple and easy to understand 

								by your guests. Red reward points can only be redeemed on reward offers published by your hotel 

								or chain. In case a guest does not plan to come back to your hotel or chain in the near future, points 

								can easily be transferred to a friend or follower (each guest can transfer points from their reward points 

								management menu).<br>Blue reward points are earned upon check-out. Blue reward points work as discovery points, and

								can be used to redeem one reward offer (free nights, stay discounts, and room upgrades only) from 

								a hotel they have never been before. Blue rewards are earned by each hotel review they place 

								(100), by sharing their hotel experience with their friends and followers on social media (150), and 

								by each referral that ever checks-in at your hotel or chain (200 per referral). Blue reward points are 

								automatically added to their balance.</p>
							</article>
							<article class="question" id="question5">
								<h2>How can I decide the costs in rewards for red or blue reward campaigns?</h2>
								<p>Red reward campaigns are designed to attract and retain loyal or existing guests on your database.

									We also call this type of offers “retention campaigns”. You can create any of the following and with 

									no limitation: free nights, stay discounts, room upgrades and check-in services (only redeemable 

									between check-in and check-out). The cost is set based on your expectations, and you are the one 

									ultimately deciding what the cost in red reward points should be. While you are designing your 

									campaign, you will be able to set the minimum spend in USD a guest should have previously made, 

									in order to access the reward offer to be published. Once the reward offer has been published, your 

									guests will be automatically notified by e-mail. <br>Blue reward campaigns are designed to attract new valuable guests. Since Hotelinking is a universal

									hotel loyalty program, guests use a unique platform to manage reward points from all hotels using 

									Hotelinking as their loyalty solution. <br>You have the chance to publish blue reward campaigns to attract guests on Hotelinking, so they can 

									discover your hotel and become loyal guest right after. If a Hotelinking user is attracted by any of 

									your blue reward campaigns, it will be a guest acquisition to your favor. <br>The way cost is established for blue reward campaigns differs from the way cost is established for red reward campaigns. We use 

									an algorithm that automatically calculates the cost of an offer for you. We take into account key 

									factors that automatically assess the value of a campaign. Only guests with a blue reward point 

									balance that exceeds the cost established, will be able to redeem the offer. It is a brilliant manner 

									to qualify valuable guests for you, since earning blue rewards involves high valuable tasks (sharing 

									on social media, a high number of referrals, etc). A guest with a high number of blue reward points, 

									generally means he or she has a social media account with a high volume of friends of followers. As 

									a result, you will be attracting valuable guests that can potentially refer many other guests to your 

									hotel or chain.</p>
								</article>
								<article class="question" id="question6">
									<h2>How can I start adding new guests on my loyalty program?</h2>
									<p>There are two ways to add guests on Hotelinking. The first one helps you import large databases 

										from other systems such as PMS (Property Management System). You should ask your vendor to 

										export the existing guest database from the system (if this is not a feature the PMS allows to do by 

										yourself). Once exported in a .txt file or excel file, you will be able to easily import up to 50k guest 

										contacts at once. Once imported, Hotelinking will let you send an invitation to each guest on the list 

										to join your loyalty program. <br>The second way to add new guests on your loyalty program is by simply checking them in from the 

										“guest check-in menu”. You will use this method to invite new guests that have never been at your 

										hotel or chain before, or in case you a running a new property that does not have an existing guests 

										database to import. Just by typing in their email, Hotelinking will automatically send an invite to join 

										your loyalty program.</p>
									</article>
									<a href="#" class="hasTooltip backtotop-btn text-center" data-toggle="tooltip" data-placement="top" title="Back to top"><i class="fa fa-arrow-up fa-2x"></i></a>
								</div>
							</div>
						</div>
					</div>
					<script>
						$(document).ready(function(){

							$('.nav a').click(function(e){
								var thisId = $(this).attr('href');
								e.preventDefault();
								$('.nav li').removeClass('active');
								$(this).parent().addClass('active');
								$('html, body').animate({
									scrollTop: $('#'+thisId).offset().top - 180
								}, 500);
							})
							var availableTags = [];
							$('.question').each(function(){
								var question = $('h2', this).text();
								availableTags.push(question);
							});

							$( "#tags" ).autocomplete({
								source: availableTags,
								minLength: 3,
								select: function(event, ui){
									var topic = ui.item.label;
									$('.question').each(function(){
										var question = $('h2', this).text();
										if(question == topic){
											var questionId = $(this).attr('id');
											$('html, body').animate({
												scrollTop: $('#'+questionId).offset().top - 180
											}, 500);
											$('.nav li').removeClass('active');
											$('.nav li').children('a[href*="'+questionId+'"]').parent().addClass('active');
										}
									});
								}
							});

							$('.backtotop-btn').click(function(e){
								e.preventDefault();
								$('html, body').animate({
									scrollTop: $('body').offset().top
								}, 500);
							})
						});
</script>