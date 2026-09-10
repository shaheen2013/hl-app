<div class="ui card fluid statistics_card">
    <div class="content">
        <div class="header"><?php echo $this->e($name) ?></div>
    </div>
    <div class="content statistic_content" id="<?php echo $this->e($id) ?>-container">
        <canvas class="funnel_statistic" id="<?php echo $this->e($id) ?>" height="215" style="float:left"></canvas>
    </div>
</div>
<?php $parent->push('scripts') ?>
<script>
    $('document').ready(function(){
        renderFunnelStatistic(<?php echo json_encode($data)?>, <?php echo json_encode($backgroundColor)?>, <?php echo json_encode($labels)?>);
    })
</script>
<?php $parent->stop() ?>