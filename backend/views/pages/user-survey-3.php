<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<?php include LANG . $_SESSION['userLang'] . '/user-survey-3.php' ?>
<?php include TEMPLATES . 'user-top-bar.php' ?>
<div class="user-utility-bar">
	<h1><i class="fa fa-check-circle-o"></i> <?php echo $UserSurvey3Lang['Thanks for making this survey!!'] ?></h1>
</div>
<div class="container text-center user-survey-3-container" id="fullContainer">
	<div class="col-lg-12">
		<?php if ($detect->isMobile() && !$detect->isTablet()) {?>
		<i class="fa fa-check-circle fa-4x verde"></i>
		<?php }else{ ?>
		<i class="fa fa-check-circle fa-6x verde"></i>
		<?php } ?>
		<h1><strong><?php echo $UserSurvey3Lang['Well Done'] ?></strong></h1>
		<h2><?php echo $UserSurvey3Lang['Invita a tus amigos a descubrir'] ?> <strong><?php echo $arrayDatosHotel['hotelName'] ?></strong> <?php echo $UserSurvey3Lang['en las redes sociales y gana aún más'] ?>  <i class="rubies rubiesHL rubix3">rubies</i></h2>
	</div>
	<div class="row">
		<div class="col-lg-12  mt4">
        	<div class="row">
                <div class="col-lg-12  mt2 text-center">
                    <form role="form" action="<?php echo $urlTree['user-survey-3'] ?>/?id=<?php echo $id_encuesta?>" method="post">
                        <div class="form-inline">
                            <?php foreach($redesSociales as $key=>$social ){?>
                            <input type="submit" value="<?php echo $key?>" name="btn-<?php echo $key?>" class="btn btn-<?php echo $key?>" <?php if($social==1)echo 'disabled="disabled" ' ?>/>
                            <?php }?>
                        </div>   
                    </form> 
                </div>
            </div>
			<?php if($nRedesSociales[0] != 0){?>
			<div class="media">
				<div class="pull-left">
					<img class="img-circle" src="<?php echo $_SESSION['image']; ?>" alt="user avatar" width="80" height="80">
				</div>
                
				<div class="media-body">
                <?php if($nRedesSociales[0] != 0){?>
					<form action="<?php echo $urlTree['user-survey-3'].'/?id='.$id_encuesta?>" method="post" class="text-left">
						<textarea name="tweet" class="tweetText form-control" onkeyup="countChar(this);"><?php //echo $UserSurvey3Lang['My experience in the'] ?> <?php //echo $arrayDatosHotel['hotelName'] ?> <?php //echo $UserSurvey3Lang['has been amazing!'] ?> </textarea>
						<div class="twitterCharCount">
							<i class="fa fa-twitter"> </i> <span id="charNum"></span>
						</div>
						<input type="hidden" name="id" value="<?php echo $id_encuesta ?>">
						<input type="submit" class="btn btn-lg btn-success mt shareBtn mb" value="<?php echo $UserSurvey3Lang['Compartir'] ?>">
					</form>
                <?php } ?>
				</div>
			</div>
			<?php }else{?>
			<strong><?php echo 'Social media must be linked';?></strong>
			
			<?php } ?>
			<div class="row mt4">
				<a class=" text-center" href="<?php echo BASE_PATH.'user-survey-3/?endsur=1'?>"><?php echo $UserSurvey3Lang['SKIP'] ?></a>
			</div>
		</div>
	</div>
</div>
<script src="//platform.twitter.com/widgets.js"></script>
<script>
	$(document).ready(function(){
		var len = $('.tweetText').text().length;
		$('#charNum').text(115 - len);
	});

	function countChar(val) {
		var len = val.value.length;
		if (len >= 115) {
			val.value = val.value.substring(0, 115);
		} else {
			$('#charNum').text(115 - len);
		}
	};
	var callback = function(e){
		if(e && e.data){
			var data;

			try{
				data = JSON.parse(e.data);
			}catch(e){
			// Don't care.
			}
			alert(data);
			if(data && data.params && data.params.indexOf('tweet') > -1){
				alert('Thanks for the tweet!');
			}
		}
	};
// Callback
window.addEventListener ? window.addEventListener("message", callback, !1) : window.attachEvent("onmessage", callback)
</script>