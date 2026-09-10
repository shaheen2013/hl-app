<?php if(!empty($urlError)) {?>
	<div class="redirect-msg">
		<h1 class="mt2"><strong><?php echo $redirectLang['Invalid URL']	?></strong></h1>
		<p><?php echo $redirectLang['This Url is invalid, sorry']?></p>
	</div>
<?php }else{ ?>
	<div id="spinner"></div>
	<div class="redirect-msg">
		<h1 class="mt2"><strong><?php echo $redirectLang['Connecting with hotel website']?></strong></h1>
		<p><?php echo $redirectLang['Please, hold on a second']?></p>
	</div>
	<?php if($affiliredId !== NULL) {//Load affilired Iframe?>
		<iframe src="<?php echo $affiliredUrl ?>" width="1" height="1" style="border:none"></iframe>
	<?php } ?>
	<script src="<?php echo DIR_JS ?>spiner.min.js"></script>
	<script>
	$(function(){var opts = {lines: 13,length: 28,width: 14,radius: 42,scale: 0.25,corners: 1,color: '#000',opacity: 0.25,rotate: 0,direction: 1,speed: 0.5,trail: 15,fps: 20,zIndex: 2e9,className: 'spinner',top: '45%',left: '50%',shadow: false,hwaccel: false,position: 'absolute'}; var target = document.getElementById('spinner'); var spinner = new Spinner(opts).spin(target);

		window.location = "<?php echo $websiteRedirect ?>";
	})
	</script>
<?php } ?>