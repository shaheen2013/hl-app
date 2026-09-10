<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<?php include LANG .$_SESSION['userLang']. '/redeem-offer.php' ?>
<div class="mainContent" id="fullContainer">
  <div class="dlpOverlayer"></div>
  <div class="mainContent dlpContent">
    <div class="vertical-align">
      <?php if($isOk == true ){ ?>
      <img src="<?php echo $info['logo'] ?>" alt="..." class="img-circle img-thumbnail center-block dlpLogo mt4" width="100" height="100">
      <h4 class="text-center mt"><strong><?php echo $info['name'] ?></strong></h4>
      <div class="text-center">
        <?php } ?>
        <?php if($isOk === true ){ ?>
        <h3><?php echo $username . ', '. $redeemOfferLang['Hi again!']?></h3>
        <p><?php echo $redeemOfferLang['Hold on, we are checking your promo code and applying to your booking'] ?></p>
        <h3><strong class="offername"></strong></h3>
        <a href="#" class="conditionsLink" data-toggle="modal" data-target="#conditionsModal" title="conditions">check conditions for this reward</a>
        <div class="row">
          <div class="col-sm-4 col-sm-offset-4 mt2 col-xs-8 col-xs-offset-2">
            <div class="okIcon"><p><strong><h3><?php echo $redeemOfferLang['redirecting'] ?></h3></strong></p></div>
            <div class="progress">
              <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                <span class="progressSpan"></span>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="promoProcess"></div>
      <?php } ?>
      <?php if($isOk === 'somewhat') {?>
      <div class="row">
        <div class="col-lg-4 col-lg-offset-4">
          <div class="alert alert-danger mt2" role="alert"><?php echo $redeemOfferLang['This promo code is not valid at our hotel or is already used'] ?></div>
        </div>
      </div>
      <div class="okIcon mt2 text-center"><p><strong><h3><?php echo $redeemOfferLang['redirecting'] ?></h3></strong></p></div>
      <?php } ?>
      <?php if($isOk === false) {?>
      <div class="text-center mt4">
        <h2><i class="fa fa-meh-o mt4 fa-3x"></i></h2>
        <h3><?php echo $redeemOfferLang['Ups... something went wrong'] ?></h3>
        <div class="row">
          <div class="col-lg-4 col-lg-offset-4">
            <p><?php echo $redeemOfferLang['This promocode appears not to be valid, maybe it is already used or never existed.'] ?></p>
          </div>
        </div>
      </div>
      <?php } ?>
    </div>
  </div>
</div>
<div class="modal fade" id="conditionsModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><?php echo $redeemOfferLang['Check conditions for this reward'] ?></h4>
      </div>
      <div class="modal-body">
        <p><?php echo $promoResponse['conditions'] ?></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $redeemOfferLang['Close'] ?></button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php if(!empty($affiliredId)) {//Load affilired Iframe?>
  <iframe src="<?php echo $affiliredUrl ?>" width="1" height="1" style="border:none"></iframe>
<?php } ?>
<script>

  $(function(){
    var backgroundImg ="<?php echo DIR_IMG?>login-bg.jpg";
    $('body').css('background-image', 'url(' + backgroundImg + ')').css('background-size','cover');
  });

</script>
<?php if($isOk === true){ ?>
<script>
  //If promocode Exists add it to localStorage (if available)
  function storageAvailable(type) {
    try {
      var storage = window[type],
      x = '__storage_test__';
      storage.setItem(x, x);
      storage.removeItem(x);
      return true;
    }
    catch(e) {
      return false;
    }
  }

  $(function(){

    //hide elements
    $('.okIcon, .offername, .countdown, .conditionsLink').hide();
    <?php if($type=='h'){?>
      // FIX: Check if fotoBg is already a full URL (starts with http)
      // If not, build the path with DIR_IMG_FICHA_HOTEL
      <?php if(strpos($info['fotoBg'], 'http') === 0): ?>
        var backgroundImg ="<?php echo $info['fotoBg'] ?>";
      <?php else: ?>
        var backgroundImg ="<?php echo DIR_IMG_FICHA_HOTEL . $info['id'] . '/fotoBg/' . $info['fotoBg'] ?>";
      <?php endif; ?>
     <?php }else{?>
      var backgroundImg ="<?php echo DIR_IMG?>login-bg.jpg";
      <?php }?>
      $('body').css('background-image', 'url(' + backgroundImg + ')').css('background-size','cover');

    //Check if local storage is available on this browser
    if (storageAvailable('localStorage')) {
        // Yippee! We can use localStorage awesomeness
        //Store promoCode
        var promoCode = '<?php echo $promoCode ?>';
        localStorage.setItem("hlpc", promoCode);

        //Anims FAKE
        $('.progressSpan').append("<?php echo $redeemOfferLang['checking reward'] ?>");
        $(".progress-bar").animate({
          width: "30%"
        }, 1);

        //Anims
        setTimeout(function(){ 
          $('.progressSpan').empty();
          $('.progressSpan').append("<?php echo $redeemOfferLang['applying code'] ?>");
          $('.offername').append("<?php echo $promoResponse['offerName'] ?>").fadeIn();
          $('.conditionsLink').fadeIn();
          $(".progress-bar").animate({
            width: "99%"
          }, 0.5);
        }, 3000);

        //Last anim
        setTimeout(function(){ 
          $(".progress").fadeOut();
          setTimeout(function(){
            $(".okIcon").fadeIn();
            setTimeout(function(){
              window.location = "<?php echo $bookingEngineUrl ?>";
            },3000)
          },1000)
        }, 5000);

      } else {
        // Too bad, no localStorage for us
        alert('<?php echo $redeemOfferLang["Opps, your browser is too old! please use a modern browser to use this page"] ?>');
      }

    });
  </script>
  <?php }else if($isOk === 'somewhat') {?>
  <script>
    $(function(){
     <?php if($type=='h'){?>
       // FIX: Check if fotoBg is already a full URL (starts with http)
       <?php if(strpos($info['fotoBg'], 'http') === 0): ?>
         var backgroundImg ="<?php echo $info['fotoBg'] ?>";
       <?php else: ?>
         var backgroundImg ="<?php echo DIR_IMG_FICHA_HOTEL.$info['id'].'/fotoBg/'.$info['fotoBg'] ?>";
       <?php endif; ?>
       <?php }else{?>
        var backgroundImg ="<?php echo DIR_IMG?>login-bg.jpg";
        <?php }?>
        $('body').css('background-image', 'url(' + backgroundImg + ')').css('background-size','cover');
      });
    </script>
    <?php } ?>