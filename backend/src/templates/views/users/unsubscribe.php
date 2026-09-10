<?php $this->layout('_layout::index')?>
<?php include_once LANG . $_SESSION['userLang'] . '/app/unsubscribe.php'; ?>

<div class="ui narrow container">
<div class="ui middle aligned center aligned grid" style="padding-top:2rem">
  <div class="eight wide computer twelve wide tablet sixteen wide mobile column">
    <h2 class="ui center aligned icon header">
        <img src="<?php echo $this->asset('/public/images/hotelinking_iso_black.png') ?>" class="ui image">
        <div class="ui segment">
          <div class="ui header"><?=e($lang['hi'])?> <?=e($user->name)?></div>
          <p><?=e($lang['intro_text'])?></p>
        </div>
    </h2>

    <?php if (!$notifications_subscribed && !$commercial_profile_subscribed) { ?>

    <div class="ui success message">
      <div class="header">
        <?=e($lang['unsubscribe_success'])?>
      </div>
      <p><?=e($lang['unsubscribe_text'])?></p>
    </div>

    <?php } else { ?>
    <form action="<?=e($this->uriFull())?>" method="POST" class="ui large form">
      <div class="ui segment">
      <?php if ($notifications_subscribed) { ?>
        <div class="field ui left aligned checkbox">
          <input type="checkbox" name="unsubscribe_notifications">
          <label><?=e($lang['unsubscribe_description_notifications'])?></label>
        </div>
      <?php } ?>
      <?php if ($commercial_profile_subscribed) { ?>
        <div class="field ui left aligned checkbox">
          <input type="checkbox" name="unsubscribe_commercial_profile">
          <label><?=e($lang['unsubscribe_description_commercial_profile'])?></label>
        </div>
        <?php } ?>
        <button class="ui fluid large primary-color bg button" type="submit"><?=e($lang['ok'])?></button>
      </div>

      <div class="ui error message"></div>

    </form>

    <?php } ?>

  </div>
</div>

</div>
