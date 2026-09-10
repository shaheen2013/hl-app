<?php
    include LANG . $_SESSION['userLang'] . '/statistics/users.php';
    $this->layout('_layout::layout') ?>
<?php
//Insert dashboard top menu
$this->insert('partials::menus/dashboard_menu', ['url' => $url])
?>

<div class="grid"
     data-isotope='{ "itemSelector": ".grid-item", "percentPosition" : true, "masonry" : {"columnWidth" : ".grid-sizer"}}'
     id="user_statistics">
    <div class="grid-sizer"></div>
    <div class="grid-item grid-item-huge">
        <?php $this->insert('statistics::line', [
            'name' => $lang['users time'],
            'id' => 'users_in_time',
            'data' => $users,
            'backgroundColor' => ['rgba(0, 0, 0, .5)'],
            'labels' => $users_days,
            'label' => '',
            'parent' => $this,
            'steps' => $steps,
            'lapse' => true,
            'tooltip_title' => $lang['title users time'],
            'tooltip_content' => $lang['explanation users time']
        ]) ?>
    </div>
    <div class="grid-item grid-item-big">
        <?php $this->insert('statistics::single_number', [
            'name' => $lang['total clients'],
            'value' => thousandsCurrencyFormat($this->e($totalUsers)),
            'label' => $lang['clients'],
            'tooltip_title' => $lang['total clients'],
            'tooltip_content' => $lang['explanation total clients']
        ]) ?>
    </div>
    <div class="grid-item grid-item-big">
        <?php $this->insert('statistics::single_number', [
            'name' => $lang['value database'],
            'value' => thousandsCurrencyFormat($this->e($databaseValue)) . ' €',
            'label' => $lang['unit value'] . $this->e($multiplier) . ' €',
            'tooltip_title' => $lang['value database'],
            'tooltip_content' => $lang['explanation value database']
        ])?>
    </div>
    <div class="grid-item">
        <?php $this->insert('statistics::doughnut', [
            'name' => $lang['form vs face'],
            'id' => 'fbvsf',
            'data' => $sourcesData,
            'backgroundColor' => $background,
            'labels' => $sourcesLabels,
            'parent' => $this,
            'tooltip_title' => $lang['title form vs face'],
            'tooltip_content' => $lang['explanation form vs face']
        ])?>
    </div>
    <div class="grid-item">
        <?php $this->insert('statistics::doughnut', [
            'name' => $lang['female vs male'],
            'id' => 'mvsf',
            'data' => $genderData,
            'backgroundColor' => $background,
            'labels' => $genderLabels,
            'parent' => $this,
            'tooltip_title' => $lang['title female vs male'],
            'tooltip_content' => $lang['explanation female vs male']
        ]) ?>
    </div>
    <div class="grid-item">
        <?php $this->insert('statistics::doughnut', [
            'name' => $lang['accommodated graph'],
            'id' => 'accommodated',
            'data' => $accommodatedData,
            'backgroundColor' => $background,
            'labels' => $accommodatedLabels,
            'parent' => $this,
            'tooltip_title' => $lang['title accommodated'],
            'tooltip_content' => $lang['explanation accommodated']
        ])?>
    </div>
    <div class="grid-item">
        <?php $this->insert('statistics::doughnut', [
            'name' => $lang['subscribed graph'],
            'id' => 'subscribed',
            'data' => $subscribedData,
            'backgroundColor' => $background,
            'labels' => $subscribedLabels,
            'parent' => $this,
            'tooltip_title' => $lang['title subscribed'],
            'tooltip_content' => $lang['explanation subscribed']
        ]) ?>
    </div>
    
    <div class="grid-item grid-item-big">
        <?php $this->insert('statistics::bar', [
            'name' => $lang['country connection'],
            'id' => 'city',
            'labels' => $countryLabels,
            'label' => $lang['this location'],
            'data' => $countryData,
            'backgroundColor' => $background,
            'steps' => 10,
            'parent' => $this,
            'tooltip_title' => $lang['title country connection'],
            'tooltip_content' => $lang['explanation country connection']
        ])?>
    </div>
    <div class="grid-item grid-item-big">
        <?php $this->insert('statistics::bar', [
            'name' => $lang['connection age'],
            'id' => 'age',
            'labels' => $generationLabels,
            'label' => $lang['this age'],
            'data' => $generationData,
            'backgroundColor' => $background,
            'steps' => 10,
            'parent' => $this,
            'tooltip_title' => $lang['title connection age'],
            'tooltip_content' => $lang['explanation connection age']
        ]) ?>
    </div>
    <div class="grid-item grid-item-big">
        <?php $this->insert('statistics::bar', [
            'name' => $lang['devices'],
            'id' => 'device',
            'labels' => $devicesLabels,
            'label' => $lang['this device'],
            'data' => $devicesData,
            'backgroundColor' => $background,
            'steps' => 10,
            'parent' => $this,
            'tooltip_title' => $lang['title devices'],
            'tooltip_content' => $lang['explanation devices']
        ]) ?>
    </div>
</div>

<?php $this->push('scripts') ?>
<script src="<?php echo $this->asset('/public/javascript/isotope.pkgd.min.js') ?>"></script>
<script src="<?php echo $this->asset('/public/javascript/moment.min.js') ?>"></script>
<script src="<?php echo $this->asset('/public/javascript/calendar.min.js') ?>"></script>
<script src="<?php echo $this->asset('/public/javascript/charts.min.js') ?>"></script>
<script src="<?php echo $this->asset('/public/javascript/templates/dashboards.min.js') ?>"></script>
<script src="<?php echo $this->asset('/public/javascript/templates/statistics/doughnut.min.js') ?>"></script>
<script src="<?php echo $this->asset('/public/javascript/templates/statistics/bar.min.js') ?>"></script>
<script src="<?php echo $this->asset('/public/javascript/templates/statistics/line.min.js') ?>"></script>
<script src="<?php echo $this->asset('/public/javascript/chartjs-plugin-datalabels.min.js') ?>"></script>
<?php $this->stop() ?>


