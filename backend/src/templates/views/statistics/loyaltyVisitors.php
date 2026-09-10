<?php

use Carbon\Carbon;

include LANG . $_SESSION['userLang'] . '/statistics/loyalty.php';
$this->layout('_layout::layout');

//Insert dashboard top menu
$this->insert('partials::menus/dashboard_menu', ['url' => $url]);
?>
<div class="ui grid" style="padding:1rem">
    <div class="column">
        <table class="ui small selectable striped celled table sortable comparison_table unstackable">
            <thead>
            <tr>
                <th><?php echo $lang['header_name']; ?></th>
                <th><?php echo $lang['header_email']; ?></th>
                <th><?php echo $lang['header_gender']; ?></th>
                <th><?php echo $lang['header_age']; ?></th>
                <th><?php echo $lang['header_country']; ?></th>
                <th><?php echo $lang['header_hotel']; ?></th>
                <th><?php echo $lang['header_room']; ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($visitors as $visitor): ?>
                <tr>
                    <td><?php echo array_get($visitor, 'name') ?></td>
                    <td><?php echo array_get($visitor, 'email') ?></td>
                    <td><?php echo !empty(array_get($lang, array_get($visitor, 'gender'))) ? array_get($lang, array_get($visitor, 'gender')) : $lang['other'] ?></td>
                    <td><?php echo Carbon::parse(array_get($visitor, 'birthdate'))->age ?></td>
                    <td><?php echo array_get($visitor, 'country') ?></td>
                    <td><?php echo array_get($visitor, 'brand_name') ?></td>
                    <td><?php echo array_get($visitor, 'access_code') ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <?php
        $this->insert('partials::menus/paginator', [
            'resultsPerPage' => $resultsPerPage,
            'page'           => $page,
            'numberPages'    => $numberPages
        ]);
        ?>
    </div>
</div>

<script src="<?php echo $this->asset('/public/javascript/isotope.pkgd.min.js') ?>"></script>
<script src="<?php echo $this->asset('/public/javascript/moment.min.js') ?>"></script>
<script src="<?php echo $this->asset('/public/javascript/calendar.min.js') ?>"></script>
<script src="<?php echo $this->asset('/public/javascript/charts.min.js') ?>"></script>
<script src="<?php echo $this->asset('/public/javascript/templates/statistics/bar-interactions.min.js') ?>"></script>
<script src="<?php echo $this->asset('/public/javascript/templates/dashboards.min.js') ?>"></script>
<script src="<?php echo $this->asset('/public/javascript/table_sorting.min.js') ?>"></script>
