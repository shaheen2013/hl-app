<?php
    include LANG . $_SESSION['userLang'] . '/statistics/comparisonTable.php';
    $this->layout('_layout::layout', [
    'title' => $this->e($title),
    'page_title' => $this->e($page_title),
    'page_icon' => $this->e($page_icon),
    'hotel_name' => $this->e($hotel_name),
    'hotel_logo' => $this->e($hotel_logo),
    'url' => $url
]) ?>

<?php
//Insert dashboard top menu
$this->insert('partials::menus/dashboard_menu', ['url' => $url, 'no_chain_selector' => true]);
?>

<div class="ui grid" style="padding:1rem">
    <div class="column" style="overflow-y:auto;white-space:nowrap; margin:0 1rem; padding:0">
        <table class="ui small selectable striped celled table sortable comparison_table unstackable">
            <thead>
            <tr>
                <th style="position:sticky;left:0;padding: 0 10px;z-index:2;background: #f9fafb;" class="single line default-sort"><?php echo $lang['name']?></th>
                <th class="column-with-tooltip">
                    <?php echo $lang['users']?>
                    <div data-tooltip=<?php echo $lang['explanation users']?> data-position="bottom left" class="limit-tooltip" style="display:inline;">
                        <i class="info circle icon"></i>
                    </div>
                </th>
                <th><?php echo $lang['value database']?></th>
                <th><?php echo $lang['form']?></th>
                <th><?php echo $lang['facebook']?></th>
                <th><?php echo $lang['publish facebook']?></th>
                <th><?php echo $lang['ratio post facebook']?></th>
                <th><?php echo $lang['total impressions']?></th>
                <th><?php echo $lang['value impressions']?></th>
                <th><?php echo $lang['average satisfaction']?></th>
                <th><?php echo $lang['response time']?></th>
                <th><?php echo $lang['satisfaction sent']?></th>
                <th><?php echo $lang['satisfaction open']?></th>
                <th><?php echo $lang['satisfaction click']?></th>
                <th><?php echo $lang['review sent']?></th>
                <th><?php echo $lang['review open']?></th>
                <th><?php echo $lang['review click']?></th>
                <th><?php echo $lang['warning sent']?></th>
                <th><?php echo $lang['warning open']?></th> 
            </tr>
            </thead>
            <tbody>
            <?php foreach ($comparisonData as $brand => $row): ?>
                <tr>
                    <td style="position:sticky;left:0;background: #f9fafb;padding: 0 10px;">
                        <h4 class="ui header"><?php echo $brand ?></h4>
                    </td>
                    <td data-sort-value="<?php echo array_get($row, 'users') ?>"
                        class="<?php echo array_get($row, 'users') == 0 ? 'warning' : '' ?>">
                        <?php echo array_get($row, 'users') ?>
                    </td>
                    <td data-sort-value="<?php echo array_get($row, 'database_value') ?>"
                        class="<?php echo array_get($row, 'database_value') == 0 ? 'warning' : '' ?>">
                        <?php echo number_format(array_get($row, 'database_value'), 2) ?> €
                    </td>
                    <td class="<?php echo array_get($row, 'form_connections') == 0 ? 'warning' : '' ?>">
                        <?php echo array_get($row, 'form_connections') ?> %
                    </td>
                    <td class="<?php echo array_get($row, 'facebook_connections') == 0 ? 'warning' : '' ?>">
                        <?php echo array_get($row, 'facebook_connections') ?> %
                    </td>
                    <td data-sort-value="<?php echo array_get($row, 'shares') ?>"
                        class="<?php echo array_get($row, 'shares') == 0 ? 'warning' : '' ?>">
                        <?php echo array_get($row, 'shares') ?>
                    </td>
                    <td data-sort-value="<?php echo array_get($row, 'shares_ratio') ?>"
                        class="<?php echo array_get($row, 'shares_ratio') == 0 ? 'warning' : '' ?>">
                        <?php echo array_get($row, 'shares_ratio') . " %";?>
                    </td>
                    <td data-sort-value="<?php
                     echo array_get($row,'impressions') ?>"
                        class="<?php echo array_get($row,'impressions') == 0 ? 'warning' : '' ?>">
                        <?php echo array_get($row,'impressions')?>
                    </td>
                    <td data-sort-value="<?php echo array_get($row, 'impressions_value') ?>"
                        class="<?php echo array_get($row, 'impressions_value') == 0 ? 'warning' : '' ?>">
                        <?php echo array_get($row, 'impressions_value') ?> €
                    </td>
                    <td class="<?php echo array_get($row, 'survey_avg') == 0 ? 'warning' : '' ?>">
                        <?php echo array_get($row, 'survey_avg') ?>
                    </td>
                    <td class="<?php echo array_get($row, 'survey_response_time_avg') == 0 ? 'warning' : '' ?>">
                        <?php echo array_get($row, 'survey_response_time_avg') ?>
                    </td>
                    <td class="<?php echo array_get($row, 'satisfaction_sent') == 0 ? 'warning' : '' ?>">
                        <?php echo array_get($row, 'satisfaction_sent') ?>
                    </td>

                    <td class="<?php echo array_get($row, 'satisfaction_opens') == 0 ? 'warning' : '' ?>">
                        <?php echo array_get($row, 'satisfaction_opens') ?> %
                    </td>

                    <td class="<?php echo array_get($row, 'satisfaction_clicks') == 0 ? 'warning' : '' ?>">
                        <?php echo array_get($row, 'satisfaction_clicks') ?> %
                    </td>

                    <td class="<?php echo array_get($row, 'review_sent') == 0 ? 'warning' : '' ?>">
                        <?php echo array_get($row, 'review_sent') ?>
                    </td>

                    <td class="<?php echo array_get($row, 'review_opens') == 0 ? 'warning' : '' ?>">
                        <?php echo array_get($row, 'review_opens') ?> %
                    </td>

                    <td class="<?php echo array_get($row, 'review_clicks') == 0 ? 'warning' : '' ?>">
                        <?php echo array_get($row, 'review_clicks') ?> %
                    </td>

                    <td class="<?php echo array_get($row, 'satisfaction_warning_sent') == 0 ? 'warning' : '' ?>">
                        <?php echo array_get($row, 'satisfaction_warning_sent') ?>
                    </td>

                    <td class="<?php echo array_get($row, 'satisfaction_warning_opens') == 0 ? 'warning' : '' ?>">
                        <?php echo array_get($row, 'satisfaction_warning_opens') ?> %
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php if (!$comparisonFinished) : ?>  
    <div class="ui one column centered grid">
        <div class="column"></div>
        <form id="requestMoreBrandsForm" method="post">
            <button style="background-color:black !important;" onclick=requestMoreBrands() class="primary-color bg ui button has-loader" type="submit">
                <i class="icon plus"></i> <?php echo $lang['show more']?>
            </button>
            <input type="hidden" name="page" value="<?php echo $comparisonPage + 1?>">
        </form>
    </div>
<?php endif; ?>

<?php $this->push('scripts') ?>
<script src="<?php echo $this->asset('/public/javascript/moment.min.js') ?>"></script>
<script src="<?php echo $this->asset('/public/javascript/calendar.min.js') ?>"></script>
<script src="<?php echo $this->asset('/public/javascript/templates/dashboards.min.js') ?>"></script>
<script src="<?php echo $this->asset('/public/javascript/table_sorting.min.js') ?>"></script>
<script>
    // Table sorting
    $('.comparison_table').tablesort().data('tablesort').sort($("th.default-sort"));

    function requestMoreBrands() {
        $("#requestMoreBrandsForm").submit()
    }
</script>
<style>
    .column-with-tooltip .limit-tooltip:after {
        width: 20em;
        white-space: normal;
    }

</style>
<?php $this->stop() ?>
