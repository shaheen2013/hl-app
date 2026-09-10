<?php
    include LANG . $_SESSION['userLang'] . '/statistics/engagement.php';
?>
<?php $this->layout('_layout::layout', [
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
    <?php if(!empty($satisfaction)) : ?>
    <div class="grid-item grid-item-huge">
        <?php $this->insert('statistics::multiple_lines', [
            'name' => $lang['ratio satisfaction'],
            'id' => 'user_satisfaction_rate',
            'data' => [
                [
                    'label' => $lang['sent'], 
                    'data' => array_get($satisfaction, 'sent'), 
                    'backgroundColor' => $sentBackground, 
                    'borderColor' => $sentBorder, 
                    'pointBackgroundColor' => $sentBorder
                ],
                [
                    'label' => $lang['opens'], 
                    'data' => array_get($satisfaction, 'opens'), 
                    'backgroundColor' => $opensBackground, 
                    'borderColor' => $opensBorder, 
                    'pointBackgroundColor' => $opensBorder
                ],
                [
                    'label' => $lang['clicks'], 
                    'data' => array_get($satisfaction, 'clicks'), 
                    'backgroundColor' => $clicksBackground, 
                    'borderColor' => $clicksBorder, 
                    'pointBackgroundColor' => $clicksBorder
                ],
            ],
            'labels' => array_get($satisfaction, 'dates'),
            'parent' => $this,
            'steps' => array_get($satisfaction, 'steps'),
            'lapse' => true,
            'average_opens' => array_get($satisfaction, 'opens_avg'),
            'average_clicks' => array_get($satisfaction, 'clicks_avg'),
            'tooltip_title' => $lang['title ratio satisfaction'],
            'tooltip_content' => $lang['explanation ratio satisfaction']

        ]) ?>
    </div>
    <?php endif; ?>
    <?php if(!empty($review)) : ?>
    <div class="grid-item grid-item-huge">
        <?php $this->insert('statistics::multiple_lines', [
            'name' => $lang['ratio review'],
            'id' => 'user_review_rate',
            'data' => [
                [
                    'label' => $lang['sent'], 
                    'data' => array_get($review, 'sent'), 
                    'backgroundColor' => $sentBackground, 
                    'borderColor' => $sentBorder, 
                    'pointBackgroundColor' => $sentBorder
                ],
                [
                    'label' => $lang['opens'], 
                    'data' => array_get($review, 'opens'), 
                    'backgroundColor' => $opensBackground, 
                    'borderColor' => $opensBorder, 
                    'pointBackgroundColor' => $opensBorder
                ],
                [
                    'label' => $lang['clicks'], 
                    'data' => array_get($review, 'clicks'), 
                    'backgroundColor' => $clicksBackground, 
                    'borderColor' => $clicksBorder, 
                    'pointBackgroundColor' => $clicksBorder
                ],
            ],
            'labels' => array_get($review, 'dates'),
            'parent' => $this,
            'steps' => array_get($review, 'steps'),
            'lapse' => true,
            'average_opens' => array_get($review, 'opens_avg'),
            'average_clicks' => array_get($review, 'clicks_avg'),
            'tooltip_title' => $lang['title ratio review'],
            'tooltip_content' => $lang['explanation ratio review']
        ]) ?>
    </div>
    <?php endif; ?>
    <?php if(!empty($birthday)) : ?>
    <div class="grid-item grid-item-huge">
        <?php $this->insert('statistics::multiple_lines', [
            'name' => $lang['ratio birthday'],
            'id' => 'user_birthday_rate',
            'data' => [
                [
                    'label' => $lang['sent'], 
                    'data' => array_get($birthday, 'sent'), 
                    'backgroundColor' => $sentBackground, 
                    'borderColor' => $sentBorder, 
                    'pointBackgroundColor' => $sentBorder
                ],
                [
                    'label' => $lang['opens'], 
                    'data' => array_get($birthday, 'opens'), 
                    'backgroundColor' => $opensBackground, 
                    'borderColor' => $opensBorder, 
                    'pointBackgroundColor' => $opensBorder
                ],
                [
                    'label' => $lang['clicks'], 
                    'data' => array_get($birthday, 'clicks'), 
                    'backgroundColor' => $clicksBackground, 
                    'borderColor' => $clicksBorder, 
                    'pointBackgroundColor' => $clicksBorder
                ],
            ],
            'labels' => array_get($birthday, 'dates'),
            'parent' => $this,
            'steps' => array_get($birthday, 'steps'),
            'lapse' => true,
            'average_opens' => array_get($birthday, 'opens_avg'),
            'average_clicks' => array_get($birthday, 'clicks_avg'),
            'tooltip_title' => $lang['title ratio birthday'],
            'tooltip_content' => $lang['explanation ratio birthday']
        ]) ?>
    </div>
    <?php endif; ?>
    <?php if(!empty($offer)) : ?>
    <div class="grid-item grid-item-huge">
        <?php $this->insert('statistics::multiple_lines', [
            'name' => $lang['ratio offer'],
            'id' => 'user_offer_rate',
            'data' => [
                [
                    'label' => $lang['sent'], 
                    'data' => array_get($offer, 'sent'), 
                    'backgroundColor' => $sentBackground, 
                    'borderColor' => $sentBorder, 
                    'pointBackgroundColor' => $sentBorder
                ],
                [
                    'label' => $lang['opens'], 
                    'data' => array_get($offer, 'opens'), 
                    'backgroundColor' => $opensBackground, 
                    'borderColor' => $opensBorder, 
                    'pointBackgroundColor' => $opensBorder
                ],
                [
                    'label' => $lang['clicks'], 
                    'data' => array_get($offer, 'clicks'), 
                    'backgroundColor' => $clicksBackground, 
                    'borderColor' => $clicksBorder, 
                    'pointBackgroundColor' => $clicksBorder
                ],
            ],
            'labels' => array_get($offer, 'dates'),
            'parent' => $this,
            'steps' => array_get($offer, 'steps'),
            'lapse' => true,
            'average_opens' => array_get($offer, 'opens_avg'),
            'average_clicks' => array_get($offer, 'clicks_avg'),
            'tooltip_title' => $lang['title ratio offer'],
            'tooltip_content' => $lang['explanation ratio offer']
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
                minDate: new Date(2018, 9 -1, 1),
                endCalendar: $('#rangeend'),
            });
            $('#rangeend').calendar({
                type: 'date',
                startCalendar: $('#rangestart')
            });
        });
    </script>
<?php $this->stop() ?>
