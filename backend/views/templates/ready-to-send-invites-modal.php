<?php include LANG . $_SESSION['userLang'] . '/ready-to-send-invites-modal.php' ?>
<div class="modal fade bs-modal-lg" id="ready-to-send-invites-modal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content text-center">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><?php echo $ReadyToSendInvitesModalLang['Confirm invites delivery'] ?></h4>
      </div>
      <div class="modal-body">
      <h2><?php echo $ReadyToSendInvitesModalLang['All invite emails are ready for delivery'] ?></h2>
      <p class="mt"><?php echo $ReadyToSendInvitesModalLang['I will procced to send all mails now.'] ?></p>
      <?php if ($_SESSION['permisos']['LY'] == 1) {?>
        <a href="#" title="enviar invitaciones de Loyalty" id="modalSendLoyaltyBtn" data-type="LY" class="modalSendFormBtn btn btn-success mt"><?php echo $ReadyToSendInvitesModalLang['Send'] ?> <span class="nValidEmails"><?php $_SESSION['n'] ?></span> <?php echo $ReadyToSendInvitesModalLang['loyalty'] .' '. $ReadyToSendInvitesModalLang['invites'] ?></a>      
      <?php } ?>
      <?php if ($_SESSION['permisos']['RF'] == 1) {?>
        <a href="#" title="enviar invitaciones de Loyalty" id="modalSendReferralBtn" data-type="RF" class="modalSendFormBtn btn btn-success mt"><?php echo $ReadyToSendInvitesModalLang['Send'] ?> <span class="nValidEmails"><?php $_SESSION['n'] ?></span> <?php echo $ReadyToSendInvitesModalLang['referral'] .' '. $ReadyToSendInvitesModalLang['invites'] ?></a>      
      <?php } ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $ReadyToSendInvitesModalLang['Cancel'] ?></button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script>
  $('.modalSendFormBtn').click(function(e){
    e.preventDefault();
    var type = $(this).data('type');
    $('#submitType').val(type);

    $('#ready-to-send-invites-modal').modal('hide');
    $("#sendInviteListForm").submit();
  });
</script>