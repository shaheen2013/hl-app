<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<?php include LANG . $_SESSION['userLang'] . '/referral-share.php' ?>
<style type="text/css" media="screen">
	#uvTab{
		display:none !important;
	}
</style>
<div class="referral-share">
	<!-- <img src="<?php echo($datosHotel['fotoBg'] != '0'? DIR_IMG_FICHA_HOTEL . $datosHotel['id'] . '/fotoBg/' . $datosHotel['fotoBg'] : DIR_IMG . 'login-bg.jpg') ?>" alt="Background image of hotel" class="rs-bg"> -->
	<img src="<?php echo($datosHotel['fotoBg'] != '0'? $datosHotel['fotoBg'] : DIR_IMG . 'login-bg.jpg') ?>" alt="Background image of hotel" class="rs-bg">
	<div class="dlpOverlayer"></div>
	<img src="<?php echo(array_get($datosHotel, 'logo', DIR_IMG . 'logo.jpg'))?>" alt="Hotel logo" class="img-thumbnail img-circle rs-logo rs-size" width="100" height="100">
	<div class="rs-text">
		<h2 class="mt0"><?php echo $datosHotel['hotelName'] ?></h2>
		<h1 class="rs-size"><?php echo $referralShareLang["Win"] ?></h1>
		<p class="rs-size"><?php echo $referralShareLang["Share"] ?></p>
		<div class="rs-links">
			<button class="btn btn-success btn-lg rs-share rs-size"><i class="fa fa-share"></i> <?php echo $referralShareLang['Share and win deals!'] ?></button>
			<div class="clearfix"></div>
			<p><small><a class="deals-modal rs-size" href="#" title="deals"><?php echo $referralShareLang['See which deals you could win sharing!'] ?></a></small></p>
		</div>
	</div>
	
	<!-- /.Share Modal -->
	<div class="modal fade" id="rsModalShare">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title"><?php echo $referralShareLang['We need some info'] ?></h4>
				</div>
				<div class="modal-body">
					<form name="shareForm" id="rs-share-form" action="<?php echo $urlTree['referral-share-step-2'] ?>" method="POST">
						<div class="form-group">
							<label for="rs-email"><?php echo $referralShareLang['Your email address'] ?></label>
							<div class="input-group">
								<div class="input-group-addon">@</div>
								<input type="email" class="form-control" name="email" id="rs-email" placeholder="e-mail..." required>
							</div>
							<p class="help-block"><?php echo $referralShareLang['To keep you updated about deals you win.'] ?></p>

							<label for="rs-name"><?php echo $referralShareLang['Your Name'] ?></label>
							<div class="input-group">
								<div class="input-group-addon"><i class="fa fa-user"></i></div>
								<input type="text" class="form-control" id="rs-name" name="name" placeholder="<?php echo $referralShareLang['name...'] ?>" required>
							</div>
							<p class="help-block"><?php echo $referralShareLang['We love to be kind in our messages.'] ?></p>
						</div>
                        <input type="hidden" id="rs-hotelId" value="<?php echo $guidHotel ?>" >
						<button class="btn btn-default btn-lg btn-primary btn-block btn-rs-share"><?php echo $referralShareLang['Ready to share!'] ?></button>
					</form>				
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

	<!-- /.Deals Modal -->
	<div class="modal fade" id="rsModalDeals">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title"><?php echo $referralShareLang['Check deals you can win'] ?></h4>
				</div>
				<div class="modal-body">
					<div class="list-group">
					<?php foreach ($goalsHotel as $goal) { ?>
						<div class="list-group-item">
							<h4 class="list-group-item-heading"><?php echo $goal['nombre'] ?></h4>
							<p class="list-group-item-text"><?php echo $referralShareLang['Bring'] .' '.$goal['n_referrals'] .' '. $referralShareLang['friends']?></p>
						</div>
					<?php } ?>
					</div>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
</div>

<script>

	$(document).ready(function(){
		setTimeout(function(){
			$('.rs-logo').removeClass('rs-size');
		},0);
		setTimeout(function(){
			$('h1').removeClass('rs-size');
		},75);
		setTimeout(function(){
			$('p').removeClass('rs-size');
		},125);
		setTimeout(function(){
			$('button').removeClass('rs-size');
		},175);
		setTimeout(function(){
			$('.deals-modal').removeClass('rs-size');
		},200);

		//Get parameters from URL
		var QueryString = function () {
			var query_string = {};
			var query = window.location.search.substring(1);
			var vars = query.split("&");
			for (var i=0;i<vars.length;i++) {
				var pair = vars[i].split("=");
		        // If first entry with this name
		        if (typeof query_string[pair[0]] === "undefined") {
		        	query_string[pair[0]] = pair[1];
		        // If second entry with this name
		    	} else if (typeof query_string[pair[0]] === "string") {
		    	var arr = [ query_string[pair[0]], pair[1] ];
		    	query_string[pair[0]] = arr;
		        // If third or later entry with this name
		    	} else {
		    	query_string[pair[0]].push(pair[1]);
		    	}
			} 

			return query_string;

		} ();

	$('.rs-share').click(function(){
		$('#rsModalShare').modal('show'); 
	})

	$('.deals-modal').click(function(e){
		e.preventDefault();
		$('#rsModalDeals').modal('show'); 
	})

	$('.btn-rs-share').click(function(e){
		
		e.preventDefault();
		$('#rsModalShare').modal('hide'); 
		if(QueryString.o == 'user'){
			var email = $("#rs-email").val();
			var name = $("#rs-name").val();
			var hotelId = $("#rs-hotelId").val();
			//alert(email+' : '+name+' : '+hotelId);
			$.ajax({ url: "/lib/webservices/referral-share-post-ws.php",
				data: 'email='+ email +'&name='+name+'&hotelId='+hotelId,
				type: 'POST',
				success: function(output) {
					var json = JSON.parse(output);
					if( json['code']=='sent' ){
						//ok
						var url = '<?php echo BASE_PATH.$urlTree["referral-share-step-2"] ?>/'+json['token']+'/';
						window.location.replace(url);
					}else{
						//ko
					}
				}
			});
			//document.shareForm.submit();
		}else if(QueryString.o == 'hotelDesk'){
			
			var email = $("#rs-email").val();
			var name = $("#rs-name").val();
			var hotelId = $("#rs-hotelId").val();
			$.ajax({ url: "/lib/webservices/referral-share-post-ws.php",
				data: 'email='+ email +'&name='+name+'&hotelId='+hotelId,
				type: 'POST',
				success: function(output) {
					var json = JSON.parse(output);
					if(json['error']=='200'){
						//ok
					}else{
						//ko
					}
				}
			});
			
			$('.rs-text, .rs-share, .deals-modal').fadeOut('fast');
			setTimeout(function(){
				$('.rs-text > h1').text('<?php echo $referralShareLang["Thanks"] ?>');
				$('.rs-text > p').text('<?php echo $referralShareLang["thanksText"] ?>');
				$('.rs-text').fadeIn('fast');
			}, 500);

			setTimeout(function(){
				location.reload();
			},3000);
		}else{
			//TODO Redirect if not o set
		}
	})

})
</script>