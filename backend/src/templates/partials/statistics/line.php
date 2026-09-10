<?php include LANG . array_get($_SESSION, 'userLang', 'en') . '/statistics/templateStatistics.php' ?>
<div class="ui card fluid statistics_card">
    <div class="content">
        <div class="header" ><?php echo $this->e($name) ?> <i style="color:black" class="ui icon question circle primary-color has-tooltip right floated" data-variation="wide"  data-title="<?php echo $this->e($tooltip_title)?>" data-content="<?php echo $this->e($tooltip_content)?>"></i>
            <?php
            if (!empty($data)) {
                include __DIR__ . '/../menus/lapse_buttons.php';
            }
            ?>
        </div>
    </div>
    <div class="content statistic_content" id="<?php echo $this->e($id) ?>-container">
        <?php if(!empty($data)) : ?>
            <canvas class="line_statistic" id="<?php echo $this->e($id) ?>" height="215"></canvas>
        <?php else : ?>
            <img class="empty_statistic_img" src="<?php echo $this->asset('/public/images/statistics/dashboard_empty_2.svg') ?>" alt="No data image">
        <?php endif; ?>
    </div>
</div>
<?php if(!empty($data)) : ?>
    <?php $parent->push('scripts') ?>
    <script>
        $(document).ready(function () {
            renderLineStatistic('<?php echo $this->e($id) ?>', <?php echo json_encode($data) ?>, <?php echo json_encode($backgroundColor) ?>, '<?php echo $this->e($label) ?>', <?php echo json_encode($labels) ?>, <?php echo(empty($steps) ? 50 : json_encode($steps)) ?>);
        });
    </script>
    <?php $parent->stop() ?>
<?php endif; ?>
