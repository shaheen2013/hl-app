<?php include LANG . array_get($_SESSION, 'userLang', 'en') . '/statistics/templateStatistics.php' ?>
<div class="content statistic_content" id="<?php echo $this->e($id) ?>-container">
    <canvas class="bar_statistic" id="<?php echo $this->e($id) ?>" height="<?php echo (count($data) + 1 ) * 60 . "em"?>"></canvas>
</div>
<?php
    $simple_bar = ($simple_bar ?? false);
    $js_simple_bar = $simple_bar ? 'true' : 'false';
    $abs = ($abs ?? false) ? 'true' : 'false';
    $on_click = $event_click ?? 'null';
    $parent->push('scripts');
?>
<script>
    $(document).ready(function () {
        renderHorizontalBarStatistic('<?php echo $this->e($id) ?>', <?php echo json_encode($data) ?>, <?php echo json_encode($backgroundColor) ?>, <?php echo json_encode($labels) ?>, <?php echo(empty($steps) ? 50 : json_encode($steps)) ?>, '<?php echo $this->e($id) ?>-container', <?php echo $abs; ?>, <?php echo $numberElements; ?>);
    });
</script>
<?php $parent->stop() ?>
