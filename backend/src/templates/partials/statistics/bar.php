<?php include LANG . array_get($_SESSION, 'userLang', 'en') . '/statistics/templateStatistics.php' ?>
<div class="ui card fluid statistics_card">
    <div class="content">
        <div class="header"><?php echo $this->e($name) ?><i style="color:black" class="ui icon question circle primary-color has-tooltip right floated" data-variation="wide"  data-title="<?php echo $this->e($tooltip_title)?>" data-content="<?php echo $this->e($tooltip_content)?>"></i>
            <?php
            $lapse = $lapse ?? false;
            if (!!$lapse) {
                include __DIR__ . '/../menus/lapse_buttons.php';
            }
            ?>
        </div>
    </div>
    <div class="content statistic_content" id="<?php echo $this->e($id) ?>-container">
        <canvas class="bar_statistic" id="<?php echo $this->e($id) ?>" height="215"></canvas>
    </div>
</div>
<?php
    $simple_bar = ($simple_bar ?? false);
    $js_simple_bar = $simple_bar ? 'true' : 'false';
    $abs = ($abs ?? false) ? 'true' : 'false';
    $decimals = $decimals ?? 2;
    $on_click = $event_click ?? 'null';
    $parent->push('scripts');
?>
<script>
    $(document).ready(function () {
        <?php
        if ($simple_bar) {
        ?>
            renderBarInteractionStatistic('<?php echo $this->e($id) ?>', <?php echo json_encode($data) ?>, <?php echo json_encode($backgroundColor) ?>, '<?php echo $this->e($label) ?>', <?php echo json_encode($labels) ?>, <?php echo(empty($steps) ? 50 : json_encode($steps)) ?>,'<?php echo $this->e($id) ?>-container', <?php echo $js_simple_bar; ?>, <?php echo $on_click; ?>);
        <?php
        } else {
        ?>
            renderBarStatistic('<?php echo $this->e($id) ?>', <?php echo json_encode($data) ?>, <?php echo json_encode($backgroundColor) ?>, '<?php echo $this->e($label) ?>', <?php echo json_encode($labels) ?>, <?php echo(empty($steps) ? 50 : json_encode($steps)) ?>, '<?php echo $this->e($id) ?>-container', <?php echo $abs; ?>, <?php echo $decimals ?>);
        <?php
        }
        ?>
    });
</script>
<?php $parent->stop() ?>
