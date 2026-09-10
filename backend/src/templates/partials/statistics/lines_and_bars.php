<div class="ui card fluid statistics_card">
    <div class="content">
        <div class="header"><?php echo $this->e($name) ?></div>
    </div>
    <div class="content statistic_content" id="<?php echo $this->e($id) ?>-container">
        <canvas class="lines_and_bars_statistic" id="<?php echo $this->e($id) ?>" height="215"></canvas>
    </div>
</div>
<?php $parent->push('scripts') ?>
<script>
    $(document).ready(function () {
        lineAndBarStatistc('<?php echo $this->e($id) ?>');
    });
</script>
<?php $parent->stop()?>