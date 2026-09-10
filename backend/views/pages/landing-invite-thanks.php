<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<?php include LANG . $_SESSION['userLang'] . '/landing-invite-thanks.php' ?>
<?php include TEMPLATES . 'landing-header.php' ?>
<div class="clearfix"></div>
<div class="user-utility-bar">
	<h1 class="pull-left">
		<?php if (!empty($msg) && $msg ="rechazar"){ echo '<i class="fa fa-sign-in"></i> '.$LandingInviteThanksLang["Thanks for sing up for an invite"];  } ?>
	</h1>
</div>
<div class="clearfix"></div>
<div class="container">
	<div class="row">
		<div class="col-lg-12 text-center">
			<?php if (!empty($msg) && $msg ="rechazar"){ ?>
				<h2><?php echo $LandingInviteThanksLang['We are inviting hotels from 3 stars'] ?></h2>
				<h4><?php echo $LandingInviteThanksLang['Text for explanation'] ?></h4>
			<?php } else if(!empty($msg) && $msg ="rechazar"){?>
				<h2><?php echo $LandingInviteThanksLang['Error inserting in database'] ?></h2>
				<h4><?php echo $LandingInviteThanksLang['Use alternative contact method'] ?></h4>
				<p><a href="maito:helpdesk@hotelinking.com"><?php echo $LandingInviteThanksLang['send a mail to us'] ?></a></p>
				<p><?php echo $LandingInviteThanksLang['And tell us about your hotel:'] ?></p>
				<ul>
					<li><?php echo $LandingInviteThanksLang['Hotel Name'] ?></li>
					<li><?php echo $LandingInviteThanksLang['Hotel Phone'] ?></li>
					<li><?php echo $LandingInviteThanksLang['Hotel Website'] ?></li>
				</ul>
			<?php } ?>
			<?php if(!empty($exito) && $exito === true) {?>
				<i class="fa fa-check-circle fa-4x verde"></i>
				<h1><?php echo $LandingInviteThanksLang['Thanks for join us'] ?></h1>
				<h4><?php echo $LandingInviteThanksLang['As reward for your confidence with us we will give you 500 guests slots free for your hotel'] ?></h4>
				<h3 class="mt4"><?php echo $LandingInviteThanksLang['¿Do you want 12.000 guest slots for free?'] ?></h3>
				<p class="mb4"><?php echo $LandingInviteThanksLang['Texto enlace link'] ?></p>
				<p><span class="referral-link code-block"><a class="code-copy" href="#"><?php echo BASE_PATH . $urlTree['landing'] . '/?referral=' .$code ?></a></span></p>
				<div class="clearfix"></div>
				<p class="mt2"><?php echo $LandingInviteThanksLang['Click on the link above to copy it to your clipboard'] ?></p>
				<p><strong><?php echo $LandingInviteThanksLang['And...'] ?></strong></p>
				<div class="share-tweet-btn text-center">
					<a title="Click me" class="btn btn-lg btn-primary" href="http://twitter.com/intent/tweet?text=<?php echo $LandingInviteThanksLang['twitterText'] . ' - ' .  BASE_PATH . $urlTree['landing'] . '/?referral=' .$code?>">
					   <i class="fa fa-twitter pl"></i> <?php echo $LandingInviteThanksLang['Share this link on twitter'] ?>
					</a>
				</div>
			<?php } ?>
		</div>
	</div>
</div>
<div class="modal fade" id="code-copy-done">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php echo $LandingInviteThanksLang['Close'] ?></span></button>
        <h4 class="modal-title"><?php echo $LandingInviteThanksLang['Link copied to your clipboard'] ?></h4>
      </div>
      <div class="modal-body">
        <p><?php echo $LandingInviteThanksLang['Link copied to your clipboard'] ?></p>
        <p><small><?php echo $LandingInviteThanksLang['TIP: you can paste it just right click and select paste'] ?></small></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $LandingInviteThanksLang['Close'] ?></button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script src="<?php echo DIR_JS . 'jquery.clipboard.min.js'?>"></script>
<script src="//platform.twitter.com/widgets.js"></script>
<script>
$(document).ready(function() {
    var copy_sel = $('.code-block a.code-copy');

    // Disables other default handlers on click (avoid issues)
    copy_sel.on('click', function(e) {
        e.preventDefault();
    });

    // Apply clipboard click event
    copy_sel.clipboard({
        path: '<?php echo DIR_JS . "jquery.clipboard.swf"?>',

        copy: function() {
            var this_sel = $(this);

            // Hide "Copy" and show "Copied, copy again?" message in link
            $('#code-copy-done').modal('show');

            // Return text in closest element (useful when you have multiple boxes that can be copied)
            return this_sel.closest('.code-block').text();
        }
    });
});
</script>
