<!-- Modal -->
<div class="modal fade" id="twitterShareModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?php echo $referralShareLang['share your experience on twitter'] ?></h4>
      </div>
      <div class="modal-body">
        <form method="post" >
          <textarea class="form-control" id="twitterShareField" name="refShareStep2twText" maxlength="120" rows="5" placeholder="<?php echo $referralShareLang['Share your experience with your followers...'] ?>"><?php echo (empty($socialMediaShareText) ? $referralShareLang['shareMsgTwitter'] : $socialMediaShareText); ?></textarea>
          <button type="submit" class="btn btn-twitter btn-icon mt"><i class="fa fa-twitter pr"></i> <?php echo $referralShareLang['share twitter'] ?></button>
          <span class="pull-right mt grisOscuro" id="charNum"></span>
        </form>
      </div>
    </div>
  </div>
</div>