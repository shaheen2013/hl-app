<?php
include LANG . $_SESSION['userLang'] . '/statistics/widgetClients.php';

    $this->layout('_layout::layout', [
        'title' => $this->e($title),
        'page_title' => $this->e($page_title),
        'page_icon' => $this->e($page_icon),
        'hotel_name' => $this->e($hotel_name),
        'hotel_logo' => $this->e($hotel_logo),
        'url' => $url
    ]);

   
?>

<?php if (array_get($_SESSION, 'permisos.widget')) { 
    //Insert dashboard top menu
    $this->insert('partials::menus/dashboard_menu', ['url' => $url]);    
?>
    <div class="ui grid" style="padding:1rem">
        <div class="column" >
            <table class="ui small selectable striped celled table sortable comparison_table unstackable">
                <thead>
                    <tr>
                        <th><?php echo $lang['name']?></th>
                        <th><?php echo $lang['email']?></th>
                        <th><?php echo $lang['gender']?></th>
                        <th><?php echo $lang['birth']?></th>
                        <th><?php echo $lang['source']?></th>
                        <th><?php echo $lang['hotel']?></th>
                        <th><?php echo $lang['amount']?></th>
                        <th><?php echo $lang['checkIn']?></th>
                        <th><?php echo $lang['checkOut']?></th>
                        <th><?php echo $lang['promocode']?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        if ($clients) :
                            foreach ($clients as $row): 
                    ?>
                                <tr>
                                    <td><?php echo array_get($row, 'first_name') . ' ' . array_get($row, 'last_name') ?></td>
                                    <td><?php echo array_get($row, 'email') ?></td>
                                    <td><?php echo array_get($row, 'gender') ?></td>
                                    <td data-sort-value="<?php echo array_get($row, 'birthday') ?>"><?php echo array_get($row, 'birthday') ?></td>
                                    <td><?php echo array_get($row, 'origin') ?></td>
                                    <td><?php echo array_get($row, 'hotel_name') ?></td>
                                    <td data-sort-value="<?php echo array_get($row, 'amount') ?>"><?php echo array_get($row, 'amount') . ' ' . array_get($row, 'currency') ?></td>
                                    <td data-sort-value="<?php echo array_get($row, 'check_in') ?>"><?php echo array_get($row, 'check_in') ?></td>
                                    <td data-sort-value="<?php echo array_get($row, 'check_out') ?>"><?php echo array_get($row, 'check_out') ?></td>
                                    <td><?php echo array_get($row, 'promocode') ?></td>
                                </tr>
                        <?php 
                            endforeach; 
                        endif;
                        ?>
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
<?php } else { 
    $this->insert('partials::widget/no-widget-message');
} ?>
<script src="<?php echo $this->asset('/public/javascript/isotope.pkgd.min.js') ?>"></script>
<script src="<?php echo $this->asset('/public/javascript/moment.min.js') ?>"></script>
<script src="<?php echo $this->asset('/public/javascript/calendar.min.js') ?>"></script>
<script src="<?php echo $this->asset('/public/javascript/charts.min.js') ?>"></script>
<script src="<?php echo $this->asset('/public/javascript/templates/statistics/bar.min.js') ?>"></script>
<script src="<?php echo $this->asset('/public/javascript/templates/dashboards.min.js') ?>"></script>
<script src="<?php echo $this->asset('/public/javascript/table_sorting.min.js') ?>"></script>

<style>
    #chain_hotel_toggle {
        visibility:hidden;
    }
</style>
