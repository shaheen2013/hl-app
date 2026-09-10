<?php include LANG .  array_get($_SESSION, 'userLang', 'en') . '/statistics/templateStatistics.php'   ?>
<div class="ui card fluid statistics_card" style="height: inherit">
    <div class="content">
        <div class="header"><?php echo $this->e($name) ?> <i
                    style="color:black"
                    class="ui icon question circle primary-color has-tooltip right floated" data-variation="wide"
                    data-title="<?php echo $this->e($tooltip_title) ?>"
                    data-content="<?php echo $this->e($tooltip_content) ?>"></i>


            <div class="right floated meta">
                <?php if ($this->e($lapse)) { ?>
                    <a href="?lapse=day"
                        style="<?php echo array_get($_SESSION, 'lapse') == 'day' ? 'background-color: black; color:white' : '' ?> "
                        class="has-loader mini ui button"><?php echo $lang['day'] ?></a>
                    <a href="?lapse=month"
                        style="<?php echo array_get($_SESSION, 'lapse') == 'month' ? 'background-color: black; color:white' : '' ?> "
                        class="has-loader mini ui button"><?php echo $lang['month'] ?></a>
                    <a href="?lapse=year"
                        style="<?php echo array_get($_SESSION, 'lapse') == 'year' ? 'background-color: black; color:white' : '' ?> "
                        class="has-loader mini ui button"><?php echo $lang['year'] ?></a>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="content statistic_content" id="<?php echo $this->e($id) ?>-container">
        <canvas class="multiple_line_statistic" id="<?php echo $this->e($id) ?>" height="215"></canvas>
    </div>
    <?php if(isset($average_clicks) || isset($average_opens)) { ?>
    <div class="content">
        <div class="ui mini horizontal statistic">
            <div style="color:black" class="value">
                <?php echo $average_opens ?>%
            </div>
            <div class="label">
                <?php echo $lang['average open'] ?>
            </div>
        </div>
        <?php if(isset($average_clicks)) { ?>
        <div class="ui mini horizontal statistic">
            <div style="color:black" class="value">
                <?php echo $average_clicks ?>%
            </div>
            <div class="label">
                <?php echo $lang['average click'] ?>
            </div>
        </div>
        <?php } ?>
    </div>

    <?php if (!empty($data)): ?>
    <div class="content">
        <ul class="legend">
            <?php foreach ($data as $item): ?>
                <li><i class="ui icon circle" style="font-size: 1.5em; color: <?php echo $item['backgroundColor'] ?? 'transparent'; ?>"></i><span style="font-size: 1.5em;"><?php echo $item['label'] ?? ''; ?></span></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>
    <?php } ?>
</div>
<?php $parent->push('scripts') ?>
<script>
    $(document).ready(function () {
        renderMultipleLinesStatistic('<?php echo $this->e($id) ?>', <?php echo json_encode($data) ?>, <?php echo json_encode($labels) ?>, <?php echo(empty($steps) ? 50 : json_encode($steps)) ?>);
    });
</script>
<?php $parent->stop() ?>
