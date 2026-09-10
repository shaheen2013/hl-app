<?php
include LANG . $_SESSION['userLang'] . '/statistics/loyalty.php';
$this->layout('_layout::layout');

//Insert dashboard top menu
$this->insert('partials::menus/dashboard_menu', ['url' => $url]);
?>

<div class="grid"
     data-isotope='{ "itemSelector": ".grid-item", "percentPosition" : true, "masonry" : {"columnWidth" : ".grid-sizer"}}'
     id="visitor_statistics">
    <div class="grid-sizer"></div>

    <div class="grid-item grid-item-huge">
        <?php $this->insert('statistics::bar', [
            'name'            => $lang['daily_visitors'],
            'id'              => 'recurrent_visitors',
            'data'            => $recurrentClientsTimeline,
            'backgroundColor' => '#000000',
            'labels'          => $recurrentDays,
            'label'           => "",
            'parent'          => $this,
            'steps'           => $steps,
            'lapse'           => true,
            'simple_bar'      => true,
            'tooltip_title'   => $lang['daily_visitors'],
            'tooltip_content' => $lang['daily_visitors_explain'],
            'event_click'      => 'onClickGetVisitorInfo'
        ]) ?>
    </div>
    <div class="grid-item grid-item-big">
        <?php $this->insert('statistics::single_number', [
            'name'            => $lang['visitors_recurrents'],
            'value'           => $recurrentAvg . '%',
            'label'           => $lang['visitors'],
            'tooltip_title'   => $lang['visitors_recurrents'],
            'tooltip_content' => $lang['explanation_visitors_recurrents']
        ]); ?>
    </div>
    <div class="grid-item grid-item-big">
        <?php $this->insert('statistics::single_number', [
            'name'            => $lang['total_visitors_recurrents'],
            'value'           => thousandsCurrencyFormat($recurrentClients),
            'label'           => $lang['total_visitors'],
            'tooltip_title'   => $lang['total_visitors_recurrents'],
            'tooltip_content' => $lang['total_explanation_visitors_recurrents'],
            'lang'            => $lang
        ]) ?>
    </div>
</div>

<?php $this->push('scripts'); ?>
<script src="<?php echo $this->asset('/public/javascript/isotope.pkgd.min.js') ?>"></script>
<script src="<?php echo $this->asset('/public/javascript/moment.min.js') ?>"></script>
<script src="<?php echo $this->asset('/public/javascript/calendar.min.js') ?>"></script>
<script src="<?php echo $this->asset('/public/javascript/charts.min.js') ?>"></script>
<script src="<?php echo $this->asset('/public/javascript/templates/dashboards.min.js') ?>"></script>
<script src="<?php echo $this->asset('/public/javascript/templates/statistics/doughnut.min.js') ?>"></script>
<script src="<?php echo $this->asset('/public/javascript/templates/statistics/bar-interactions.min.js') ?>"></script>

<script>
    function onClickGetVisitorInfo(event, elem) {
        for (var i = 0; i < elem.length; i ++) {
            var date = elem[i]._model.label || null;

            if (date) {
                window.open('<?php echo SECURE_BASE_PATH . 'app/statistics/loyalty/visitors?date=';?>' + date + '&lapse=<?php echo $_SESSION['lapse']; ?>');
            }
        }
    }
</script>
<?php $this->stop(); ?>
