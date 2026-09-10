  <?php include LANG . $_SESSION['userLang'] . '/bookingContactModal.php' ?>
<div class="modal fade bs-modal-lg" id="bookingContactModal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><?php echo $BookingContactModalLang['Booking contact details'] ?></h4>
      </div>
      <div class="modal-body">
          <p><strong><?php echo $BookingContactModalLang['By email'] ?></strong> <a href="mailto:<?php echo $arrayBookingContact['emailReserva'] ?>" title="email"><?php echo $arrayBookingContact['emailReserva'] ?></a></p>
          <p class="mt"><strong><?php echo $BookingContactModalLang['Phone'] ?></strong><?php echo $arrayBookingContact['telefonoReservas'] ?></p>
          <p class="mt"><strong><?php echo $BookingContactModalLang['Direct booking website:'] ?></strong> <a href="<?php echo $websiteReservaUrl ?>" class="btn btn-sm btn-success pl" title="booking form"><?php echo $BookingContactModalLang['click here'] ?></a></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $BookingContactModalLang['Close'] ?></button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->