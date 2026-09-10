<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>
<?php include LANG . $_SESSION['userLang'].'/404.php' ?>

<?php $this->layout('_layout::index', [
  'title'=> 'Hotelinking 404!'
])?>


<div class="ui narrow container">
  <div class="ui middle aligned center aligned grid" style="padding-top:2rem">
    <div class="eight wide computer twelve wide tablet sixteen wide mobile column">
      <h2 class="ui center aligned icon header">
          <img src="<?php echo $this->asset('/public/images/hotelinking_iso_black.png') ?>" class="ui image">
          <div class="ui segment">
            <div class="ui header"><?php echo $lang['error 404'] ?></div>
            <p> Oops! This page does not exist! </p>
          </div>
      </h2>
      <button onclick="goBack()" class="ui primary-color button"><?php echo $lang['Go back'] ?></button>
    </div>
  </div>
</div>


<?php $this->push('scripts') ?>
<script>
    function goBack(){ window.history.back();}
</script>
<?php $this->stop('scripts')?>
