<?php $this->layout('_layout::index', [
  'title'=> 'Hotelinking Error Page'
])?>

<?php include LANG . $_SESSION['userLang'].'/404.php' ?>


<div class="ui narrow container">
  <div class="ui middle aligned center aligned grid" style="padding-top:2rem">
    <div class="eight wide computer twelve wide tablet sixteen wide mobile column">
      <h2 class="ui center aligned icon header">
          <img src="<?php echo $this->asset('/public/images/hotelinking_iso_black.png') ?>" class="ui image">
          <div class="ui segment">
            <div class="ui header">500</div>
            <p> There seems to have been an error! </p>
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
