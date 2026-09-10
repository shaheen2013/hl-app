<?php 
    include LANG . $_SESSION['userLang'] . '/statistics/staffEngagement.php';
    $this->layout('_layout::layout', [
    'title' => $this->e($title),
    'page_title' => $this->e($page_title),
    'page_icon' => $this->e($page_icon),
    'hotel_name' => $this->e($hotel_name),
    'hotel_logo' => $this->e($hotel_logo),
    'url' => $url
]) ?>

<?php
//Insert dashboard top menu && hotelinking limit message
$this->insert('partials::menus/dashboard_menu', ['url' => $url]);
?>


<div class="grid"
     data-isotope='{ "itemSelector": ".grid-item", "percentPosition" : true, "masonry" : {"columnWidth" : ".grid-sizer"}}'
     id="engagement_statistics">
    <div class="grid-sizer"></div>

    <?php if(!empty($warning)) : ?>
    <div class="grid-item grid-item-huge">
        <?php $this->insert('statistics::multiple_lines', [
            'name' => $lang['notify client'],
            'id' => 'user_satisfaction_rate',
            'data' => [
                [
                    'label' => $lang['sent'], 
                    'data' => array_get($warning, 'sent'), 
                    'backgroundColor' => $sentBackground, 
                    'borderColor' => $sentBorder, 
                    'pointBackgroundColor' => $sentBorder
                ],
                [
                    'label' => $lang['open'], 
                    'data' => array_get($warning, 'opens'), 
                    'backgroundColor' => $opensBackground, 
                    'borderColor' => $opensBorder, 
                    'pointBackgroundColor' => $opensBorder
                ],
                [
                    'label' => $lang['click'], 
                    'data' => array_get($warning, 'clicks'), 
                    'backgroundColor' => $clicksBackground, 
                    'borderColor' => $clicksBorder, 
                    'pointBackgroundColor' => $clicksBorder
                ],
            ],
            'labels' => array_get($warning, 'dates'),
            'parent' => $this,
            'steps' => array_get($warning, 'steps'),
            'lapse' => true,
            'average_opens' => array_get($warning, 'opens_avg'),
            'tooltip_title' => $lang['title notify client'],
            'tooltip_content' => $lang['explanation notify client']
        ]) ?>
    </div>
    <?php endif; ?>
    <?php if(!empty($birthdayWarning)) : ?>
    <div class="grid-item grid-item-huge">
        <?php $this->insert('statistics::multiple_lines', [
            'name' => $lang['notify birthday'],
            'id' => 'user_review_rate',
            'data' => [
                [
                    'label' => $lang['sent'], 
                    'data' => array_get($birthdayWarning, 'sent'), 
                    'backgroundColor' => $sentBackground, 
                    'borderColor' => $sentBorder, 
                    'pointBackgroundColor' => $sentBorder
                ],
                [
                    'label' => $lang['open'], 
                    'data' => array_get($birthdayWarning, 'opens'), 
                    'backgroundColor' => $opensBackground, 
                    'borderColor' => $opensBorder, 
                    'pointBackgroundColor' => $opensBorder
                ],
                [
                    'label' => $lang['click'], 
                    'data' => array_get($birthdayWarning, 'clicks'), 
                    'backgroundColor' => $clicksBackground, 
                    'borderColor' => $clicksBorder, 
                    'pointBackgroundColor' => $clicksBorder
                ],
            ],
            'labels' => array_get($birthdayWarning, 'dates'),
            'parent' => $this,
            'steps' => array_get($birthdayWarning, 'steps'),
            'lapse' => true,
            'average_opens' => array_get($birthdayWarning, 'opens_avg'),
            'tooltip_title' => $lang['title notify birthday'],
            'tooltip_content' => $lang['explanation notify birthday']
        ]) ?>
    </div>
    <?php endif; ?>
    <?php if(!empty($loyalWarning)) : ?>
    <div class="grid-item grid-item-huge">
        <?php $this->insert('statistics::multiple_lines', [
            'name' => $lang['notify return client'],
            'id' => 'user_birthday_rate',
            'data' => [
                [
                    'label' => $lang['sent'], 
                    'data' => array_get($loyalWarning, 'sent'), 
                    'backgroundColor' => $sentBackground, 
                    'borderColor' => $sentBorder, 
                    'pointBackgroundColor' => $sentBorder
                ],
                [
                    'label' => $lang['open'],
                    'data' => array_get($loyalWarning, 'opens'), 
                    'backgroundColor' => $opensBackground, 
                    'borderColor' => $opensBorder, 
                    'pointBackgroundColor' => $opensBorder
                ],
                [
                    'label' => $lang['click'], 
                    'data' => array_get($loyalWarning, 'clicks'), 
                    'backgroundColor' => $clicksBackground, 
                    'borderColor' => $clicksBorder, 
                    'pointBackgroundColor' => $clicksBorder
                ],
            ],
            'labels' => array_get($loyalWarning, 'dates'),
            'parent' => $this,
            'steps' => array_get($loyalWarning, 'steps'),
            'lapse' => true,
            'average_opens' => array_get($loyalWarning, 'opens_avg'),
            'tooltip_title' => $lang['title notify return client'],
            'tooltip_content' => $lang['explanation notify return client'],
        ]) ?>
    </div>
    <?php endif; ?>
</div>
<?php $this->push('scripts') ?>
<script src="<?php echo $this->asset('/public/javascript/isotope.pkgd.min.js') ?>"></script>
<script src="<?php echo $this->asset('/public/javascript/moment.min.js') ?>"></script>
<script src="<?php echo $this->asset('/public/javascript/calendar.min.js') ?>"></script>
<script src="<?php echo $this->asset('/public/javascript/charts.min.js') ?>"></script>
<script src="<?php echo $this->asset('/public/javascript/templates/statistics/multiple_lines.min.js') ?>"></script>
<script>
        $(document).ready(function () {
            $('#rangestart').calendar({
                type: 'date',
                minDate: new Date(2018, 09 -1, 01),
                endCalendar: $('#rangeend'),
            });
            $('#rangeend').calendar({
                type: 'date',
                startCalendar: $('#rangestart')
            });
        });
    </script> 
<?php $this->stop() ?>
