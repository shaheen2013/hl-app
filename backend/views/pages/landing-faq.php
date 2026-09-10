<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<?php include LANG . $_SESSION['userLang'] . '/landing-faq.php' ?>
<?php include TEMPLATES . 'landing-header.php' ?>
<div class="user-utility-bar">
	<h1 class="pull-left"><i class="fa fa-question"></i> <?php echo $LandingFaqLang['Hotelinking frequently asked question'] ?></h1>
</div>
<div class="clearfix"></div>

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
			<h4><?php echo $LandingFaqLang['Guest faq'] ?></h4>
			<ul class="nav nav-pills nav-stacked">
				<li class="active"><a href="question1"><?php echo $LandingFaqLang['What is Hotelinking?'] ?></a></li>
				<li><a href="question2"><?php echo $LandingFaqLang['How do I become a Hotelinking Member?'] ?></a></li>
				<li><a href="question3"><?php echo $LandingFaqLang['Is social media login available?'] ?></a></li>
				<li><a href="question4"><?php echo $LandingFaqLang['How do I earn reward points?'] ?></a></li>
				<li><a href="question5"><?php echo $LandingFaqLang['Are there more ways to earn rewards?'] ?> </a></li>
				<li><a href="question6"><?php echo $LandingFaqLang['Where I can get my loyalty card?'] ?></a></li>
				<li><a href="question7"><?php echo $LandingFaqLang['How many Hotelinking loyalty cards do I need?'] ?></a></li>
				<li><a href="question8"><?php echo $LandingFaqLang['How can I see all the reward points balance from all hotels I stayed?'] ?></a></li>
				<li><a href="question9"><?php echo $LandingFaqLang['How do I redeem rewards?'] ?></a></li>				
				<li><a href="question10"><?php echo $LandingFaqLang['Are points transferable?'] ?> </a></li>
			</ul>
<!-- 			<h4><?php //echo $LandingFaqLang['Hotelier faq'] ?></h4>
			<ul class="nav nav-pills nav-stacked">

			</ul> -->
		</div>
		<div class="col-lg-8">
			<article class="question" id="question1">
				<h2><?php echo $LandingFaqLang['What is Hotelinking?'] ?></h2>
				<p><?php echo $LandingFaqLang['What is Hotelinking text'] ?>
				</p>
			</article>
			<article class="question" id="question2">
				<h2><?php echo $LandingFaqLang['How do I become a Hotelinking Member?'] ?></h2>
				<p><?php echo $LandingFaqLang['How do I become a Hotelinking Member text'] ?></p>
			</article>
			<article class="question" id="question3">
				<h2><?php echo $LandingFaqLang['Is social media login available?'] ?></h2>
				<p><?php echo $LandingFaqLang['Is social media login available text'] ?></p>
			</article>
			<article class="question" id="question4">
				<h2><?php echo $LandingFaqLang['How do I earn reward points?'] ?></h2>
				<p><?php echo $LandingFaqLang['How do I earn reward points text'] ?></p>
			</article>
			<article class="question" id="question5">
				<h2><?php echo $LandingFaqLang['Are there more ways to earn rewards?'] ?></h2>
				<p><?php echo $LandingFaqLang['Are there more ways to earn rewards text'] ?></p>
			</article>
			<article class="question" id="question6">
				<h2><?php echo $LandingFaqLang['Where I can get my loyalty card?'] ?></h2>
				<p><?php echo $LandingFaqLang['Where I can get my loyalty card text'] ?></p>
			</article>
			<article class="question" id="question7">
				<h2><?php echo $LandingFaqLang['How many Hotelinking loyalty cards do I need?'] ?></h2>
				<p><?php echo $LandingFaqLang['How many Hotelinking loyalty cards do I need text'] ?></p>
			</article>
			<article class="question" id="question8">
				<h2><?php echo $LandingFaqLang['How can I see all the reward points balance from all hotels I stayed?'] ?></h2>
				<p><?php echo $LandingFaqLang['How can I see all the reward points balance from all hotels I stayed text'] ?></p>
			</article>
			<article class="question" id="question9">
				<h2><?php echo $LandingFaqLang['How do I redeem rewards?'] ?></h2>
				<p><?php echo $LandingFaqLang['How do I redeem rewards text'] ?></p>
			</article>			
			<article class="question" id="question10">
				<h2><?php echo $LandingFaqLang['Are points transferable?'] ?></h2>
				<p><?php echo $LandingFaqLang['Are points transferable text'] ?></p>
			</article>
			<a href="#" class="hasTooltip backtotop-btn text-center" data-toggle="tooltip" data-placement="top" title="Back to top"><i class="fa fa-arrow-up fa-2x"></i></a>
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