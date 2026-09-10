<?php //TODO insert lang file here ?>

<?php $this->layout('_layout::layout', [
    'title' => $this->e($title),
    'page_title' => $this->e($page_title),
    'page_icon' => $this->e($page_icon),
    'hotel_name' => $this->e($hotel_name),
    'hotel_logo' => $this->e($hotel_logo),
    'url' => $url
]) ?>

<?php if ($widgetId && array_get($_SESSION, 'permisos.widget')) { ?>
    <?php
        //Insert dashboard top menu
        $this->insert('partials::menus/dashboard_menu', ['url' => $url])
    ?>
    <div class="grid"
        data-isotope='{ "itemSelector": ".grid-item", "percentPosition" : true, "masonry" : {"columnWidth" : ".grid-sizer"}}'
        id="widget_statistics">
        <div class="grid-sizer"></div>
        <div class="grid-item grid-item-big">
            <?php $this->insert('statistics::single_number', [
                'name' => 'Total revenue',
                'value' => thousandsCurrencyFormat($amountBookings),
                'label' => 'Euros',
                'tooltip_title' => 'Total de revenue generado por el widget',
                'tooltip_content' => 'Total de revenue generado por el motor de reservas donde el widget ha intervenido ayudando a la conversión'
            ]) ?>
        </div>
        <div class="grid-item grid-item-big">
            <?php $this->insert('statistics::single_number', [
                'name' => 'Reservas generadas',
                'value' => thousandsCurrencyFormat($totalBookings),
                'label' => 'Reservas',
                'tooltip_title' => 'Reservas generadas por el Widget',
                'tooltip_content' => 'Reservas en las que el uso del widget por el usuario ha sido clave para la generación de la reserva.'
            ]) ?>
        </div>
        <div class="grid-item grid-item-big">
            <?php $this->insert('statistics::single_number', [
                'name' => 'Leads generados',
                'value' => thousandsCurrencyFormat($leads),
                'label' => 'Leads',
                'tooltip_title' => 'Leads generados por el widget',
                'tooltip_content' => 'Se considera un lead a un usuario que ha rellenado el formulario del widget.'
            ]) ?>
        </div>
        <div class="grid-item grid-item-big">
            <?php $this->insert('statistics::bar', [
                'name' => 'Leads por edad',
                'id' => 'age',
                'labels' => $age_labels,
                'label' => $this->e($age_label),
                'data' => $age_data,
                'backgroundColor' => $age_backgroundColor,
                'steps' => 10,
                'parent' => $this,
                'tooltip_title' => 'Edad de los usuarios que han usado el widget',
                'tooltip_content' => 'La edad media de los usuarios que han usado el widget y se han registrado',
                'abs' => true
            ]) ?>
        </div>
        <div class="grid-item grid-item-big">
            <?php $this->insert('statistics::bar', [
                'name' => 'Leads por nacionalidad',
                'id' => 'country',
                'labels' => $country_labels,
                'label' => $this->e($country_label),
                'data' => $country_data,
                'backgroundColor' => $country_backgroundColor,
                'steps' => 10,
                'parent' => $this,
                'tooltip_title' => 'Nacionalidad de los usuarios que han usado el widget',
                'tooltip_content' => 'La nacionalidad de los usuarios que han usado el widget y se han registrado',
                'abs' => true
            ]) ?>
        </div>
        <div class="grid-item grid-item-big">
            <?php $this->insert('statistics::bar', [
                'name' => 'Porcentaje de referidos',
                'id' => 'referrer',
                'labels' => $referrals_labels,
                'label' => $this->e($referrals_label),
                'data' => $referrals_data,
                'backgroundColor' => $referrals_backgroundColor,
                'steps' => 10,
                'parent' => $this,
                'tooltip_title' => 'Origen de la visita',
                'tooltip_content' => 'Si el visistante viene redirigido de otro dominio'
            ]) ?>
        </div>
    </div>
<?php } else {
    $this->insert('partials::widget/no-widget-message');
} ?>

<style>
    #chain_hotel_toggle{
        visibility:hidden;
    }
</style>

<script src="<?php echo $this->asset('/public/javascript/isotope.pkgd.min.js') ?>"></script>
<script src="<?php echo $this->asset('/public/javascript/moment.min.js') ?>"></script>
<script src="<?php echo $this->asset('/public/javascript/calendar.min.js') ?>"></script>
<script src="<?php echo $this->asset('/public/javascript/charts.min.js') ?>"></script>
<script src="<?php echo $this->asset('/public/javascript/templates/statistics/bar.min.js') ?>"></script>
<script src="<?php echo $this->asset('/public/javascript/templates/dashboards.min.js') ?>"></script>
<script src="<?php echo $this->asset('/public/javascript/chartjs-plugin-datalabels.min.js') ?>"></script>
