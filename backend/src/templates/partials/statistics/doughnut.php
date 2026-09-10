<div class="ui card fluid statistics_card doughnut_statistics_card">
    <div class="content">
        <div class="header"><?php echo $this->e($name) ?><i style="color:black" class="ui icon question circle primary-color has-tooltip right floated" data-variation="wide"  data-title="<?php echo $this->e($tooltip_title)?>" data-content="<?php echo $this->e($tooltip_content)?>"></i>
        </div>
    </div>
    <div class="content statistic_content doughnout_extend_container" id="<?php echo $this->e($id) ?>-container">
        <?php if (!empty($data)) : ?>
            <canvas class="doughnut_statistic" id="<?php echo $this->e($id) ?>" height="140" width="140" style="max-width: 240px;
    max-height: 240px;margin:0"></canvas>
        <?php else : ?>
            <img class="empty_statistic_img" src="<?php echo $this->asset('/public/images/statistics/dashboard_empty_4.svg') ?>" alt="No data image">
        <?php endif; ?>
    </div>
</div>
<?php if (!empty($data)) : ?>
    <?php $parent->push('scripts') ?>
    <script>
        $(document).ready(function () {
            renderDoughnutStatistic('<?php echo $this->e($id) ?>', <?php echo json_encode($data) ?>, <?php echo json_encode($backgroundColor) ?>, <?php echo(!empty($labels) ? json_encode($labels) : '') ?>, '<?php echo $this->e($id) ?>-container');
        });
    </script>
    <?php $parent->stop() ?>
<?php endif; ?>
