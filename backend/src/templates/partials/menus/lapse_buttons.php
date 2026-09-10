<div class="right floated meta">
    <?php if ($this->e($lapse)) : ?>
        <a href="?lapse=day"
            style="<?php echo array_get($_SESSION, 'lapse') == 'day' ? 'background-color: black; color:white' : '' ?> "
            class="has-loader mini ui button"><?php echo $lang['day'] ?></a>
        <a href="?lapse=month"
            style="<?php echo array_get($_SESSION, 'lapse') == 'month' ? 'background-color: black; color:white' : '' ?> "
            class="has-loader mini ui button"><?php echo $lang['month'] ?></a>
        <a href="?lapse=year"
            style="<?php echo array_get($_SESSION, 'lapse') == 'year' ? 'background-color: black; color:white' : '' ?> "
            class="has-loader mini ui button"><?php echo $lang['year'] ?></a>
    <?php endif; ?>
</div>
