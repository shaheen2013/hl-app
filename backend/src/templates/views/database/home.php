<?php $this->layout('_layout::layout') ?>
    <div class="ui feed large">
    </div>
<?php $this->push('scripts') ?>
    <script src="<?php echo $this->asset('/public/javascript/moment.min.js') ?>"></script>
    <script src="<?php echo $this->asset('/public/javascript/templates/database/home/main.js') ?>"></script>
    <script>
        $(document).ready(function () {
            setTimeout(fetchNewUserData, 3000, <?php echo $hotel_id ?>, 1800, 20);
        });
    </script>
<?php $this->stop() ?>