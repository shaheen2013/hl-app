<?php include LANG . $_SESSION['userLang'] . '/statistics/clicks.php'; ?>
<?php $this->layout('_layout::layout', [
    'title' => $this->e($title),
    'page_title' => $this->e($page_title),
    'page_icon' => $this->e($page_icon),
    'hotel_name' => $this->e($hotel_name),
    'hotel_logo' => $this->e($hotel_logo),
    'url' => $url
]) ?>

<?php
//Insert dashboard top menu
$this->insert('partials::menus/dashboard_menu', ['url' => $url])
?>

<div class="grid" data-isotope='{ "itemSelector": ".grid-item", "percentPosition" : true, "masonry" : {"columnWidth" : ".grid-sizer"}}' id="user_statistics">
    <div class="grid-sizer"></div>
    <div class="grid-item grid-item-big">
        <?php $this->insert('statistics::single_number', [
            'name' => $lang['facebook connection'],
            'value' => thousandsCurrencyFormat($socialMediaUsers),
            'label' => $lang['connections'],
            'tooltip_title' => $lang['title facebook connection'],
            'tooltip_content' => $lang['explanation facebook connection']
        ]) ?>
    </div>
    <div class="grid-item">
        <?php $this->insert('statistics::single_number', [
            'name' => $lang['medium range'],
            'value' => thousandsCurrencyFormat($averageFacebookFriends),
            'label' => $lang['friends'],
            'tooltip_title' => $lang['title medium range'],
            'tooltip_content' => $lang['explanation medium range']
        ]) ?>
    </div>
    <div class="grid-item">
        <?php $this->insert('statistics::single_number', [
            'name' => $lang['title post facebook'],
            'value' => thousandsCurrencyFormat($socialMediaPublications),
            'label' => $lang['publishing'],
            'tooltip_title' => 'Número de publicaciones en Facebook',
            'tooltip_content' => 'Cuantas publicaciones se han hecho en Facebook en este periodo de tiempo.'
        ]) ?>
    </div>
    <div class="grid-item">
        <?php $this->insert('statistics::single_number', [
            'name' => $lang['average post'],
            'value' => $averageSocialMediaShares . ' %',
            'label' => $lang['to total'],
            'tooltip_title' => $lang['average post'],
            'tooltip_content' => $lang['explanation average post']
        ]) ?>
    </div>
    <div class="grid-item grid-item-big">
        <?php $this->insert('statistics::single_number', [
            'name' => $lang['total impressions'],
            'value' => thousandsCurrencyFormat($impressions),
            'label' => $lang['to firends'],
            'tooltip_title' => $lang['title total impressions'],
            'tooltip_content' => $lang['explanation total impressions']
        ]) ?>
    </div>
    <div class="grid-item">
        <?php $this->insert('statistics::single_number', [
            'name' => $lang['value impressions'],
            'value' => thousandsCurrencyFormat($impressionsValue) . ' €',
            'label' => $lang['total'],
            'tooltip_title' => $lang['value impressions'],
            'tooltip_content' => $lang['explanation value impressions']
        ]) ?>
    </div>
    <div class="grid-item grid-item-big">
        <?php $this->insert('statistics::single_number', [
            'name' => $lang['leads post'],
            'value' => $booking_engine_integrated ? thousandsCurrencyFormat($leads) : $lang['title no booking engine integrated alert'],
            'label' => $lang['total'],
            'tooltip_title' => $lang['title leads post'],
            'tooltip_content' => $lang['explanation leads post'],
            'is_alert' => $booking_engine_integrated ? false : $lang['no booking engine integrated alert']
        ]) ?>
    </div>
    <div class="grid-item grid-item-big">
        <?php $this->insert('statistics::single_number', [
            'name' => $lang['leads value'],
            'value' => $booking_engine_integrated ? thousandsCurrencyFormat($leadsValue) . ' €' : $lang['title no booking engine integrated alert'],
            'label' => $lang['total'],
            'tooltip_title' => $lang['leads value'],
            'tooltip_content' => $lang['explanation leads value'],
            'is_alert' => $booking_engine_integrated ? false : $lang['no booking engine integrated alert']
        ]) ?>
    </div>
</div>
<script src="<?php echo $this->asset('/public/javascript/isotope.pkgd.min.js') ?>"></script>
<script src="<?php echo $this->asset('/public/javascript/moment.min.js') ?>"></script>
<script src="<?php echo $this->asset('/public/javascript/calendar.min.js') ?>"></script>
<script src="<?php echo $this->asset('/public/javascript/charts.min.js') ?>"></script>
<script src="<?php echo $this->asset('/public/javascript/templates/dashboards.min.js') ?>"></script>
